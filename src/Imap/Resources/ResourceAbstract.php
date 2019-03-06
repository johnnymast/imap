<?php

namespace Redbox\Imap\Resources;

use Redbox\Imap\Client;

class ResourceAbstract
{
    /**
     * @var Redbox\Imap\Client
     */
    private $client;

    /**
     * @var string
     */
    private $resource_name;

    /**
     * ResourceAbstract constructor.
     *
     * @param Client $client
     * @param string $resource_name
     */
    public function __construct(Client $client, $resource_name)
    {
        $this->client = $client;
        $this->resource_name = $resource_name;
    }

    /**
     * Call and process the 'virtual' method as defined in Client.php
     *
     * @param string $method
     * @param array $arguments
     * @param array $body
     * @return mixed
     * @throws Exception\AuthorizationRequiredException
     * @throws Exception\RuntimeException
     */
    public function call($method, $arguments = [], $body = [])
    {

    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return string
     */
    public function getResourceName()
    {
        return $this->resource_name;
    }
}