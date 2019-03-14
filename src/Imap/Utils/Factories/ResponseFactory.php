<?php

namespace Redbox\Imap\Utils\Factories;

use Redbox\Imap\Utils\Response;

class ResponseFactory
{
    /**
     * Tag strings end with CRLF.
     */
    const CLRF = "\r\n";

    /**
     * Check to see if this is a response to a tag sent to the server. ALl tags sent
     * to the server have a unique prefix. If the server responds with the same prefix it
     * will be tagged a response.
     *
     * @param string $prefix
     *
     * @return array|bool
     */
    public static function isResponse($prefix = '')
    {
        return (TagFactory::get($prefix));
    }

    /**
     * Parse a response message and return a response object or if it fails false.
     *
     * @param string $prefix
     * @param string $data
     *
     * @return bool|\Redbox\Imap\Utils\Response
     */
    public static function parseResponse($prefix = '', $data = '')
    {
        if (strlen($data) > 0) {
            $lines = explode(self::CLRF, $data);
            foreach ($lines as $line) {
                if (substr($line, 0, strlen($prefix)) === $prefix) {
                    if ($data == $line) {
                        $data = '';
                    }

                    $response_line = substr($line, strlen($prefix) + 1);

                    $response = new Response($prefix, trim($response_line), $data);

                    return $response;
                }
            }
        }

        return false;
    }

    /**
     *
     */
    public static function clear()
    {
        // TODO
    }
}