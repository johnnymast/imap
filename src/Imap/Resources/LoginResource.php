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
     * The LOGIN command identifies the client to the server and carries
     * the plaintext password authenticating this user.
     *
     * A server MAY include a CAPABILITY response code in the tagged OK
     * response to a successful LOGIN command in order to send
     * capabilities automatically.  It is unnecessary for a client to
     * send a separate CAPABILITY command if it recognizes these
     * automatic capabilities.
     *
     * Example:
     *
     * C: a001 LOGIN SMITH SESAME
     * S: a001 OK LOGIN completed
     *
     * Note: Use of the LOGIN command over an insecure network
     * (such as the Internet) is a security risk, because anyone
     * monitoring network traffic can obtain plaintext passwords.
     * The LOGIN command SHOULD NOT be used except as a last
     * resort, and it is recommended that client implementations
     * have a means to disable any automatic use of the LOGIN
     * command.
     *
     * Unless either the STARTTLS command has been negotiated or
     * some other mechanism that protects the session from
     * password snooping has been provided, a server
     * implementation MUST implement a configuration in which it
     * advertises the LOGINDISABLED capability and does NOT permit
     * the LOGIN command.  Server sites SHOULD NOT use any
     * configuration which permits the LOGIN command without such
     * a protection mechanism against password snooping.  A client
     * implementation MUST NOT send a LOGIN command if the
     * LOGINDISABLED capability is advertised. *
     *
     * @param string $username
     * @param string $password
     *
     * @return Response
     */
    public function login(string $username, string $password): Response
    {
        if (! $this->getClient()->isAuthenticated()) {

            if (strlen($username) == 0 || strlen($password) == 0) {
                throw new MissingArgumentException('LOGIN command is missing the username or password argument.');
            }

            $tag = TagFactory::createTag('LOGIN '.$username.' '.$password);

            $response = $this->sendTag($tag);

            $this->getClient()
                ->setAuthenticated($response->isOk());

            if ($response->isOk() == true) {
                Logger::log(LogLevel::DEBUG, 'Authentication successful for user {username}',
                    ['username' => $username]);
            } else {
                Logger::log(LogLevel::DEBUG, 'Authentication failed for user {username}', ['username' => $username]);
            }

            return $response;
        }
    }
}