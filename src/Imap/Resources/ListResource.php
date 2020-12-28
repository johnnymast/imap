<?php

namespace Redbox\Imap\Resources;

use Redbox\Imap\Log\LogLevel;
use Redbox\Imap\Utils\Factories\TagFactory;
use Redbox\Imap\Utils\Generators\ListGenerator;
use Redbox\Imap\Utils\Generators\MailboxGenerator;
use Redbox\Imap\Utils\Logger;
use Redbox\Imap\Utils\MailboxResponse;
use Redbox\Imap\Utils\Response;
use Redbox\Imap\Utils\Responses\ListResponse;
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
    
            print_r($parsed);
            
            $list = $parsed;

//            $mailbox = new ListResponse($parsed);
            $response->setParsedData($list);
        }
        // print_r($response);
        
        //$response = $this->call('listTopGames', $args);
        //if (!$this->getClient()->isAuthenticated()) {
        //
        //}
        return $response;
    }
}