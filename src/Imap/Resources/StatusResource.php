<?php

namespace Redbox\Imap\Resources;

use Redbox\Imap\Exceptions\CommandNotSupportedException;
use Redbox\Imap\Exceptions\MissingArgumentException;
use Redbox\Imap\Log\LogLevel;
use Redbox\Imap\Utils\Factories\TagFactory;
use Redbox\Imap\Utils\Generators\StatusGenerator;
use Redbox\Imap\Utils\Logger;
use Redbox\Imap\Utils\Response;
use Redbox\Imap\Utils\Status;

/**
 * Class StatusResource
 *
 * @package Redbox\Imap\Resources
 */
class StatusResource extends ResourceAbstract
{
    /**
     * The STATUS command requests the status of the indicated mailbox.
     * It does not change the currently selected mailbox, nor does it
     * affect the state of any messages in the queried mailbox (in
     * particular, STATUS MUST NOT cause messages to lose the \Recent
     * flag).
     *
     * The STATUS command provides an alternative to opening a second
     * IMAP4rev1 connection and doing an EXAMINE command on a mailbox to
     * query that mailbox's status without deselecting the current
     * mailbox in the first IMAP4rev1 connection.
     *
     * Unlike the LIST command, the STATUS command is not guaranteed to
     * be fast in its response.  Under certain circumstances, it can be
     * quite slow.  In some implementations, the server is obliged to
     * open the mailbox read-only internally to obtain certain status
     * information.  Also unlike the LIST command, the STATUS command
     * does not accept wildcards. *
     *
     * Note: The STATUS command is intended to access the
     * status of mailboxes other than the currently selected
     * mailbox.  Because the STATUS command can cause the
     * mailbox to be opened internally, and because this
     * information is available by other means on the selected
     * mailbox, the STATUS command SHOULD NOT be used on the
     * currently selected mailbox.
     *
     * The STATUS command MUST NOT be used as a "check for new
     * messages in the selected mailbox" operation (refer to
     * sections 7, 7.3.1, and 7.3.2 for more information about
     * the proper method for new message checking).
     *
     * Because the STATUS command is not guaranteed to be fast
     * in its results, clients SHOULD NOT expect to be able to
     * issue many consecutive STATUS commands and obtain
     * reasonable performance. *
     *
     * The currently defined status data items that can be requested are:
     *
     * MESSAGES
     * The number of messages in the mailbox.
     *
     * RECENT
     * The number of messages with the \Recent flag set.
     *
     * UIDNEXT
     * The next unique identifier value of the mailbox.  Refer to
     * section 2.3.1.1 for more information.
     *
     * UIDVALIDITY
     * The unique identifier validity value of the mailbox.  Refer to
     * section 2.3.1.1 for more information.
     *
     * UNSEEN
     * The number of messages which do not have the \Seen flag set. *
     *
     * @param string $mailbox
     * @param string|array $data_status_items
     *
     * @throws \Redbox\Imap\Exceptions\CommandNotSupportedException
     * @throws \Redbox\Imap\Exceptions\MissingArgumentException
     *
     * @return \Redbox\Imap\Utils\Response
     */
    public function status(string $mailbox = '', $data_status_items = ''): Response
    {

        if (strlen($data_status_items) == 0) {
            throw new MissingArgumentException('status command is missing the required data_status_items argument.');
        }

        $tag = TagFactory::createTag(sprintf('STATUS %s (%s)', $mailbox, $data_status_items));

        $response = $this->call($tag);

        if ($response->isBad()) {
            throw new CommandNotSupportedException('command unknown or arguments invalid');
        }

        if ($response->isNo()) {
            Logger::log(LogLevel::DEBUG, 'status failure: no status for that name {name}', ['name' => $mailbox]);
        }

        if ($response->isOk()) {
            Logger::log(LogLevel::DEBUG, 'status completed {name} with data status items {data_status_items}',
                ['name' => $mailbox, 'data_status_items' => $data_status_items]);

            $parsed = [];

            foreach (StatusGenerator::parse($response->getUnparsedData()) as $item) {
                $parsed += $item;
            }

            $status = new Status($parsed);
            $response->setParsedData($status);
        }

        return $response;
    }
}