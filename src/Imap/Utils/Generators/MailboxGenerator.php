<?php

namespace Redbox\Imap\Utils\Generators;

use Generator;

class MailboxGenerator implements GeneratorInterface
{
    /**
     * I had no idea how to parse the response of SELECT and
     * EXAMINE so decided to create a generator. If you have any
     * better idea contact me at mastjohnny@gmail.com or leave an
     * issue on the github page for this project.
     *
     * @param string $data
     *
     * @return Generator
     */
    public static function parse($data = ''): Generator
    {
        if (($str = strstr($data, 'FLAGS'))) {
            $left = strpos($str, '(');
            $right = strpos($str, ')');

            if ($left > -1 && $right > -1) {
                $unparsed_flags = trim(substr($str, $left, ($right - $left + 1)), '()');
                $parsed_flags = explode(" ", $unparsed_flags);

                if (count($parsed_flags) > 0) {
                    yield ['flags' => explode(" ", $unparsed_flags)];
                }
            }
        }

        if (($str = strstr($data, 'PERMANENTFLAGS'))) {
            $left = strpos($str, '(');
            $right = strpos($str, ')');

            if ($left > -1 && $right > -1) {
                $unparsed_flags = trim(substr($str, $left, ($right - $left + 1)), '()');
                $parsed_flags = explode(" ", $unparsed_flags);

                if (count($parsed_flags) > 0) {
                    yield ['permanentflags' => explode(" ", $unparsed_flags)];
                }
            }
        }

        if ((strpos($data, 'EXISTS') > -1)) {
            $lines = explode("\n", $data);

            // Find the exact line
            foreach ($lines as $line) {
                if (($right = strpos($line, 'EXISTS'))) {
                    $left = strlen('* ');
                    $string = substr($line, $left);
                    $length = sscanf($string, "%d EXISTS", $parsed_value);

                    if ($length > -1 && $parsed_value > 0) {
                        yield ['exists' => $parsed_value];
                    }
                }
            }
        }

        if ((strpos($data, 'RECENT') > -1)) {
            $lines = explode("\n", $data);

            // Find the exact line
            foreach ($lines as $line) {
                if (($right = strpos($line, 'RECENT'))) {
                    $left = strlen('* ');
                    $string = substr($line, $left);
                    $length = sscanf($string, "%d RECENT", $parsed_value);

                    if ($length > -1 && $parsed_value > 0) {
                        yield ['recent' => $parsed_value];
                    }
                }
            }
        }

        if (($str = strstr($data, '[UNSEEN'))) {
            $left = strpos($str, ' ');
            $right = strpos($str, ']');

            if ($left > -1 && $right > -1) {
                $parsed_unseen = trim(substr($str, $left, ($right - $left)));

                if (strlen($parsed_unseen) > 0) {
                    yield ['unseen' => $parsed_unseen];
                }
            }
        }

        if (($str = strstr($data, '[UIDVALIDITY'))) {
            $left = strpos($str, ' ');
            $right = strpos($str, ']');

            if ($left > -1 && $right > -1) {
                $parsed_uidvalidity = trim(substr($str, $left, ($right - $left)));

                if (strlen($parsed_uidvalidity) > 0) {
                    yield ['uidvalidity' => $parsed_uidvalidity];
                }
            }
        }

        if (($str = strstr($data, '[UIDNEXT'))) {
            $left = strpos($str, ' ');
            $right = strpos($str, ']');

            if ($left > -1 && $right > -1) {
                $parsed_uidnext = trim(substr($str, $left, ($right - $left)));

                if (strlen($parsed_uidnext) > 0) {
                    yield ['uidnext' => $parsed_uidnext];
                }
            }
        }
    }
}