<?php

namespace Redbox\Imap\Utils\Traits;

trait ValueAwareTrait
{
    /**
     * Defined fields go in here.
     *
     * @var array
     */
    private $fields = [];

    /**
     * Its all magic right? This function will translate fields
     * into getter functions.
     *
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
     * Magically return the value of a field.
     *
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
     * When called upon like with a print_r or a var_dump it will return
     * the fields array.
     *
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