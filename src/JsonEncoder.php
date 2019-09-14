<?php
/**
 * PHP Version 7
 *
 * LICENSE:
 * Copyright 2019 Subsession
 *
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation
 * files (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge,
 * publish, distribute, sublicense, and/or sell copies of the Software,
 * and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY
 * CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
 * TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE
 * OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * @category Serializers
 * @package  Subsession\Serializers
 * @author   Cristian Moraru <cristian.moraru@live.com>
 * @license  https://opensource.org/licenses/MIT MIT
 * @version  GIT: &Id&
 * @link     https://github.com/Subsession/Serializers-JSON
 */

namespace Subsession\Serializers\Json;

use Subsession\Exceptions\InvalidOperationException;
use Subsession\Serializers\Abstraction\EncoderInterface;

/**
 * Undocumented class
 *
 * @category Serializers
 * @package  Subsession\Serializers
 * @author   Cristian Moraru <cristian.moraru@live.com>
 * @license  https://opensource.org/licenses/MIT MIT
 * @version  Release: 1.0.0
 * @link     https://github.com/Subsession/Serializers-JSON
 */
class JsonEncoder implements EncoderInterface
{
    /**
     * Encodes PHP data to a JSON string.
     *
     * @inheritdoc
     *
     * @access public
     * @throws InvalidOperationException If $data fails to encode
     * @return string
     */
    public function encode($data)
    {
        try {
            $encodedJson = json_encode($data);
        } catch (\JsonException $e) {
            throw new InvalidOperationException($e->getMessage(), 0, $e);
        }

        if (\PHP_VERSION_ID >= 70300) {
            return $encodedJson;
        }

        if (JSON_ERROR_NONE !== json_last_error() && (false === $encodedJson)) {
            throw new InvalidOperationException(json_last_error_msg());
        }

        return $encodedJson;
    }
}
