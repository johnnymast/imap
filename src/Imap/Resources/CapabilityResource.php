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
class CapabilityResource extends ResourceAbstract
{
  /**
   * Arguments:  none
   *
   * Responses:  REQUIRED untagged response: CAPABILITY
   *
   * Result:     OK - capability completed
   * BAD - command unknown or arguments invalid
   *
   * The CAPABILITY command requests a listing of capabilities that the
   * server supports.  The server MUST send a single untagged
   * CAPABILITY response with "IMAP4rev1" as one of the listed
   * capabilities before the (tagged) OK response.
   *
   * A capability name which begins with "AUTH=" indicates that the
   * server supports that particular authentication mechanism.  All
   * such names are, by definition, part of this specification.  For
   * example, the authorization capability for an experimental
   * "blurdybloop" authenticator would be "AUTH=XBLURDYBLOOP" and not
   * "XAUTH=BLURDYBLOOP" or "XAUTH=XBLURDYBLOOP".
   *
   * Other capability names refer to extensions, revisions, or
   * amendments to this specification.  See the documentation of the
   * CAPABILITY response for additional information.  No capabilities,
   * beyond the base IMAP4rev1 set defined in this specification, are
   * enabled without explicit client action to invoke the capability.
   *
   * Client and server implementations MUST implement the STARTTLS,
   * LOGINDISABLED, and AUTH=PLAIN (described in [IMAP-TLS])
   * capabilities.  See the Security Considerations section for
   * important information.
   *
   * See the section entitled "Client Commands -
   *       Experimental/Expansion" for information about the form of site or
   * implementation-specific capabilities.
   *
   *
   * Example:    C: abcd CAPABILITY
   * S: * CAPABILITY IMAP4rev1 STARTTLS AUTH=GSSAPI
   * LOGINDISABLED
   * S: abcd OK CAPABILITY completed
   * C: efgh STARTTLS
   * S: efgh OK STARTLS completed
   * <TLS negotiation, further commands are under [TLS] layer>
   * C: ijkl CAPABILITY
   * S: * CAPABILITY IMAP4rev1 AUTH=GSSAPI AUTH=PLAIN
   * S: ijkl OK CAPABILITY completed
   *
   * @return Response
   * @throws CommandNotSupportedException
   */
  public function capability(): Response
  {
    $tag = TagFactory::createTag(sprintf('CAPABILITY'));
    
    
    $response = $this->call($tag);
    
    if($response->isBad()) {
      throw new CommandNotSupportedException('command unknown or arguments invalid');
    }
    
    if($response->isOk() == true) {
      Logger::log(LogLevel::DEBUG, 'Capability  completed');
    }
    
    return $response;
  }
  
}