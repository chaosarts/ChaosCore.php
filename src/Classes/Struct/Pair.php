<?php

namespace Chaos\Core\Struct;

class Pair {

    /**
     * Provides the key of the attribute
     * @var string
     */
    private $_key;

    /**
     * Provides the value of the attribute
     * @var mixed
     */
    private $_value;


    /**
     * Creates a new attribute object
     * @param string $key
     * @param mixed $value
     */
    public function __construct ($key, $value = null) {
        $this->_key = $key;
        $this->_value = $value;
    }


    /**
     * Returns the string representation of this attribute
     * @return string
     */
    public function __toString () {
        if (empty($this->_value)) return $this->_key;
        return sprintf('%s="%s"', $this->_key, $this->_value);
    }


    /**
     * Returns the key of the attribute
     * @return string
     */
    public function getKey () {
        return $this->_key;
    }


    /**
     * Sets the value of this pair
     * @param mixed $value
     */
    public function setValue ($value) {
        $this->_value = $value;
    }


    /**
     * Returns the value of the attribute
     * @return mixed
     */
    public function getValue () {
        return $this->_value;
    }
}