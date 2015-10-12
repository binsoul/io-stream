<?php

namespace BinSoul\Test\IO\Stream\Type;

use BinSoul\IO\Stream\AccessMode;
use BinSoul\IO\Stream\Decorator\BufferedWriter;
use BinSoul\IO\Stream\Stream;
use BinSoul\IO\Stream\Type\MemoryStream;
use BinSoul\Test\IO\Stream\AbstractStreamTest;

class BufferedWriterTest extends AbstractStreamTest
{
    protected function buildStream()
    {
        return new BufferedWriter(new MemoryStream(), 1000);
    }

    public function test_writes_flush_buffer()
    {
        $decoratedStream = $this->getMock(Stream::class);
        $decoratedStream->expects($this->any())->method('isWritable')->willReturn(true);
        $decoratedStream->expects($this->once())->method('write')->with('xxxxxyyyyyzzzzz')->willReturn(15);

        $stream = new BufferedWriter($decoratedStream, 10);
        $stream->open(new AccessMode('w+'));
        $stream->write(str_repeat('x', 5));
        $stream->write(str_repeat('y', 5));
        $stream->write(str_repeat('z', 5));
    }

    public function test_write_larger_than_buffer_flushes_buffer()
    {
        $decoratedStream = $this->getMock(Stream::class);
        $decoratedStream->expects($this->any())->method('isWritable')->willReturn(true);
        $decoratedStream->expects($this->once())->method('write')->willReturn(15);

        $stream = new BufferedWriter($decoratedStream, 10);
        $stream->open(new AccessMode('w+'));
        $stream->write(str_repeat('x', 15));
    }

    public function test_write_returns_bytes_written_without_buffer()
    {
        $decoratedStream = $this->getMock(Stream::class);
        $decoratedStream->expects($this->any())->method('isWritable')->willReturn(true);
        $decoratedStream->expects($this->any())->method('write')->willReturn(15);

        $stream = new BufferedWriter($decoratedStream, 10);
        $stream->open(new AccessMode('w+'));
        $stream->write(str_repeat('x', 5));
        $this->assertEquals(15, $stream->write(str_repeat('y', 15)));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function test_write_raises_exception_if_flush_fails()
    {
        $decoratedStream = $this->getMock(Stream::class);
        $decoratedStream->expects($this->any())->method('isWritable')->willReturn(true);
        $decoratedStream->expects($this->any())->method('write')->willReturn(0);

        $stream = new BufferedWriter($decoratedStream, 10);
        $stream->open(new AccessMode('w+'));
        $stream->write(str_repeat('x', 5));
        $this->assertEquals(15, $stream->write(str_repeat('y', 15)));
    }

    public function test_detach_returns_null()
    {
        $stream = $this->buildStream();
        $stream->open(new AccessMode('w+'));
        $this->assertNull($stream->detach());
    }
}
