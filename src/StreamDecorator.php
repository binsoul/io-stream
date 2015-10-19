<?php

namespace BinSoul\IO\Stream;

use BinSoul\Common\Decorator;

/**
 * Implements the {@see Stream} interface and delegates all methods to the decorated stream.
 */
trait StreamDecorator
{
    use Decorator;

    public function open(AccessMode $mode)
    {
        return $this->decoratedObject->open($mode);
    }

    public function close()
    {
        return $this->decoratedObject->close();
    }

    public function read($numberOfBytes)
    {
        return $this->decoratedObject->read($numberOfBytes);
    }

    public function write($data)
    {
        return $this->decoratedObject->write($data);
    }

    public function flush()
    {
        return $this->decoratedObject->flush();
    }

    public function seek($offset, $whence = SEEK_SET)
    {
        return $this->decoratedObject->seek($offset, $whence);
    }

    public function tell()
    {
        return $this->decoratedObject->tell();
    }

    public function isEof()
    {
        return $this->decoratedObject->isEof();
    }

    public function isReadable()
    {
        return $this->decoratedObject->isReadable();
    }

    public function isWritable()
    {
        return $this->decoratedObject->isWritable();
    }

    public function isSeekable()
    {
        return $this->decoratedObject->isSeekable();
    }

    public function getSize()
    {
        return $this->decoratedObject->getSize();
    }

    public function getStatistics()
    {
        return $this->decoratedObject->getStatistics();
    }

    public function getMetadata($key = null)
    {
        return $this->decoratedObject->getMetadata($key);
    }

    public function detach()
    {
        return $this->decoratedObject->detach();
    }

    public function appendTo(Stream $stream, $maxBufferSize = 1048576)
    {
        return $this->decoratedObject->appendTo($stream, $maxBufferSize);
    }
}
