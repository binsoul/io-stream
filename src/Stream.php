<?php

namespace BinSoul\IO\Stream;

/**
 * Provides methods for the operations on an ordered sequence of bytes.
 */
interface Stream
{
    /**
     * Opens the stream with the desired access mode.
     *
     * @param AccessMode $mode
     *
     * @return bool
     */
    public function open(AccessMode $mode);

    /**
     * Closes the stream.
     *
     * @return bool
     */
    public function close();

    /**
     * Returns the given number of bytes from the stream.
     *
     * @param int $numberOfBytes
     *
     * @return string
     */
    public function read($numberOfBytes);

    /**
     * Writes given data to the stream and returns the number of bytes written.
     *
     * @param string $data
     *
     * @return int
     */
    public function write($data);

    /**
     * Flushes all buffered data.
     *
     * @return bool
     */
    public function flush();

    /**
     * Changes the position of the internal pointer.
     *
     * Possible whence values are:
     *  - SEEK_SET - Set position equal to offset bytes.
     *  - SEEK_CUR - Set position to current location plus offset.
     *  - SEEK_END - Set position to end-of-file plus offset.
     *
     * @param int $offset number of bytes to move
     * @param int $whence SEEK_XXX mode of movement
     *
     * @return bool
     */
    public function seek($offset, $whence = SEEK_SET);

    /**
     * Returns the current position of the internal pointer or null if unknown.
     *
     * @return int|null
     */
    public function tell();

    /**
     * Checks if the internal pointer has reached the end of the stream.
     *
     * @return bool
     */
    public function isEof();

    /**
     * Returns whether or not the stream is readable.
     *
     * @return bool
     */
    public function isReadable();

    /**
     * Returns whether or not the stream is writable.
     *
     * @return bool
     */
    public function isWritable();

    /**
     * Returns whether or not the stream is seekable.
     *
     * @return bool
     */
    public function isSeekable();

    /**
     * Returns the size of the stream if known or null if unknown.
     *
     * @return int|null
     */
    public function getSize();

    /**
     * Returns information about a stream.
     *
     * The keys returned are identical to the keys returned from PHP's fstat() function.
     *
     * @link http://php.net/manual/en/function.fstat.php
     *
     * @return mixed[]
     */
    public function getStatistics();

    /**
     * Returns stream metadata as an associative array or retrieve a specific key.
     *
     * The keys returned are identical to the keys returned from PHP's stream_get_meta_data() function.
     *
     * @link http://php.net/manual/en/function.stream-get-meta-data.php
     *
     * @param string|null $key Specific metadata to retrieve.
     *
     * @return mixed[]|mixed|null Returns an associative array if no key is
     *                            provided. Returns a specific key value if a key is provided and the
     *                            value is found, or null if the key is not found.
     */
    public function getMetadata($key = null);

    /**
     * Separates any underlying resources from the stream.
     *
     * After the stream has been detached, the stream is in an unusable state.
     *
     * @return resource|null Underlying PHP stream
     */
    public function detach();
}
