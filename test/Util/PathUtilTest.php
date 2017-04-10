<?php

use \Chaos\Core\System\PathUtil;

if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

class PathUtilTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function join ()
    {
        $this->assertEquals('A' . DS . 'B' . DS . 'C', PathUtil::join('A', 'B', 'C'));
        $this->assertEquals(DS, PathUtil::join(''));
    }
}