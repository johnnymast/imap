<?php
/**
 * Created by PhpStorm.
 * User: jmast
 * Date: 6-3-2019
 * Time: 19:50
 */

namespace Redbox\Imap\Transport\Adapter;

interface AdapterInterface
{
    /**
     * Since PSR-4 does not allow constructors to throw exceptions
     * we need to get creative. Every Adapter needs to verify that it can
     * be used.
     *
     * @throws BadFunctionCallException
     * @return bool
     */
    public function verifySupport();

    /**
     * @return mixed
     */
    public function open();

    /**
     * @return mixed
     */
    public function close();

    /**
     * @param $address
     * @param $method
     * @param null $body
     * @return mixed
     */
    public function send($address, $method, $headers = null, $body = null);

    /**
     * @return mixed
     */
    public function getHttpStatusCode();

    /**
     * @return mixed
     */
    public function getContentType();
}
