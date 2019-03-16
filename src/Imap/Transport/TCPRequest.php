<?php

namespace Redbox\Imap\Transport;

class TCPRequest
{
    /**
     * Server hostname.
     *
     * @var string
     */
    protected $host = '';

    /**
     * Server port
     *
     * @var int
     */
    protected $port = 993;

    /**
     * TLS / SSL enabled.
     *
     * @var bool
     */
    protected $secure = false;

    /**
     * The connection URI.
     *
     * @var string
     */
    protected $connection_uri = '';

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
     * Is this a secured connection?
     *
     * @return bool
     */
    public function isSecure()
    {
        return $this->secure;
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
     * @return string
     */
    public function getConnectionUri(): string
    {
        return $this->connection_uri;
    }
}