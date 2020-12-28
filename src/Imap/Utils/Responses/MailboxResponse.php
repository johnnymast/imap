<?php

namespace Redbox\Imap\Utils\Responses;

use Redbox\Imap\Utils\Traits\ValueAwareTrait;

class MailboxResponse
{
    use ValueAwareTrait;

    /**
     * MailboxResponse constructor.
     *
     * @param array $fields
     */
    public function __construct($fields = [])
    {
        $this->fields = [
            'flags' => [],
            'exists' => 0,
            'recent' => 0,
            'unseen' => 0,
            'permanentflags' => [],
            'uidvalidity' => '',
            'uidnext' => '',
        ];

        if (is_array($fields) == true) {
            $this->fields = array_merge($this->fields, $fields);
        }

        $this->fields['exists'] = (int)$this->fields['exists'];
        $this->fields['unseen'] = (int)$this->fields['unseen'];
    }
}