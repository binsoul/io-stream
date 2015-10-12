<?php

namespace BinSoul\IO\Stream\Type;

use BinSoul\IO\Stream\AccessMode;

/**
 * Provides a wrapper around the php://input stream.
 */
class PhpInputStream extends ResourceStream
{
    /**
     * Constructs an instance of this class.
     */
    public function __construct()
    {
        parent::__construct('php://input');
    }

    public function open(AccessMode $mode)
    {
        if ($mode->allowsWrite()) {
            throw new \LogicException(sprintf('The stream "%s" is not writable.', 'php://input'));
        }

        return parent::open($mode);
    }

    public function isWritable()
    {
        parent::isWritable();

        return false;
    }
}
