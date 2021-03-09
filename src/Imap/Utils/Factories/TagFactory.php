<?php declare(strict_types=1);

namespace Redbox\Imap\Utils\Factories;

use Redbox\Imap\Utils\Tag;

class TagFactory
{
    /**
     * This is the increment for the tag prefix
     * sending to the server.
     *
     * @var int
     */
    private static int $increment = 0;

    /**
     * This is the prefix char for tags.
     *
     * @var string
     */
    private static string $prefixChar = 'a';

    /**
     * This is the tag sending history.
     *
     * @var array
     */
    private static array $history = [];

    /**
     * Create a command prefix.
     *
     * @param string $command
     * @return Tag
     */
    public static function createTag($command = ''): Tag
    {

        $prefix = self::createPrefix();

        $tag = new Tag($prefix, $command);

        self::remember($prefix, $tag);

        return $tag;
    }

    /**
     * Create a new Tag prefix.
     *
     * @return string
     */
    private static function createPrefix(): string
    {
        self::increase();

        return self::$prefixChar.self::$increment;
    }

    /**
     * Increase the increment.
     *
     * @return int
     */
    private static function increase(): int
    {
        return self::$increment++;
    }

    /**
     * Save the tag to cache.
     *
     * @param string $key
     * @param string $command
     *
     * @return void
     */
    private static function remember($key = '', $command = ''): void
    {
        self::$history[$key] = $command;
    }

    /**
     * Get all history or get one tag by prefix.
     *
     * @param null $prefix
     * @return Tag|bool
     */
    public static function get($prefix = null)
    {
        if (! is_null($prefix)) {
            if (isset(self::$history[$prefix])) {
                return self::$history[$prefix];
            }

            return false;
        }

        return self::$history;
    }

    /**
     * Clear the tag history.
     *
     * @return void
     */
    public static function clear(): void
    {
        self::$history = [];
        self::$increment = 0;
    }
}