<?php

namespace Redbox\Imap\Resources;

use Redbox\Imap\Exceptions\CommandNotSupportedException;
use Redbox\Imap\Utils\Generators\ListGenerator;
use Redbox\Imap\Utils\Responses\ListResponse;
use Redbox\Imap\Utils\Factories\TagFactory;
use Redbox\Imap\Utils\MailboxResponse;
use Redbox\Imap\Utils\Response;
use Redbox\Imap\Utils\Logger;
use Redbox\Imap\Log\LogLevel;
use Redbox\Imap\Utils\Tag;

/**
 * Class ListResource
 *
 * @package Redbox\Imap\Resources
 */
class ListResource extends ResourceAbstract
{
    /**
     * Send the list command to the server.
     *
     *
     * @param  string  $reference
     * @param  string  $mailbox
     *
     * @return \Redbox\Imap\Utils\Response
     */
    public function list(string $reference = '', string $mailbox = ''): Response
    {
        $tag = TagFactory::createTag(sprintf('LIST  "%s" "%s"', $reference, $mailbox));
        
        $response = $this->call($tag);
        
        if ($response->isBad()) {
            throw new CommandNotSupportedException('command unknown or arguments invalid');
        }
        
        if ($response->isNo()) {
            Logger::log(LogLevel::DEBUG, 'list failure: can\'t list that reference or name {name}',
              ['name' => $mailbox]);
        }
        
        if ($response->isOk()) {
            Logger::log(LogLevel::DEBUG, 'Listed mailboxes');
            
            $parsed = [];
            $data = explode(Tag::CLRF, $response->getUnparsedData());
            
            foreach ($data as $line) {
                foreach (ListGenerator::parse(trim($line)) as $item) {
                    if (is_array($item)) {
                        $parsed [] = new ListResponse($item);
                    }
                }
            }
            
            $list = $parsed;
            $response->setParsedData($list);
        }
        
        return $response;
    }
}