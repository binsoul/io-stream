<?php

namespace BinSoul\Test\IO\Stream;

use BinSoul\IO\Stream\AccessMode;

class AccessModeTest extends \PHPUnit_Framework_TestCase
{
    public function test_sets_binary_flag()
    {
        $this->assertTrue((new AccessMode('r'))->isBinary());
        $this->assertTrue((new AccessMode('r+'))->isBinary());

        $this->assertEquals('r+b', (new AccessMode('r+'))->getMode());
    }

    public function test_keeps_provided_flag()
    {
        $this->assertTrue((new AccessMode('rb'))->isBinary());
        $this->assertTrue((new AccessMode('r+t'))->isText());

        $this->assertEquals('r+t', (new AccessMode('r+t'))->getMode());
    }

    public function test_allowsRead()
    {
        $this->assertTrue((new AccessMode('r'))->allowsRead());
        $this->assertTrue((new AccessMode('w+'))->allowsRead());
        $this->assertTrue((new AccessMode('w+b'))->allowsRead());

        $this->assertFalse((new AccessMode('w'))->allowsRead());
        $this->assertFalse((new AccessMode('x'))->allowsRead());
        $this->assertFalse((new AccessMode('a'))->allowsRead());
    }

    public function test_allowsWrite()
    {
        $this->assertTrue((new AccessMode('w'))->allowsWrite());
        $this->assertTrue((new AccessMode('x'))->allowsWrite());
        $this->assertTrue((new AccessMode('a'))->allowsWrite());
        $this->assertTrue((new AccessMode('r+'))->allowsWrite());
        $this->assertTrue((new AccessMode('r+b'))->allowsWrite());
    }

    public function test_allowsExistingStreamOpening()
    {
        $this->assertTrue((new AccessMode('r'))->allowsExistingStreamOpening());
        $this->assertTrue((new AccessMode('w'))->allowsExistingStreamOpening());
        $this->assertTrue((new AccessMode('a'))->allowsExistingStreamOpening());
        $this->assertFalse((new AccessMode('x'))->allowsExistingStreamOpening());
    }

    public function test_allowsNewStreamOpening()
    {
        $this->assertFalse((new AccessMode('r'))->allowsNewStreamOpening());
        $this->assertTrue((new AccessMode('w'))->allowsNewStreamOpening());
        $this->assertTrue((new AccessMode('a'))->allowsNewStreamOpening());
        $this->assertTrue((new AccessMode('x'))->allowsNewStreamOpening());
    }

    public function test_impliesExistingContentDeletion()
    {
        $this->assertFalse((new AccessMode('r'))->impliesExistingContentDeletion());
        $this->assertTrue((new AccessMode('w'))->impliesExistingContentDeletion());
        $this->assertFalse((new AccessMode('a'))->impliesExistingContentDeletion());
        $this->assertFalse((new AccessMode('x'))->impliesExistingContentDeletion());
    }

    public function test_impliesPositioningCursorAtTheBeginning()
    {
        $this->assertTrue((new AccessMode('r'))->impliesPositioningCursorAtTheBeginning());
        $this->assertTrue((new AccessMode('w'))->impliesPositioningCursorAtTheBeginning());
        $this->assertFalse((new AccessMode('a'))->impliesPositioningCursorAtTheBeginning());
        $this->assertTrue((new AccessMode('x'))->impliesPositioningCursorAtTheBeginning());
    }

    public function test_impliesPositioningCursorAtTheEnd()
    {
        $this->assertFalse((new AccessMode('r'))->impliesPositioningCursorAtTheEnd());
        $this->assertFalse((new AccessMode('w'))->impliesPositioningCursorAtTheEnd());
        $this->assertTrue((new AccessMode('a'))->impliesPositioningCursorAtTheEnd());
        $this->assertFalse((new AccessMode('x'))->impliesPositioningCursorAtTheEnd());
    }
}
