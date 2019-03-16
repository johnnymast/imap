<?php

namespace Redbox\Imap\Transport;

interface TransportInterface
{
    /**
     * @param \Redbox\Imap\Transport\TCPRequest $request
     *
     * @return mixed
     */
    public function connect(TCPRequest $request);

    /**
     * Send data on the connection.
     *
     * @param string $message
     *
     * @return mixed
     */
    public function send(string $message = '');

    /**
     * @return mixed
     */
    public function read();

    /**
     * Close the connection.
     *
     * @return mixed
     */
    public function close();
}