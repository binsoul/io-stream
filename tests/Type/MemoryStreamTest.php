<?php

namespace BinSoul\Test\IO\Stream\Type;

use BinSoul\IO\Stream\AccessMode;
use BinSoul\IO\Stream\Type\MemoryStream;
use BinSoul\Test\IO\Stream\ReadWriteStreamTest;

class MemoryStreamTest extends ReadWriteStreamTest
{
    protected function buildStream()
    {
        return new MemoryStream();
    }

    public function test_detach_returns_null()
    {
        $stream = $this->buildStream();
        $stream->open(new AccessMode('w+'));
        $this->assertNull($stream->detach());
    }

    public function test_uses_provided_content()
    {
        $stream = new MemoryStream('foobar');
        $stream->open(new AccessMode('r+'));
        $this->assertEquals('foobar', $stream->read(6));
    }

    public function test_clears_content_if_mode_implies_deletion()
    {
        $stream = new MemoryStream('foobar');
        $stream->open(new AccessMode('w+'));
        $this->assertEquals(0, $stream->tell());
        $this->assertEquals('', $stream->read(6));
    }

    public function test_positions_cursor_at_the_end()
    {
        $stream = new MemoryStream('foobar');
        $stream->open(new AccessMode('a+'));
        $this->assertEquals(6, $stream->tell());
        $this->assertEquals('', $stream->read(6));
    }
}
