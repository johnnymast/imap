<?php


namespace Redbox\Imap\Utils\Responses;

use Redbox\Imap\Utils\Traits\ValueAwareTrait;

class LSubResponse
{
    use ValueAwareTrait;
    
    /**
     * ListResponse constructor.
     *
     * @param  array  $fields
     */
    public function __construct($fields = [])
    {
        $this->fields = [
          'flags' => [],
          'delimiter' => '',
          'name' => '',
        ];
    
        if (is_array($fields) == true) {
            $this->fields = array_merge($this->fields, $fields);
        }
    }
    
}