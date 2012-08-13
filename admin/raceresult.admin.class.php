<?php
if(!class_exists('WP_List_Table')){
	require_once( ABSPATH . 'wp-admin/includes/screen.php' );
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Handle the admin tasks for the BHAA companies
 * @author assure
 *
 */
class RaceResultAdmin extends WP_List_Table
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
			//'id'        => 'ID',//<input type="checkbox" />', //Render a checkbox instead of text
			'race'     => 'Rame',
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
	
	function prepare_items() {
		$columns = $this->get_columns();
		$hidden = array();
		$sortable = array();
		$this->_column_headers = array($columns, $hidden, $sortable);
		
		global $wpdb;
		$query = "SELECT * FROM ".$wpdb->prefix .'bhaa_raceresult';
		$totalitems = $wpdb->query($query);
		$this->items = $wpdb->get_results($query,ARRAY_A);
	}
	
	function table()
	{
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
		echo '<div class="wrap"><h2>BHAA Race Result Admin Page</h2>';
		$this->prepare_items();
		$this->display();
		echo '</div>';
	}
}
?>