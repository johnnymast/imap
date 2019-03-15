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
     * @param string $message
     * @return mixed
     */
    public function send($message = '');

    /**
     * @return mixed
     */
    public function read();

    /**
     * @return mixed
     */
    public function close();
}