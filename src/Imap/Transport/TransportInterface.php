<?php

namespace Redbox\Imap\Transport;

interface TransportInterface
{
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