<?php
/**
 * Created by PhpStorm.
 * User: jmast
 * Date: 6-3-2019
 * Time: 19:51
 */

namespace Redbox\Imap\Transport\Adapter;

use BadFunctionCallException;
use Redbox\Imap\Transport\TCPRequest;

class FSock implements AdapterInterface
{
    /**
     * @var resource
     */
    protected $socket;

    /**
     * @var int
     */
    protected $timeout = 30;

    /**
     * @var int
     */
    protected $connect_timeout = 30;

    /**
     * Verify that we can support the fsockopen function.
     * If this is not the case we will throw a BadFunctionCallException.
     *
     * @throws BadFunctionCallException
     * @return bool
     */
    public function verifySupport()
    {
        if (! function_exists('fsockopen')) {

            throw new \BadFunctionCallException('fsockopen is not supported.');
        }

        return true;
    }

    /**
     * Initialize and close the connection if
     * needed.
     *
     * @param \Redbox\Imap\Transport\TCPRequest $request
     */
    public function open(TCPRequest $request)
    {
        if (is_resource($this->socket)) {
            $this->close();
        }

        $errno = 0;
        $errstr = '';

        $this->socket = stream_socket_client($request->getConnectionUri(), $errno, $errstr, $this->connect_timeout);
        //stream_set_blocking($this->socket, false);
    }

    /**
     * Close the connection
     */
    public function close()
    {
        if (is_resource($this->socket)) {
            fclose($this->socket);
        }
    }

    /**
     * @param string $message
     * @return mixed|void
     */
    public function send($message = '')
    {
        if (! $this->socket) {
            return false;
        }

        fwrite($this->socket, $message);

        while (($buffer = fgets($this->socket, 4096)) !== false) {
            echo $buffer."\n";
            break;
        }

        //if (! feof($this->socket)) {
        //    $this->close();
        //}

        echo 'done';
        //$this->close();
    }
}