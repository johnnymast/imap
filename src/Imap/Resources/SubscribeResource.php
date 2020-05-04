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
class SubscribeResource extends ResourceAbstract
{
  /**
   * Arguments:  mailbox
   *
   * Responses:  no specific responses for this command
   *
   * Result:     OK - subscribe completed
   * NO - subscribe failure: can't subscribe to that name
   *                BAD - command unknown or arguments invalid
   *
   * The SUBSCRIBE command adds the specified mailbox name to the
   * server's set of "active" or "subscribed" mailboxes as returned by
   * the LSUB command.  This command returns a tagged OK response only
   * if the subscription is successful.
   *
   * A server MAY validate the mailbox argument to SUBSCRIBE to verify
   * that it exists.  However, it MUST NOT unilaterally remove an
   * existing mailbox name from the subscription list even if a mailbox
   * by that name no longer exists.
   *
   * Note: This requirement is because a server site can
   * choose to routinely remove a mailbox with a well-known
   * name (e.g., "system-alerts") after its contents expire,
   * with the intention of recreating it when new contents
   * are appropriate.
   *
   *
   * Example:    C: A002 SUBSCRIBE #news.comp.mail.mime
   *             S: A002 OK SUBSCRIBE completed
   *
   * @param string $mailbox
   *
   * @return \Redbox\Imap\Utils\Response
   * @throws \Redbox\Imap\Exceptions\CommandNotSupportedException
   */
  public function subscribe($mailbox = ""): Response
  {
    
    $tag = TagFactory::createTag(sprintf('SUBSCRIBE  %s', $mailbox));
    
    $response = $this->call($tag);
    
    if($response->isBad()) {
      throw new CommandNotSupportedException('command unknown or arguments invalid');
    }
    
    if($response->isNo()) {
      Logger::log(LogLevel::DEBUG,
        'subscribe failure: can\'t subscribe to that name {name}',
        ['name' => $mailbox]);
    }
    
    if($response->isOk()) {
      Logger::log(LogLevel::DEBUG, 'subscribe completed {name}',
        ['name' => $mailbox]);
    }
    
    return $response;
  }
}