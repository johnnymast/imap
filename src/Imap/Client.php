<?php declare(strict_types=1);

namespace Redbox\Imap;

use Redbox\Imap\Exceptions\MethodNotFoundException;
use Redbox\Imap\Log\LogLevel;
use Redbox\Imap\Log\NullLogger;
use Redbox\Imap\Log\OutputLogger;
use Redbox\Imap\Resources\ListResource;
use Redbox\Imap\Resources\LoginResource;
use Redbox\Imap\Resources\LogoutResource;
use Redbox\Imap\Resources\ResourceAbstract;
use Redbox\Imap\Resources\SelectResource;
use Redbox\Imap\Transport\TCP;
use Redbox\Imap\Transport\TCPRequest;
use Redbox\Imap\Transport\TransportInterface;
use Redbox\Imap\Utils\Factories\ResponseFactory;
use Redbox\Imap\Utils\Factories\TagFactory;
use Redbox\Imap\Utils\Logger;
use Redbox\Imap\Utils\Options;

/**
 * Class Client
 *
 * @package Redbox\Imap
 * @method login()
 * @method logout()
 * @method list()
 */
class Client
{
    /**
     * @var TransportInterface
     */
    protected $transport;

    /**
     *
     * @var bool
     */
    protected $authenticated = false;

    /**
     * @var bool
     */
    protected $connected = false;

    /**
     * @var Options
     */
    protected $options = null;

    /**
     * A list of command resources.
     *
     * @var array
     */
    protected $resources = [];

    /**
     * Client constructor.
     *
     * @param array $options
     */
    public function __construct($options = [])
    {
        $this->options = $options;

        $logger = new NullLogger();

        if ($this->options->verbose) {
            $logger = new OutputLogger();
        }

        Logger::create($logger);

        if (! $this->options->debug) {
            Logger::ignoreLevels([LogLevel::DEBUG]);
        }

        $this->registerResource(new LoginResource($this, 'login', false))
            ->registerResource(new LogoutResource($this, 'logout', false))
            ->registerResource(new SelectResource($this, 'select', true))
            ->registerResource(new ListResource($this, 'list', true));

        $this->connect();
    }

    public function registerResource(ResourceAbstract $resource)
    {

        if ($resource) {
            $name = $resource->getMethodName();
            $this->resources[$name] = $resource;
        }

        return $this;
    }

    public function connect()
    {
        $options = $this->getOptions();
        $request = new TCPRequest($options->host, $options->port, $options->secure);

        if ($this->getTransport()->connect($request)) {
            Logger::log(LogLevel::DEBUG, 'Connected to {host}:{port}',
                ['host' => $options->host, 'port' => $options->port]);

            $this->setConnected(true);
        } else {
            Logger::log(LogLevel::DEBUG, 'Could not connect to {host}:{port}',
                ['host' => $options->host, 'port' => $options->port]);

            $this->setConnected(false);
        }
    }

    /**
     * @return \Redbox\Imap\Utils\Options
     */
    public function getOptions(): Options
    {
        return $this->options;
    }

    /**
     * Get transport.
     *
     * @return \Redbox\Imap\Transport\TransportInterface
     */
    public function getTransport(): TransportInterface
    {
        if (! $this->transport) {
            $this->setTransport(new TCP($this));
        }

        return $this->transport;
    }

    /**
     * Set transport
     *
     * @param TransportInterface $transport
     */
    public function setTransport(TransportInterface $transport)
    {
        $this->transport = $transport;
    }

    /**
     * @param $options
     * @return \Redbox\Imap\Client
     * @throws \Exception
     */
    public static function create($options)
    {
        $options = new Options($options);

        if (! $options->validate()) {
            throw new \Exception('Invalid options provided.');
        }

        return new static($options);
    }

    /**
     * Client deconstructing.
     */
    public function __destruct()
    {
        $this->disconnect();
    }

    public function disconnect()
    {
        TagFactory::clear();
        ResponseFactory::clear();

        if ($this->getTransport()->close()) {

            Logger::log(LogLevel::DEBUG, 'Disconnected.');
            $this->setConnected(false);
        } else {
            Logger::log(LogLevel::DEBUG, 'Failed to disconnect.');
            $this->setConnected(true);
        }
    }

    /**
     * @return bool
     */
    public function isAuthenticated()
    {
        return $this->authenticated;
    }

    /**
     * @param bool $authenticated
     */
    public function setAuthenticated($authenticated)
    {
        $this->authenticated = $authenticated;
    }

    public function executeAfterLogin($method = null, $arguments = [])
    {
        if ($method) {
            if ($this->login()) {
                return call_user_func_array($method, $arguments);
            }
        }

        return false;
    }

    public function __call($name, $arguments)
    {
        if (isset($this->resources[$name])) {
            $resource = $this->resources[$name];

            if ($this->isConnected()) {
                return call_user_func_array([$resource, $resource->getMethodName()], $arguments);
            } else {
                return false;
            }
        } else {
            throw new MethodNotFoundException('Call to undefined method '.get_class($this).':'.$name.'()');
        }
    }

    /**
     * @return bool
     */
    public function isConnected(): bool
    {
        return $this->connected;
    }

    /**
     * @param bool $connected
     */
    public function setConnected(bool $connected): void
    {
        $this->connected = $connected;
    }
}