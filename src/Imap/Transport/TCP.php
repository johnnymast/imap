<?php

namespace Redbox\Imap\Transport;

use Redbox\Imap\Client;
use Redbox\Imap\Exceptions\AdapterNotSupportedAdapter;
use Redbox\Imap\Transport\Adapter\Stream as DefaultAdapter;

class TCP implements TransportInterface
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var TCPRequest
     */
    protected $request = null;

    /**
     * @var Adapter\AdapterInterface
     */
    protected $adapter;

    /**
     * TCP constructor.
     *
     * @param \Redbox\Imap\Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return \Redbox\Imap\Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @param \Redbox\Imap\Transport\TCPRequest $request
     * @return mixed
     * @throws \Redbox\Imap\Exceptions\AdapterNotSupportedAdapter
     */
    public function connect(TCPRequest $request)
    {
        $this->request = $request;

        return $this->getAdapter()->open($this->request);
    }

    /**
     * Returns the Adapter set to communicate with.
     * If none is set we will try to work with Curl.
     *
     * @return Adapter\AdapterInterface
     * @throws \Redbox\Imap\Exceptions\AdapterNotSupportedAdapter
     */
    public function getAdapter()
    {
        if (! $this->adapter) {
            $this->setAdapter(new DefaultAdapter);
        }

        return $this->adapter;
    }

    /**
     * Set the Transport adapter we will use to communicate with.
     *
     * @param Adapter\AdapterInterface $adapter
     * @throws \Redbox\Imap\Exceptions\AdapterNotSupportedAdapter
     */
    public function setAdapter($adapter)
    {
        /**
         * Not a adapter throws a BadFunctionCallException or true
         * if usable.
         */
        if ($adapter->verifySupport() === true) {
            $this->adapter = $adapter;
        } else {
            throw new AdapterNotSupportedAdapter('Adapter '.get_class($adapter).' is not supported on this installation');
        }
    }

    /**
     * @param string $message
     * @return mixed
     * @throws \Redbox\Imap\Exceptions\AdapterNotSupportedAdapter
     */
    public function send($message = '')
    {
        return $this->getAdapter()->send($message);
    }

    /**
     * @return mixed
     * @throws \Redbox\Imap\Exceptions\AdapterNotSupportedAdapter
     */
    public function read()
    {
        return $this->getAdapter()->read();
    }

    /**
     * @return mixed
     * @throws \Redbox\Imap\Exceptions\AdapterNotSupportedAdapter
     */
    public function close()
    {
        return $this->getAdapter()->close();
    }
}