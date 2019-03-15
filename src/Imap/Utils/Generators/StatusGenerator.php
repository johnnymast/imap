<?php

namespace Redbox\Imap\Utils\Generators;

use Generator;

class StatusGenerator implements GeneratorInterface
{
    /**
     * I had no idea how to parse the response of STATUS so it
     * decided to create a generator. If you have any
     * better idea contact me at mastjohnny@gmail.com or leave an
     * issue on the github page for this project.
     *
     * @param string $data
     *
     * @return Generator
     */
    public static function parse($data = ''): Generator
    {
        $fields = [
            'MESSAGES',
            'RECENT',
            'UIDNEXT',
            'UIDVALIDITY',
            'UNSEEN',
        ];

        /**
         * First parse out the () so we can work with the content between it.
         */
        $left = strpos($data, '(');
        $right = strpos($data, ')', $left);

        if ($left > -1 && $right > -1) {
            $parsed_string = substr($data, $left + 1, $right - $left - 1);

            foreach ($fields as $field) {
                if (($str = strstr($parsed_string, $field))) {
                    $left = strlen($field) + 1; // Considering spaces
                    $right = strpos($str, ' ', $left);

                    if ($right !== false) {
                        $value = substr($str, $left, $right - $left);
                    } else {
                        $value = substr($str, $left);
                    }

                    yield [strtolower($field) => $value];
                }
            }
        }
    }
}