<?php

namespace BinSoul\IO\Stream;

/**
 * Provides methods to safely work with strings as bytes.
 *
 * If mbstring function overloading is enabled regular string functions like strlen or substr also work with
 * multibyte characters instead of bytes. In this case the mb_...-functions with the encoding "8bit" are used.
 */
trait ByteManipulator
{
    /**
     * Returns the number of bytes of the given string.
     *
     * @param string $string
     *
     * @return int
     */
    protected function numberOfBytes($string)
    {
        if (function_exists('mb_strlen')) {
            return mb_strlen($string, '8bit');
        } else {
            return strlen($string);
        }
    }

    /**
     * Returns a part of a string.
     *
     * @param string   $string
     * @param int      $start
     * @param int|null $length
     *
     * @return string
     */
    protected function subBytes($string, $start, $length = null)
    {
        if (function_exists('mb_substr')) {
            return mb_substr($string, $start, $length, '8bit');
        } else {
            return substr($string, $start, $length);
        }
    }
}
