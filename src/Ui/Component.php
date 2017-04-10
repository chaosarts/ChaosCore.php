<?php
namespace Chaos\Core\Ui;

use Chaos\Core\Struct\PairList;
use Chaos\Core\Struct\Pair;
use Chaos\Core\Util\PathUtil;

abstract class Component {

    /**
     * Returns example arguments to pass to partial for given variant
     * @return array
     */
    abstract public static function getExamples ();

    /**
     * Returns one example for given section
     * @param string $section
     * @return Component
     */
    abstract public static function getExample ($section);

    /**
     * Provides the tag of the component
     * @var string
     */
    private $_tag = '';

    /**
     * Provides the list of arguments to pass to the partial
     * @var PairList
     */
    private $_attributes;

    /**
     * Provides the list of css class names
     * @var array
     */
    private $_classes = array();

    /**
     * Provides the parent of this component
     * @var Container
     */
    protected $_parent = null;

    /**
     * Provides the name of the partial to render. If not set, the name will
     * be inferred from namespace and class name of this class
     * @var string
     */
    private $_partial = 'Ui/Component';

    /**
     * Provides the section to render
     * @var string
     */
    private $_section = '';

    /**
     * Provides a list of child components
     * @var array
     */
    private $_children = array();

    /**
     * Indicates whether the component renders a partial or not
     * @var boolean;
     */
    private $_usePartial = true;


    /**
     * Creates a new Component instance
     * @param Container $parent
     */
    public function __construct () {
        $this->_attributes = new PairList();
        $parts = explode('\\', strtolower(get_class($this)));
        $parts = array_slice($parts, 2);
        $this->setAttribute('id', uniqid(implode('-', $parts)));
    }


    /**
     * Returns the attribute or private property
     * @param string $methodname
     * @param array $arguments
     * @return mixed
     */
    public function __call ($methodname, array $arguments = array()) {
        $matches = array();
        preg_match('/([a-z]+)(.*)/', $methodname, $matches);
        list($fullMatch, $command, $attribute) = $matches;
        $attribute = lcfirst($attribute);

        switch ($command) {
            case 'get':
                if ($this->hasAttribute($attribute))
                    return $this->getAttribute($attribute);

                $property = '_' . $attribute;
                if (property_exists($this, $property))
                    return $this->{$property};
                break;
        }

        return null;
    }


    /**
     * Returns the string representation of this component
     * @return string
     */
    public function __toString () {
        return '';
    }


    /** 
     * Sets the tag name of the component explicitly
     * @param string $tag
     */
    public function setTag ($tag) {
        $this->_tag = $tag;   
    }


    /**
     * Returns the tag name of the component
     * @return string
     */
    public function getTag () {
        if (empty($this->_tag)) {
            $className = get_class($this);
            $parts = explode('\\', $className);
            list($tag) = array_slice($parts, -1);
            $this->_tag = $tag;
        }
        return $this->_tag;
    }


    /**
     * Returns a copy of the attribute list object
     * @return PairList
     */
    public function getAttributes () {
        $attributes = clone $this->_attributes;
        if (!empty($this->_classes))
            $attributes->append(new Pair('class', $this->getClasses()));
        return $attributes;
    }


    /**
     * Returns the attribute of given $name
     * @param string $name
     * @return string
     */
    public function getAttribute ($name) {
        if (!isset($this->_attributes[$name]))
            return null;

        return $this->_attributes[$name]->getValue();
    }


    /**
     * Determines if the component has attribute set with given name
     * @param string $name
     * @return boolean
     */
    public function hasAttribute ($name) {
        return isset($this->_attributes[$name]);
    }


    /**
     * Sets the list of arguments to pass to partial
     * @param array $arguments
     */
    public function setAttribute ($name, $value) {
        $this->_attributes->append(new Pair($name, $value));
    }


    /** 
     * Removes the attribute with given name
     * @param string $name
     * @return Pair
     */
    public function removeAttribute ($name) {
        if (!$this->hasAttribute($name)) return null;
        $attribute = $this->_attributes[$name];
        unset($this->_attributes[$name]);
        return $attribute;
    }


    public function addClass ($class) {
        if ($this->hasClass($class)) 
            return;
        array_push($this->_classes, $class);
    }


    public function removeClass ($class) {
        $index = array_search($class, $this->_classes);
        if ($index === false) return;
        $this->_classes = array_merge(
            array_slice($this->_classes, 0, $index),
            array_slice($this->_classes, $index + 1)
        );
    }


    public function hasClass ($class) {
        return array_search($class, $this->_classes) !== false;
    }


    public function enableClass ($class, $enable = true) {
        if ($this->hasClass($class) && !$enable) {
            $this->removeClass($class);
        }
        elseif (!$this->hasClass($class) && $enable) {
            $this->addClass($class);
        }
    }


    public function getClasses () {
        return implode(' ', $this->_classes);
    }


    /**
     * Sets the explicit partial to render
     * @param string $partial
     * @return AbstractComponent
     */
    public function setPartial ($partial) {
        $this->_partial = $partial;
        return $this;
    }


    /**
     * Returns the name of the partial
     * @return string
     */
    public function getPartial () {
        if (empty($this->_partial)) {
            $parts = explode('\\', get_class($this));
            $parts = array_slice($parts, 2);
            $this->_partial = implode('/', $parts);
        }

        return $this->_partial;
    }


    /**
     * Sets the flag for whether to use a partial template or not
     * @param boolean $use
     */
    public function setUsePartial ($use) {
        $this->_usePartial = !empty($use);
    }


    /** 
     * Returns the flag, 
     */
    public function getUsePartial () {
        return $this->_usePartial;
    }


