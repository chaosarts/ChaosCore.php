<?php 

namespace Chaos\Core\Struct;

interface Equatable {

    /**
     * Returns true, if given object is equal to this object
     * @param Equatable $object
     * @return boolean
     */
    public function equals (Equatable $object);
}