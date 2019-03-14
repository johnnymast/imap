<?php declare(strict_types=1);

namespace Redbox\Imap\Resources;

use Redbox\Imap\Exceptions\CommandNotSupportedException;
use Redbox\Imap\Utils\Capabilities;
use Redbox\Imap\Utils\Factories\TagFactory;
use Redbox\Imap\Utils\Response;

/**
 * Class AuthenticateResource
 *
 * @package Redbox\Imap\Resources
 */
class CapabilityResource extends ResourceAbstract
{
    /**
     * Send the authenticate command to the imap server.
     *
     * @throws \Redbox\Imap\Exceptions\CommandNotSupportedException
     */
    public function capability(): ?Response
    {
        $tag = TagFactory::createTag('CAPABILITY');

        $response = $this->call($tag);

        if ($response->isBad()) {
            throw new CommandNotSupportedException('command unknown or arguments invalid');
        }

        if ($response->isStar()) {
            $capabilities = new Capabilities($response->getStatusline());

            return $capabilities->toArray();
        }

        return $response;
    }
}