<?php

namespace Chaos\Core\Struct;

class PairList implements \ArrayAccess, \Iterator {

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
    public function __construct () {

    }


    public function __toString () {
        return implode(' ', $this->_pairs);
    }


    /**
     * Appends a ne pair to this list
     * @param Attr $pair
     */
    public function append (Pair $pair) {
        $index = array_search($pair->getName(), $this->_keys);
        if ($index === false) {
            array_push($this->_pairs, $pair);
            array_push($this->_keys, $pair->getName());
        }
    }



    public function hasAttr (Pair $pair) {
        return array_search($pair, $this->_pairs) !== false;
    } 


    /**
     * Returns the current pair
     * @return Attr
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
        return array_search($offset, $this->_keys);
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
        if (!$this->offsetExists($offset) || !($value instanceof Pair) || !is_int($offset)) return;
        $this->_pairs[$offset] = $value;
        $this->_keys[$offset] = $value->getName();    
    }


    /**
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