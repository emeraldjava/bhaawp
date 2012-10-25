<?php
if(!class_exists('WP_List_Table')){
	require_once( ABSPATH . 'wp-admin/includes/screen.php' );
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * handle team results
 */
class TeamResultTable extends WP_List_Table
{
	function __construct()
	{
		global $status, $page;
		//Set parent defaults
		parent::__construct( array(
			'singular'  => 'teamraceresults',     //singular name of the listed records
			'plural'    => 'teamraceresult',    //plural name of the listed records
			'ajax'      => false        //does this table support ajax?
		));
	}
		
	function get_columns(){
		$columns = array(
			//'cb'        => '<input type="checkbox" />',
			//'id' => 'ID',//<input type="checkbox" />', //Render a checkbox instead of text
			'team'=>'Team',
			//'race'=> 'Race',
			//'league' => 'League',
			'positiontotal'  => 'Position Total',
			'standardtotal'  => 'Standard Total',
			'leaguepoints' => 'League Points',
			'class'  => 'Class',
			//'status'=>'Status'
		);
		return $columns;
	}
	
	function column_default( $item, $column_name ) {
		switch( $column_name )
		{
 			case 'team':
 			case 'class':
 			case 'positiontotal':
 			case 'standardtotal':
 			case 'leaguepoints':
 				return $item[$column_name];
			default:
				return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
		}
	}
	
	function prepare_items($race)
	{
		$columns = $this->get_columns();
		$hidden = array();
		$sortable = array();
		$this->_column_headers = array($columns, $hidden, $sortable);
	
		global $wpdb;
// 		if(!isset($race))
// 			$query = 'SELECT wp_bhaa_raceresult.*,wp_users.display_name FROM '
// 			.$wpdb->prefix .'bhaa_raceresult
// 			join wp_users on wp_users.id=wp_bhaa_raceresult.runner';
// 		else
		$query = '
			SELECT '.$wpdb->prefix .'bhaa_teamresult.*
			FROM '.$wpdb->prefix .'bhaa_teamresult
			where race=201219 order by class, positiontotal';
		//	join wp_posts on wp_posts.id=wp_bhaa_raceresult
		echo '<p>'.$query.'</p>';
		//error_log($mgs);
	
		//echo $query;
		$totalitems = $wpdb->query($query);
		//echo $totalitems;
		$this->items = $wpdb->get_results($query,ARRAY_A);
	}
	
	function renderTable($event)
	{
		echo '<div class="wrap"><h2>BHAA Team Results Table '.$event.'</h2>';
		$this->prepare_items($event);
		$this->display();
		echo '</div>';
	}
	
	function get_table_classes() {
		return array( 'widefat', 'fixed','table','table-bordered','table-striped','table-condensed',$this->_args['plural'] );
	}
}
?>