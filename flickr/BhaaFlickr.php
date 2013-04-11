<?php
class BhaaFlickr
{
	private $phpFlickr;
	
	function __construct()
	{
		$this->phpFlickr = new phpFlickr(get_option('bhaa_flickr_api_key'));
		//$this->phpFlickr->enableCache("db","mysql://root:root@localhost/bhaaie_wp");
	}
	
	function bhaa_flickr_shortcode($attrs)
	{
		extract( shortcode_atts( array(
			'foo' => 'something',
			'bar' => 'something else',
			), $atts ) );
		print_r($this->bhaa_flickr_list_albums(),true);//'bhaa_flickr_shortcode';
	}
	
	function bhaa_flickr_list_albums() {
		
		//$list = $this->phpFlickr->photosets_getList(get_option('bhaa_flickr_user_id'));
		
		//$person = $this->phpFlickr->people_findByUsername('50904170@N08');//eoinfegan');//get_option('bhaa_flickr_username'));
		//var_dump($person);
		
		// Get the friendly URL of the user's photos
		$photos_url = $this->phpFlickr->urls_getUserPhotos('50904170@N08');//$person['id']);
		
		// Get the user's first 36 public photos
		$photos = $this->phpFlickr->people_getPublicPhotos($person['id'], NULL, NULL, 36);
		
		// Loop through the photos and output the html
		foreach ((array)$photos['photos']['photo'] as $photo) {
			echo "<a href=$photos_url$photo[id]>";
			echo "<img border='0' alt='$photo[title]' ".
			"src=" . $this->phpFlickr->buildPhotoURL($photo, "Square") . ">";
			echo "</a>";
					$i++;
					// If it reaches the sixth photo, insert a line break
					if ($i % 6 == 0) {
					echo "<br>\n";
		}
		}
		
		//var_dump($list);
		//return $list;
	}
}
?>