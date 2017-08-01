<?php
 /**
 * Runner test case.
 */
class RunnerTest extends PHPUnit_Framework_TestCase {
 
    function testGetStatus() {
        $r = new Runner(7713);
        $this->assertEquals('M',$r->getStatus());
    }

    function testGetGender() {
        $r = new Runner(7713);
        $this->assertEquals('M',$r->getGender());
    }
}