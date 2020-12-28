<?php

namespace Redbox\Imap\Resources;

use Redbox\Imap\Log\LogLevel;
use Redbox\Imap\Utils\Factories\TagFactory;
use Redbox\Imap\Utils\Generators\MailboxGenerator;
use Redbox\Imap\Utils\Logger;
use Redbox\Imap\Utils\MailboxResponse;
use Redbox\Imap\Utils\Response;

/**
 * Class ListResource
 *
 * @package Redbox\Imap\Resources
 */
class SelectResource extends ResourceAbstract
{
    /**
     * @param string $mailbox
     *
     * @return Response
     */
    public function select(string $mailbox = ''): Response
    {
        $tag = TagFactory::createTag(sprintf('SELECT "%s"', $mailbox));

        $response = $this->call($tag);

        if ($response->isOk()) {
            Logger::log(LogLevel::DEBUG, 'Selected mailbox {mailbox}', ['mailbox' => $mailbox]);

            $parsed = [];

            foreach (MailboxGenerator::parse($response->getUnparsedData()) as $item) {
                $parsed += $item;
            }

            $mailbox = new MailboxResponse($parsed);
            $response->setParsedData($mailbox);
        }

        return $response;
    }
}