<?php
/**
 * Created by PhpStorm.
 * User: jmast
 * Date: 6-3-2019
 * Time: 19:50
 */

namespace Redbox\Imap\Transport\Adapter;

use Redbox\Imap\Transport\TCPRequest;

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
     * @param \Redbox\Imap\Transport\TCPRequest $request
     * @return mixed
     */
    public function open(TCPRequest $request);

    /**
     * @return mixed
     */
    public function close();

    /**
     * @param string $message
     * @return mixed
     */
    public function send($message = '');

    /**
     * Read from the connection.
     *
     * @return mixed
     */
    public function read();
}
