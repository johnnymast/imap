<?php declare(strict_types=1);

namespace Redbox\Imap\Resources;

use Redbox\Imap\Log\LogLevel;
use Redbox\Imap\Utils\Factories\TagFactory;
use Redbox\Imap\Utils\Logger;

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
     * @return bool
     */
    public function logout(): bool
    {

        $options = $this->getClient()->getOptions();

        $tag = TagFactory::createTag('LOGOUT');

        $response = $this->call($tag);

        $didLogout = $response->isOk();

        if ($didLogout == true) {
            Logger::log(LogLevel::DEBUG, 'Logout successful for user {username}', ['username' => $options->username]);
            $this->getClient()->setAuthenticated(false);
        } else {
            Logger::log(LogLevel::DEBUG, 'Logout failed for user {username}', ['username' => $options->username]);
        }

        return $didLogout;
    }
}