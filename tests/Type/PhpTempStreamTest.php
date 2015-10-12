<?php

namespace BinSoul\Test\IO\Stream\Type;

use BinSoul\IO\Stream\AccessMode;
use BinSoul\IO\Stream\Type\PhpTempStream;

class PhpTempStreamTest extends \PHPUnit_Framework_TestCase
{
    public function test_uses_correct_php_stream()
    {
        $stream = new PhpTempStream();
        $stream->open(new AccessMode('r+'));
        $this->assertEquals('php://temp', $stream->getMetadata('uri'));
    }

    public function test_uses_memory_buffer_size()
    {
        $stream = new PhpTempStream(1024);
        $stream->open(new AccessMode('r+'));
        $this->assertEquals('php://temp/maxmemory:1024', $stream->getMetadata('uri'));
    }

    public function test_ignores_invalid_memory_buffer_size()
    {
        $stream = new PhpTempStream(-1);
        $stream->open(new AccessMode('r+'));
        $this->assertEquals('php://temp', $stream->getMetadata('uri'));

        $stream = new PhpTempStream('foo');
        $stream->open(new AccessMode('r+'));
        $this->assertEquals('php://temp', $stream->getMetadata('uri'));
    }
}
