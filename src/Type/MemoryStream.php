<?php

namespace BinSoul\IO\Stream\Type;

use BinSoul\IO\Stream\AccessMode;
use BinSoul\IO\Stream\Stream;

/**
 * Provides an in-memory stream.
 */
class MemoryStream implements Stream
{
    /** @var AccessMode */
    private $mode;
    /** @var string */
    private $content;
    /** @var int */
    private $size;
    /** @var int */
    private $position;
    /** @var int */
    private $lastModified;
    /** @var bool */
    private $isOpen;

    /**
     * Constructs an instance of this class.
     *
     * @param string $content content of the stream
     */
    public function __construct($content = '')
    {
        $this->content = $content;
    }

    /**
     * Asserts that the stream is open.
     *
     * @throws \LogicException
     */
    private function assertOpen()
    {
        if (!$this->isOpen) {
            throw new \LogicException('The stream is not open.');
        }
    }

    public function open(AccessMode $mode)
    {
        if ($this->isOpen) {
            throw new \LogicException('The stream is already open.');
        }

        $this->mode = $mode;
        if ($this->mode->impliesExistingContentDeletion()) {
            $this->content = '';
        }

        $this->size = $this->length($this->content);

        $this->position = 0;
        if ($this->mode->impliesPositioningCursorAtTheEnd()) {
            $this->position = $this->size;
        }

        $this->isOpen = true;

        return true;
    }

    public function close()
    {
        $this->assertOpen();

        $this->content = '';
        $this->isOpen = false;
    }

    public function read($numberOfBytes)
    {
        if (!$this->isReadable()) {
            throw new \LogicException('The stream is not readable.');
        }

        $chunk = substr($this->content, $this->position, $numberOfBytes);
        $this->position += $this->length($chunk);

        return $chunk;
    }

    public function write($data)
    {
        if (!$this->isWritable()) {
            throw new \LogicException('The stream is not writable.');
        }

        $bytesWritten = $this->length($data);

        $newPosition = $this->position + $bytesWritten;
        $newSize = $newPosition > $this->size ? $newPosition : $this->size;

        if ($this->isEof()) {
            $this->size += $bytesWritten;
            if ($this->position > 0 && $this->content == '') {
                $data = str_pad($data, $this->position + $this->length($data), chr(0), STR_PAD_LEFT);
            }

            $this->content .= $data;
        } else {
            $before = substr($this->content, 0, $this->position);
            $after = $newSize > $newPosition ? substr($this->content, $newPosition) : '';
            $this->content = $before.$data.$after;
        }

        $this->position = $newPosition;
        $this->size = $newSize;
        $this->lastModified = time();

        return $bytesWritten;
    }

    public function seek($offset, $whence = SEEK_SET)
    {
        if (!$this->isSeekable()) {
            throw new \LogicException('The stream is not seekable.');
        }

        switch ($whence) {
            case SEEK_SET:
                $this->position = $offset;
                break;
            case SEEK_CUR:
                $this->position += $offset;
                break;
            case SEEK_END:
                $this->position = $this->size + $offset;
                break;
            default:
                return false;
        }

        return true;
    }

    public function tell()
    {
        $this->assertOpen();

        return $this->position;
    }

    public function flush()
    {
        $this->assertOpen();

        return true;
    }

    public function isEof()
    {
        $this->assertOpen();

        return $this->position >= $this->size;
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

        return true;
    }

    public function getSize()
    {
        $this->assertOpen();

        return $this->size;
    }

    public function getStatistics()
    {
        $this->assertOpen();

        $time = $this->lastModified;

        $stats = [
            'dev' => 1,
            'ino' => 0,
            'mode' => 33204,
            'nlink' => 1,
            'uid' => 0,
            'gid' => 0,
            'rdev' => 0,
            'size' => $this->size,
            'atime' => $time,
            'mtime' => $time,
            'ctime' => $time,
            'blksize' => -1,
            'blocks' => -1,
        ];

        return $stats;
    }

    public function getMetadata($key = null)
    {
        $this->assertOpen();

        $meta = [
            'timed_out' => false,
            'blocked' => false,
            'eof' => $this->isEof(),
            'unread_bytes' => 0,
            'stream_type' => 'MEMORY',
            'wrapper_type' => '',
            'wrapper_data' => null,
            'mode' => $this->mode->getMode(),
            'seekable' => true,
            'uri' => 'binsoul://memory',
        ];

        if ($key === null) {
            return $meta;
        }

        if (!array_key_exists($key, $meta)) {
            return null;
        }

        return $meta[$key];
    }

    public function detach()
    {
        $this->assertOpen();
        $this->close();

        return;
    }

    /**
     * Returns the number of bytes of the given string.
     *
     * If mbstring function overloading is enabled strlen could return the number of characters
     * instead of the number of bytes. In this case mb_strlen with the encoding "8bit" is used.
     *
     * @param string $string
     *
     * @return int
     */
    private function length($string)
    {
        if (function_exists('mb_strlen')) {
            return mb_strlen($string, '8bit');
        } else {
            return strlen($string);
        }
    }
}
