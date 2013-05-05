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
		
		var_dump(get_option('bhaa_flickr_api_key'));
		var_dump(get_option('bhaa_flickr_username'));
		var_dump(get_option('bhaa_flickr_user_id'));
		
		//$list = $this->phpFlickr->photosets_getList(get_option('bhaa_flickr_user_id'));
		
		$person = $this->phpFlickr->people_findByUsername('bhaa');//eoinfegan');//get_option('bhaa_flickr_username'));
		//$person = $this->phpFlickr->people_findByUsername('50904170@N08');//eoinfegan');//get_option('bhaa_flickr_username'));
		var_dump($person);
		
		var_dump($person['id']);
		// Get the friendly URL of the user's photos
		//$photos_url = $this->phpFlickr->urls_getUserPhotos('50904170@N08');//$person['id']);
		
		// Get the user's first 36 public photos
		$photos = $this->phpFlickr->people_getPublicPhotos($person['id'], NULL, NULL, 36);
		
		// http://www.flickr.com/photos/34896940@N06/8542579566/
		// http://farm9.staticflickr.com/8520/8542579566_8849d72778_b_d.jpg
		
		// array(9) { ["id"]=> string(10) "8542579566" ["owner"]=> string(12) "34896940@N06" ["secret"]=> string(10) "8849d72778" 
		// ["server"]=> string(4) "8520" ["farm"]=> float(9) ["title"]=> string(8) "IMG_6299" ["ispublic"]=> int(1) ["isfriend"]=> int(0)
		// ["isfamily"]=> int(0) }
		
		// Loop through the photos and output the html
		foreach ((array)$photos['photos']['photo'] as $photo) {
			
			var_dump($photo);
			echo '<hr/>';
			echo '<a href="'.$this->phpFlickr->buildPhotoURL($photo).'" rel="prettyPhoto[bhaa]" >';
			echo "<img border='0' alt='$photo[title]' src=".$this->phpFlickr->buildPhotoURL($photo, "thumbnail") . ">";
			// http://www.flickr.com/photos/34896940@N06/8542579566/
			//echo "<img border='0' alt='$photo[title]' src=".$this->phpFlickr->buildPhotoURL($photo, "Square") . ">";
			echo "</a>";
					$i++;
					// If it reaches the sixth photo, insert a line break
					if ($i % 6 == 0) {
					echo "<br>\n";
			}
		}	
		var_dump($list);
		return $list;
	}
}
?>