<?php

namespace BinSoul\Test\IO\Stream\Type;

use BinSoul\IO\Stream\AccessMode;
use BinSoul\IO\Stream\Type\MemoryStream;
use BinSoul\Test\IO\Stream\AbstractStreamTest;

class MemoryStreamTest extends AbstractStreamTest
{
    protected function getStreamName()
    {
        return 'file';
    }

    protected function buildStream()
    {
        return new MemoryStream($this->getStreamName());
    }

    public function test_detach_returns_null()
    {
        $stream = $this->buildStream();
        $stream->open(new AccessMode('w+'));
        $this->assertNull($stream->detach());
    }
}
