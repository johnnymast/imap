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
class CreateResource extends ResourceAbstract
{
    /**
     * Send the authenticate command to the imap server.
     *
     * @param string $mailbox
     *
     * @return \Redbox\Imap\Utils\Response
     * @throws \Redbox\Imap\Exceptions\CommandNotSupportedException
     */
    public function create($mailbox = ""): Response
    {

        $tag = TagFactory::createTag(sprintf('CREATE %s', $mailbox));

        $response = $this->call($tag);

        if ($response->isBad()) {
            throw new CommandNotSupportedException('command unknown or arguments invalid');
        }

        if ($response->isNo()) {
            Logger::log(LogLevel::DEBUG, 'create failure: can\'t create mailbox with that name {name}',
                ['name' => $mailbox]);
        }

        if ($response->isOk()) {
            Logger::log(LogLevel::DEBUG, 'create completed {name}', ['name' => $mailbox]);
        }

        return $response;
    }
}