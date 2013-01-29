<?php
/**
 * Handle race actions
 * 
 */
class Race
{
	function __construct()
	{}
	
	function deleteResults($id)
	{
		error_log('deleteResults - '.$id);
	}
	
	function loadResults($id)
	{
		error_log('loadResults - '.$id);
	}
}
?>