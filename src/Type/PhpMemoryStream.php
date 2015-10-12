<?php

namespace BinSoul\IO\Stream\Type;

/**
 * Provides a wrapper around the php://memory stream.
 */
class PhpMemoryStream extends ResourceStream
{
    /**
     * Constructs an instance of this class.
     */
    public function __construct()
    {
        parent::__construct('php://memory');
    }
}
