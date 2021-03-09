<?php

namespace Redbox\Imap\Tests\Unit\Transport\Tcp;

use PHPUnit\Framework\TestCase;
use Redbox\Imap\Transport\TCPRequest;

/**
 * Class TCPRequestTest
 * @package Redbox\Imap\Tests\Unit\Transport\Tcp
 */
class TCPRequestTest extends TestCase
{

    /**
     * @var TCPRequest|null
     */
    protected ?TCPRequest $secureRequest = null;
    protected ?TCPRequest $insecureRequest = null;

    /**
     * Create two fresh instances of TCPRequest before
     * every test.
     */
    public function setUp(): void
    {
        $this->secureRequest = new TCPRequest('localhost', 896, true);
        $this->insecureRequest = new TCPRequest('localhost', 896, false);
    }

    /**
     * Test getHost() returns the value the TCPRequest is constructed with.
     */
    public function test_gethost_is_returning_the_correct_value(): void
    {
        $expected = 'localhost';
        $actual = $this->secureRequest->getHost();
        $this->assertEquals($expected, $actual);
    }

    /**
     * Test getPort() returns the value the TCPRequest is constructed with.
     */
    public function test_getport_is_returning_the_correct_value(): void
    {
        $expected = 896;
        $actual = $this->secureRequest->getPort();
        $this->assertEquals($expected, $actual);
    }

    /**
     * Test isSecure() returns the value the TCPRequest is constructed with.
     */
    public function test_issecure_is_returning_the_correct_value(): void
    {

        $actual = $this->secureRequest->isSecure();
        $this->assertTrue($actual);
    }

    /**
     * Test isSecure() returns the value the TCPRequest is constructed with.
     */
    public function test_issecure_is_returning_the_correct_value_if_constructed_with_false(): void
    {
        $actual = $this->insecureRequest->isSecure();
        $this->assertFalse($actual);
    }

    /**
     * Test getConnectionUri() returns correct value if constructed with a secure connection.
     */
    public function test_getConnectionUri_is_returning_the_correct_value_secure(): void
    {
        $actual = $this->secureRequest->getConnectionUri();
        $expected =  'tls://localhost:896';
        $this->assertEquals($expected, $actual);
    }

    /**
     * Test getConnectionUri() returns correct value if constructed with a secure connection.
     */
    public function test_getConnectionUri_is_returning_the_correct_value_insecure(): void
    {
        $actual = $this->insecureRequest->getConnectionUri();
        $expected =  'localhost:896';
        $this->assertEquals($expected, $actual);
    }
}
