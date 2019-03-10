<?php

namespace Redbox\Imap\Utils;

/**
 * Class Response
 *
 * @package Redbox\Imap\Utils
 */
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
     * @var bool
     */
    private $is_ok = false;

    /**
     * @var bool
     */
    private $is_no = false;

    /**
     * Response text
     */
    private $response_text = '';

    /**
     * Response constructor.
     *
     * @param string $prefix
     * @param string $response_text
     */
    public function __construct(string $prefix, string $response_text = '')
    {
        $this->prefix = $prefix;
        $this->response_text = $response_text;

        if (substr($response_text, 0, 3) === 'BAD') {
            $this->is_error = true;
        }

        if (substr($response_text, 0, 1) !== 'OK') {
            $this->is_ok = true;
        }

        if (substr($response_text, 0, 1) !== 'NO') {
            $this->is_no = true;
        }
    }

    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * @return bool
     */
    public function isError(): bool
    {

        return $this->is_error;
    }

    /**
     * @return bool
     */
    public function isOk(): bool
    {
        return $this->is_ok;
    }

    /**
     * @return bool
     */
    public function isNo(): bool
    {
        return $this->is_no;
    }

    /**
     * @return mixed
     */
    public function getResponseText()
    {
        return $this->response_text;
    }
}