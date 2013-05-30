<?php
if(!class_exists('WP_List_Table')){
	require_once( ABSPATH . 'wp-admin/includes/template.php' );
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
			'singular'  => 'raceresult',     //singular name of the listed records
			'plural'    => 'raceresults',    //plural name of the listed records
			'ajax'      => false        //does this table support ajax?
		) );
	}

	/**
	 *  the columns
	 */
	function get_columns(){
		$columns = array(
			//'cb'        => '<input type="checkbox" />',
			//'id'        => 'ID',//<input type="checkbox" />', //Render a checkbox instead of text
			'position'  => 'Pos',
			'racenumber' => 'No',
			'display_name' => 'Name',
			'racetime'  => 'Time',
			'category'  => 'Category',
			'gender' => 'Gender', 
			'posincat' => 'Pos Cat',
			'standard'  => 'Std',
			'posinstd' => 'Pos Std',
			'cname'    => 'Company'
		);
		return $columns;
	}
	
	/**
	 * http://wpengineer.com/2426/wp_list_table-a-step-by-step-guide/
	 * @see WP_List_Table::get_sortable_columns()
	 */
	function get_sortable_columns() {
		$sortable_columns = array(
	    	'position' => array('position',true),
	    	'category' => array('category',false),
	    	'standard' => array('standard',false)
	  	);
		return $sortable_columns;
		//return array();
	}

	function column_default( $item, $column_name ) {
		//error_log('column_default '.$item['position'].' '.memory_get_usage());
		switch( $column_name )
		{
			case 'race':
			case 'runner':
			case 'display_name':
			case 'user_nicename';
			case 'gender';
			case 'cname':
			case 'racetime':
			case 'position':
			case 'racenumber':
			case 'category':
			case 'posincat':
			case 'standard':
			case 'posinstd':
			case 'pace':
			case 'event':
				return $item[$column_name];
			default:
				return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
		}
		//ob_flush();
	}

	/**
	 * http://localhost/?post_type=company&p=100
	 * http://webtide.wordpress.com/2010/12/15/using-custom-permalinks-with-custom-post-types-in-wordpress/
	 * http://wordpress.org/support/topic/custom-post-type-permalink-structure
	 * @param unknown_type $item
	 * @return string
	 */
 	function column_cname($item) {
 		return sprintf('<a href="/?post_type=house&p=%d"><b>%s</b></a>',$item['cid'],$item['cname']);
 	}
 	
 	function column_racetime($item) {
 		$actions = array(
 			'edit' => sprintf('<a href="?page=%1$s&action=%2$s&id=%3$d">Edit %3$d</a>',$_REQUEST['page'],'editracetime',$item['id'])
 		);
 		return sprintf('%1$s [%3$d] %2$s', $item['racetime'], $this->row_actions($actions), $item['actualstandard'] );
 		//return sprintf('%s [%d]',$item['racetime'],$item['actualstandard']);
 	}
 	
 	function column_standard($item) {
 		if($item['standard']!='0')
	 		return sprintf('%d->%d',$item['standard'],$item['poststandard']);
 		else 
 			return '';// sprintf('%d',$item['poststandard']);
 	}
 	
 	function column_display_name($item) {
 		
 		$page = get_page_by_title('runner');
 		$permalink = get_permalink( $page );
 		return sprintf('<a r="%d" href="%s"><b>%s</b></a>',
			$item['runner'],
			add_query_arg( array ( 'user_nicename'=>$item['user_nicename']),$permalink ),
 			$item['display_name']
 			);
 		//return sprintf('<a href="/?page_id=%d&name=%s">%s</a>',$page->ID,$item['user_nicename'],$item['display_name']);
 	}
 	
 	function column_event($item) {
 		$page = get_post($item['event']);
 		return '<a href='.get_permalink($item['event']).'><b>'.get_the_title($item['event']).'</b></a>';
 	}

	/**
	 *
	 */
	function prepare_items($race)
	{
		$columns = $this->get_columns();
		$hidden = array();
		$this->_column_headers = array($columns, $hidden, $this->get_sortable_columns());

		$orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'position+0'; //If no sort, default to title
		$order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'asc'; //If no order, default to asc
		if($orderby!='position+0')
			$order .= ', position+0';
		
		global $wpdb;
		$query = '
			SELECT wp_bhaa_raceresult.*,wp_users.display_name,
			wp_users.user_nicename,gender.meta_value as gender,wp_posts.id as cid,wp_posts.post_title as cname
			FROM '.$wpdb->prefix .'bhaa_raceresult 
			left join wp_users on wp_users.id=wp_bhaa_raceresult.runner 
			left join wp_usermeta gender on (gender.user_id=wp_users.id and gender.meta_key="bhaa_runner_gender")
			left join wp_usermeta company on (company.user_id=wp_users.id and company.meta_key="bhaa_runner_company")
			left join wp_posts on (wp_posts.post_type="house" and company.meta_value=wp_posts.id)
			where race='.$race.' and wp_bhaa_raceresult.class="RAN" and position<=300 ORDER BY '.$orderby.' '. $order;
		//	join wp_posts on wp_posts.id=wp_bhaa_raceresult	
		//error_log($query);	
		
		//echo $query;
		//$totalitems = $wpdb->query($query);
		$querydata = $wpdb->get_results($query,ARRAY_A);
		//usort( $querydata, array( &$this, 'usort_reorder' ) );
		$this->items = $querydata;
	}

	function renderTable($race)
	{   
		//error_log("render table ".$race);
		
		$this->prepare_items($race);
		//echo '<div class="wrap">';
		//ob_clean();
		ob_start();
		$this->display();
		//$res = ob_get_contents();
		//flush();
		return ob_get_clean();
		//return ob_end_flush();
		//return $res;
		//echo '</div>';
		//return ob_get_clean();
	}
	
