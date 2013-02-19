<?php
class RaceModel extends BaseModel
{
	var $post;
	
	function __construct($post)
	{
		$this->post = $post;
		//$this->post = new WP_User($post);
	}
	
	function getDistance()
	{
		return get_post_meta($this->post,'bhaa_race_distance',true);
	}
}
?>