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
		$action = $_REQUEST['action'];
		if(!isset($action))
			echo $this->getTable()->renderTable(201218);
		else
		{
			echo 'The action is '.$action;
			echo $this->getTable()->renderTable(201218);
		}
	}
	
	/**
	 * handle the row form
	 */
	function raceresult_table_form_page_handler()
	{
		//echo "the race results form";
		
		$default = array(
				'race' => $_REQUEST['race'],
				'runner' => $_REQUEST['runner'],
				'time' => $_REQUEST['time']
		);
		$row = $default;
		
		if (wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__)))
		{
			// insert or update row
		}
		else
		{
			// read the row details.
		}
		
		add_meta_box('raceresult_form_meta_box', 'Race Result Details', array($this,'raceresult_meta_box'), 'raceresult', 'normal', 'default');
		
		echo '<div class="wrap">';
		
		echo '<form id="form" method="POST">
			<input type="hidden" name="nonce" value="'.wp_create_nonce(basename(__FILE__)).'"/>
        	<input type="hidden" name="race" value="'.$row['race'].'"/>
    	    <input type="hidden" name="runner" value="'.$row['runner'].'"/>
	        <input type="hidden" name="time" value="'.$row['time'].'"/>
	        <div class="metabox-holder" id="poststuff">
	            <div id="post-body">
	                <div id="post-body-content">'
					.do_meta_boxes('raceresult', 'normal', $row).
					'<input type="submit" value="Save" id="submit" class="button-primary" name="submit">
	                </div>
	            </div>
	        </div>
	    </form>';
		echo '</div>';
	}
	
	function raceresult_meta_box($row)
	{
		echo '<table cellspacing="2" cellpadding="5" style="width: 100%;" class="form-table">
		    <tbody>
		    <tr class="form-field">
		        <th valign="top" scope="row">
		            <label for="race">Race</label>
		        </th>
		        <td>
		            <input id="race" name="race" type="text" style="width: 95%" value="'.$row['race'].'" size="10" class="code" placeholder="race"/>
		        </td>
		    </tr>
		    <tr class="form-field">
		        <th valign="top" scope="row">
		            <label for="runner">Runner</label>
		        </th>
		        <td>
	            <input id="runner" name="runner" type="text" style="width: 95%" value="'.$row['runner'].'" size="10" class="code" placeholder="runner"/>
		        </td>
		    </tr>
		    <tr class="form-field">
		        <th valign="top" scope="row">
		            <label for="time">Time</label>
		        </th>
		        <td>
		            <input id="time" name="time" type="text" style="width: 95%" value="'.$row['time'].'" size="10" class="code" placeholder="hh:mm:ss" required>
		        </td>
		    </tr>
		    </tbody>
		</table>';
	}
	
	function getTable()
	{
		return $this->table;
	}
}
?>