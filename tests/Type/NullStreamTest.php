<?php

namespace BinSoul\Test\IO\Stream\Type;

use BinSoul\IO\Stream\AccessMode;
use BinSoul\IO\Stream\Type\NullStream;
use BinSoul\Test\IO\Stream\MinimalStreamTest;

class NullStreamTest extends MinimalStreamTest
{
    protected function buildStream()
    {
        return new NullStream();
    }

    public function test_null_behaviour()
    {
        $stream = $this->buildStream();
        $stream->open(new AccessMode('w+'));

        $this->assertEquals(3, $stream->write('foo'));
        $this->assertEquals('', $stream->read(3));
        $this->assertTrue($stream->flush());
        $this->assertFalse($stream->seek(10));
        $this->assertEquals(0, $stream->tell());
        $this->assertTrue($stream->isEof());
        $this->assertTrue($stream->isReadable());
        $this->assertTrue($stream->isWritable());
        $this->assertFalse($stream->isSeekable());
        $this->assertEquals(0, $stream->getSize());
    }

    public function test_stat_returns_correct_values()
    {
        $expected = [
            'dev',
            'ino',
            'mode',
            'nlink',
            'uid',
            'gid',
            'rdev',
            'size',
            'atime',
            'mtime',
            'ctime',
            'blksize',
            'blocks',
        ];

        $stream = $this->buildStream();
        $stream->open(new AccessMode('w+'));

        $stat = $stream->getStatistics();
        foreach ($expected as $key) {
            $this->assertArrayHasKey($key, $stat);
        }
    }

    public function test_getMetadata_returns_correct_values()
    {
        $expected = [
            'timed_out',
            'blocked',
            'eof',
            'unread_bytes',
            'stream_type',
            'wrapper_type',
            'mode',
            'seekable',
            'uri',
        ];

        $stream = $this->buildStream();
        $stream->open(new AccessMode('w+'));

        $meta = $stream->getMetadata();
        foreach ($expected as $key) {
            $this->assertArrayHasKey($key, $meta);
        }

        $this->assertContains('w+', $meta['mode']);
        $this->assertEquals('MEMORY', $meta['stream_type']);
    }

    public function test_getMetadata_returns_null_for_unknown_key()
    {
        $stream = $this->buildStream();
        $stream->open(new AccessMode('w+'));
        $this->assertNull($stream->getMetadata('abc'));
    }

    public function test_getMetadata_returns_value_for_known_key()
    {
        $stream = $this->buildStream();
        $stream->open(new AccessMode('w+'));

        $this->assertContains('w+', $stream->getMetadata('mode'));
        $this->assertEquals('MEMORY', $stream->getMetadata('stream_type'));
    }

    public function test_detach_returns_null()
    {
        $stream = $this->buildStream();
        $stream->open(new AccessMode('w+'));
        $this->assertNull($stream->detach());
    }
}
