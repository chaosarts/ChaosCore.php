<?php

class StringTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 */
	public function underscore2camelcase ()
	{
		$result = \Ca\Core\String::underscore2camelcase('customer_id');
		$this->assertEquals('customerId', $result);

		$result = \Ca\Core\String::underscore2camelcase('customer_id', true);
		$this->assertEquals('CustomerId', $result);

		$result = \Ca\Core\String::underscore2camelcase('customer__id');
		$this->assertEquals('customerId', $result);
	}


	/**
	 * @test
	 */
	public function camelcase2underscore ()
	{
		$result = \Ca\Core\String::camelcase2underscore('customerId');
		$this->assertEquals('customer_id', $result);

		$result = \Ca\Core\String::camelcase2underscore('HtmlElement');
		$this->assertEquals('html_element', $result);

		$result = \Ca\Core\String::camelcase2underscore('ABC');
		$this->assertEquals('a_b_c', $result);
	}
}