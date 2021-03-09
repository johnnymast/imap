<?php

namespace Redbox\Imap\Utils\Factories;

use Redbox\Imap\Utils\Response;
use Redbox\Imap\Utils\Tag;

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
     * @param string $prefix - The response prefix.
     *
     * @return bool
     */
    public static function isResponse(string $prefix = ''): bool
    {
        $result = TagFactory::get($prefix);
        return (($result instanceof Tag) == true || is_array($result) === true);
    }

    /**
     * Parse a response message and return a response object or if it fails false.
     *
     * @param string $prefix - The response prefix.
     * @param string $data - The data to parse.
     *
     * @return bool|Response
     */
    public static function parseResponse(string $prefix = '', string $data = '')
    {
        if (strlen($data) > 0) {
            $lines = explode(self::CLRF, $data);
            foreach ($lines as $line) {
                if (substr($line, 0, strlen($prefix)) === $prefix) {
                    if ($data == $line) {
                        $data = '';
                    }

                    $response_line = substr($line, strlen($prefix) + 1);

                    return new Response($prefix, trim($response_line), $data);
                }
            }
        }

        return false;
    }
}