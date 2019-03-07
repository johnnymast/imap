<?php declare(strict_types=1);

namespace Redbox\Imap\Utils;

class Tag
{
    /**
     * Tag strings end with CRLF.
     */
    const CLRF = "\r\n";

    /**
     * The Tag prefix.
     *
     * @var string
     */
    private $prefix = '';

    /**
     * The tag command.
     *
     * @var string
     */
    private $command = '';

    /**
     * The timestamp of when this tag was created.
     *
     * @var int
     */
    private $created_at = 0;

    /**
     * Tag constructor.
     *
     * @param string $prefix
     * @param string $command
     */
    public function __construct(string $prefix, string $command)
    {
        $this->prefix = $prefix;
        $this->command = $command;
        $this->created_at = time();
    }

    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * @return string
     */
    public function getCommand(): string
    {
        return $this->command;
    }

    /**
     * @return int|string
     */
    public function getCreatedAt(): int
    {
        return $this->created_at;
    }

    public function __toString(): string
    {
        return $this->getPrefix().' '.$this->getCommand().self::CLRF;
    }
}