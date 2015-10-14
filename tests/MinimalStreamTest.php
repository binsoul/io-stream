<?php

namespace BinSoul\Test\IO\Stream;

use BinSoul\IO\Stream\Stream;
use BinSoul\IO\Stream\AccessMode;

abstract class MinimalStreamTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return Stream
     */
    abstract protected function buildStream();

    /**
     * @expectedException \LogicException
     */
    public function test_open_throws_exception_if_already_open()
    {
        $stream = $this->buildStream();
        $accessMode = 'w';
        try {
            $stream->open(new AccessMode($accessMode));
        } catch (\Exception $e) {
            $accessMode = 'r';
            $stream->open(new AccessMode($accessMode));
        }

        $stream->open(new AccessMode($accessMode));
    }

    /**
     * @expectedException \LogicException
     */
    public function test_close_throws_exception_if_not_open()
    {
        $stream = $this->buildStream();
        $stream->close();
    }

    /**
     * @expectedException \LogicException
     */
    public function test_read_throws_exception_if_not_open()
    {
        $stream = $this->buildStream();
        $stream->read(3);
    }

    /**
     * @expectedException \LogicException
     */
    public function test_write_throws_exception_if_not_open()
    {
        $stream = $this->buildStream();
        $stream->write('abc');
    }

    /**
     * @expectedException \LogicException
     */
    public function test_seek_throws_exception_if_not_open()
    {
        $stream = $this->buildStream();
        $stream->seek(0);
    }

    /**
     * @expectedException \LogicException
     */
    public function test_tell_throws_exception_if_not_open()
    {
        $stream = $this->buildStream();
        $stream->tell();
    }

    /**
     * @expectedException \LogicException
     */
    public function test_flush_throws_exception_if_not_open()
    {
        $stream = $this->buildStream();
        $stream->flush();
    }

    /**
     * @expectedException \LogicException
     */
    public function test_eof_throws_exception_if_not_open()
    {
        $stream = $this->buildStream();
        $stream->isEof();
    }

    /**
     * @expectedException \LogicException
     */
    public function test_getSize_throws_exception_if_not_open()
    {
        $stream = $this->buildStream();
        $stream->getSize();
    }

    /**
     * @expectedException \LogicException
     */
    public function test_getStatistics_throws_exception_if_not_open()
    {
        $stream = $this->buildStream();
        $stream->getStatistics();
    }

    /**
     * @expectedException \LogicException
     */
    public function test_isReadable_throws_exception_if_not_open()
    {
        $stream = $this->buildStream();
        $stream->isReadable();
    }

    /**
     * @expectedException \LogicException
     */
    public function test_isWritable_throws_exception_if_not_open()
    {
        $stream = $this->buildStream();
        $stream->isWritable();
    }

    /**
     * @expectedException \LogicException
     */
    public function test_isSeekable_throws_exception_if_not_open()
    {
        $stream = $this->buildStream();
        $stream->isSeekable();
    }

    /**
     * @expectedException \LogicException
     */
    public function test_getMetadata_throws_exception_if_not_open()
    {
        $stream = $this->buildStream();
        $stream->getMetadata();
    }

    /**
     * @expectedException \LogicException
     */
    public function test_appendTo_throws_exception_if_not_open()
    {
        $stream = $this->buildStream();
        $stream->appendTo($stream);
    }
}
