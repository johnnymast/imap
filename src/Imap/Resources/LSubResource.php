<?php

declare(strict_types=1);

namespace Redbox\Imap\Resources;

use Redbox\Imap\Exceptions\CommandNotSupportedException;
use Redbox\Imap\Log\LogLevel;
use Redbox\Imap\Utils\Factories\TagFactory;
use Redbox\Imap\Utils\Generators\LSubGenerator;
use Redbox\Imap\Utils\Logger;
use Redbox\Imap\Utils\Response;
use Redbox\Imap\Utils\Responses\LSubResponse;
use Redbox\Imap\Utils\Tag;

/**
 * Class AuthenticateResource
 *
 * @package Redbox\Imap\Resources
 */
class LSubResource extends ResourceAbstract
{
    /**
     * Send the UNSUBSCRIBE command to the imap server.
     *
     * @param  string  $name
     * @param  string  $mailbox
     *
     * @return \Redbox\Imap\Utils\Response
     * @throws \Redbox\Imap\Exceptions\CommandNotSupportedException
     */
    public function lsub($name = '', $mailbox = ""): Response
    {
        $tag = TagFactory::createTag(sprintf('LSUB "%s" "%s"', $name, $mailbox));
        
        $response = $this->call($tag);
        
        if ($response->isBad()) {
            throw new CommandNotSupportedException('command unknown or arguments invalid');
        }
        
        if ($response->isNo()) {
            Logger::log(LogLevel::DEBUG, 'lsub failure: can\'t list that reference or name {name}',
              ['name' => $mailbox]);
        }
        
        if ($response->isOk()) {
            Logger::log(LogLevel::DEBUG, 'lsub completed {name}', ['name' => $mailbox]);
            
            $parsed = [];
            $data = explode(Tag::CLRF, $response->getUnparsedData());
            
            foreach ($data as $line) {
                foreach (LSubGenerator::parse(trim($line)) as $item) {
                    if (is_array($item)) {
                        $parsed [] = new LSubResponse($item);
                    }
                }
            }
            
            $list = $parsed;
            $response->setParsedData($list);
        }
        
        return $response;
    }
}