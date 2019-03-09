<?php

namespace Redbox\Imap\Utils\Factories;

use Redbox\Imap\Utils\Response;

class ResponseFactory
{
    /**
     * Tag strings end with CRLF.
     */
    const CLRF = "\r\n";

    public static function isResponse($prefix = '')
    {
        return (TagFactory::get($prefix));
    }

    public static function parseResponse($prefix = '', $data = '')
    {
        if (strlen($data) > 0) {
            $lines = explode(self::CLRF, $data);
            foreach ($lines as $line) {
                if (substr($line, 0, strlen($prefix)) === $prefix) {
                    $data = substr($line, strlen($prefix)+1);
                    $response = new Response($prefix, trim($data));
                    return $response;
                }
            }
        }

        return false;
    }

    public static function clear() {
        // TODO
    }
}