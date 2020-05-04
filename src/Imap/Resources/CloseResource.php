<?php


namespace Redbox\Imap\Resources;

use Redbox\Imap\Exceptions\CommandNotSupportedException;
use Redbox\Imap\Log\LogLevel;
use Redbox\Imap\Utils\Factories\TagFactory;
use Redbox\Imap\Utils\Logger;
use Redbox\Imap\Utils\Response;

/**
 * Class NoopResource
 *
 * @package Redbox\Imap\Resources
 */
class CloseResource extends ResourceAbstract
{
  
  /**
   * Arguments:  none
   *
   * Responses:  no specific responses for this command
   *
   * Result:     OK - close completed, now in authenticated state
   * BAD - command unknown or arguments invalid
   *
   * The CLOSE command permanently removes all messages that have the
   * \Deleted flag set from the currently selected mailbox, and returns
   * to the authenticated state from the selected state.  No untagged
   * EXPUNGE responses are sent.
   *
   * No messages are removed, and no error is given, if the mailbox is
   * selected by an EXAMINE command or is otherwise selected read-only.
   *
   * Even if a mailbox is selected, a SELECT, EXAMINE, or LOGOUT
   * command MAY be issued without previously issuing a CLOSE command.
   * The SELECT, EXAMINE, and LOGOUT commands implicitly close the
   * currently selected mailbox without doing an expunge.  However,
   * when many messages are deleted, a CLOSE-LOGOUT or CLOSE-SELECT
   * sequence is considerably faster than an EXPUNGE-LOGOUT or
   * EXPUNGE-SELECT because no untagged EXPUNGE responses (which the
   * client would probably ignore) are sent.
   *
   * Example:    C: A341 CLOSE
   *             S: A341 OK CLOSE completed
   *
   *
   * @return Response
   * @throws CommandNotSupportedException
   */
  public function close(): Response
  {
    $tag = TagFactory::createTag(sprintf('CLOSE'));
    
    
    $response = $this->call($tag);
    
    if($response->isBad()) {
      throw new CommandNotSupportedException('command unknown or arguments invalid');
    }
    
    if($response->isOk() == true) {
      Logger::log(LogLevel::DEBUG, 'CLOSE completed');
    }
    
    return $response;
  }
}