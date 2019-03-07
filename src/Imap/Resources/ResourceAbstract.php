<?php declare(strict_types=1);

namespace Redbox\Imap\Resources;

use Redbox\Imap\Client;
use Redbox\Imap\Transport\TCPRequest;
use Redbox\Imap\Utils\Factories\TagFactory;

class ResourceAbstract
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $resource_name;

    /**
     * @var array|mixed
     */
    private $methods = [];

    /**
     * ResourceAbstract constructor.
     *
     * @param Client $client
     * @param string $resource_name
     * @param array $declaration
     */
    public function __construct(Client $client, $resource_name, $declaration = [])
    {
        $this->client = $client;
        $this->resource_name = $resource_name;
        $this->methods = $declaration['methods'] ?? [];
    }

    /**
     * Call and process the 'virtual' method as defined in Client.php
     *
     * @param string $method
     * @param array $arguments
     * @param array $body
     * @return mixed
     */
    public function call($method, $arguments = [], $body = [])
    {

        $options = $this->getClient()->getOptions();

        print_r($options);
        $request = new TCPRequest($options['host'], (int)$options['port'], (bool)$options['secure']);

        $this->client->getTransport()->getAdapter()->open($request);

        $this->client->getTransport()->send($request,
            TagFactory::createTag('LOGIN '.$options['username'].' '.$options['password']));

        $this->client->getTransport()->send($request, TagFactory::createTag('LIST "" "*"'));

        var_dump(TagFactory::get());

        TagFactory::clear();

        print_r(TagFactory::get());
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @return string
     */
    public function getResourceName(): string
    {
        return $this->resource_name;
    }
}