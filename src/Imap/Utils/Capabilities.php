<?php

namespace Redbox\Imap\Utils;

class Capabilities
{
    protected $capabilities = [];

    public function __construct($capabilities = '')
    {
        if (is_string($capabilities)) {
            $capabilities = explode(' ', $capabilities);

            foreach ($capabilities as $capability) {
                if (strpos($capability, '=') > -1) {
                    $this->capabilities[] = explode('=', $capability);
                } else {
                    $this->capabilities[] = $capability;
                }
            }
        }
    }

    public function toArray()
    {
        return $this->capabilities;
    }
}