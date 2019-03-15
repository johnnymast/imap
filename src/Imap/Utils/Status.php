<?php

namespace Redbox\Imap\Utils;

use Redbox\Imap\Utils\Traits\ValueAwareTrait;

class Status
{
    use ValueAwareTrait;

    /**
     * Status constructor.
     *
     * @param array $fields
     */
    public function __construct($fields = [])
    {
        $this->fields = [
            'messages' => 0,
            'recent' => 0,
            'uidnext' => 0,
            'uidvalidity' => 0,
            'unseen' => 0,
        ];

        if (is_array($fields) == true) {
            $this->fields = array_merge($this->fields, $fields);
        }

        $this->fields['uidvalidity'] = (int)$this->fields['uidvalidity'];
        $this->fields['messages'] = (int)$this->fields['messages'];
        $this->fields['uidnext'] = (int)$this->fields['uidnext'];
        $this->fields['recent'] = (int)$this->fields['recent'];
        $this->fields['unseen'] = (int)$this->fields['unseen'];
    }
}