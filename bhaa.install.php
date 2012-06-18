<?php
	function bhaa_install() 
	{
		//global $wp_rewrite;
		//$wp_rewrite->flush_rules();
	 	// Creates the events table if necessary
		bhaa_create_event_table();
	}
	
	function bhaa_create_event_table() {
		global  $wpdb, $user_level, $user_ID;
		get_currentuserinfo();
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	
		$charset_collate = '';
		if ( $wpdb->has_cap( 'collation' ) ) {
			if ( ! empty($wpdb->charset) )
				$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
			if ( ! empty($wpdb->collate) )
				$charset_collate .= " COLLATE $wpdb->collate";
		}
		$event_sql = "CREATE TABLE `".$wpdb->prefix."bhaa_event` (
			  `id` int(11) NOT NULL auto_increment,
			  `name` varchar(40) NOT NULL,
			  `tag` varchar(15) NOT NULL,
			  `location` varchar(100) NOT NULL,
			  `date` date NOT NULL,
			  PRIMARY KEY  (`id`)
		) ENGINE=InnoDB $charset_collate;";
		
		global $wpdb;
		//didn't find it try to create it.
		$q = $wpdb->query($event_sql);
	}
	
?>