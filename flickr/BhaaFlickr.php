<?php
class BhaaFlickr
{
	private $phpFlickr;
	
	function __construct()
	{
		error_log('bhaa_flickr_api_key '.get_option('bhaa_flickr_api_key'));
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
		
		//var_dump(get_option('bhaa_flickr_api_key'));
		//var_dump(get_option('bhaa_flickr_username'));
		//var_dump(get_option('bhaa_flickr_user_id'));
		
		//$list = $this->phpFlickr->photosets_getList(get_option('bhaa_flickr_user_id'));
		
		// http://idgettr.com/ flickr id
		
		// http://www.flickr.com/photos/tomhealy/ 50904170@N08
		// rte   - 72157633370206334
		// kclub - 72157633286712233
		
		// 34896940@N06
		// bhaa 34896940@N06/sets/72157631769511635/ teachers2012
		// 72157632557211817 - eircom2013
				
		$person = $this->phpFlickr->people_findByUsername('34896940@N06');
		//$person = $this->phpFlickr->people_findByUsername('bhaa');//eoinfegan');//get_option('bhaa_flickr_username'));
		// $person = $this->phpFlickr->people_findByUsername('tomhealy');//eoinfegan');//get_option('bhaa_flickr_username'));
		var_dump($person);

		//var_dump($this->phpFlickr->photosets_getList());
		
		/**
["photoset"]=> array(10) { ["id"]=> string(17) "72157632557211817" ["primary"]=> string(10) "8394625459" ["owner"]=> string(12) "34896940@N06" 
["ownername"]=> string(4) "bhaa" ["photo"]=> array(178) { [0]=> array(6) { ["id"]=> string(10) "8394625459" ["secret"]=> string(10) "b3f0bedfb8"
 ["server"]=> string(4) "8221" ["farm"]=> float(9) ["title"]=> string(8) "_MG_3486" ["isprimary"]=> string(1) "1" } [1]=> array(6) { 
 ["id"]=> string(10) "8395707526" ["secret"]=> string(10) "6b98e926cf" ["server"]=> string(4) "8076" ["farm"]=> float(9) ["title"]=> 
 string(8) "_MG_3493" ["isprimary"]=> string(1) "0" } [2]=> array(6) { ["id"]=> string(10) "8394625703" ["secret"]=> string(10) "06d561b876" 
 ["server"]=> string(4) "8378" ["farm"]=> float(9) ["title"]=> string(8) "_MG_3501" ["isprimary"]=> string(1) "0" } [3]=> array(6) { ["id"]=> 
 string(10) "8394625933" ["secret"]=> string(10) "079883489b" ["server"]=> string(4) "8354" ["farm"]=> float(9) ["title"]=> string(8) "_MG_3503" 
 ["isprimary"]=> string(1) "0" } [4]=> array(6) { ["id"]=> string(10) "8395707994" ["secret"]=> string(10) "d34e1e05d5" ["server"]=> string(4) "8214"
  ["farm"]=> float(9) ["title"]=> string(8) "_MG_3512" ["isprimary"]=> 
		 */
		//$photos = $this->phpFlickr->photosets_getPhotos('72157632557211817');
		//var_dump($photos);
		//var_dump( $this->phpFlickr->getErrorCode());
		//var_dump( $this->phpFlickr->getErrorMsg());
		
		//var_dump($person['id']);
		// Get the friendly URL of the user's photos
		//$photos_url = $this->phpFlickr->urls_getUserPhotos('50904170@N08');//$person['id']);
		
		// Get the user's first 36 public photos
		$photos = $this->phpFlickr->people_getPublicPhotos($person['id'], NULL, NULL, 136);
		var_dump($photos);
		var_dump( $this->phpFlickr->getErrorCode());
		var_dump( $this->phpFlickr->getErrorMsg());
		
		
		// http://www.flickr.com/photos/34896940@N06/8542579566/
		// http://farm9.staticflickr.com/8520/8542579566_8849d72778_b_d.jpg
		
		// array(9) { ["id"]=> string(10) "8542579566" ["owner"]=> string(12) "34896940@N06" ["secret"]=> string(10) "8849d72778" 
		// ["server"]=> string(4) "8520" ["farm"]=> float(9) ["title"]=> string(8) "IMG_6299" ["ispublic"]=> int(1) ["isfriend"]=> int(0)
		// ["isfamily"]=> int(0) }
		
		// Loop through the photos and output the html
		foreach ((array)$photos['photoset']['photo'] as $photo) {
			
			var_dump($photo);
			//echo '<hr/>';
			echo '<a href="'.$this->phpFlickr->buildPhotoURL($photo,"large").'" rel="prettyPhoto[bhaa]" >';
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
		//var_dump($list);
		return $list;
	}
}
?>