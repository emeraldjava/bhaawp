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
	
	function loadResults($id,$content)
	{
		error_log('loadResults - '.$id.' - '.$content);
	}
}
?>