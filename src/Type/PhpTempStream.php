<?php

namespace BinSoul\IO\Stream\Type;

/**
 * Provides a wrapper around the php://temp stream.
 */
class PhpTempStream extends ResourceStream
{
    /**
     * Constructs an instance of this class.
     *
     * @param int|null $memoryBufferSize maximum number of bytes to keep in memory before using a temporary file
     */
    public function __construct($memoryBufferSize = null)
    {
        $uri = 'php://temp';
        if ((int) $memoryBufferSize > 0) {
            $uri .= '/maxmemory:'.((int) $memoryBufferSize);
        }

        parent::__construct($uri);
    }
}
