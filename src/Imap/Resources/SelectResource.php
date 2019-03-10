<?php

namespace Redbox\Imap\Resources;

use Redbox\Imap\Log\LogLevel;
use Redbox\Imap\Utils\Factories\TagFactory;
use Redbox\Imap\Utils\Logger;

/**
 * Class ListResource
 *
 * @package Redbox\Imap\Resources
 */
class SelectResource extends ResourceAbstract
{
    /**
     * @param string $mailbox
     * @return bool
     */
    public function select($mailbox = '')
    {
        $tag = TagFactory::createTag(sprintf('SELECT "%s"', $mailbox));

        $response = $this->call($tag);

        if ($response->isOk()) {
            Logger::log(LogLevel::DEBUG, ' Selected mailbox {mailbox}', ['mailbox' => $mailbox]);
        }

        return $response->isOk();
    }
}