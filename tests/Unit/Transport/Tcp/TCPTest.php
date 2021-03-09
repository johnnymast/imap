<?php

namespace Redbox\Imap\Tests\Unit\Transport\Tcp;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Redbox\Imap\Client;
use Redbox\Imap\Exceptions\AdapterNotSupportedAdapter;
use Redbox\Imap\Transport\Adapter\AdapterInterface;
use Redbox\Imap\Transport\Adapter\FSockAdapter;
use Redbox\Imap\Transport\Adapter\StreamAdapter;
use Redbox\Imap\Transport\TCP;
use Redbox\Imap\Transport\TCPRequest;

//$time = $this->getFunctionMock(__NAMESPACE__, "time");
//$time->expects($this->once())->willReturn(3);

class TCPTest extends TestCase
{

    /**
     * The instance of our TCP class
     * to test.
     *
     * @var TCP
     */
    protected TCP $tcp;

    /**
     * Return a fake mock of an adapter.
     *
     * @param string $class
     * @param bool $supported
     *
     * @return MockObject
     */
    public function getAdapterMock($class = '', $supported = true): MockObject
    {
        $adapter = $this->getMockBuilder($class)
            ->getMock();

        $adapter->method('verifySupport')
            ->will($this->returnValue($supported));

        return $adapter;
    }

    /**
     * Return a fake mock of the Client class.
     *
     * @return MockObject
     */
    public function getClientMock(): MockObject
    {
        return $this->getMockBuilder('\Redbox\Imap\Client')
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * Create a fresh instance of TCP before
     * every test.
     */
    protected function setUp(): void
    {
        $this->client = $this->getClientMock();
        $this->tcp = new TCP($this->client);
    }

    /**
     * Test that getClient will return the client passed trough the
     * constructor.
     */
    public function test_get_client_will_return_the_client_instance(): void
    {
        $client = $this->tcp->getClient();
        $this->assertInstanceOf(Client::class, $client);
    }

    /**
     * Test that the StreamAdapter is supported by default.
     *
     * @throws AdapterNotSupportedAdapter
     */
    public function test_getadapter_will_return_stream_adapter_by_default(): void
    {
        $adapter = $this->tcp->getAdapter();
        $this->assertInstanceOf(StreamAdapter::class, $adapter);
    }

    /**
     * Test that if there are so supported adapters that null will be returned by getAdapter.
     *
     * @throws AdapterNotSupportedAdapter
     */
    public function test_get_adapter_will_return_null_if_no_adapters_are_supported(): void
    {
        $tcp = $this->getMockBuilder(TCP::class)
            ->setConstructorArgs([$this->client])
            ->getMock();

        $tcp->method('getDefaultAdapters')
            ->will($this->returnValue([]));

        $expected = null;
        $actual = $tcp->getAdapter();

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tes that setAdapter will throw AdapterNotSupportedAdapter if an adapter if
     * passed without support.
     *
     * @throws AdapterNotSupportedAdapter
     */
    public function test_setadapter_will_throw_UnsupportedException_on_adapter_without_support(): void
    {
        $this->expectException(AdapterNotSupportedAdapter::class);

        $unsupportedAdapter = $this->getAdapterMock(AdapterInterface::class, false);

        $this->tcp->setAdapter($unsupportedAdapter);
    }

    /**
     * Test support for FSockAdapter if this not the first adapter from getDefaultAdapters.
     *
     * @throws AdapterNotSupportedAdapter
     */
    public function test_fsockadapter_support_is_detected(): void
    {

        $streamAdapter = $this->getAdapterMock(StreamAdapter::class, false);
        $fSockAdapter = $this->getAdapterMock(FSockAdapter::class);

        $tcp = $this->getMockBuilder(TCP::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getDefaultAdapters'])
            ->getMock();

        $tcp->method('getDefaultAdapters')
            ->will($this->returnValue([
                $fSockAdapter,
                $streamAdapter,
            ]));

        $actual = $tcp->getAdapter();

        $this->assertInstanceOf(FSockAdapter::class, $actual);
    }

    /**
     * Test support for StreamAdapter if this not the first adapter from getDefaultAdapters.
     *
     * @throws AdapterNotSupportedAdapter
     */
    public function test_stream_support_is_detected(): void
    {

        $streamAdapter = $this->getAdapterMock(StreamAdapter::class);
        $fSockAdapter = $this->getAdapterMock(FSockAdapter::class, false);

        $tcp = $this->getMockBuilder(TCP::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getDefaultAdapters'])
            ->getMock();

        $tcp->method('getDefaultAdapters')
            ->will($this->returnValue([
                $fSockAdapter,
                $streamAdapter,
            ]));

        $actual = $tcp->getAdapter();

        $this->assertInstanceOf(StreamAdapter::class, $actual);
    }

    /**
     * Test that connect() calls open() on the adapter.
     */
    public function test_connect_will_call_open_on_adapter(): void
    {

        $request = $this->getMockBuilder(TCPRequest::class)
            ->disableOriginalConstructor()
            ->getMock();

        $streamAdapter = $this->getAdapterMock(StreamAdapter::class);

        $streamAdapter->expects($this->once())
            ->method('open')
            ->with($this->equalTo($request));

        $tcp = $this->getMockBuilder(TCP::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getDefaultAdapters'])
            ->getMock();

        $tcp->method('getDefaultAdapters')
            ->will($this->returnValue([
                $streamAdapter,
            ]));

        $tcp->connect($request);
    }

    /**
     * Test that send() calls send() on the adapter.
     */
    public function test_send_will_call_send_on_adapter(): void
    {

        $request = $this->getMockBuilder(TCPRequest::class)
            ->disableOriginalConstructor()
            ->getMock();

        $streamAdapter = $this->getAdapterMock(StreamAdapter::class);

        $streamAdapter->expects($this->once())
            ->method('send')
            ->with($this->equalTo($request));

        $tcp = $this->getMockBuilder(TCP::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getDefaultAdapters'])
            ->getMock();

        $tcp->method('getDefaultAdapters')
            ->will($this->returnValue([
                $streamAdapter,
            ]));

        $tcp->send($request);
    }

    /**
     * Test that read() calls read() on the adapter.
     */
    public function test_read_will_call_read_on_adapter(): void
    {
        $streamAdapter = $this->getAdapterMock(StreamAdapter::class);

        $streamAdapter->expects($this->once())
            ->method('read');

        $tcp = $this->getMockBuilder(TCP::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getDefaultAdapters'])
            ->getMock();

        $tcp->method('getDefaultAdapters')
            ->will($this->returnValue([
                $streamAdapter,
            ]));

        $tcp->read();
    }

    /**
     * Test that close() calls close() on the adapter.
     */
    public function test_close_will_call_close_on_adapter(): void
    {
        $streamAdapter = $this->getAdapterMock(StreamAdapter::class);

        $streamAdapter->expects($this->once())
            ->method('close');

        $tcp = $this->getMockBuilder(TCP::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getDefaultAdapters'])
            ->getMock();

        $tcp->method('getDefaultAdapters')
            ->will($this->returnValue([
                $streamAdapter,
            ]));

        $tcp->close();
    }
}
