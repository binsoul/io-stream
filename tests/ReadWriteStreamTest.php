<?php

namespace BinSoul\Test\IO\Stream;

use BinSoul\IO\Stream\AccessMode;

abstract class ReadWriteStreamTest extends MinimalStreamTest
{
    public function test_open_is_successfull()
    {
        $stream = $this->buildStream();
        $this->assertTrue($stream->open(new AccessMode('w')));
        $this->assertFalse($stream->isReadable());
        $this->assertTrue($stream->isWritable());

        $stream = $this->buildStream();
        $this->assertTrue($stream->open(new AccessMode('r')));
        $this->assertTrue($stream->isReadable());
        $this->assertFalse($stream->isWritable());
    }

    public function test_close_is_successful()
    {
        $stream = $this->buildStream();
        $stream->open(new AccessMode('r'));
        $stream->close();
    }

    public function test_read_returns_written_data()
    {
        $stream = $this->buildStream();
        $stream->open(new AccessMode('r+'));
        $stream->write('abc');
        $stream->seek(0);
        $this->assertSame('abc', $stream->read(3));
    }

    /**
     * @expectedException \LogicException
     */
    public function test_read_throws_exception_if_not_readable()
    {
        $stream = $this->buildStream();
        $stream->open(new AccessMode('w'));
        $stream->read(3);
    }

    public function test_write_works()
    {
        $stream = $this->buildStream();
        $stream->open(new AccessMode('w+'));
        $this->assertEquals(3, $stream->write('abc'));

        $stream->seek(0);
        $this->assertSame('abc', $stream->read(3));

        $stream->seek(0);
        $this->assertSame(1, $stream->write('d'));
        $stream->seek(0);
        $this->assertSame('dbc', $stream->read(3));
    }

    public function test_write_prepends_zeros()
    {
        $stream = $this->buildStream();
        $stream->open(new AccessMode('w+'));
        if ($stream->seek(3)) {
            $this->assertEquals(3, $stream->write('abc'));
            $stream->seek(0);
            $this->assertEquals(chr(0).chr(0).chr(0).'abc', $stream->read(6));
        } else {
            $this->assertEquals(3, $stream->write('abc'));
            $stream->seek(0);
            $this->assertEquals('abc', $stream->read(3));
        }
    }

    /**
     * @expectedException \LogicException
     */
    public function test_write_throws_exception_if_not_writeable()
    {
        $stream = $this->buildStream();
        $stream->open(new AccessMode('r'));
        $stream->write('abc');
    }

    public function test_seek_works()
    {
        $stream = $this->buildStream();
        $stream->open(new AccessMode('w+'));
        $this->assertSame(3, $stream->write('abc'));
        $this->assertTrue($stream->seek(0, SEEK_SET));
        $this->assertSame('abc', $stream->read(3));
        $stream->seek(0, SEEK_SET);
        $this->assertTrue($stream->seek(2, SEEK_CUR));
        $this->assertSame('c', $stream->read(1));
        $stream->seek(2, SEEK_SET);
        $this->assertTrue($stream->seek(-1, SEEK_CUR));
        $this->assertSame('b', $stream->read(1));
        $this->assertTrue($stream->seek(-1, SEEK_END));
        $this->assertSame('c', $stream->read(1));

        $this->assertFalse($stream->seek(0, 123456));
    }

    public function test_tell_returns_correct_position()
    {
        $stream = $this->buildStream();
        $stream->open(new AccessMode('w+'));
        $stream->write('abc');

        $this->assertEquals(3, $stream->tell());
        $stream->seek(0);
        $this->assertEquals(0, $stream->tell());
        $stream->seek(2, SEEK_CUR);
        $this->assertEquals(2, $stream->tell());
        $stream->seek(-3, SEEK_END);
        $this->assertEquals(0, $stream->tell());
    }

    public function test_flush_works()
    {
        $stream = $this->buildStream();
        $stream->open(new AccessMode('w+'));
        $this->assertTrue($stream->flush());
    }

    public function test_eof_works()
    {
        $stream = $this->buildStream();
        $stream->open(new AccessMode('r'));

        if ($stream->seek(3)) {
            $this->assertTrue($stream->isEof());
        } else {
            $stream->isEof();
            $this->assertTrue(true);
        }
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
        $stream->write('abc');

        $stat = $stream->getStatistics();
        foreach ($expected as $key) {
            $this->assertArrayHasKey($key, $stat);
        }

        $this->assertEquals(3, $stat['size']);
    }

    public function test_size_returns_correct_value()
    {
        $stream = $this->buildStream();
        $stream->open(new AccessMode('w+'));
        $stream->write('abc');

        $this->assertEquals(3, $stream->getSize());
        if ($stream->seek(10)) {
            $stream->write('d');
            $this->assertEquals(11, $stream->getSize());
        }
    }

    public function test_isReadable_uses_mode()
    {
        $stream = $this->buildStream();
        $stream->open(new AccessMode('r'));
        $this->assertTrue($stream->isReadable());

        $stream = $this->buildStream();
        $stream->open(new AccessMode('w'));
        $this->assertFalse($stream->isReadable());
    }

    public function test_isWriteable_uses_mode()
    {
        $stream = $this->buildStream();
        $stream->open(new AccessMode('r'));
        $this->assertFalse($stream->isWritable());

        $stream = $this->buildStream();
        $stream->open(new AccessMode('w'));
        $this->assertTrue($stream->isWritable());
    }

    public function test_isSeekable_returns_true()
    {
        $stream = $this->buildStream();
        $stream->open(new AccessMode('r'));
        $this->assertTrue($stream->isSeekable());
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
        $stream->write('abc');

        $meta = $stream->getMetadata();
        foreach ($expected as $key) {
            $this->assertArrayHasKey($key, $meta);
        }

        $this->assertContains('w+', $meta['mode']);
        $this->assertEquals('MEMORY', $meta['stream_type']);

        $stream->seek(0);
        $meta = $stream->getMetadata();
        $this->assertFalse($meta['eof']);
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
        $stream->write('abc');

        $this->assertContains('w+', $stream->getMetadata('mode'));
        $this->assertEquals('MEMORY', $stream->getMetadata('stream_type'));
    }

    /**
     * @expectedException \LogicException
     */
    public function test_detach_closes_stream()
    {
        $stream = $this->buildStream();
        $stream->open(new AccessMode('w+'));
        $stream->detach();
        $stream->isWritable();
    }
}
