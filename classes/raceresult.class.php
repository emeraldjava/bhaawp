<?php

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
class RaceResult
{
	var $table;
	
	function RaceResult()
	{
		add_action('admin_menu', array(&$this,'race_result_admin_menu'));
		$this->table = new RaceResultTable();		
	}
	
	function race_result_admin_menu()
	{
		add_menu_page(
			__('Race Results', 'raceresult_table'), 
			__('Race Results', 'raceresult_table'), 
			'activate_plugins', 'raceresults', array(&$this,'raceresult_table_page_handler'));
		add_submenu_page(
			'raceresults', 
			__('Results', 'raceresult_table'), 
			__('Results', 'raceresult_table'), 
			'activate_plugins', 'raceresults', array(&$this,'raceresult_table_page_handler'));
		// add new will be described in next part
		add_submenu_page(
			'raceresults', 
			__('Add new', 'raceresult_table'), 
			__('Add new', 'raceresult_table'), 
			'activate_plugins', 'raceresults_form', array(&$this,'raceresult_table_form_page_handler'));
	}
	
	function raceresult_table_page_handler()
	{
		echo $this->getTable()->renderTable(201218);
	}
	
	function raceresult_table_form_page_handler()
	{
		echo "the race results form";
	}
	
	function getTable()
	{
		return $this->table;
	}
}
?>