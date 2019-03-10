<?php
/**
 * Created by PhpStorm.
 * User: jmast
 * Date: 6-3-2019
 * Time: 19:51
 */

namespace Redbox\Imap\Transport\Adapter;

use BadFunctionCallException;
use Redbox\Imap\Log\LogLevel;
use Redbox\Imap\Transport\TCPRequest;
use Redbox\Imap\Utils\Factories\ResponseFactory;
use Redbox\Imap\Utils\Logger;

class Stream implements AdapterInterface
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
     * Verify that we can support the stream_socket_client function.
     * If this is not the case we will throw a BadFunctionCallException.
     *
     * @throws BadFunctionCallException
     * @return bool
     */
    public function verifySupport()
    {
        if (! function_exists('stream_socket_client')) {
            throw new BadFunctionCallException('stream_socket_client is not supported.');
        }

        return true;
    }

    /**
     * Initialize and close the connection if
     * needed.
     *
     * @param \Redbox\Imap\Transport\TCPRequest $request
     * @return bool
     */
    public function open(TCPRequest $request)
    {
        if (is_resource($this->socket)) {
            $this->close();
        }

        $errno = 0;
        $errstr = '';

        $this->socket = stream_socket_client($request->getConnectionUri(), $errno, $errstr, $this->connect_timeout);

        if (substr(PHP_OS, 0, 3) != 'WIN') {
            $max = ini_get('max_execution_time');

            if (0 != $max and $this->read_limit > $max) {
                @set_time_limit($this->read_limit);
            }
            stream_set_timeout($this->socket, $this->read_limit, 0);
        }

        if (is_resource($this->socket)) {
            Logger::Log(LogLevel::DEBUG, __CLASS__.':'.__FUNCTION__.' Stream created successfully.');

            return true;
        } else {
            Logger::Log(LogLevel::DEBUG, __CLASS__.':'.__FUNCTION__.' Stream could not be created.');

            return false;
        }
    }

    /**
     * Close the connection
     */
    public function close()
    {
        if (is_resource($this->socket)) {
            return fclose($this->socket);
        }

        return false;
    }

    /**
     * @param string $message
     * @return bool|mixed
     */
    public function send($message = '')
    {

        if (! is_resource($this->socket)) {
            return false;
        }

        if (fwrite($this->socket, $message)) {
            Logger::log(LogLevel::DEBUG, 'Sending: {message}', ['message' => rtrim($message, PHP_EOL)]);

            return true;
        }

        return false;
    }

    /**
     * @return bool|mixed|string
     */
    public function read()
    {
        if (! is_resource($this->socket)) {
            Logger::Log(LogLevel::DEBUG, __CLASS__.':'.__FUNCTION__.' Socket is not a resource');

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

            if (! stream_select($selR, $selW, $selW, $endtime)) {
                Logger::Log(LogLevel::DEBUG, __CLASS__.':'.__FUNCTION__.' Stream_select timed out');
                break;
            }

            $str = @fgets($this->socket, 1024);
            $data .= $str;

            $prefix = explode(' ', $str);

            if (ResponseFactory::isResponse($prefix[0])) {
                Logger::Log(LogLevel::DEBUG, __CLASS__.':'.__FUNCTION__.' Response detected.');
                break;
            }

            $info = stream_get_meta_data($this->socket);

            if ($info['timed_out']) {
                Logger::Log(LogLevel::DEBUG, __CLASS__.':'.__FUNCTION__.' stream_get_meta_data timeout.');
                break;
            }

            if ($endtime and time() > $endtime) {
                Logger::Log(LogLevel::DEBUG, __CLASS__.':'.__FUNCTION__.' endtime took to long.');
                break;
            }
        }

        if (strlen($data)) {
            Logger::log(LogLevel::DEBUG, 'Incomming {data}', ['data' => rtrim($data, PHP_EOL)]);
        }

        return $data;
    }
}