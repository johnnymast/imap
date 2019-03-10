<?php

namespace Redbox\Imap\Utils\Traits;

trait ValueAwareTrait
{
    /**
     * @var array
     */
    private $fields = [];

    /**
     * @param $name
     * @param $arguments
     *
     * @return mixed
     */
    function __call($name, $arguments)
    {
        if (isset($this->fields[$name]) == true) {
            return $this->fields[$name];
        }

        return null;
    }

    /**
     * @param $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        if (isset($this->fields[$name]) == true) {
            return $this->fields[$name];
        }

        return null;
    }

    /**
     * @return array
     */
    public function __debugInfo()
    {
        return $this->fields;
    }

    /**
     * Return the fields as array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->fields;
    }

    /**
     * Return the fields as json string.
     *
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->fields);
    }
}