<?php
class BhaaFlickr
{
	private $phpFlickr;
	
	function __construct()
	{
		$this->phpFlickr = new phpFlickr(get_option('bhaa_flickr_api_key'));
		//$this->phpFlickr->enableCache("db", "mysql://user:password@server/database");
	}
	
	function bhaa_flickr_shortcode($attrs)
	{
		extract( shortcode_atts( array(
			'foo' => 'something',
			'bar' => 'something else',
			), $atts ) );
		return 'bhaa_flickr_shortcode';
	}
	
	function bhaa_flickr_list_albums() {
		return $this->phpFlickr->photosets_getList(get_option('bhaa_flickr_user_id'));
	}
}
?>