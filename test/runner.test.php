<?php

// http://stackoverflow.com/questions/5306612/using-wpdb-in-standalone-script
$path = 'C:\poconnell\tools\wamp\www';//$_SERVER['DOCUMENT_ROOT'];

include_once $path . '/wp-config.php';
include_once $path . '/wp-load.php';
include_once $path . '/wp-includes/wp-db.php';
include_once $path . '/wp-includes/pluggable.php';



class RunnerTest
{
	function readRunner()
	{
		echo "runner test";
		
		global $wpdb;
		
		$user = get_userdata( 7713 );
		var_dump($user,true);
		//$user = new WP_User( 7713 );
		var_dump($user->get('user_login'));
		
		$first_name = $user->first_name;
		$last_name = $user->last_name;
		echo "$first_name $last_name logs into her WordPress site with the user name of";
		
		//$wpdb->query($query);
	}
	
	function readRace()
	{
		echo "race";
		global $wpdb;
		$id = 204268;
		$post = get_post($id);
		//var_dump($post);
		$csv = get_post_meta($post->ID,'bhaa_race_csv',true);
		echo $csv;
		echo "-\n";
		// http://stackoverflow.com/questions/5053373/explode-a-string-by-r-n-n-r-at-once
		$lines = preg_split('/\n|\r\n?/', $csv);
		echo count($lines);
//		var_dump($csv);
		
		// http://php.net/manual/en/function.str-getcsv.php
		$parsed = str_getcsv($lines[0],
			',',   # Delimiter
    		'"',   # Enclosure
    		''   # Escape char
    	);
		var_dump( $parsed );
		 
	}
}

$runner = new RunnerTest();
$runner->readRace();

?>