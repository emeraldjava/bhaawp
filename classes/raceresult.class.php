<?php
if(!class_exists('WP_List_Table')){
	require_once( ABSPATH . 'wp-admin/includes/screen.php' );
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * https://gist.github.com/7f923479a4ed135e35b2#comments
 * http://wpengineer.com/2426/wp_list_table-a-step-by-step-guide/
 * http://codex.wordpress.org/Class_Reference/WP_List_Table
 * http://wp.smashingmagazine.com/2011/11/03/native-admin-tables-wordpress/
 * 
 * @author oconnellp
 *
 */
class RaceResult extends WP_List_Table
{
	function __construct(){
		global $status, $page;
		//Set parent defaults
		parent::__construct( array(
				'singular'  => 'RaceResult',    
				'plural'    => 'RaceResults',   
				'ajax'      => false    
		));
	}
	
	/**
	 * 		$raceResultSql = "race int(11) NOT NULL,
			runner int(11) NOT NULL,
			racetime time,
			position int(11),
			racenumber int(11),
			category varchar(5),
			standard int(11),
			paceKM time,
			class varchar(25)";
	 * @return multitype:string
	 */
	function get_columns(){
		$columns = array(
			'cb'        => '<input type="checkbox" />',
			//'id'        => 'ID',//<input type="checkbox" />', //Render a checkbox instead of text
			'race'     => 'Race',
			'runner'    => 'Runner',
			'racetime'  => 'Time',
			'position'  => 'Position',
			'racenumber' => 'Number',
			'category'  => 'Category',
			'standard'  => 'Std',
			'class'  => 'Class'
		);
		return $columns;
	}
		
	function column_default( $item, $column_name ) {
		switch( $column_name ) 
		{
			case 'race':
			case 'runner':
			case 'racetime':
			case 'position':
			case 'racenumber':
			case 'category':
			case 'standard':
			case 'class':
				return $item[$column_name];
			default:
				return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
		}
	}
	
	function column_race($item){
		$actions = array(
				'edit'      => sprintf('<a href="?page=%s&action=%s&race=%s">Edit</a>',$_REQUEST['page'],'edit',$item['ID'])
			//	'delete'    => sprintf('<a href="?page=%s&action=%s&race=%s">Delete</a>',$_REQUEST['page'],'delete',$item['ID']),
		);
		return sprintf('%1$s %2$s', $item['race'], $this->row_actions($actions) );
	}
	
	function column_cb($item) {
		return sprintf(
				'<input type="checkbox" name="book[]" value="%s" />', $item['ID']
		);
	}
	
	/**
	 * 
	 */
	function prepare_items($race) 
	{
		$columns = $this->get_columns();
		$hidden = array();
		$sortable = array();
		$this->_column_headers = array($columns, $hidden, $sortable);
		
		global $wpdb;
		if(!isset($race))
			$query = "SELECT * FROM ".$wpdb->prefix .'bhaa_raceresult';
		else 
			$query = 'SELECT * FROM '.$wpdb->prefix .'bhaa_raceresult where race='.$race;
		$totalitems = $wpdb->query($query);
		$this->items = $wpdb->get_results($query,ARRAY_A);
	}
	
	function table($race)
	{
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		echo '<div class="wrap"><h2>BHAA Race Result Admin Page</h2>';
		$this->prepare_items($race);
		$this->display();
		echo '</div>';
	}
}
?>