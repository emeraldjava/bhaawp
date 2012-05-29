<?php
class Bhaa
{
	function __construct()
	{
		return true;
	}
	
	function activate()
	{}
	
	/**
	 * Handle shortcode
	 *
	 * @param $attr - attributes
	 * @return unknown_type
	 */
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