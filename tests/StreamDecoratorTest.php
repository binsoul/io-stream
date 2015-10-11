<?php

namespace BinSoul\Test\IO\Stream\Type;

use BinSoul\IO\Stream\StreamDecorator;
use BinSoul\IO\Stream\Type\MemoryStream;
use BinSoul\Test\IO\Stream\AbstractStreamTest;

class StreamDecoratorImplementation
{
    use StreamDecorator;

    public function __construct($decoratedStream)
    {
        $this->decoratedStream = $decoratedStream;
    }
}

class StreamDecoratorTest extends AbstractStreamTest
{
    protected function getStreamName()
    {
        return 'file';
    }

    protected function buildStream()
    {
        return new StreamDecoratorImplementation(new MemoryStream($this->getStreamName()));
    }
}
