<?php

namespace Redbox\Imap;

use Redbox\Imap\Resources\Auth;
use Redbox\Imap\Transport\TransportInterface;

class Client
{
    /**
     * @var TransportInterface
     */
    protected $transport;

    public function __construct()
    {
        $this->auth = new Auth(this, 'auth');
    }

    public function setTransport(TransportInterface $transport)
    {

    }

    public static function create($options)
    {
        return new static($options);
    }
}