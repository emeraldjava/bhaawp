<?php
if(!class_exists('WP_List_Table')){
	require_once( ABSPATH . 'wp-admin/includes/template.php' );
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
if( ! class_exists('WP_Screen') ) {
	require_once( ABSPATH . 'wp-admin/includes/screen.php' );
}

/**
 * handle team results
 * Deprecated
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
			'ajax'      => false,        //does this table support ajax?
			'screen'      => 'team-list'
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
	
	function column_teamname($item) {
		return sprintf('<a href="/?post_type=house&p=%d">%s</a>',$item['team'],$item['teamname']);
	}
		
	function prepare_items($event)
	{
		$columns = $this->get_columns();
		$hidden = array();
		$sortable = array();
		$this->_column_headers = array($columns, $hidden, $sortable);
	
		global $wpdb;
		$query = $wpdb->prepare('
			SELECT wp_bhaa_teamresult.*,wp_posts.post_title as teamname 
			FROM wp_bhaa_teamresult
			join wp_posts on wp_posts.post_type="house" and wp_bhaa_teamresult.team=wp_posts.id
			where race IN (select p2p_to from wp_p2p where p2p_from=%d)
			order by class, positiontotal',$event);
		//echo '<p>'.$query.'</p>';
		$totalitems = $wpdb->query($query);
		$this->items = $wpdb->get_results($query,ARRAY_A);
	}
	
	function renderTable($event)
	{
		$msg = '<div class="wrap">';
		$msg .= $this->prepare_items($event);
		$msg .= $this->display();
		$msg .= '</div>';
		return $msg;
	}
	
	function get_table_classes() {
		return array( 'table-1',$this->_args['plural'] );
	}
}
?>