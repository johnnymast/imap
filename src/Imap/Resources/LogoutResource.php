<?php declare(strict_types=1);

namespace Redbox\Imap\Resources;

use Redbox\Imap\Log\LogLevel;
use Redbox\Imap\Utils\Factories\TagFactory;
use Redbox\Imap\Utils\Logger;
use Redbox\Imap\Utils\Response;

/**
 * Class LogoutResource
 *
 * @package Redbox\Imap\Resources
 */
class LogoutResource extends ResourceAbstract
{
    /**
     * Send the logout command to the imap server.
     *
     * @return Response
     */
    public function logout(): Response
    {

        $options = $this->getClient()->getOptions();

        $tag = TagFactory::createTag('LOGOUT');

        $response = $this->call($tag);

        if ($response->isOk() == true) {
            Logger::log(LogLevel::DEBUG, 'Logout successful for user {username}', ['username' => $options->username]);
            $this->getClient()->setAuthenticated(false);
        } else {
            Logger::log(LogLevel::DEBUG, 'Logout failed for user {username}', ['username' => $options->username]);
        }

        return $response;
    }
}