<?php declare(strict_types=1);

namespace Redbox\Imap;

use Redbox\Imap\Resources\AuthResource;
use Redbox\Imap\Resources\ListResource;
use Redbox\Imap\Transport\TCP;
use Redbox\Imap\Transport\TCPRequest;
use Redbox\Imap\Transport\TransportInterface;
use Redbox\Imap\Utils\Factories\ResponseFactory;
use Redbox\Imap\Utils\Factories\TagFactory;

/**
 * Class Client
 *
 * @package Redbox\Imap
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
     * @var array
     */
    protected $options = [];

    /**
     * Client constructor.
     *
     * @param array $options
     */
    public function __construct($options = [])
    {
        $this->options = $options;

        $this->auth = new AuthResource($this, 'auth');
        $this->list = new ListResource($this, 'list', [
            'methods' => [
                'LOGIN' => [
                    'requiresAuth' => false,
                ],
            ],
        ]);

        $this->connect();
    }

    public function connect()
    {
        $options = $this->getOptions();
        $request = new TCPRequest($options['host'], (int)$options['port'], (bool)$options['secure']);

        $this->getTransport()->connect($request);
    }

    /**
     * @return array
     */
    public function getOptions(): array
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

    public static function create($options)
    {
        // TODO: Options object
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

        //$this->getTransport()->
        // TODO: end Transport
    }

    public function authenticate()
    {
        return $this->auth->authenticate();
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
}