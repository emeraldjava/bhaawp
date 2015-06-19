<?php
 /**
 * Runner test case.
 */
class RunnerManagerTest extends WP_UnitTestCase {

    private $runnerManager;

    function setUp() {
        $this->runnerManager = Runner_Manager::get_instance();
    }

    function testRunnerExistsTrue() {
        $this->assertEquals(1,$this->runnerManager->runnerExists(7713));
    }

    function testRunnerExistsFalse() {
        $this->assertEquals(0,$this->runnerManager->runnerExists(50000));
    }

    function testFormatName() {
        $this->assertEquals("Pat O'Donnell",$this->runnerManager->formatDisplayName("pAT O'DONnELl"));
    }
}