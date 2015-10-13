<?php

namespace BinSoul\IO\Stream\Type;

use BinSoul\IO\Stream\AccessMode;

/**
 * Provides a wrapper around the php://output stream.
 */
class PhpOutputStream extends ResourceStream
{
    /**
     * Constructs an instance of this class.
     */
    public function __construct()
    {
        parent::__construct('php://output');
    }

    public function open(AccessMode $mode)
    {
        if ($mode->allowsRead()) {
            throw new \LogicException(sprintf('The stream "%s" is not readable.', 'php://output'));
        }

        return parent::open($mode);
    }

    public function isReadable()
    {
        parent::isReadable();

        return false;
    }

    public function isSeekable()
    {
        parent::isSeekable();

        return false;
    }
}
