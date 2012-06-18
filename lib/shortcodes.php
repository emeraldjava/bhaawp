<?php
class BhaaShortCodes
{
	function __construct()
	{
	}
	
	function BhaaShortCodes()
	{
		$this->__construct();
	}
	
	function bhaa_shortcode($atts) {
		$this->js_footer = true;
		extract(shortcode_atts(array(
				'id' => -1			// id
		), $atts));
	
		return $this->showBhaa();
	}
	
	function showBhaa()
	{
		return '<div>BHAA User</div>';
	}
}
?>