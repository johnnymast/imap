<?php

namespace Redbox\Imap\Tests\Utils\Factories;

use PHPUnit\Framework\TestCase;
use Redbox\Imap\Utils\Factories\TagFactory;
use Redbox\Imap\Utils\Tag;

class TagFactoryTest extends TestCase
{

    /**
     * This function will be called before every test.
     */
    public function setUp(): void
    {
        TagFactory::clear();
    }

    /**
     * Test that createTag() returns an instance of a Tag class.
     */
    public function test_createtag_returns_an_instance_of_tag(): void
    {
        $expected = Tag::class;
        $actual = TagFactory::createTag("bleep");

        $this->assertInstanceOf($expected, $actual);
    }

    /**
     * Test that getPrefix() returns the correct correct increment
     */
    public function test_getprefix_increments_the_prefix_correctly(): void
    {
        $first = TagFactory::createTag("bleep");
        $second = TagFactory::createTag("bleep2");

        $this->assertEquals('a1', $first->getPrefix());
        $this->assertEquals('a2', $second->getPrefix());
    }

    /**
     * Test that get() returns a tag object if an correct prefix has been received.
     */
    public function test_get_returns_valid_tag_with_correct_prefix()
    {
        $first = TagFactory::createTag("bleep");

        $expected = Tag::class;
        $actual = TagFactory::get($first->getPrefix());

        $this->assertInstanceOf($expected, $actual);
    }

    /**
     * Test that get() returns false if an not expected tag is returned.
     */
    public function test_get_returns_false_with_incorrect_prefix()
    {
        TagFactory::createTag("bleep");

        $actual = TagFactory::get('a2');

        $this->assertFalse($actual);
    }
}
