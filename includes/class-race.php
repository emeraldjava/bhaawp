<?php
class Race {
	
	var $race_id;
	
	const BHAA_RACE_DISTANCE = 'bhaa_race_distance';
	const BHAA_RACE_UNIT = 'bhaa_race_unit';
	const BHAA_RACE_TYPE = 'bhaa_race_type';
		
	function __construct($race_id) {
		$this->race_id = $race_id;
	}
		
	function getDistance() {
		return (int) get_post_meta($this->race_id,Race::BHAA_RACE_DISTANCE,true);
	}
	
	function getUnit() {
		return get_post_meta($this->race_id,Race::BHAA_RACE_UNIT,true);
	}
	
	function getKmDistance() {
		if(strpos($this->getUnit(), 'Mile') === 0)
			return $this->getDistance() * 1.609344;
		else 
			return $this->getDistance();
	}
	
	function getTitle() {
		return get_the_title($this->race_id).' '.$this->getDistance().' '.$this->getUnit();
	}
	
	function getDate(){
		global $wpdb;
		$SQL = $wpdb->prepare('SELECT eventdate FROM wp_bhaa_race_detail WHERE race=%d LIMIT 1',$this->race_id);
		$res = $wpdb->get_var($SQL);
		return $res;
	}
	
	function getRunnersAgeCategory($runner) {
		global $wpdb;
		$SQL = $wpdb->prepare('SELECT getAgeCategory(dob.meta_value,eventdate,gender.meta_value) as ageCat
			FROM wp_bhaa_race_detail 
			LEFT JOIN wp_users runner ON runner.id=%d 
			LEFT JOIN wp_usermeta gender ON (gender.user_id=runner.id and gender.meta_key="bhaa_runner_gender") 
			LEFT JOIN wp_usermeta dob ON (dob.user_id=runner.id and dob.meta_key="bhaa_runner_dateofbirth") 
			WHERE race=%d LIMIT 1',$runner,$this->race_id);
		$res = $wpdb->get_var($SQL);
		return $res;
	}
}
?>