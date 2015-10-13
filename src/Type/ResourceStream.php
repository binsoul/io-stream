<?php

namespace BinSoul\IO\Stream\Type;

use BinSoul\IO\Stream\AccessMode;
use BinSoul\IO\Stream\Stream;

/**
 * Provides a wrapper around a regular PHP resource.
 */
class ResourceStream implements Stream
{
    /** @var string */
    private $uri;
    /** @var AccessMode */
    private $mode;
    /** @var resource */
    private $handle;

    /**
     * Constructs an instance of this class.
     *
     * @param string $uri URI of the resource
     */
    public function __construct($uri)
    {
        $this->uri = $uri;
    }

    public function __destruct()
    {
        if ($this->handle) {
            $this->close();
        }
    }

    public function open(AccessMode $mode)
    {
        if ($this->handle) {
            throw new \LogicException(sprintf('The stream "%s" is already open.', $this->uri));
        }

        $handle = @fopen($this->uri, $mode->getMode());
        if ($handle === false) {
            throw new \RuntimeException(sprintf('The stream "%s" cannot be opened.', $this->uri));
        }

        if (!is_resource($handle) || get_resource_type($handle) !== 'stream') {
            throw new \InvalidArgumentException(sprintf('The stream "%s" is not of type "stream".', $this->uri));
        }

        $this->mode = $mode;
        $this->handle = $handle;

        return true;
    }

    public function close()
    {
        $this->assertOpen();

        $closed = @fclose($this->handle);

        if ($closed) {
            $this->mode = null;
            $this->handle = null;
        }

        return $closed;
    }

    public function read($numberOfBytes)
    {
        if (!$this->isReadable()) {
            throw new \LogicException(sprintf('The stream "%s" is not readable.', $this->uri));
        }

        return @fread($this->handle, $numberOfBytes);
    }

    public function write($data)
    {
        if (!$this->isWritable()) {
            throw new \LogicException(sprintf('The stream "%s" is not writable.', $this->uri));
        }

        return @fwrite($this->handle, $data);
    }

    public function flush()
    {
        $this->assertOpen();

        return @fflush($this->handle);
    }

    public function seek($offset, $whence = SEEK_SET)
    {
        if (!$this->isSeekable()) {
            throw new \LogicException(sprintf('The stream "%s" is not seekable.', $this->uri));
        }

        $result = @fseek($this->handle, $offset, $whence);
        if ($result !== 0) {
            return false;
        }

        return true;
    }

    public function tell()
    {
        $this->assertOpen();

        $result = @ftell($this->handle);

        return ($result !== false) ? $result : null;
    }

    public function isEof()
    {
        $this->assertOpen();

        return @feof($this->handle);
    }

    public function isReadable()
    {
        $this->assertOpen();

        return $this->mode->allowsRead();
    }

    public function isWritable()
    {
        $this->assertOpen();

        return $this->mode->allowsWrite();
    }

    public function isSeekable()
    {
        $this->assertOpen();

        $meta = stream_get_meta_data($this->handle);

        return $meta['seekable'];
    }

    public function getSize()
    {
        $this->assertOpen();

        $stats = @fstat($this->handle);

        return (int) $stats['size'];
    }

    public function getStatistics()
    {
        $this->assertOpen();

        return @fstat($this->handle);
    }

    public function getMetadata($key = null)
    {
        $this->assertOpen();

        if ($key === null) {
            return stream_get_meta_data($this->handle);
        }

        $metadata = stream_get_meta_data($this->handle);
        if (!array_key_exists($key, $metadata)) {
            return;
        }

        return $metadata[$key];
    }

    public function detach()
    {
        $this->assertOpen();

        $handle = $this->handle;
        $this->handle = null;

        return $handle;
    }

    /**
     * Asserts that the stream is open.
     *
     * @throws \LogicException
     */
    private function assertOpen()
    {
        if (!$this->handle) {
            throw new \LogicException(sprintf('The stream "%s" is not open.', $this->uri));
        }
    }
}
