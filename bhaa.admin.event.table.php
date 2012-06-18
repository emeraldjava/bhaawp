<?php
if(!class_exists('WP_List_Table')){
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class BHAA_Event_Table extends WP_List_Table {

	function __construct() {
		parent::__construct( array(
				'singular'=> 'wp_bhaa_event', //Singular label
				'plural' => 'wp_bhaa_events' //plural label, also this well be one of the table css class
		//		'ajax'	=> false //We won't support Ajax for this table
		) );
	}
}

function add_bhaa_event_menu_item(){
	add_submenu_page('main', 'BHAA Events', 'BHAA Events', 'activate_plugins', 'tt_render_list_page', array(&$this, 'tt_render_list_page'));
	//add_menu_page('BHAA Admin Menu Title', 'BHAA Event Table', 'activate_plugins', 'tt_list_test', array(&$this, 'tt_render_list_page'));
	//add_menu_page('Example Plugin List Table', 'List Table Example', 'activate_plugins', 'tt_list_test', 'tt_render_list_page');
} 
add_action('admin_menu', 'add_bhaa_event_menu_item');

function tt_render_list_page(){
    
    //Create an instance of our package class...
    $testListTable = new BHAA_Event_Table();
    //Fetch, prepare, sort, and filter our data...
    //$testListTable->prepare_items();
    
    ?>
    <div class="wrap">
        
        <div id="icon-users" class="icon32"><br/></div>
        <h2>List Table Test</h2>
        
        <div style="background:#ECECEC;border:1px solid #CCC;padding:0 10px;margin-top:5px;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;">
            <p>This page demonstrates the use of the <tt><a href="http://codex.wordpress.org/Class_Reference/WP_List_Table" target="_blank" style="text-decoration:none;">WP_List_Table</a></tt> class in plugins.</p> 
            <p>For a detailed explanation of using the <tt><a href="http://codex.wordpress.org/Class_Reference/WP_List_Table" target="_blank" style="text-decoration:none;">WP_List_Table</a></tt>
            class in your own plugins, you can view this file <a href="/wp-admin/plugin-editor.php?plugin=table-test/table-test.php" style="text-decoration:none;">in the Plugin Editor</a> or simply open <tt style="color:gray;"><?php echo __FILE__ ?></tt> in the PHP editor of your choice.</p>
            <p>Additional class details are available on the <a href="http://codex.wordpress.org/Class_Reference/WP_List_Table" target="_blank" style="text-decoration:none;">WordPress Codex</a>.</p>
        </div>
        
        <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
        <form id="movies-filter" method="get">
            <!-- For plugins, we also need to ensure that the form posts back to our current page -->
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
            <!-- Now we can render the completed list table -->
            <?php $testListTable->display() ?>
        </form>
        
    </div>
    <?php
}
?>