// 	function display_rows() {
// 		parent::display_rows();
// 		//error_log('column_display_name '.$item.' '.memory_get_usage(true));
// 		//ob_flush();
// 	}

// 	function single_row( $item ) {
// 		parent::single_row($item);
// 		//error_log('single_row '.$item.' '.memory_get_usage(true));
// 		//ob_flush();
// 	}
	
	function renderRunnerTable($runner)
	{
		echo '<div class="wrap">';
		$this->prepareRunnerItems($runner);
		$this->display();
		echo '</div>';
	}
	
	function getRunnerColumns(){
		$columns = array(
			'event'     => 'Event',
			'position'  => 'Pos',
			'racenumber' => 'Race No',
			'racetime'  => 'Time',		
			'standard'  => 'Std'
		);
		return $columns;
	}
	
	function prepareRunnerItems($runner)
	{
		$columns = $this->getRunnerColumns();
		$hidden = array();
		$this->_column_headers = array($columns, $hidden, $this->get_sortable_columns());
	
		global $wpdb;
		$query = 'SELECT wp_p2p.p2p_from as event,wp_bhaa_raceresult.* FROM wp_bhaa_raceresult '.
			'join wp_p2p on (wp_p2p.p2p_to=wp_bhaa_raceresult.race and wp_p2p.p2p_type="event_to_race") '.
			'where runner='.$runner.' order by race desc';
	
		$this->items = $wpdb->get_results($query,ARRAY_A);
	}
	
	/**
	 * Add tw bootstrap styling to the table 
	 * http://twitter.github.com/bootstrap/base-css.html
	 * @see WP_List_Table::get_table_classes()
	 */
	function get_table_classes() {
		return array( 'bhaatables',$this->_args['plural'] );
	}
	
	function usort_reorder( $a, $b ) {
		// If no sort, default to title
		$orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'position';
		// If no order, default to asc
		$order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
		// Determine sort order
		$result = strcmp( $a[$orderby], $b[$orderby] );
		// Send final sort direction to usort
		return ( $order === 'asc' ) ? $result : -$result;
	}
}
?>