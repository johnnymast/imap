<?php

namespace Redbox\Imap\Tests\Utils\Factories;

use PHPUnit\Framework\TestCase;
use Redbox\Imap\Utils\Factories\ResponseFactory;
use Redbox\Imap\Utils\Factories\TagFactory;
use Redbox\Imap\Utils\Response;

class ResponseFactoryTest extends TestCase
{

    /**
     * Test that isResponse would return false on a valid response.
     *
     * @return void
     */
    public function test_isresponse_returns_true_on_correct_response(): void
    {
        $tag = TagFactory::createTag('NOOP');
        $actual = ResponseFactory::isResponse($tag->getPrefix());

        $this->assertTrue($actual);
    }

    /**
     * Test that isResponse would return false on a invalid response.
     *
     * @return void
     */
    public function test_isresponse_returns_false_on_incorrect_response(): void
    {
        $actual = ResponseFactory::isResponse('a2');
        $this->assertFalse($actual);
    }

    /**
     * Test parseResponse returns true on an valid response.
     *
     * @return void
     */
    public function test_parseresponse_returns_response_object_on_valid_response(): void
    {
        $tag = TagFactory::createTag('LSUB "" "*"');
        $actual = ResponseFactory::parseResponse($tag->getPrefix(), sprintf("%s OK Lsub completed (0.001 + 0.000 + 0.001 secs).", $tag->getPrefix()));

        $this->assertInstanceOf(Response::class, $actual);
    }

    /**
     * Test parseResponse returns false on an invalid response.
     *
     * @return void
     */
    public function test_parseresponse_returns_false_on_invalid_response(): void
    {
        $tag = TagFactory::createTag('"" "*"');
        $actual = ResponseFactory::parseResponse("a5", sprintf("%s OK Lsub completed (0.001 + 0.000 + 0.001 secs).", $tag->getPrefix()));

        $this->assertFalse($actual);
    }
}
