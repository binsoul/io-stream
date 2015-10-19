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
        $this->decoratedObject = $decoratedStream;
        $this->bufferSize = $bufferSize;
    }

    public function close()
    {
        $this->flushBuffer();

        return $this->decoratedObject->close();
    }

    public function read($numberOfBytes)
    {
        $this->flushBuffer();

        return $this->decoratedObject->read($numberOfBytes);
    }

    public function write($data)
    {
        if (!$this->isWritable()) {
            throw new \LogicException('The decorated stream is not writable.');
        }

        $dataLength = $this->numberOfBytes($data);
        if ($dataLength > $this->bufferSize) {
            $this->flushBuffer();
            $dataLength = $this->decoratedObject->write($data);
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

        return $this->decoratedObject->flush();
    }

    public function seek($offset, $whence = SEEK_SET)
    {
        $this->flushBuffer();

        return $this->decoratedObject->seek($offset, $whence);
    }

    public function tell()
    {
        $this->flushBuffer();

        return $this->decoratedObject->tell();
    }

    public function isEof()
    {
        $this->flushBuffer();

        return $this->decoratedObject->isEof();
    }

    public function getSize()
    {
        $this->flushBuffer();

        return $this->decoratedObject->getSize();
    }

    public function getStatistics()
    {
        $this->flushBuffer();

        return $this->decoratedObject->getStatistics();
    }

    public function getMetadata($key = null)
    {
        $this->flushBuffer();

        return $this->decoratedObject->getMetadata($key);
    }

    public function detach()
    {
        $this->flushBuffer();

        return $this->decoratedObject->detach();
    }

    public function appendTo(Stream $stream, $maxBufferSize = 1048576)
    {
        $this->flushBuffer();

        return $this->decoratedObject->appendTo($stream, $maxBufferSize);
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

        $bytesWritten = $this->decoratedObject->write($this->buffer);
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
