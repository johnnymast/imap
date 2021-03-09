<?php

namespace Redbox\Imap\Utils;

use Redbox\Imap\Log\LoggerAwareInterface;
use Redbox\Imap\Log\LoggerInterface;
use Redbox\Imap\Log\LoggerTrait;
use Redbox\Imap\Log\NullLogger;

/**
 * Class Logger
 *
 * @package Redbox\Imap\Utils
 */
class Logger implements LoggerAwareInterface
{
    use LoggerTrait;

    /**
     * @var \Redbox\Imap\Utils\Logger
     */
    protected static $instance = null;

    /**
     * @var \Redbox\Imap\Log\LoggerInterface
     */
    protected $logger = null;

    /**
     * @var array
     */
    protected $ignoredLevels = [];

    /**
     * Logger constructor.
     *
     * @param \Redbox\Imap\Log\LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->setLogger($logger);
    }

    /**
     * Set the logger.
     *
     * @param \Redbox\Imap\Log\LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Log a message to the logger.
     *
     * @param $level
     * @param string $message
     * @param array $context
     */
    public static function log($level, string $message, array $context = [])
    {
        if (! self::$instance) {
            self::create(new NullLogger());
        }

        if (! in_array($level, self::$instance->ignoredLevels)) {
            self::$instance->logger->log($level, $message, $context);
        }
    }

    /**
     * Create a new logger.
     *
     * @deprecated  Make it a class instead of interface
     *
     * @param \Redbox\Imap\Log\LoggerInterface $logger
     *
     * @return \Redbox\Imap\Utils\Logger
     */
    public static function create(LoggerInterface $logger)
    {
        if (! self::$instance) {
            self::$instance = new static ($logger);
        }

        return self::$instance;
    }

    /**
     * Set ignored log levels.
     *
     * @param array $levels
     */
    public static function ignoreLevels($levels = [])
    {
        if (count($levels)) {
            foreach ($levels as $level) {
                self::$instance->ignoredLevels[] = $level;
            }
        }
    }
}