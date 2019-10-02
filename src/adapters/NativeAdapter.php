<?php

/**
 * PHP Version 7
 *
 * LICENSE:
 * See the LICENSE file that was provided with the software.
 *
 * Copyright (c) 2019 - present Subsession
 *
 * @author Cristian Moraru <cristian@subsession.org>
 */

namespace Subsession\Serializers\Json\Adapters;

use Subsession\Exceptions\InvalidOperationException;

use Subsession\Serializers\Abstraction\{
    EncoderInterface,
    DecoderInterface,
};

/**
 * This adapter uses the native php `json_encode` & `json_decode` functions
 *
 * @author Cristian Moraru <cristian@subsession.org>
 */
class NativeAdapter implements EncoderInterface, DecoderInterface
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
     * Encodes PHP data to a JSON string.
     *
     * @inheritdoc
     *
     * @throws InvalidOperationException If $data fails to encode
     * @access public
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
     * @throws InvalidOperationException
     * @throws ArgumentNullException If $data argument is null
     * @access public
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
