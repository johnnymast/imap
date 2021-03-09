<?php

namespace Redbox\Imap\Resources;

use Redbox\Imap\Exceptions\CommandNotSupportedException;
use Redbox\Imap\Log\LogLevel;
use Redbox\Imap\Utils\Factories\TagFactory;
use Redbox\Imap\Utils\Logger;
use Redbox\Imap\Utils\Response;

class SearchResource extends ResourceAbstract
{
    /**
     * Arguments:  OPTIONAL [CHARSET] specification
     * searching criteria (one or more)
     *
     * Responses:  REQUIRED untagged response: SEARCH
     *
     * Result:  OK - search completed
     *          NO - search error: can't search that [CHARSET] or
     *               criteria
     *          BAD - command unknown or arguments invalid
     *
     * The SEARCH command searches the mailbox for messages that match
     * the given searching criteria.  Searching criteria consist of one
     * or more search keys.  The untagged SEARCH response from the server
     * contains a listing of message sequence numbers corresponding to
     * those messages that match the searching criteria.
     *
     *
     * When multiple keys are specified, the result is the intersection
     * (AND function) of all the messages that match those keys.  For
     * example, the criteria DELETED FROM "SMITH" SINCE 1-Feb-1994 refers
     * to all deleted messages from Smith that were placed in the mailbox
     * since February 1, 1994.  A search key can also be a parenthesized
     * list of one or more search keys (e.g., for use with the OR and NOT
     * keys).
     *
     * Server implementations MAY exclude [MIME-IMB] body parts with
     * terminal content media types other than TEXT and MESSAGE from
     * consideration in SEARCH matching.
     *
     * The OPTIONAL [CHARSET] specification consists of the word
     * "CHARSET" followed by a registered [CHARSET].  It indicates the
     * [CHARSET] of the strings that appear in the search criteria.
     * [MIME-IMB] content transfer encodings, and [MIME-HDRS] strings in
     * [RFC-2822]/[MIME-IMB] headers, MUST be decoded before comparing
     * text in a [CHARSET] other than US-ASCII.  US-ASCII MUST be
     * supported; other [CHARSET]s MAY be supported.
     *
     * If the server does not support the specified [CHARSET], it MUST
     * return a tagged NO response (not a BAD).  This response SHOULD
     * contain the BADCHARSET response code, which MAY list the
     * [CHARSET]s supported by the server.
     *
     * @see https://tools.ietf.org/html/rfc3501#page-49
     *
     */
    public function search(...$arguments): Response
    {

        $args = '';

        if (count($arguments) > 0) {
            $args = implode(' ', $arguments);
        }

        $tag = TagFactory::createTag(sprintf('SEARCH "%s"', $args));

        $response = $this->call($tag);

        if ($response->isBad()) {
            throw new CommandNotSupportedException('command unknown or arguments invalid');
        }

        if ($response->isNo()) {
            Logger::log(LogLevel::DEBUG, 'search error: can\'t search that [CHARSET] or criteria',
                ['name' => $args]);
        }

        if ($response->isOk()) {
            Logger::log(LogLevel::DEBUG, 'SEARCH completed');
        }

        return $response;
    }
}