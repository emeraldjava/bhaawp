<?php
if(!class_exists('WP_List_Table')){
	require_once( ABSPATH . 'wp-admin/includes/template.php' );
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
if( ! class_exists('WP_Screen') ) {
	require_once( ABSPATH . 'wp-admin/includes/screen.php' );
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
class RaceResult_List_Table extends WP_List_Table
{
	protected static $instance = null;
	protected static $runnerTable = false;
	
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	public function __construct() {
		global $status, $page;
		//Set parent defaults
		parent::__construct( array(
			'singular'  => 'result',     //singular name of the listed records
			'plural'    => 'results',    //plural name of the listed records
			'ajax'      => false,        //does this table support ajax?
			'screen'    => 'race-list'
		) );
	}

	/**
	 *  the columns
	 */
	function get_columns() {
		$columns = array(
			'position'  => 'Pos',
			'racenumber' => 'No',
			'display_name' => 'Name',
			'racetime'  => 'Time',
			'category'  => 'Cat',
			//'pcategory'  => 'Pos Cat',
			'standard'  => 'Std',
			//'posinstd' => 'Pos Std',
			'cname'    => 'Company'
		);
		return $columns;
	}

	function column_default( $item, $column_name ) {
		switch( $column_name )
		{
			case 'race':
			case 'runner':
			case 'display_name':
			case 'user_nicename';
			case 'cname':
			case 'racetime':
			case 'position':
			case 'racenumber':
			case 'category':
			case 'standard':
			case 'posinstd':
			case 'pace':
			case 'event':
			case 'eventdate':
			case 'distance':
				return $item[$column_name];
			default:
				return "";//print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
		}
	}
	
	/**
	 * http://localhost/?post_type=company&p=100
	 * http://webtide.wordpress.com/2010/12/15/using-custom-permalinks-with-custom-post-types-in-wordpress/
	 * http://wordpress.org/support/topic/custom-post-type-permalink-structure
	 * @param unknown_type $item
	 * @return string
	 */
 	function column_cname($item) {
 		return sprintf('<a class="bhaa-url-link" href="/?post_type=house&p=%d"><b>%s</b></a>',$item['cid'],$item['cname']);
 	}
 	
 	function column_racetime($item) {
 		//return sprintf('%1$s [%2$d]', $item['racetime'], $item['actualstandard']);
 		return sprintf('%1$s',$item['racetime']);
 	}
 	
 	/**
 	 * Merge the category and Gender columns
 	 */
 	function column_category($item) {
 		return $item['category'].$item['gender'].' p'.str_pad($item['posincat'],2,"0",STR_PAD_LEFT);
 		//return $item['category'].$item['gender'];
 	}
 	
 	function column_pcategory($item) {
 		return $item['posincat'];//$item['category'].$item['gender'].' p'.str_pad($item['posincat'],2,"0",STR_PAD_LEFT);
 	}
 	
 	function column_position($item) {
 		if ( ! current_user_can('edit_users')) {
 			return sprintf('%d',$item['position']);
 		} else {
	 		return sprintf('<a target="_blank" class="bhaa-url-link" href="/edit-result-page-template?bhaa_raceresult_id=%d&bhaa_runner=%d&bhaa_pre_standard=%d&bhaa_post_standard=%d&bhaa_race=%d&bhaa_time=%s">%d</a>',
	 			$item['id'],$item['runner'],$item['standard'],$item['poststandard'],$item['race'],$item['racetime'],$item['position']);
 		}
 	}
 	
 	function column_standard($item) {
 		if($item['standard']!='0') {
	 		return sprintf('%d->%d',$item['standard'],$item['poststandard']);
 		} else { 
 			return sprintf('%d', $item['actualstandard']);
 		}
 	}
 	
 	function column_display_name($item) {
 		$page = get_page_by_title('runner');
 		$permalink = get_permalink($page);
 		$permalink = add_query_arg(array('user_nicename'=>$item['user_nicename']),$permalink);
 		$permalink = add_query_arg(array('id'=>$item['runner']),$permalink);
 		return sprintf('<a class="bhaa-url-link" r="%d" href="%s"><b>%s</b></a>',
			$item['runner'],
			$permalink,
 			$item['display_name']
 		);
 	}
 	
 	function column_event($item) {
 		$page = get_post($item['event']);
 		return '<a class="bhaa-url-link" href='.get_permalink($item['event']).'><b>'.get_the_title($item['event']).'</b></a>';
 	}

	/**
	 *
	 */
	function prepare_items($race)
	{
		$columns = $this->get_columns();
		$hidden = array();
		$this->_column_headers = array($columns, $hidden, null);// $this->get_sortable_columns());
	
		global $wpdb;
		$query = '
			SELECT wp_bhaa_raceresult.*,wp_users.display_name,
			wp_users.user_nicename,gender.meta_value as gender,wp_posts.id as cid,wp_posts.post_title as cname
			FROM wp_bhaa_raceresult 
			left join wp_users on wp_users.id=wp_bhaa_raceresult.runner 
			left join wp_usermeta gender on (gender.user_id=wp_users.id and gender.meta_key="bhaa_runner_gender")
			left join wp_usermeta company on (company.user_id=wp_users.id and company.meta_key="bhaa_runner_company")
			left join wp_posts on (wp_posts.post_type="house" and company.meta_value=wp_posts.id)
			where race='.$race.' and wp_bhaa_raceresult.class="RAN" and position<=500 ORDER BY position';
		//	join wp_posts on wp_posts.id=wp_bhaa_raceresult	
		//error_log($query);	

		$querydata = $wpdb->get_results($query,ARRAY_A);
		$this->items = $querydata;
	}

	function renderTable($race)	{   
		self::$runnerTable = false;
		$this->prepare_items($race);
		ob_start();
		$this->display();
		return ob_get_clean();
	}
	
	function renderRunnerTable($runner) {
		if(isset($runner)&&($runner!='')) {
			ob_start();
			self::$runnerTable = true;
			//echo '<div class="wrap">';
			$this->prepareRunnerItems($runner);
			$this->display();
			//echo '</div>';
			self::$runnerTable = false;
			return ob_get_clean();
		} else {
			return 'No results for this runner.';
		}		 
	}
		
	function getRunnerColumns() {
		$columns = array(
			'event'     => 'Event',
			'eventdate' => 'Date',
			'distance'	=> 'Distance',
			'position'  => 'Pos',
			'racetime'  => 'Time',		
			'standard'  => 'Std'
		);
		return $columns;
	}
	
	function prepareRunnerItems($runner) {
		$columns = $this->getRunnerColumns();
		$hidden = array();
		$this->_column_headers = array($columns, $hidden, $this->get_sortable_columns());
	
		global $wpdb;
		$query = 'select 
			d.event,
			d.eventname,
			d.eventdate,
			d.race,
			concat(d.distance,d.unit) as distance,
			rr.racetime,
			rr.position,
			rr.standard,
			rr.poststandard,
			rr.runner,
			rr.id
			from wp_bhaa_race_detail d
			join wp_bhaa_raceresult rr on (rr.race=d.race and d.leaguetype="I")
			where rr.runner='.$runner.' AND rr.class="RAN" order by d.eventdate desc';
		$this->items = $wpdb->get_results($query,ARRAY_A);
	}
	
	/**
	 * Set different table class styles so sorting can be controlled
	 * @see WP_List_Table::get_table_classes()
	 */
	function get_table_classes() {
		if(self::$runnerTable) {
			return array('widefat', 'runner_results');
		} else {
			return array('widefat', 'race_results');
		}
	}
}
?>