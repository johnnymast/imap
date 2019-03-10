<?php declare(strict_types=1);

namespace Redbox\Imap\Resources;

use Redbox\Imap\Log\LogLevel;
use Redbox\Imap\Utils\Factories\TagFactory;
use Redbox\Imap\Utils\Logger;

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
     * @return bool
     */
    public function login(): bool
    {
        if (! $this->getClient()->isAuthenticated()) {
            $client = $this->getClient();

            $options = $client->getOptions();

            $tag = TagFactory::createTag('LOGIN '.$options->username.' '.$options->password);

            $response = $this->sendTag($tag);

            $didAuthenticate = $response->isOk();

            $this->getClient()->setAuthenticated($didAuthenticate);

            if ($didAuthenticate == true) {
                Logger::log(LogLevel::DEBUG, 'Authentication successful for user {username}',
                    ['username' => $options->username]);
            } else {
                Logger::log(LogLevel::DEBUG, 'Authentication failed for user {username}',
                    ['username' => $options->username]);
            }

            return $didAuthenticate;
        }
    }
}