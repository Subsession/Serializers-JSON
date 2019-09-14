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

use Subsession\Exceptions\ArgumentNullException;
use Subsession\Exceptions\InvalidOperationException;
use Subsession\Serializers\Abstraction\DecoderInterface;

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
class JsonDecoder implements DecoderInterface
{
    /**
     * True to return the result as an associative array, false for a nested stdClass hierarchy.
     */
    const ASSOCIATIVE = 'json_decode_associative';

    const OPTIONS = 'json_decode_options';

    /**
     * Specifies the recursion depth.
     */
    const RECURSION_DEPTH = 'json_decode_recursion_depth';

    private $defaultContext = [
        self::ASSOCIATIVE => false,
        self::RECURSION_DEPTH => 512,
        self::OPTIONS => 0,
    ];

    /**
     * Decodes data.
     *
     * @param string $data    The encoded JSON string to decode
     * @param array  $context An optional set of options for the JSON decoder; see below
     *
     * The $context array is a simple key=>value array, with the following supported keys:
     *
     * json_decode_associative: boolean
     *      If true, returns the object as an associative array.
     *      If false, returns the object as nested stdClass
     *      If not specified, this method will use the default set in JsonDecode::__construct
     *
     * json_decode_recursion_depth: integer
     *      Specifies the maximum recursion depth
     *      If not specified, this method will use the default set in JsonDecode::__construct
     *
     * json_decode_options: integer
     *      Specifies additional options as per documentation for json_decode
     *
     * @see https://php.net/json_decode
     *
     * @inheritDoc
     *
     * @access public
     * @throws InvalidOperationException
     * @throws ArgumentNullException If $data argument is null
     * @return mixed
     */
    public function decode($data, array $context = [])
    {
        if (null === $data) {
            throw new ArgumentNullException("Argument 'data' cannot be null");
        }

        $associative = $context[self::ASSOCIATIVE] ?? $this->defaultContext[self::ASSOCIATIVE];
        $recursionDepth = $context[self::RECURSION_DEPTH] ?? $this->defaultContext[self::RECURSION_DEPTH];
        $options = $context[self::OPTIONS] ?? $this->defaultContext[self::OPTIONS];

        try {
            $decodedData = json_decode($data, $associative, $recursionDepth, $options);
        } catch (\JsonException $e) {
            throw new InvalidOperationException($e->getMessage(), 0, $e);
        }

        if (\PHP_VERSION_ID >= 70300 && (JSON_THROW_ON_ERROR & $options)) {
            return $decodedData;
        }

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new InvalidOperationException(json_last_error_msg());
        }

        return $decodedData;
    }
}
