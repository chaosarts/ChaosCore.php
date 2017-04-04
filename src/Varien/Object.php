<?php

namespace Chaos\Core\Varien;
use\ Chaos\Core\StringUtil as StringUtil;

class Object implements \ArrayAccess
{
	/**
	 * Provides the data of the object
	 * @var array
	 */
	private $_data = array();


	/**
	 * Magic method to access data as property
	 * @param string $name
	 * @return mixed
	 */
	public function __get ($name)
	{
		return $this->getData(StringUtil::camelcase2underscore($name));
	}


	/**
	 * Magic method to access data as property
	 * @param string $name
	 * @return mixed
	 */
	public function __set ($name, $value)
	{
		$this->setData(StringUtil::camelcase2underscore($name), $value);
	}


	/**
	 * Magic method to access data via methods
	 * @param string $methodname
	 * @param array $args
	 */
	public function __call ($methodname, array $args)
	{
		$matches = array();
		preg_match('/^([a-z]+)(.*)/', $methodname, $matches);
		if (empty($matches)) return null;

		list($_, $method, $name) = $matches;
		array_unshift($args, StringUtil::camelcase2underscore($name));

		switch ($method)
		{
			case 'has': 
			case 'get': 
			case 'set': 
			case 'unset': 
				return call_user_func_array(array($this, $method . 'Data'), $args); 
				break;
			default:
				throw new \Exception('Unsupported method ' . $method . 'Data for index ' . $name);
		}
	}


	/**
	 * Determines if the Object has data associated with given name
	 * @param string $name
	 * @return boolean
	 */
	public function hasData ($name)
	{	
		return array_key_exists($name, $this->_data);
	}
	

	/**
	 * Gets the data associated with given $name
	 * @param string $name Name of the data to get
	 * @param mixed $default The default value to return, if data does not exists
	 * @return mixed
	 */
	public function getData ($name, $default = null) 
	{
		if (!$this->hasData($name)) return $default;
		return $this->_data[$name];
	}


	/**
	 * Sets the data associated with given name
	 * @param string $name Name of the data to associate with
	 * @param mixed $value The value of the data to set
	 * @return Object
	 */
	public function setData ($name, $value)
	{
		if (null === $value) $this->unsetData($name);
		else $this->_data[$name] = $value;
		return $this;
	}


	/**
	 * Deletes the data from object with given name.
	 * @param string $name The name of the data to delete
	 * @return mixed The value of the data, that has been deleted
	 */
	public function unsetData ($name)
	{
		$value = null;
		
		if ($this->hasData($name)) 
		{
			$value = $this->_data[$name];
			unset($this->_data[$name]);
		}	

		return $value;
	}


	/**
	 * Whether an offset exists
	 * @param mixed $offset
	 * @return boolean
	 */
	public function offsetExists ($offset) 
	{
		return $this->hasData($offset);
	}


	/**
	 * Offset to retrieve
	 * @param mixed $offset
	 * @return mixed
	 */
	public function offsetGet ($offset) 
	{
		return $this->getData($offset);
	}


	/**
	 * Assign a value to the specified offset
	 * @param mixed $offset
	 * @param mixed $value
	 */
	public function offsetSet ($offset , $value) 
	{
		$this->setData($offset, $value);
	}


	/**
	 * Unsets data at given offset
	 * @param mixed $offset
	 */
	public function offsetUnset ($offset) 
	{
		$this->unsetData($offset);
	}
}