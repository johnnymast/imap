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
class UnsubscribeResource extends ResourceAbstract
{
  /**
   * Arguments:  mailbox name
   *
   * Responses:  no specific responses for this command
   *
   * Result:     OK - unsubscribe completed
   *             NO - unsubscribe failure: can't unsubscribe that name
   *             BAD - command unknown or arguments invalid
   *
   * The UNSUBSCRIBE command removes the specified mailbox name from
   * the server's set of "active" or "subscribed" mailboxes as returned
   * by the LSUB command.  This command returns a tagged OK response
   * only if the unsubscription is successful.
   *
   * Example:    C: A002 UNSUBSCRIBE #news.comp.mail.mime
   * S: A002 OK UNSUBSCRIBE completed
   *
   * @param string $mailbox
   *
   * @return \Redbox\Imap\Utils\Response
   * @throws \Redbox\Imap\Exceptions\CommandNotSupportedException
   */
  public function unsubscribe($mailbox = ""): Response
  {
    
    $tag = TagFactory::createTag(sprintf('UNSUBSCRIBE  %s', $mailbox));
    
    $response = $this->call($tag);
    
    if($response->isBad()) {
      throw new CommandNotSupportedException('command unknown or arguments invalid');
    }
    
    if($response->isNo()) {
      Logger::log(LogLevel::DEBUG,
        'unsubscribe failure: can\'t unsubscribe to that name {name}',
        ['name' => $mailbox]);
    }
    
    if($response->isOk()) {
      Logger::log(LogLevel::DEBUG, 'UNSUBSCRIBE completed {name}',
        ['name' => $mailbox]);
    }
    
    return $response;
  }
}