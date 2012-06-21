<?php
class BhaaShortCodes
{
	function __construct()
	{
		$this->addShortCodes();
	}
	
	function BhaaShortCodes()
	{
		$this->__construct();
	}
	
	function addShortCodes()
	{
		//global $loader;
		add_shortcode( 'companies', array($this, 'listCompanies'));
		//add_shortcode( 'companies', array($loader->company, 'listCompanies'));
	}
	
	function listCompanies($attr)
	{
		return "BHAA Company List Short Code";
	}
	
// 	function bhaa_shortcode($atts) {
// 		$this->js_footer = true;
// 		extract(shortcode_atts(array(
// 				'id' => -1			// id
// 		), $atts));
	
// 		return $this->showBhaa();
// 	}
	
// 	function showBhaa()
// 	{
// 		return '<div>BHAA User</div>';
// 	}
}
?>