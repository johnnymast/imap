<?php

namespace Redbox\Imap\Transport;

class TCPRequest
{
    /**
     * Server hostname.
     *
     * @var string
     */
    protected string $host = '';

    /**
     * Server port
     *
     * @var int
     */
    protected int $port = 993;

    /**
     * TLS / SSL enabled.
     *
     * @var bool
     */
    protected bool $secure = false;

    /**
     * The connection URI.
     *
     * @var string
     */
    protected string $connection_uri = '';

    /**
     * TCPRequest constructor.
     *
     * @param string $host
     * @param int $port
     * @param bool $secure
     */
    public function __construct(string $host, int $port, bool $secure)
    {
        $this->host = $host;
        $this->port = $port;
        $this->secure = $secure;
        $this->connection_uri = ($this->isSecure() ? 'tls://' : '').$this->getHost().':'.$this->getPort();
    }

    /**
     * Return the host.
     *
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * Return the port.
     *
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * Is this a secured connection?
     *
     * @return bool
     */
    public function isSecure(): bool
    {
        return $this->secure;
    }


    /**
     * @return string
     */
    public function getConnectionUri(): string
    {
        return $this->connection_uri;
    }
}