<?php

namespace Redbox\Imap\Utils;

use Redbox\Imap\Utils\Traits\ValueAwareTrait;

class Mailbox
{
    use ValueAwareTrait;

    /**
     * Options constructor.
     *
     * @param array $options
     */
    public function __construct($options = [])
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

        if (is_array($options) == true) {
            $this->fields = array_merge($this->fields, $options);
        }

        $this->fields['exists'] = (int)$this->fields['exists'];
        $this->fields['unseen'] = (int)$this->fields['unseen'];
    }
}