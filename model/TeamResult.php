<?php
class TeamResult extends BaseModel
{
	var $race;
	
	function __construct($race)
	{
		parent::__construct();
		$this->race = $race;
	}
	
	public function getName()
	{
		return $this->wpdb->prefix.'bhaa_teamresult';
	}
	
	public function deleteResults()
	{
		return $this->wpdb->delete(
			$this->getName(),
			array('race' => $this->race
		));
	}
}
?>
