<?php
class Race extends BaseModel
{
	var $post_id;
	
	const BHAA_RACE_DISTANCE = 'bhaa_race_distance';
	const BHAA_RACE_UNIT = 'bhaa_race_unit';
	const BHAA_RACE_TYPE = 'bhaa_race_type';
		
	function __construct($post_id)
	{
		$this->post_id = $post_id;
		//$this->post = WP_Post::get_instance($post_id);
		//$this->meta = get_post_meta($post_id);
		//var_dump($this->post->get);
	}
	
	function getDistance()
	{
		return (int) get_post_meta($this->post_id,Race::BHAA_RACE_DISTANCE,true);
	}
	
	function getUnit()
	{
		return get_post_meta($this->post_id,Race::BHAA_RACE_UNIT,true);
	}
	
	function getKmDistance()
	{
		if(strpos($this->getUnit(), 'Mile') === 0)
			return $this->getDistance() * 1.609344;
		else 
			return $this->getDistance();
	}
}
?>