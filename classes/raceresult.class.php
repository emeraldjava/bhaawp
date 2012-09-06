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
 * to handle the edit/delete row
 * http://mac-blog.org.ua/942/
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
				'plural'    => 'RaceResults'
				//'ajax'      => false
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
			//'cb'        => '<input type="checkbox" />',
			//'id'        => 'ID',//<input type="checkbox" />', //Render a checkbox instead of text
			//'race'     => 'Race',
			'runner'    => 'Runner',
			'display_name'    => 'Name',
			'cname'    => 'Company',
			'racetime'  => 'Time',
			'position'  => 'Position',
			'racenumber' => 'Number',
			'category'  => 'Category',
			'standard'  => 'Std',
			'paceKM'  => 'Pace',
			//'class'  => 'Class'
		);
		return $columns;
	}

	function column_default( $item, $column_name ) {
		switch( $column_name )
		{
			//case 'race':
			case 'runner':
			case 'display_name':
			case 'cname':
			case 'racetime':
			case 'position':
			case 'racenumber':
			case 'category':
			case 'standard':
			case 'paceKM':
			//case 'class':
				return $item[$column_name];
			default:
				return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
		}
	}

//  	function column_race($item){
//  		$actions = array(
//  				'edit'      => sprintf('<a href="?page=%s&action=%s&race=%s">Edit</a>',$_REQUEST['page'],'edit',$item['ID']),
//  				'delete'    => sprintf('<a href="?page=%s&action=%s&race=%s">Delete</a>',$_REQUEST['page'],'delete',$item['ID'])
//  		);
//  		return sprintf('%1$s %2$s', $item['race'], $this->row_actions($actions) );
//  	}

// 	function column_cb($item) {
// 		return sprintf(
// 				'<input type="checkbox" name="book[]" value="%s" />', $item['ID']
// 		);
// 	}

	/**
	 * http://localhost/?post_type=company&p=100
	 * http://webtide.wordpress.com/2010/12/15/using-custom-permalinks-with-custom-post-types-in-wordpress/
	 * http://wordpress.org/support/topic/custom-post-type-permalink-structure
	 * @param unknown_type $item
	 * @return string
	 */
 	function column_cname($item) {
 		return sprintf('<a href="/?post_type=company&p=%d">%s</a>',$item['cid'],$item['cname']);
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
			$query = 'SELECT wp_bhaa_raceresult.*,wp_users.display_name FROM '
				.$wpdb->prefix .'bhaa_raceresult 
				join wp_users on wp_users.id=wp_bhaa_raceresult.runner';
		else
			$query = 'SELECT wp_bhaa_raceresult.*,wp_users.display_name,
			cid.meta_value as cid,cname.meta_value as cname FROM '
				.$wpdb->prefix .'bhaa_raceresult 
				join wp_users on wp_users.id=wp_bhaa_raceresult.runner 
left join wp_usermeta as cid on cid.user_id=wp_users.id and cid.meta_key="bhaa_runner_company"
left join wp_usermeta as cname on cname.user_id=wp_users.id and cname.meta_key="bhaa_runner_companyname"
				where race='.$race;
		
		//echo $query;
		$totalitems = $wpdb->query($query);
		//echo $totalitems;
		$this->items = $wpdb->get_results($query,ARRAY_A);
	}

	function table($race)
	{
		//if ( !current_user_can( 'manage_options' ) )  {
		//	wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		//}
		echo '<div class="wrap"><h2>BHAA Race Result Admin Page</h2>';
		$this->prepare_items($race);
		$this->display();
		echo '</div>';
	}
}
?>