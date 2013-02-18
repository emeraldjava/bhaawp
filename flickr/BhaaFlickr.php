<?php
class BhaaFlickr
{
	var $phpFlickr;
	
	function __construct()
	{
		$phpFlickr = new phpFlickr(get_option('bhaa_flickr_api_key'));
	}
	
	function bhaa_flickr_list_albums() {
		return $phpFlickr->photosets_getList(get_option('bhaa_flickr_user_id'));
	}
}
?>