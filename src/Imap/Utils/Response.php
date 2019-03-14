<?php

namespace Redbox\Imap\Utils;

/**
 * Class Response
 *
 * @package Redbox\Imap\Utils
 */
class Response
{
    const STATUS_WILDCARD = '*';

    const STATUS_BAD = 'OK';

    const STATUS_NO = 'NO';

    const STATUS_OK = 'OK';

    /**
     * The Tag prefix.
     *
     * @var string
     */
    private $prefix = '';

    /**
     * Is this response a bad reply?
     *
     * @var bool
     */
    private $is_bad = false;

    /**
     * Is this response an OK reply?
     *
     * @var bool
     */
    private $is_ok = false;

    /**
     * Is this response a NO reply?
     *
     * @var bool
     */
    private $is_no = false;

    /**
     * Is this response a STAR reply?
     *
     * @var bool
     */
    private $is_star = false;

    /**
     * Response text
     */
    private $status_text = '';

    /**
     * @var string
     */
    private $unparsed_data = '';

    /**
     * @var null
     */
    private $parsed_data = null;

    /**
     * @var string
     */
    private $response_status = 'UNKNOWN';

    /**
     * Response constructor.
     *
     * @param string $prefix
     * @param string $status_text
     * @param string $unparsed_data
     */
    public function __construct(string $prefix, string $status_text = '', string $unparsed_data = '')
    {
        $this->prefix = $prefix;
        $this->status_text = $status_text;
        $this->unparsed_data = $unparsed_data;

        if (substr($this->status_text, 0, 3) === self::STATUS_BAD) {
            $this->setResponseStatus(self::STATUS_BAD);
            $this->is_bad = true;
        }

        if (substr($this->status_text, 0, 2) === self::STATUS_OK) {
            $this->setResponseStatus(self::STATUS_OK);
            $this->is_ok = true;
        }

        if (substr($this->status_text, 0, 2) === self::STATUS_NO) {
            $this->setResponseStatus(self::STATUS_NO);
            $this->is_no = true;
        }

        if (substr($this->status_text, 0, 1) === self::STATUS_WILDCARD) {
            $this->setResponseStatus(self::STATUS_WILDCARD);
            $this->is_star = true;
        }
    }

    /**
     * Return the prefix for this response.
     *
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * Is this a BAD reply?
     *
     * @return bool
     */
    public function isBad(): bool
    {

        return $this->is_bad;
    }

    /**
     * Is this an OK reply?
     *
     * @return bool
     */
    public function isOk(): bool
    {
        return $this->is_ok;
    }

    /**
     * Is this a NO reply?
     *
     * @return bool
     */
    public function isNo(): bool
    {
        return $this->is_no;
    }

    /**
     * Is this a Wildcard reply?
     *
     * @return bool
     */
    public function isStar(): bool
    {
        return $this->is_star;
    }

    /**
     * @return string
     */
    public function getResponseStatus(): string
    {
        return $this->response_status;
    }

    /**
     * @param string $response_status
     */
    public function setResponseStatus(string $response_status): void
    {
        $this->response_status = $response_status;
    }

    /**
     * @return mixed
     */
    public function getStatusText()
    {
        return $this->status_text;
    }

    /**
     * @return string
     */
    public function getUnparsedData(): string
    {
        return $this->unparsed_data;
    }

    /**
     * @param string $unparsed_data
     */
    public function setUnparsedData(string $unparsed_data): void
    {
        $this->unparsed_data = $unparsed_data;
    }

    /**
     * @return null
     */
    public function getParsedData()
    {
        return $this->parsed_data;
    }

    /**
     * @param null $parsed_data
     */
    public function setParsedData($parsed_data): void
    {
        $this->parsed_data = $parsed_data;
    }
}