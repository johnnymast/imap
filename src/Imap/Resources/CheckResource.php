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
class CheckResource extends ResourceAbstract
{
  /**
   * Arguments:  none
   *
   * Responses:  no specific responses for this command
   *
   * Result:     OK - check completed
   * BAD - command unknown or arguments invalid
   *
   * The CHECK command requests a checkpoint of the currently selected
   * mailbox.  A checkpoint refers to any implementation-dependent
   * housekeeping associated with the mailbox (e.g., resolving the
   * server's in-memory state of the mailbox with the state on its
   *
   *  disk) that is not normally executed as part of each command.  A
   *  checkpoint MAY take a non-instantaneous amount of real time to
   *  complete.  If a server implementation has no such housekeeping
   *  considerations, CHECK is equivalent to NOOP.
   *
   *  There is no guarantee that an EXISTS untagged response will happen
   *  as a result of CHECK.  NOOP, not CHECK, SHOULD be used for new
   *  message polling.
   *
   *  Example:    C: FXXZ CHECK
   *              S: FXXZ OK CHECK Completed
   *
   *
   * @return Response
   * @throws CommandNotSupportedException
   */
  public function check(): Response
  {
    $tag = TagFactory::createTag(sprintf('CHECK'));
    
    
    $response = $this->call($tag);
    
    if($response->isBad()) {
      throw new CommandNotSupportedException('command unknown or arguments invalid');
    }
    
    if($response->isOk() == true) {
      Logger::log(LogLevel::DEBUG, 'CHECK completed');
    }
    
    return $response;
  }
}