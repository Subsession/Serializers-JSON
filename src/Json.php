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

namespace Subsession\Serializers\Json;

use Subsession\Serializers\Json\Adapters\{
    NativeAdapter,
};

/**
 * Undocumented class
 *
 * @author Cristian Moraru <cristian@subsession.org>
 */
class Json
{
    private $adapter = null;

    public function __construct()
    {
        $this->adapter = new NativeAdapter();
    }

    public function encode($data)
    {
        return $this->adapter->encode($data);
    }

    public function decode($data)
    {
        return $this->adapter->decode($data);
    }
}
