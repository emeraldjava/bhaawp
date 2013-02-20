<?php
class RaceModel extends BaseModel
{
	var $post_id;
	//var $post;
	//var $meta;
	
	function __construct($post_id)
	{
		$this->post_id = $post_id;
		//$this->post = WP_Post::get_instance($post_id);
		//$this->meta = get_post_meta($post_id);
		//var_dump($this->post->get);
	}
	
	function getDistance()
	{
		return (int) get_post_meta($this->post_id,'bhaa_race_distance',true);
	}
	
	function getUnit()
	{
		return get_post_meta($this->post_id,'bhaa_race_unit',true);
	}
	
	function getKmDistance()
	{
		if($this->getUnit()=='Mile')
			return $this->getDistance() * 1.6;
		else 
			return $this->getDistance();
	}
}
?>