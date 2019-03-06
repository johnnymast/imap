<?php

namespace Redbox\Imap\Transport;

interface TransportInterface
{
    /**
     * @param $request
     * @return mixed
     */
    public function sendRequest(TCPRequest $request);
}