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
class RaceResultTable extends WP_List_Table
{
	function __construct()
	{
		global $status, $page;
		//Set parent defaults
		parent::__construct( array(
				'singular'  => 'raceresults',     //singular name of the listed records
				'plural'    => 'raceresult',    //plural name of the listed records
				'ajax'      => false        //does this table support ajax?
		) );
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
			//'runner'    => 'Runner',
			'position'  => 'Position',
			'display_name'    => 'Name',
			'cname'    => 'Company',
			'racetime'  => 'Time',
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
			case 'race':
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
//  			'edit'      => sprintf('<a href="?page=%s&action=%s&race=%d&runner=%d">Edit</a>',$_REQUEST['page'],'edit',$item['race'],$item['runner']),
//  			'delete'    => sprintf('<a href="?page=%s&action=%s&race=%d&runner=%d">Delete</a>',$_REQUEST['page'],'delete',$item['race'],$item['runner'])
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
 	
 	function column_display_name($item) {
 		$page = get_page_by_title('member');
 		return sprintf('<a href="/?page_id=%d&id=%d">%s</a>',$page->ID,$item['runner'],$item['display_name']);
 	}

 	var $message;
 	
	/**
	 *
	 */
	function prepare_items($race)
	{
// 		if(isset($_REQUEST['action']))
// 		{
// 			$action = $_REQUEST['action'];	
// 			if($action = 'edit')
// 			{
// 				$message = "edit something";
// 			}
// 			elseif($action = 'delete')
// 			{
// 				$message = "delete something";
// 			}
// 		}
			
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
			$query = '
		SELECT wp_bhaa_raceresult.*,wp_users.display_name,wp_posts.id as cid,wp_posts.post_title as cname
		FROM '.$wpdb->prefix .'bhaa_raceresult 
		left join wp_users on wp_users.id=wp_bhaa_raceresult.runner 
		left join wp_posts on wp_posts.post_type="house" and wp_bhaa_raceresult.company=wp_posts.id
		where race='.$race.'  and wp_bhaa_raceresult.class="RAN" order by position';
		//	join wp_posts on wp_posts.id=wp_bhaa_raceresult		
		//echo '<p>'.$query.'</p>';
		//error_log($mgs);
		
		//echo $query;
		$totalitems = $wpdb->query($query);
		//echo $totalitems;
		$this->items = $wpdb->get_results($query,ARRAY_A);
	}

	function renderTable($race)
	{
		//if ( !current_user_can( 'manage_options' ) )  {
		//	wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		//}
// 		if (!empty($notice))
// 			echo '<div id="notice" class="error"><p>'.$notice.'</p></div>';
// 		if (!empty($message))
// 			echo '<div id="message" class="updated"><p>'.$message.'</p></div>';
		    
		echo '<div class="wrap"><h2>BHAA Race Results Table</h2>';
		$this->prepare_items($race);
		$this->display();
		echo '</div>';
	}
	
	function renderRunnerTable($runner)
	{
		echo '<div class="wrap"><h2>BHAA Runner Results Table</h2>';
		$this->prepareRunnerItems($runner);
		$this->display();
		echo '</div>';
	}
	
	function getRunnerColumns(){
		$columns = array(
				'race'     => 'Race',
//				'runner'    => 'Runner',
	//			'display_name'    => 'Name',
		//		'cname'    => 'Company',
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
	
	function prepareRunnerItems($runner)
	{
		$columns = $this->getRunnerColumns();
		$hidden = array();
		$sortable = array();
		$this->_column_headers = array($columns, $hidden, $sortable);
	
		global $wpdb;
		if(!isset($runner))
			$runner=7713;
// 			$query = 'SELECT wp_bhaa_raceresult.*,wp_users.display_name FROM '
// 			.$wpdb->prefix .'bhaa_raceresult
// 			join wp_users on wp_users.id=wp_bhaa_raceresult.runner';
		//else
		$query = 'SELECT wp_bhaa_raceresult.* FROM '
			.$wpdb->prefix .'bhaa_raceresult where runner='.$runner.' order by race desc';
	
		//echo '<p>'.$mgs.'</p>';
		//error_log($mgs);
	
		//echo $query;
		$totalitems = $wpdb->query($query);
		//echo $totalitems;
		$this->items = $wpdb->get_results($query,ARRAY_A);
	}
	
	/**
	 * Add tw bootstrap styling to the table 
	 * http://twitter.github.com/bootstrap/base-css.html
	 * @see WP_List_Table::get_table_classes()
	 */
	function get_table_classes() {
		return array( 'widefat', 'fixed','table','table-bordered','table-striped','table-condensed',$this->_args['plural'] );
	}
}
?>