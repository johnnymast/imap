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
use Redbox\Imap\Utils\Factories\ResponseFactory;

class FSock implements AdapterInterface
{
    /**
     * @var resource
     */
    protected $socket;

    /**
     * @var int
     */
    protected $read_limit = 30;

    /**
     * @var int
     */
    protected $connect_timeout = 300;

    /**
     * Verify that we can support the fsockopen function.
     * If this is not the case we will throw a BadFunctionCallException.
     *
     * @throws BadFunctionCallException
     * @return bool
     */
    public function verifySupport()
    {
        // FIXME: stream
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

        if (substr(PHP_OS, 0, 3) != 'WINS') { // WIN
            $max = ini_get('max_execution_time');
            // Don't bother if unlimited
            if (0 != $max and $this->read_limit > $max) {
                @set_time_limit($this->read_limit);
            }
            stream_set_timeout($this->socket, $this->read_limit, 0);
        }
        // TODO: stream_socket_enable_crypto
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

        if (is_resource($this->socket)) {
            return false;
        }

        fwrite($this->socket, $message);
    }

    public function read()
    {
        if (is_resource($this->socket)) {
            return false;
        }

        $data = '';
        $endtime = 0;

        if ($this->read_limit > 0) {
            $endtime = time() + $this->read_limit;
        }

        $selR = [$this->socket];
        $selW = null;

        while (is_resource($this->socket) and ! feof($this->socket)) {
            //Must pass vars in here as params are by reference
            if (! stream_select($selR, $selW, $selW, $endtime)) {
                echo "stream_select timeout";

                break;
            }

            //Deliberate noise suppression - errors are handled afterwards
            $str = @fgets($this->socket, 1024);
            $data .= $str;

            $prefix = explode(' ', $str);

            if (ResponseFactory::isResponse($prefix[0])) {
                break;
            }

            // Timed-out? Log and break
            $info = stream_get_meta_data($this->socket);

            if ($info['timed_out']) {
                echo "stream_get_meta_data timeout";
                break;
            }
            // Now check if reads took too long
            if ($endtime and time() > $endtime) {
                echo "endtime took to long";
                break;
            }
        }

        return $data;
    }
}