<?php

namespace Redbox\Imap\Transport;

use Redbox\Imap\Client;
use Redbox\Imap\Transport\Adapter\FSock as DefaultAdapter;

class TCP implements TransportInterface
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Adapter\AdapterInterface
     */
    protected $adapter;

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

    public function send(TCPRequest $request, $message = '')
    {

        $data = $this->getAdapter()->send($message);
        //$status_code = $this->getAdapter()->getHttpStatusCode();

        //$this->getAdapter()->close();
    }

    /**
     * Returns the Adapter set to communicate with.
     * If none is set we will try to work with Curl.
     *
     * @return Adapter\AdapterInterface
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
     */
    public function setAdapter($adapter)
    {
        /**
         * Not a adapter throws a BadFunctionCallException or true
         * if usable.
         */
        if ($adapter->verifySupport() === true) {
            $this->adapter = $adapter;
        }
    }
}