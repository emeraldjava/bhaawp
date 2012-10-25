<?php
/**
 * handle team results
 */
class TeamResult
{
	var $table;
	
	function TeamResult()
	{
		$this->table = new TeamResultTable();
	}
	
	function getTable()
	{
		return $this->table;
	}
}
?>