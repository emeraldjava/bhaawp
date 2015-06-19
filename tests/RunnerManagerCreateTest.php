<?php
 /**
 * Runner test case.
 */
class RunnerManagerCreateTest extends WP_UnitTestCase {

    private $runnerManager;
    private $runner;

    function setUp() {
        $this->runnerManager = Runner_Manager::get_instance();
        $new = $this->runnerManager->createNewUser('pAT_Test',"O'DONnELl_test",'test_email@bhaa.ie','M','1980-01-01',null);
        $this->runner = new Runner($new);
    }

    function testGetFirstName() {
        $this->assertEquals('Pat_test',$this->runner->getFirstName(),'firstname');
        $this->assertEquals("O'Donnell_test",$this->runner->getLastName(),'lastname');
        $this->assertEquals("Pat_test O'Donnell_test",$this->runner->getFullName(),'fullname');
        $this->assertEquals("Pat_test O'Donnell_test",$this->runner->display_name,'displayname');
    }
}