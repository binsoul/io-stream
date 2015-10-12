<?php

namespace BinSoul\Test\IO\Stream\Type;

use BinSoul\IO\Stream\AccessMode;
use BinSoul\IO\Stream\Type\PhpMemoryStream;

class PhpMemoryStreamTest extends \PHPUnit_Framework_TestCase
{
    public function test_uses_correct_php_stream()
    {
        $stream = new PhpMemoryStream();
        $stream->open(new AccessMode('r+'));
        $this->assertEquals('php://memory', $stream->getMetadata('uri'));
    }
}
