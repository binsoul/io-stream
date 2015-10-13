<?php

namespace BinSoul\Test\IO\Stream;

use BinSoul\IO\Stream\StreamDecorator;
use BinSoul\IO\Stream\Type\MemoryStream;

class StreamDecoratorImplementation
{
    use StreamDecorator;

    public function __construct($decoratedStream)
    {
        $this->decoratedStream = $decoratedStream;
    }
}

class StreamDecoratorTest extends ReadWriteStreamTest
{
    protected function buildStream()
    {
        return new StreamDecoratorImplementation(new MemoryStream());
    }
}
