<?php

namespace Redbox\Imap\Transport;

interface TransportInterface
{
    /**
     * @param \Redbox\Imap\Transport\TCPRequest $request
     * @param string $message
     * @return mixed
     */
    public function send(TCPRequest $request, $message = '');
}