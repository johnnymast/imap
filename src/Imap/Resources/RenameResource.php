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
class RenameResource extends ResourceAbstract
{
    /**
     * Send the SUBSCRIBE command to the imap server.
     *
     * @param string $mailbox
     * @param string $newname
     *
     * @return \Redbox\Imap\Utils\Response
     * @throws \Redbox\Imap\Exceptions\CommandNotSupportedException
     */
    public function rename($mailbox = "", $newname = ''): Response
    {

        $tag = TagFactory::createTag(sprintf('RENAME "%s" "%s"', $mailbox, $newname));

        $response = $this->call($tag);

        if ($response->isBad()) {
            throw new CommandNotSupportedException('command unknown or arguments invalid');
        }

        if ($response->isNo()) {
            Logger::log(LogLevel::DEBUG,
                'rename failure: can\'t rename mailbox with that name, can\'t rename to mailbox with that name {name} to {newname}',
                ['name' => $mailbox, 'to' => $newname]);
        }

        if ($response->isOk()) {
            Logger::log(LogLevel::DEBUG, 'rename completed {name} to {newname}',
                ['name' => $mailbox, 'newname' => $newname]);
        }

        return $response;
    }
}