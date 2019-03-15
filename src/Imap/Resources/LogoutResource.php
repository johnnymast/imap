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
     * The LOGOUT command informs the server that the client is done with
     * the connection.  The server MUST send a BYE untagged response
     * before the (tagged) OK response, and then close the network
     * connection.
     *
     * Example:
     * C: A023 LOGOUT
     * S: * BYE IMAP4rev1 Server logging out
     * S: A023 OK LOGOUT completed
     * (Server and client then close the connection) *
     *
     * @return Response
     */
    public function logout(): Response
    {

        $options = $this->getClient()
            ->getOptions();

        $tag = TagFactory::createTag('LOGOUT');

        $response = $this->call($tag);

        if ($response->isOk() == true) {
            Logger::log(LogLevel::DEBUG, 'Logout successful for user {username}', ['username' => $options->username]);
            $this->getClient()
                ->setAuthenticated(false);
        } else {
            Logger::log(LogLevel::DEBUG, 'Logout failed for user {username}', ['username' => $options->username]);
        }

        return $response;
    }
}