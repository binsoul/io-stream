<?php

namespace BinSoul\IO\Stream\Decorator;

use BinSoul\IO\Stream\ByteManipulator;
use BinSoul\IO\Stream\StreamDecorator;
use BinSoul\IO\Stream\Stream;

/**
 * Buffers all writes to the decorated stream until the desired buffer size is reached.
 */
class BufferedWriter implements Stream
{
    use StreamDecorator;
    use ByteManipulator;

    /** @var int */
    private $bufferSize;
    /** @var string */
    private $buffer;

    /**
     * Constructs an instance of this class.
     *
     * @param Stream $decoratedStream
     * @param int    $bufferSize
     */
    public function __construct(Stream $decoratedStream, $bufferSize)
    {
        $this->decoratedStream = $decoratedStream;
        $this->bufferSize = $bufferSize;
    }

    public function close()
    {
        $this->flushBuffer();

        return $this->decoratedStream->close();
    }

    public function read($numberOfBytes)
    {
        $this->flushBuffer();

        return $this->decoratedStream->read($numberOfBytes);
    }

    public function write($data)
    {
        if (!$this->isWritable()) {
            throw new \LogicException('The decorated stream is not writable.');
        }

        $dataLength = $this->numberOfBytes($data);
        if ($dataLength > $this->bufferSize) {
            $this->flushBuffer();
            $dataLength = $this->decoratedStream->write($data);
        } else {
            $this->buffer .= $data;
            if ($this->numberOfBytes($this->buffer) > $this->bufferSize) {
                $this->flushBuffer();
            }
        }

        return $dataLength;
    }

    public function flush()
    {
        $this->flushBuffer();

        return $this->decoratedStream->flush();
    }

    public function seek($offset, $whence = SEEK_SET)
    {
        $this->flushBuffer();

        return $this->decoratedStream->seek($offset, $whence);
    }

    public function tell()
    {
        $this->flushBuffer();

        return $this->decoratedStream->tell();
    }

    public function isEof()
    {
        $this->flushBuffer();

        return $this->decoratedStream->isEof();
    }

    public function getSize()
    {
        $this->flushBuffer();

        return $this->decoratedStream->getSize();
    }

    public function getStatistics()
    {
        $this->flushBuffer();

        return $this->decoratedStream->getStatistics();
    }

    public function getMetadata($key = null)
    {
        $this->flushBuffer();

        return $this->decoratedStream->getMetadata($key);
    }

    public function detach()
    {
        return $this->decoratedStream->detach();
    }

    /**
     * Writes all buffered bytes to the stream and clears the buffer.
     *
     * @throws \RuntimeException
     */
    private function flushBuffer()
    {
        $bufferLength = $this->numberOfBytes($this->buffer);
        if ($bufferLength == 0) {
            return;
        }

        $bytesWritten = $this->decoratedStream->write($this->buffer);
        if ($bytesWritten < $bufferLength) {
            throw new \RuntimeException(
                sprintf(
                    'Expected to flush buffer of %d bytes but only %d bytes were written.',
                    $bufferLength,
                    $bytesWritten
                )
            );
        }

        $this->buffer = '';
    }
}
