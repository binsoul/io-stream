<?php

namespace BinSoul\Test\IO\Stream\Type;

use BinSoul\IO\Stream\AccessMode;
use BinSoul\IO\Stream\Type\PhpInputStream;

class PhpInputStreamTest extends \PHPUnit_Framework_TestCase
{
    public function test_uses_correct_php_stream()
    {
        $stream = new PhpInputStream();
        $stream->open(new AccessMode('r'));
        $this->assertEquals('php://input', $stream->getMetadata('uri'));
    }

    public function test_is_readonly()
    {
        $stream = new PhpInputStream();
        $stream->open(new AccessMode('r'));
        $this->assertTrue($stream->isReadable());
        $this->assertFalse($stream->isWritable());
    }

    /**
     * @expectedException \LogicException
     */
    public function test_raises_exceptions_for_writeable_mode()
    {
        $stream = new PhpInputStream();
        $stream->open(new AccessMode('w'));
    }
}
