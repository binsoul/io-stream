<?php

namespace BinSoul\Test\IO\Stream\Type;

use BinSoul\IO\Stream\AccessMode;
use BinSoul\IO\Stream\Type\PhpOutputStream;

class PhpOutputStreamTest extends \PHPUnit_Framework_TestCase
{
    public function test_uses_correct_php_stream()
    {
        $stream = new PhpOutputStream();
        $stream->open(new AccessMode('w'));
        $this->assertEquals('php://output', $stream->getMetadata('uri'));
    }

    /**
     * @expectedException \LogicException
     */
    public function test_raises_exceptions_for_readable_mode()
    {
        $stream = new PhpOutputStream();
        $stream->open(new AccessMode('r'));
    }

    public function test_is_writeonly()
    {
        $stream = new PhpOutputStream();
        $stream->open(new AccessMode('w'));
        $this->assertTrue($stream->isWritable());
        $this->assertFalse($stream->isReadable());
        $this->assertFalse($stream->isSeekable());
    }
}
