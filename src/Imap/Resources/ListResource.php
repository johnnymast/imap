<?php

namespace Redbox\Imap\Resources;

class ListResource extends ResourceAbstract
{
    public function Authenticate()
    {
        $response = $this->call('listTopGames', $args);
        if (!$this->getClient()->isAuthenticated()) {

        }
    }
}