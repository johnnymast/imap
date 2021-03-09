<?php

namespace Redbox\Imap\Transport;

use Redbox\Imap\Client;
use Redbox\Imap\Exceptions\AdapterNotSupportedAdapter;
use Redbox\Imap\Transport\Adapter\AdapterInterface;
use Redbox\Imap\Transport\Adapter\FSockAdapter;
use Redbox\Imap\Transport\Adapter\StreamAdapter;

class TCP implements TransportInterface
{
    /**
     * @var Client
     */
    protected Client $client;

    /**
     * @var TCPRequest
     */
    protected TCPRequest $request;

    /**
     * @var AdapterInterface
     */
    protected ?AdapterInterface $adapter = null;

    /**
     * TCP constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Return the Client object.
     *
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }


    /**
     * Return the default supported adapters.
     *
     * @return AdapterInterface[]
     */
    public function getDefaultAdapters(): array
    {
        return [
            new StreamAdapter(),
            new FSockAdapter(),
        ];
    }

    /**
     * Returns the Adapter set to communicate with.
     * If none is set we will try to work with Curl.
     *
     * @return Adapter\AdapterInterface
     * @throws AdapterNotSupportedAdapter
     */
    public function getAdapter(): ?AdapterInterface
    {
        if (!$this->adapter) {
            $defaultAdapters = $this->getDefaultAdapters();
            foreach ($defaultAdapters as $adapter) {
                if ($adapter->verifySupport() == true) {
                    $this->setAdapter($adapter);
                    break;
                }
            }
        }

        return $this->adapter;
    }

    /**
     * Set the Transport adapter we will use to communicate with.
     *
     * @param Adapter\AdapterInterface $adapter
     *
     * @throws AdapterNotSupportedAdapter
     */
    public function setAdapter(AdapterInterface $adapter): void
    {
        /**
         * Not a adapter throws a BadFunctionCallException or true
         * if usable.
         */
        if ($adapter->verifySupport() === true) {
            $this->adapter = $adapter;
        } else {
            throw new AdapterNotSupportedAdapter('Adapter ' . get_class($adapter) . ' is not supported on this installation');
        }
    }

    /**
     * Connect to the IMAP server.
     *
     * @param \Redbox\Imap\Transport\TCPRequest $request
     *
     * @return mixed
     * @throws AdapterNotSupportedAdapter
     */
    public function connect(TCPRequest $request)
    {
        $this->request = $request;

        return $this->getAdapter()
            ->open($this->request);
    }

    /**
     * Send a message over to the IMAP server.
     *
     * @param string $message
     *
     * @return mixed
     * @throws AdapterNotSupportedAdapter
     */
    public function send($message = '')
    {
        return $this->getAdapter()
            ->send($message);
    }

    /**
     * Read a message from the IMAP server.
     *
     * @return mixed
     * @throws AdapterNotSupportedAdapter
     */
    public function read()
    {
        return $this->getAdapter()
            ->read();
    }

    /**
     * Close the connection to the IMAP server.
     *
     * @return mixed
     * @throws AdapterNotSupportedAdapter
     */
    public function close()
    {
        return $this->getAdapter()
            ->close();
    }
}