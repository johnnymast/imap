<?php declare(strict_types=1);

namespace Redbox\Imap\Resources;

use Redbox\Imap\Exceptions\CommandNotSupportedException;
use Redbox\Imap\Log\LogLevel;
use Redbox\Imap\Utils\Factories\TagFactory;
use Redbox\Imap\Utils\Logger;

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
     * @param string $name
     * @return bool
     * @throws \Redbox\Imap\Exceptions\CommandNotSupportedException
     */
    public function create($name = ""): bool
    {

        $tag = TagFactory::createTag(sprintf('CREATE %s', $name));

        $response = $this->call($tag);

        if ($response->isBad()) {
            throw new CommandNotSupportedException('command unknown or arguments invalid');
        }

        if ($response->isNo()) {
            Logger::log(LogLevel::DEBUG, 'create failure: can\'t create mailbox with that name {name}',
                ['name' => $name]);
        }

        if ($response->isOk()) {
            Logger::log(LogLevel::DEBUG, 'create completed {name}', ['name' => $name]);
        }

        return $response->isOk();
    }
}