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
			return $this->getDistance() * 1.6;
		else 
			return $this->getDistance();
	}
	
	function deleteResults()
	{
		error_log('deleteResults - '.$id);
	}
	
	/**
	 * Process an array which is a race result
	[0] => 7
    [1] => 1719
    [2] => 1683
    [3] => 00:13:15
    [4] => McDonnell
    [5] => Karen
    [6] => F
    [7] => 13
    [8] => 25/03/1976
    [9] => 35
    [10] => Gardai
    [11] => 94
    [12] => Gardai
    [13] => 94
	 * 
	 * @param unknown $details
	 */
// 	function addResult($details)
// 	{
// 		//error_log(print_r($details,true));	
// 		$raceResult = new RaceResult();
// 		$res = $raceResult->addResult($this->post_id, $details);
// 		error_log($res);
		
// 	}
}
?>