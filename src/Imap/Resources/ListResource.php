<?php

namespace Redbox\Imap\Resources;

use Redbox\Imap\Utils\Factories\TagFactory;

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
     * @param array $arguments
     */
    public function list($arguments = [])
    {
        $tag = TagFactory::createTag('LIST "" "INBOX"');

        $response = $this->call($tag);

        // print_r($response);

        //$response = $this->call('listTopGames', $args);
        //if (!$this->getClient()->isAuthenticated()) {
        //
        //}
    }
}