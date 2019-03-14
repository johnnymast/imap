<?php

namespace Redbox\Imap\Resources;

use Redbox\Imap\Utils\Factories\TagFactory;
use Redbox\Imap\Utils\Response;

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
     * @param string $reference
     * @param string $mailbox
     *
     * @return \Redbox\Imap\Utils\Response
     */
    public function list(string $reference = '', string $mailbox = ''): Response
    {
        $tag = TagFactory::createTag(sprintf('LIST  "%s" "%s"', $reference, $mailbox));

        $response = $this->call($tag);

        // print_r($response);

        //$response = $this->call('listTopGames', $args);
        //if (!$this->getClient()->isAuthenticated()) {
        //
        //}
        return $response;
    }
}