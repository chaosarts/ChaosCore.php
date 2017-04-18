<?php

use \Chaos\Core\Struct\Pair;

class PairTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function main () {
        $pair = new Pair('foo', 'bar');
        $this->assertEquals('foo', $pair->getKey());
        $this->assertEquals('bar', $pair->getValue());

        $pair->setValue('test');
        $this->assertEquals('test', $pair->getValue());
    }
}