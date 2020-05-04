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
class NoopResource extends ResourceAbstract
{
  
  /**
   * Arguments:  none
   *
   * Responses:  no specific responses for this command (but see below)
   *
   * Result:     OK - noop completed
   * BAD - command unknown or arguments invalid
   *
   * The NOOP command always succeeds.  It does nothing.
   *
   * Since any command can return a status update as untagged data, the
   * NOOP command can be used as a periodic poll for new messages or
   * message status updates during a period of inactivity (this is the
   * preferred method to do this).  The NOOP command can also be used
   * to reset any inactivity autologout timer on the server.
   *
   * Example:    C: a002 NOOP
   * S: a002 OK NOOP completed
   * . . .
   * C: a047 NOOP
   * S: * 22 EXPUNGE
   * S: * 23 EXISTS
   * S: * 3 RECENT
   * S: * 14 FETCH (FLAGS (\Seen \Deleted))
   * S: a047 OK NOOP completed
   *
   * @return Response
   * @throws CommandNotSupportedException
   */
  public function noop(): Response
  {
    $tag = TagFactory::createTag(sprintf('NOOP'));
    
    
    $response = $this->call($tag);
    
    if($response->isBad()) {
      throw new CommandNotSupportedException('command unknown or arguments invalid');
    }
    
    if($response->isOk() == true) {
      Logger::log(LogLevel::DEBUG, 'Noop completed');
    }
    
    return $response;
  }
}