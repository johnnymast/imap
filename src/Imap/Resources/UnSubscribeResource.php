<?php declare(strict_types=1);

namespace Redbox\Imap\Resources;

use Redbox\Imap\Exceptions\CommandNotSupportedException;
use Redbox\Imap\Log\LogLevel;
use Redbox\Imap\Utils\Factories\TagFactory;
use Redbox\Imap\Utils\Logger;
use Redbox\Imap\Utils\Response;

/**
 * Class AuthenticateResource
 *
 * @package Redbox\Imap\Resources
 */
class UnSubscribeResource extends ResourceAbstract
{
    /**
     * Send the UNSUBSCRIBE command to the imap server.
     *
     * @param string $mailbox
     *
     * @return \Redbox\Imap\Utils\Response
     * @throws \Redbox\Imap\Exceptions\CommandNotSupportedException
     */
    public function unsubscribe($mailbox = ""): Response
    {

        $tag = TagFactory::createTag(sprintf('UNSUBSCRIBE  %s', $mailbox));

        $response = $this->call($tag);

        if ($response->isBad()) {
            throw new CommandNotSupportedException('command unknown or arguments invalid');
        }

        if ($response->isNo()) {
            Logger::log(LogLevel::DEBUG, 'unsubscribe failure: can\'t unsubscribe that name {name}',
                ['name' => $mailbox]);
        }

        if ($response->isOk()) {
            Logger::log(LogLevel::DEBUG, 'unsubscribe completed {name}', ['name' => $mailbox]);
        }

        return $response;
    }
}