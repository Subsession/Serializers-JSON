<?php
/**
 * PHP Version 7
 *
 * LICENSE:
 * Proprietary, see the LICENSE file that was provided with the software.
 *
 * Copyright (c) 2019 - present Comertis <info@comertis.com>
 *
 * @category Serializers
 * @package  Comertis\Serializers
 * @author   Cristian Moraru <cristian@comertis.com>
 * @license  Proprietary
 * @version  GIT: &Id&
 * @link     https://github.com/Comertis/Serializers
 */

namespace Comertis\Serializers\Json;

use Comertis\Exceptions\InvalidOperationException;
use Comertis\Serializers\Abstraction\EncoderInterface;

/**
 * Undocumented class
 *
 * @category Serializers
 * @package  Comertis\Serializers
 * @author   Cristian Moraru <cristian@comertis.com>
 * @license  Proprietary
 * @version  Release: 1.0.0
 * @link     https://github.com/Comertis/Serializers
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
