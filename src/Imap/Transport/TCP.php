<?php

namespace Redbox\Imap\Transport;

use Redbox\Imap\Transport\Adapter\Stream;
use Redbox\Imap\Transport\Adapter\Curl as DefaultAdapter;

class TCP implements TransportInterface
{
    public function sendRequest(TCPRequest $request)
    {
        // TODO: Implement sendRequest() method.
    }
}