<?php

namespace BinSoul\IO\Stream\Type;

use BinSoul\IO\Stream\AccessMode;
use BinSoul\IO\Stream\ByteManipulator;
use BinSoul\IO\Stream\Stream;

/**
 * Does not store any data written to it.
 */
class NullStream implements Stream
{
    use ByteManipulator;

    /** @var AccessMode */
    private $mode;
    /** @var bool */
    private $isOpen;

    public function open(AccessMode $mode)
    {
        if ($this->isOpen) {
            throw new \LogicException('The stream is already open.');
        }

        $this->mode = $mode;
        $this->isOpen = true;

        return true;
    }

    public function close()
    {
        $this->assertOpen();
        $this->isOpen = false;

        return true;
    }

    public function read($numberOfBytes)
    {
        $this->assertOpen();

        return '';
    }

    public function write($data)
    {
        $this->assertOpen();

        return $this->numberOfBytes($data);
    }

    public function flush()
    {
        $this->assertOpen();

        return true;
    }

    public function seek($offset, $whence = SEEK_SET)
    {
        $this->assertOpen();

        return false;
    }

    public function tell()
    {
        $this->assertOpen();

        return 0;
    }

    public function isEof()
    {
        $this->assertOpen();

        return true;
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

        return false;
    }

    public function getSize()
    {
        $this->assertOpen();

        return 0;
    }

    public function getStatistics()
    {
        $this->assertOpen();

        $stats = [
            'dev' => 1,
            'ino' => 0,
            'mode' => 33204,
            'nlink' => 1,
            'uid' => 0,
            'gid' => 0,
            'rdev' => 0,
            'size' => 0,
            'atime' => time(),
            'mtime' => time(),
            'ctime' => time(),
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
            'seekable' => false,
            'uri' => 'binsoul://null',
        ];

        if ($key === null) {
            return $meta;
        }

        if (!array_key_exists($key, $meta)) {
            return;
        }

        return $meta[$key];
    }

    public function detach()
    {
        $this->close();

        return;
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
}
