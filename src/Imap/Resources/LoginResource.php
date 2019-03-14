<?php declare(strict_types=1);

namespace Redbox\Imap\Resources;

use Redbox\Imap\Log\LogLevel;
use Redbox\Imap\Utils\Factories\TagFactory;
use Redbox\Imap\Utils\Logger;
use Redbox\Imap\Utils\Response;

/**
 * Class LoginResource
 *
 * @package Redbox\Imap\Resources
 */
class LoginResource extends ResourceAbstract
{
    /**
     * Send the login command to the imap server.
     *
     * @return Response
     */
    public function login(): Response
    {
        if (! $this->getClient()->isAuthenticated()) {
            $client = $this->getClient();

            $options = $client->getOptions();

            $tag = TagFactory::createTag('LOGIN '.$options->username.' '.$options->password);

            $response = $this->sendTag($tag);

            $this->getClient()->setAuthenticated($response->isOk());

            if ($response->isOk() == true) {
                Logger::log(LogLevel::DEBUG, 'Authentication successful for user {username}',
                    ['username' => $options->username]);
            } else {
                Logger::log(LogLevel::DEBUG, 'Authentication failed for user {username}',
                    ['username' => $options->username]);
            }

            return $response;
        }
    }
}