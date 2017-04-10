<?php

namespace Chaos\Core\Struct;

class Pair implements Hashable, Equatable {

    /**
     * Provides the name of the attribute
     * @var string
     */
    private $_name;

    /**
     * Provides the value of the attribute
     * @var string
     */
    private $_value;


    /**
     * Creates a new attribute object
     * @param string $name
     * @param string $value
     */
    public function __construct ($name, $value) {
        $this->_name = $name;
        $this->_value = $value;
    }


    /**
     * Returns the string representation of this attribute
     * @return string;
     */
    public function __toString () {
        if (empty($this->_value)) return $this->_name;
        return sprintf('%s="%s"', $this->_name, $this->_value);
    }


    /**
     * Returns the has value of the attribute
     */
    public function hash () {
        return md5($this->__toString());
    }


    /**
     * Determines if given attribute is equal to this
     * @param Pair $attr
     * @return boolean
     */
    public function equals (Equatable $attr) {
        return $attr instanceof Pair && $this->hash() == $attr->hash();
    }


    /**
     * Returns the name of the attribute
     * @return string
     */
    public function getName () {
        return $this->_name;
    }


    /**
     * Returns the value of the attribute
     * @return string
     */
    public function getValue () {
        return $this->_value;
    }
}