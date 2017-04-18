<?php

namespace Chaos\Core\Struct;

class PairList implements \ArrayAccess, \Iterator, \Countable {

    /**
     * Provides the list of pairs
     * @var array
     */
    private $_pairs = array();

    /**
     * Provides the list of keys associated with pairs in $_pairs at according
     * position in array
     * @var array
     */
    private $_keys = array();

    /**
     * Helper property for Iterator
     * @var int
     */
    private $_itPosition = -1;

    /**
     * Creates a new name dpair list
     */
    public function __construct (array $assocList = array()) {
        foreach ($assocList as $key => $value) {
            if (!is_string($key)) continue;
            $this->set($key, $value);
        }
    }


    /**
     * Returns the pair list as string
     * @return string
     */
    public function __toString () {
        return implode(' ', $this->_pairs);
    }


    /**
     * Appends a ne pair to this list
     * @param string $key
     * @param mixed $value
     */
    public function set ($key, $value) {
        $index = $this->_key2index($key);
        if ($index < 0) {
            array_push($this->_pairs, new Pair($key, $value));
            array_push($this->_keys, $key);
        }
        else {
            $this->_pairs[$index]->setValue($value);
        }
    }


    /**
     * Returns the index according to the key
     * @param string $key
     * @return int
     */
    private function _key2index ($key) {
        $index = array_search($key, $this->_keys);
        return $index === false ? -1 : $index;
    }


    /**
     * Returns the index according to the key
     * @param string $key
     * @return int
     */
    private function _index2key ($index) {
        if ($index < 0 || $index > $this->count() - 1)
            return null;

        return $this->_keys[$index];
    }


    /**
     * Returns the count of pairs containing in this list
     * @return int
     */
    public function count () {
        return count($this->_pairs);
    }


    /**
     * Returns the current pair
     * @return Pair
     */
    public function current () {
        return $this->_pairs[$this->_itPosition];
    }


    /**
     * Returns the key of the current Attr
     * @return scalar
     */
    public function key () {
        return $this->current()->getName();
    }


    /**
     * Moves the iterator position to the next position
     */
    public function next () {
        $this->_itPosition++;
    }


    /**
     * Resets the iterator position to start
     */
    public function rewind () {
        $count = count($this->_pairs);
        $this->_itPosition = $count == 0 ? -1 : 0;
    }


    /**
     * Determines if the current iterator position is valid or not
     * @return boolean
     */
    public function valid () {
        return $this->_itPosition > -1 && $this->_itPosition < count($this->_pairs);
    }


    /**
     * @return boolean
     */
    public function offsetExists ($offset) {
        if (is_int($offset)) return isset($this->_pairs[$offset]);
        return array_search($offset, $this->_keys) !== false;
    }


    /**
     * @return mixed
     */
    public function offsetGet ($offset) {
        if (!$this->offsetExists($offset)) return null;

        $index = $offset;
        if (!is_int($offset)) 
            $index = array_search($offset, $this->_keys);

        return $this->_pairs[$index];
    }


    /**
     * @return void
     */
    public function offsetSet ($offset, $value) {
        
    }


    /**
     * Unsets an value coressponding to the offset from this list
     * @return void
     */
    public function offsetUnset ($offset) {
        if (!$this->offsetExists($offset))
            return;

        $index = $offset;
        if (!is_int($index))
            $index = array_search($offset, $this->_keys);

        $this->_pairs = array_merge(
            array_slice($this->_pairs, 0, $index),
            array_slice($this->_pairs, $index + 1)
        );

        $this->_keys = array_merge(
            array_slice($this->_keys, 0, $index),
            array_slice($this->_keys, $index + 1)
        );
    }

}