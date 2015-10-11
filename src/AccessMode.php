<?php

namespace BinSoul\IO\Stream;

/**
 * Describes the type of access you desire to a stream.
 *
 * Possible modes:
 * - *r* - Open for reading only; place the internal pointer at the beginning of the stream.
 * - *w* - Open for writing only; place the internal pointer at the beginning of the stream
 *   and truncate the stream to zero length. If the stream does not exist, attempt to create it.
 * - *a* - Open for writing only; place the internal pointer at the end of the stream. If the stream does not exist,
 *   attempt to create it. In this mode, seek() only affects the reading position, writes are always appended.
 * - *x* - Create and open for writing only; place the stream pointer at the beginning of the stream.
 *   If the stream already exists, the open() call will fail. If the stream does not exist, attempt to create it.
 * - *c* - Open the stream for writing only. If the stream does not exist, it is created. If it exists,
 *   it is neither truncated (as opposed to 'w'), nor the call to this function fails (as is the case with 'x').
 *   The internal pointer is positioned on the beginning of the stream.
 *
 * Possible flags:
 * - *+* - Open for reading and writing.
 * - *b* - Force binary mode.
 * - *t* - Force text mode.
 */
class AccessMode
{
    /** @var string */
    private $base;
    /** @var bool */
    private $plus;
    /** @var string */
    private $flag;

    /**
     * Constructs an instance of this class.
     *
     * Forces the mode to binary if no other flag is specified.
     *
     * @param string $mode desired access mode
     */
    public function __construct($mode)
    {
        $mode = substr($mode, 0, 3);
        $rest = substr($mode, 1);

        $this->base = substr($mode, 0, 1);
        $this->plus = (strpos($rest, '+') !== false);
        $this->flag = trim($rest, '+');

        if ($this->flag == '') {
            $this->flag = 'b';
        }
    }

    /**
     * Returns the access mode as a string.
     *
     * @return string
     */
    public function getMode()
    {
        return $this->base.($this->plus ? '+' : '').$this->flag;
    }

    /**
     * Returns whether reading from the stream is allowed.
     *
     * @return bool
     */
    public function allowsRead()
    {
        if ($this->plus) {
            return true;
        }

        return $this->base == 'r';
    }

    /**
     * Returns whether writing to the stream is allowed.
     *
     * @return bool
     */
    public function allowsWrite()
    {
        if ($this->plus) {
            return true;
        }

        return $this->base != 'r';
    }

    /**
     * Returns if existing streams can be opened.
     *
     * @return bool
     */
    public function allowsExistingStreamOpening()
    {
        return $this->base != 'x';
    }

    /**
     * Returns if new streams can be opened.
     *
     * @return bool
     */
    public function allowsNewStreamOpening()
    {
        return $this->base != 'r';
    }

    /**
     * Returns if existing streams are truncated on opening the stream.
     *
     * @return bool
     */
    public function impliesExistingContentDeletion()
    {
        return $this->base == 'w';
    }

    /**
     * Returns if the cursor is positioned at the beginning of the stream on opening the stream.
     *
     * @return bool
     */
    public function impliesPositioningCursorAtTheBeginning()
    {
        return $this->base != 'a';
    }

    /**
     * Returns if the cursor is positioned at the end of the stream on opening the stream.
     *
     * @return bool
     */
    public function impliesPositioningCursorAtTheEnd()
    {
        return $this->base == 'a';
    }

    /**
     * Returns if the stream is opened in binary mode.
     *
     * @return bool
     */
    public function isBinary()
    {
        return $this->flag == 'b';
    }

    /**
     * Returns if the stream is opened in text mode.
     *
     * @return bool
     */
    public function isText()
    {
        return $this->isBinary() === false;
    }
}
