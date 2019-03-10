<?php declare(strict_types=1);

namespace Redbox\Imap\Resources;

use Redbox\Imap\Client;
use Redbox\Imap\Utils\Factories\ResponseFactory;
use Redbox\Imap\Utils\Tag;

/**
 * Class ResourceAbstract
 *
 * @package Redbox\Imap\Resources
 */
class ResourceAbstract
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $method_name;

    /**
     * @var bool
     */
    private $requires_auth = false;

    /**
     * ResourceAbstract constructor.
     *
     * @param Client $client
     * @param string $method_name
     * @param bool $requires_auth
     */
    public function __construct(Client $client, $method_name, $requires_auth = true)
    {
        $this->client = $client;
        $this->method_name = $method_name;
        $this->requires_auth = $requires_auth;
    }

    /**
     * Call and process the 'virtual' method as defined in Client.php
     *
     * @param \Redbox\Imap\Utils\Tag $tag
     * @return bool|mixed|\Redbox\Imap\Utils\Response
     */
    public function call(Tag $tag)
    {
        if ($this->isRequiringAuth() && ! $this->getClient()->isAuthenticated()) {
            return $this->getClient()->executeAfterLogin([$this, 'call'], [$tag]);
        }

        return $this->sendTag($tag);
    }

    /**
     * Method to check if this resource requires to be logged in to
     * execute.
     *
     * @return bool
     */
    public function isRequiringAuth(): bool
    {
        return $this->requires_auth;
    }

    /**
     * Return the client.
     *
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * Send a single tag to the server.
     *
     * @param \Redbox\Imap\Utils\Tag $tag
     * @return bool|\Redbox\Imap\Utils\Response
     */
    public function sendTag(Tag $tag)
    {
        $this->client->getTransport()->send($tag);

        $input = $this->client->getTransport()->read();

        $response = ResponseFactory::parseResponse($tag->getPrefix(), $input);

        return $response;
    }

    /**
     * Return the method name.
     *
     * @return string
     */
    public function getMethodName(): string
    {
        return $this->method_name;
    }
}