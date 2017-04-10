<?php

namespace Chaos\Core\Struct;

interface Hashable {

    /**
     * Returns the hash of the object
     * @return string
     */
    public function hash ();
}