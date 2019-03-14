<?php declare(strict_types=1);

namespace Redbox\Imap\Resources;

use Redbox\Imap\Exceptions\CommandNotSupportedException;
use Redbox\Imap\Utils\Factories\TagFactory;
use Redbox\Imap\Utils\Response;

/**
 * Class AuthenticateResource
 *
 * @package Redbox\Imap\Resources
 */
class AuthenticateResource extends ResourceAbstract
{
    /**
     * Send the authenticate command to the imap server.
     *
     * @return Response
     * @throws \Redbox\Imap\Exceptions\CommandNotSupportedException
     */
    public function authenticate(): Response
    {
        $client = $this->getClient();

        $tag = TagFactory::createTag('AUTHENTICATE');

        $response = $this->sendTag($tag);

        if ($response->isBad()) {
            throw new CommandNotSupportedException('command unknown or arguments invalid, authentication exchange cancelled');
        }

        if ($response->isNo()) {
            $client->setAuthenticated(false);
        }

        return $response;
    }
}