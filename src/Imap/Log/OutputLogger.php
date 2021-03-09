<?php

namespace Redbox\Imap\Log;

class OutputLogger extends AbstractLogger
{
    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message - The message to log.
     * @param array $context - The context of the message to log.
     *
     * @return void
     */
    public function log($level, string $message, array $context = []): void
    {
        $message = $this->interpolate(date("Y-m-d H:i:s") . ' {level} | ' . $message,
            $context + ['level' => ucfirst($level)]);

        echo $message . PHP_EOL;
    }

    /**
     * Interpolates context values into the message placeholders.
     *
     * @param string $message - The message to log.
     * @param array $context - The context of the message to log.
     *
     * @return string
     */
    private function interpolate(string $message, array $context = []): string
    {
        // build a replacement array with braces around the context keys
        $replace = [];
        foreach ($context as $key => $val) {
            // check that the value can be casted to string
            if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                $replace['{' . $key . '}'] = $val;
            }
        }

        // interpolate replacement values into the message and return
        return strtr($message, $replace);
    }
}