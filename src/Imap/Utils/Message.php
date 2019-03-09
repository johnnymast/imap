<?php declare(strict_types=1);

namespace Redbox\Imap\Utils;

class Message
{
    /**
     * This message is flagged as 'seen'
     */
    public const SEEN = 'Seen';

    /**
     * This message is flagged as 'Answered'
     */
    public const ANSWERED = 'Answered';

    /**
     * This message is flagged as 'Flagged'
     */
    public const FLAGGED = 'Flagged';

    /**
     * This message is flagged as 'Deleted'
     */
    public const DELETED = 'Deleted';

    /**
     * This message is flagged as 'Draft'
     */
    public const DRAFT = 'Draft';

    /**
     * This message is flagged as 'Recent'
     */
    public const RECENT = 'Recent';

    protected $uid = '';

    protected $id = '';


}