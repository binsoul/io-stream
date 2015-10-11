<?php

namespace BinSoul\IO\Stream;

/**
 * Implements the Stream interface and delegates all methods to the decorated stream.
 */
trait StreamDecorator
{
    /** @var Stream */
    protected $decoratedStream;

    public function open(AccessMode $mode)
    {
        return $this->decoratedStream->open($mode);
    }

    public function close()
    {
        return $this->decoratedStream->close();
    }

    public function read($numberOfBytes)
    {
        return $this->decoratedStream->read($numberOfBytes);
    }

    public function write($data)
    {
        return $this->decoratedStream->write($data);
    }

    public function flush()
    {
        return $this->decoratedStream->flush();
    }

    public function seek($offset, $whence = SEEK_SET)
    {
        return $this->decoratedStream->seek($offset, $whence);
    }

    public function tell()
    {
        return $this->decoratedStream->tell();
    }

    public function isEof()
    {
        return $this->decoratedStream->isEof();
    }

    public function isReadable()
    {
        return $this->decoratedStream->isReadable();
    }

    public function isWritable()
    {
        return $this->decoratedStream->isWritable();
    }

    public function isSeekable()
    {
        return $this->decoratedStream->isSeekable();
    }

    public function getSize()
    {
        return $this->decoratedStream->getSize();
    }

    public function getStatistics()
    {
        return $this->decoratedStream->getStatistics();
    }

    public function getMetadata($key = null)
    {
        return $this->decoratedStream->getMetadata($key);
    }

    public function detach()
    {
        return $this->decoratedStream->detach();
    }
}
