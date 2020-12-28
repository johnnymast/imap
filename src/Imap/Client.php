<?php declare(strict_types=1);

namespace Redbox\Imap;

use Redbox\Imap\Exceptions\MethodNotFoundException;
use Redbox\Imap\Log\LogLevel;
use Redbox\Imap\Log\NullLogger;
use Redbox\Imap\Log\OutputLogger;
use Redbox\Imap\Resources\AuthenticateResource;
use Redbox\Imap\Resources\CapabilityResource;
use Redbox\Imap\Resources\CheckResource;
use Redbox\Imap\Resources\CloseResource;
use Redbox\Imap\Resources\CreateResource;
use Redbox\Imap\Resources\DeleteResource;
use Redbox\Imap\Resources\ExamineResource;
use Redbox\Imap\Resources\ListResource;
use Redbox\Imap\Resources\LoginResource;
use Redbox\Imap\Resources\LogoutResource;
use Redbox\Imap\Resources\NoopResource;
use Redbox\Imap\Resources\RenameResource;
use Redbox\Imap\Resources\ResourceAbstract;
use Redbox\Imap\Resources\SelectResource;
use Redbox\Imap\Resources\StatusResource;
use Redbox\Imap\Resources\SubscribeResource;
use Redbox\Imap\Resources\UnSubscribeResource;
use Redbox\Imap\Transport\TCP;
use Redbox\Imap\Transport\TCPRequest;
use Redbox\Imap\Transport\TransportInterface;
use Redbox\Imap\Utils\Factories\ResponseFactory;
use Redbox\Imap\Utils\Factories\TagFactory;
use Redbox\Imap\Utils\Logger;
use Redbox\Imap\Utils\Options;
use Redbox\Imap\Utils\Response;

/**
 * Class Client
 *
 * @package Redbox\Imap
 * @method Response login()
 * @method Response logout()
 * @method Response close()
 * @method Response check()
 * @method Response noop()
 * @method Response select(string $mailbox = '')
 * @method Response examine(string $mailbox = '')
 * @method Response list(string $reference = '', string $mailbox = '')
 * @method Response create(string $mailbox = '')
 * @method Response delete(string $mailbox = '')
 * @method Response subscribe(string $mailbox = '')
 * @method Response unsubscribe(string $mailbox = '')
 * @method Response lsub(string $name = '', string $mailbox = '')
 * @method Response status(string $string, string $data_status_items)
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
            ->registerResource(new ExamineResource($this, 'examine', true))
            //->registerResource(new AuthenticateResource($this, 'authenticate', false))
            //->registerResource(new CapabilityResource($this, 'capability', false))
            ->registerResource(new CreateResource($this, 'create', true))
            ->registerResource(new DeleteResource($this, 'delete', true))
            ->registerResource(new ListResource($this, 'list', true))// NOT DONE
            ->registerResource(new SubscribeResource($this, 'subscribe', true))
            ->registerResource(new UnsubscribeResource($this, 'unsubscribe', true))// NOT CONFIRMED YET
            //->registerResource(new LSubResource($this, 'lsub', true)); // NOT CONFIRMED YET
            ->registerResource(new RenameResource($this, 'rename', true))// NOT CONFIRMED YET
            ->registerResource(new StatusResource($this, 'status', true))
        
            ->registerResource(new CloseResource($this, 'close', true))
            ->registerResource(new CheckResource($this, 'check', true))
            ->registerResource(new CapabilityResource($this, 'capability', false))
            ->registerResource(new NoopResource($this, 'noop', false));

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

    /**
     * Connect to the IMAP server.
     */
    public function connect()
    {
        $options = $this->getOptions();
        $request = new TCPRequest($options->host, $options->port, $options->secure);

        if ($this->getTransport()
            ->connect($request)) {
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
     *
     * @deprecated Find better name
     *
     * @param $options
     *
     * @return \Redbox\Imap\Client
     * @throws \Exception
     */
    public static function make($options): Client
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

    /**
     *
     */
    public function disconnect()
    {
        TagFactory::clear();
        ResponseFactory::clear();

        if ($this->getTransport()
            ->close()) {

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
    public function isAuthenticated(): bool
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

    /**
     * @param null $method
     * @param array $arguments
     *
     * @return bool|mixed
     */
    public function executeAfterLogin($method = null, $arguments = [])
    {
        if ($method) {
            $options = $this->getOptions();

            if ($this->login($options->username, $options->password)) {
                return call_user_func_array($method, $arguments);
            }
        }

        return false;
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return bool|mixed
     * @throws \Redbox\Imap\Exceptions\MethodNotFoundException
     */
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
    public function setConnected(bool $connected)
    {
        $this->connected = $connected;
    }
}