<?php

namespace Redbox\Imap\Utils;

use Redbox\Imap\Utils\Traits\ValueAwareTrait;

/**
 * @property string username
 * @property string password
 * @property string host
 * @property integer port
 * @property bool secure
 * @property bool verbose
 * @property bool debug
 */
class Options
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
            'host' => '',
            'username' => '',
            'password' => '',
            'port' => 993,
            'secure' => true,
            'verbose' => false,
            'debug' => false,
        ];

        if (is_array($options) == true) {
            $this->fields = array_merge($this->fields, $options);
        }

        $this->fields['port'] = (int)$this->fields['port'];
        $this->fields['secure'] = $this->fields['secure'] == 'true' ? true: false;
        $this->fields['verbose'] = $this->fields['verbose'] == 'true' ? true: false;
        $this->fields['debug'] = $this->fields['debug'] == 'true' ? true: false;
    }

    /**
     * Validate the options.
     *
     * @return bool
     */
    public function validate(): bool
    {
        return (! empty($this->fields['host']) && ! empty($this->fields['username']) && ! empty($this->fields['password']));
    }
}