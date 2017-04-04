<?php

class StringUtilUtilTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 */
	public function underscore2camelcase ()
	{
		$result = \Chaos\Core\StringUtil::underscore2camelcase('customer_id');
		$this->assertEquals('customerId', $result);

		$result = \Chaos\Core\StringUtil::underscore2camelcase('customer_id', true);
		$this->assertEquals('CustomerId', $result);

		$result = \Chaos\Core\StringUtil::underscore2camelcase('customer__id');
		$this->assertEquals('customerId', $result);
	}


	/**
	 * @test
	 */
	public function camelcase2underscore ()
	{
		$result = \Chaos\Core\StringUtil::camelcase2underscore('customerId');
		$this->assertEquals('customer_id', $result);

		$result = \Chaos\Core\StringUtil::camelcase2underscore('HtmlElement');
		$this->assertEquals('html_element', $result);

		$result = \Chaos\Core\StringUtil::camelcase2underscore('ABC');
		$this->assertEquals('a_b_c', $result);
	}
}