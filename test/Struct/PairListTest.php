<?php

use \Chaos\Core\Struct\PairList;

class PairListTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function main () {
        $pairList = new PairList(array(
            0 => 'foo',
            'lorem' => 'ipsum'
        ));

        $pairList->set('aset', 'arem');
        $pairList->set('pop', 'push');
        $pairList->set('shift', 'unshift');
        $pairList->set('plus', 'minus');
        $this->assertEquals(5, count($pairList));
        $this->assertEquals($pairList[2], $pairList['pop']);
        
        unset($pairList['pop']);
        $this->assertEquals(4, count($pairList));
        $this->assertEquals($pairList[2], $pairList['shift']);

        unset($pairList[2]);
        $this->assertEquals($pairList[2], $pairList['plus']);
        $this->assertEquals(null, $pairList['asdf']);
    }
}