    /**
     * Sets the section to render
     * @param string $section
     * @return AbstractComponent
     */
    public function setSection ($section) {
        $this->_section = $section;
    }


    /**
     * Returns the section of this component to render
     * @return string
     */
    public function getSection () {
        return $this->_section;
    }


    /**
     * Traverses the component tree up to the root and returns it
     * @return Component
     */
    public final function getRoot () {
        $component = $this;
        while (!$component->isRoot()) {
            $component = $component->_parent;
        }

        return $component;
    }


    /**
     * Determines if the component is the root
     * @return boolean
     */
    public final function isRoot () {
        return $this->_parent == null;
    }


    /**
     * Sets the parent of the component
     * @param Container $parent
     */
    public function setParent (Container $parent = null) {
        if ($this->_parent != null) 
            $this->_parent->_removeChild($this);

        $this->_parent = $parent;
    }


    /**
     * Returns the parent of this component
     * @return Container
     */
    public function getParent () {
        return $this->_parent;
    }


    /**
     * Returns the count of children
     * @return int
     */
    public final function countChildren () {
        return count($this->_children);
    }


    /**
     * Returns a copy of the list of child components
     * @return array
     */
    public final function getChildren () {
        return array_slice($this->_children, 0);
    }


    /**
     * Returns the child at given index if exists, otherwise null
     * @return Component
     */
    public final function childAt ($index) {
        if ($index < 0 || $index > $this->countChildren() - 1)
            return null;

        return $this->_children[$index];
    }


    /**
     * Returns the index of the child in this component
     * @param Component $child
     * @return int
     */
    public final function indexOf (Component $child) {
        $index = array_search($child, $this->_children);

        if ($index === false)
            return -1;

        return $index;
    }


    /**
     * Determines whether the child can be added to this component
     * @param Component $child
     * @return boolean
     */
    protected final function _canAddChild (Component $child) {
        return $child != $this && !$child->_isAncestorOf($this);
    }


    /**
     * Determines if this component is an ancestor of given component
     * @param Component $component
     * @return boolean
     */
    protected final function _isAncestorOf (Component $component) {
        while ($component->_parent != null) {
            if ($component->_parent == $this)
                return true;
            $component = $component->_parent;
        }

        return false;
    }


    /**
     * Determines whether the component has the child or not
     * @param Component $child
     * @return boolean
     */
    protected final function _hasChild (Component $child) {
        return $this->indexOf($child) >= 0;
    }


    /**
     * Appends a child to this component
     * @param Component
     */
    protected final function _appendChild (Component $child) {
        if (!$this->_canAddChild($child))
            return;

        if ($child->_parent != null)
            $child->_parent->_removeChild($child);

        array_push($this->_children, $child);
    }


    /**
     * Prepends a child to this component
     * @param Component
     */
    protected final function _prependChild (Component $child) {
        if (!$this->_canAddChild($child))
            return;

        if ($child->_parent != null)
            $child->_parent->_removeChild($child);

        array_unshift($this->_children, $child);
    }


    /**
     * Inserts the child at given index
     * @param int $index
     * @param Component $child
     */
    protected final function _insertChildAt ($index, Component $child) {
        if (!$this->_canAddChild($child))
            return;

        if ($child->_parent != null)
            $child->_parent->_removeChild($child);
        
        $i = max(0, min($index, $this->countChildren()));
        $leftArray = array_slice($this->_children, 0, $i);
        $rightArray = array_slice($this->_children, $i);

        array_push($leftArray, $child);
        $this->_children = array_merge($leftArray, $rightArray);
    }


    /**
     * Inserts a component to the parent component after this component
     * @param Component $sibling
     */
    protected final function insertAfter (Component $sibling) {
        if ($this->isRoot() || !$this->_canAddChild($child)) 
            return;

        $index = $this->_parent->indexOf($this) + 1;
        $this->_parent->_insertChildAt($index, $sibling);
    }


    /**
     * Inserts a component to the parent component before this component
     * @param Component $sibling
     */
    protected final function _insertBefore (Component $sibling) {
        if ($this->isRoot() || !$this->_canAddChild($child)) 
            return;

        $index = $this->_parent->indexOf($this);
        $this->_parent->_insertChildAt($index, $sibling);
    }


    /**
     * Replaces given child with new child. If child is not contained in this 
     * component, the new child will not be inserted
     *
     * @param Component $child
     * @param Component $newChild
     * @return int 
     */
    protected final function _replaceChild (Component $child, Component $newChild) {
        if (!$this->_hasChild($child) || !$this->_canAddChild($newChild) || $child == $newChild) 
            return -1;
        $child->_insertBefore($newChild);
        $this->_removeChild($child);
    }


    /**
     * Replaces given child with new child. If child is not contained in this 
     * component, the new child will not be inserted
     *
     * @param Component $child
     * @param Component $newChild
     * @return int 
     */
    protected final function _replaceChildAt ($index, Component $newChild) {
        if (!$this->_canAddChild($newChild) || $child == $newChild) 
            return -1;

        $child = $this->childAt($index);
        if (null != $child) 
            $this->_replaceChild($child, $newChild);

        return $child;
    }


    /**
     * Removes the child from this component
     * @param Component $child
     * @return int
     */
    protected final function _removeChild (Component $child) {
        $index = $this->indexOf($child);
        $this->_removeChildAt($index);
        return $index;
    }


    /**
     * Removes the child at given index
     * @param int $index
     * @return Component
     */
    protected final function _removeChildAt ($index) {
        if ($index < 0 || $index > $this->countChildren() - 1)
            return null;

        $child = $this->_children[$index];
        $leftArray = array_slice($this->_children, 0, $index);
        $rightArray = array_slice($this->_children, $index + 1);
        $this->_children = array_merge($leftArray, $rightArray);
        return $child;
    }
}