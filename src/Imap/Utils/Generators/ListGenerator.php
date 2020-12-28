<?php


namespace Redbox\Imap\Utils\Generators;


/**
 * Class ListGenerator
 *
 * @package Redbox\Imap\Utils\Generators
 */
class ListGenerator implements GeneratorInterface
{
    
    
    /**
     * Parse the mailbox items and yield results.
     *
     * @param  string  $data
     *
     * @return Generator
     */
    public static function parse($data = '')
    {
        if (strpos($data, '*') === 0) {
            $info = [
              'flags' => [],
              'delimiter' => '',
              'name' => '',
            ];
            
            if (($str = strstr($data, 'LIST'))) {
                $left = strpos($str, '(');
                $right = strpos($str, ')');
                
                
                if ($left > -1 && $right > -1) {
                    $unparsed = trim(substr($str, $left, ($right - $left + 1)), '()');
                    $parsed = explode(" ", $unparsed);
                    
                    if (count($parsed) > 0) {
                        $info['flags'] = explode(" ", $unparsed);
                    }
                }
                
                $left = strpos($str, '"');
                $right = strpos($str, '"', $left + 1);
                
                if ($left > -1 && $right > -1) {
                    $parsed = trim(substr($str, $left, ($right - $left + 1)), '"');
                    
                    if (strlen($parsed) > 0) {
                        $info['delimiter'] = $parsed;
                    }
                }
                
                $data = substr($str, $right + 1);
                $info['name'] = trim($data);
                
                yield $info;
            }
        }
    }
}