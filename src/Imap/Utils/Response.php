<?php

namespace Redbox\Imap\Utils;

class Response
{
    /**
     * The Tag prefix.
     *
     * @var string
     */
    private $prefix = '';

    /**
     * @var bool
     */
    private $is_error = false;

    /**
     * Response text
     */
    private $respose_text = false;

    /**
     * @var string
     */
    private $data ='';

    /**
     * Response constructor.
     *
     * @param string $prefix
     * @param string $data
     */
    public function __construct(string $prefix, string $data)
    {
        $this->prefix = $prefix;
        $this->data = $data;
    }
}