<?php
class BhaaFlickr
{
	var $phpFlickr;
	
	function __construct()
	{
		$phpFlickr = new phpFlickr(get_option('flickr_plus_api_key'));
	}
	
	function flickr_plus_list_albuns() {
		return $phpFlickr->photosets_getList(get_option('flickr_plus_user_id'));
	}
}
?>