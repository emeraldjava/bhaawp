<?php
/**
 * Handle race actions
 * 
 */
class Race
{
	//var $individualResultTable;
	
	function __construct()
	{
		//$this->individualResultTable = new RaceResultTable();
	}
		
	function deleteResults($id)
	{
		error_log('deleteResults - '.$id);
	}
	
	function loadResults($id,$content)
	{
		error_log('loadResults - '.$id.' - '.$content);
	}
}
?>