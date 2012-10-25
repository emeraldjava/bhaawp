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
			'teamname'=>'Team',
			'positiontotal'  => 'Position Total',
			'standardtotal'  => 'Standard Total',
			'leaguepoints' => 'League Points',
			'class'  => 'Class',
		);
		return $columns;
	}
	
	function column_default( $item, $column_name ) {
		switch( $column_name )
		{
 			case 'teamname':
 			case 'class':
 			case 'positiontotal':
 			case 'standardtotal':
 			case 'leaguepoints':
 				return $item[$column_name];
			default:
				return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
		}
	}
	
	function column_cname($item) {
		return sprintf('<a href="/?post_type=company&p=%d">%s</a>',$item['cid'],$item['cname']);
	}
	
	function column_display_name($item) {
		$page = get_page_by_title('member');
		return sprintf('<a href="/?page_id=%d&id=%d">%s</a>',$page->ID,$item['runner'],$item['display_name']);
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
			SELECT '.$wpdb->prefix .'bhaa_teamresult.*,wp_posts.post_title as teamname
			FROM '.$wpdb->prefix .'bhaa_teamresult
			left join wp_posts on wp_posts.post_type="house" and '.$wpdb->prefix .'bhaa_teamresult.team=wp_posts.id
			where race=201219 order by class, positiontotal';
		//	join wp_posts on wp_posts.id=wp_bhaa_raceresult
		echo '<p>'.$query.'</p>';
		$totalitems = $wpdb->query($query);
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