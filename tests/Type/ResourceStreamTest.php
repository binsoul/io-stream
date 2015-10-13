<?php

namespace BinSoul\Test\IO\Stream\Type;

use BinSoul\IO\Stream\AccessMode;
use BinSoul\IO\Stream\Type\ResourceStream;
use BinSoul\Test\IO\Stream\ReadWriteStreamTest;

class ResourceStreamTest extends ReadWriteStreamTest
{
    protected function buildStream()
    {
        return new ResourceStream('php://memory');
    }

    /**
     * @expectedException \RuntimeException
     */
    public function test_open_invalid_resource()
    {
        $stream = new ResourceStream('abc://abc');
        $stream->open(new AccessMode('w+'));
    }

    public function test_detach_returns_resource()
    {
        $stream = $this->buildStream();
        $stream->open(new AccessMode('w+'));
        $this->assertInternalType('resource', $stream->detach());
    }
}
