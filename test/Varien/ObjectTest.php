<?php

class ObjectTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 */
	public function data ()
	{
		$varien = new \Ca\Core\Varien\Object();
		$varien->setData('foo', 'bar');
		$varien->setData('bar', 'foo');

		$this->assertEquals($varien->hasData('foo'), true);
		$this->assertEquals($varien->hasData('bar'), true);
		$this->assertEquals($varien->getData('foo'), 'bar');
		$this->assertEquals($varien->getData('bar'), 'foo');

		$this->assertEquals($varien->unsetData('bar'), 'foo');
		$this->assertEquals($varien->hasData('bar'), false);
		$this->assertEquals($varien->getData('bar'), null);
	}

	/**
	 * @test
	 */
	public function magicProperty ()
	{
		$varien = new \Ca\Core\Varien\Object();
		$varien->customerId = 23;
		
		$this->assertEquals($varien->hasData('customerId'), false);
		$this->assertEquals($varien->hasData('customer_id'), true);

		$this->assertEquals($varien->getData('customer_id'), 23);
		$this->assertEquals($varien->customer_id, 23);
		$this->assertEquals($varien->customerId, 23);

		$varien->customerEmail = 'fulam.diep@googlemail.com';
		$varien->customerEmail = null;

		$this->assertEquals($varien->hasData('customerEmail'), false);
		$this->assertEquals($varien->customerEmail, null);
	}


	/**
	 * @test
	 */
	public function magicCall ()
	{
		$varien = new \Ca\Core\Varien\Object();
		$varien->setFooBar('baz');

		$this->assertEquals($varien->hasData('foo_bar'), true);
		$this->assertEquals($varien->hasFooBar(), true);

		$this->assertEquals($varien->getData('foo_bar'), 'baz');
		$this->assertEquals($varien->getFooBar(), 'baz');
		$this->assertEquals($varien->fooBar, 'baz');
	}


	/**
	 * @test
	 */
	public function arrayAccess ()
	{
		$varien = new \Ca\Core\Varien\Object();
		$varien['id'] = 13;
		$varien['name'] = 'Foo';

		unset($varien['name']);

		$this->assertEquals($varien->hasData('id'), true);
		$this->assertEquals($varien->hasData('name'), false);
		$this->assertEquals($varien->getId(), 13);
		$this->assertEquals($varien->getName(), null);
		$this->assertEquals($varien['id'], 13);
		$this->assertEquals($varien['name'], null);
		$this->assertEquals(isset($varien['id']), true);
		$this->assertEquals(isset($varien['name']), false);
	}
}