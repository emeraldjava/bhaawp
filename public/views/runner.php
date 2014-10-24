<?php 

//echo "<pre>GET "; print_r($_GET); echo "</pre>";
//echo "<pre>POST "; print_r($_POST); echo "</pre>";

//echo 'BHAA Runner Page : Name = '.$_REQUEST['user_nicename'];
if(isset($_REQUEST['user_nicename']))
	$user = get_user_by('slug', $_REQUEST['user_nicename']);
else if (isset($_REQUEST['id']))
	$user = get_user_by('id', $_REQUEST['id']);

if(isset($user->ID)){
	
	global $current_user;
	//echo error_log($current_user->ID.' - '.$user->ID);
	//if( ( is_user_logged_in()&&($current_user->ID==$user->ID) ) ||current_user_can('manage_options'))
		
	//echo 'BHAA Runner Page '.print_r($user,true);	
/* 	echo 'is_user_logged_in() '.is_user_logged_in();
	echo '$current_user->ID '.$current_user->ID;
	echo 'current_user_can(manage_options) '.current_user_can('manage_options');
	echo 'current_user_can(edit_users)' .current_user_can('edit_users');
	echo '<ht/>'; */
	if(isset($_POST['std-form'])) {
		if(trim($_POST['std']) === '') {
			$runnerError = 'Please enter a runner ID.';
			$hasError = true;
		} else {
			$std = trim($_POST['std']);
			update_user_meta($user->ID,'bhaa_runner_standard',$std);
		}
	}
	if(isset($_POST['mobilephone-form'])) {
		if(trim($_POST['mobilephone']) === '') {
			$runnerError = 'Please enter a mobilephone.';
			$hasError = true;
		} else {
			$mobilephone = trim($_POST['mobilephone']);
			update_user_meta($user->ID,'bhaa_runner_mobilephone',$mobilephone);
		}
	}
	if(isset($_POST['gender-form'])) {
		if(trim($_POST['gender']) === '') {
		} else {
			$gender = trim($_POST['gender']);
			update_user_meta($user->ID,'bhaa_runner_gender',$gender);
		}
	}
	if(isset($_POST['companyteam-form'])){
		if(trim($_POST['companyteam']) === '') {
		} else {
			$companyteam = trim($_POST['companyteam']);
			Connections::get_instance()->updateRunnersHouse(Connections::HOUSE_TO_RUNNER,$companyteam,$user->ID);
			wp_redirect(home_url().'/runner/?id='.$user->ID);
			//update_user_meta($user->ID,'bhaa_runner_gender',$gender);
		}
	}

	$metadata = get_user_meta($user->ID);
	$status = $metadata['bhaa_runner_status'][0];
	$company = $metadata['bhaa_runner_company'][0];
	
	echo '<h1>'.$user->display_name.'</h1><hr/>';
	//var_dump($metadata);
	
	// first section - general info
	echo '<h3>BHAA Details</h3>'.
			'<ul>'.
			'<li><b>BHAA ID</b> : '.$user->ID.'</li>'.
			'<li>Standard : '.$metadata['bhaa_runner_standard'][0].'</li>'.
			(isset($company) ? '<li>Company : '.sprintf('<a href="/?post_type=house&p=%d"><b>%s</b></a>',$company,get_post($company)->post_title).'</li>':'').
			'</ul>';
	
	// second section - personal
	if( ( is_user_logged_in()&&($current_user->ID==$user->ID) ) ||current_user_can('manage_options')) {
		$content = 
				'<h3>Your Details</h3>'.
				'<ul>'.
				'<li>dateofbirth : '.$metadata['bhaa_runner_dateofbirth'][0].'</li>'.
				'<li>gender : '.$metadata['bhaa_runner_gender'][0].'</li>'.
				'<li>mobilephone : '.$metadata['bhaa_runner_mobilephone'][0].'</li>'.
				'<li>email : '.$user->user_email.'</li>'.
				'</ul>';
		echo $content;
	}
					
	if(current_user_can('edit_users')) {
		// third section - admin
		$content = 
				'<h2>Admin Details</h2>'.
				'<div>'.
				'<div><form action="" method="POST"><input type="text" size=10 name="bhaa_first_name" id="bhaa_first_name" placeholder="bhaa_first_name" value="'.$user->first_name.'"/><input type="hidden" name="bhaa_first_name_form" value="true"/><input type="submit" value="Update Firstname"/></form></div>'.
				'<div><form action="" method="POST"><input type="text" size=20 name="bhaa_last_name" id="bhaa_last_name" placeholder="bhaa_last_name" value="'.$user->last_name.'"/><input type="hidden" name="bhaa_last_name_form" value="true"/><input type="submit" value="Update Surname"/></form></div>'.
				'<div><form action="" method="POST"><input type="text" size=2 name="std" id="std" placeholder="std" value="'.$metadata['bhaa_runner_standard'][0].'"/><input type="hidden" name="std-form" value="true"/><input type="submit" value="Update Std"/></form></div>'.
				'<div><form action="" method="POST"><input type="text" size=10 name="dob" id="dob" placeholder="dob" value="'.$metadata['bhaa_runner_dateofbirth'][0].'"/><input type="hidden" name="dob-form" value="true"/><input type="submit" value="Update DOB"/></form></div>'.
				'<div><form action="" method="POST"><input type="text" size=30 name="email" id="email" value="'.$user->user_email.'"/><input type="hidden" name="email-form" value="true"/><input type="submit" value="Update Email"/></form></div>'.
				'<div><form action="" method="POST"><input type="text" size=10 name="mobilephone" id="mobilephone" value="'.$metadata['bhaa_runner_mobilephone'][0].'"/><input type="hidden" name="mobilephone-form" value="true"/><input type="submit" value="Update Mobile"/></form></div>'.
				'<div><form action="" method="POST"><input type="text" size=2 name="gender" id="gender" value="'.$metadata['bhaa_runner_gender'][0].'"/><input type="hidden" name="gender-form" value="true"/><input type="submit" value="Update Gender"/></form></div>'.
				'<div><form action="" method="POST">'.House_Manager::get_instance()->getCompanyTeamDropdown($user->ID).'<input type="hidden" name="companyteam-form" value="true"/><input type="submit" value="Company Team"/></form></div>'.
				//'<div><form action="" method="POST">'.House_Manager::get_instance()->getSectorTeamDropdown().'<input type="hidden" name="sectorteam-form" value="true"/><input type="submit" value="Sector Team"/></form></div>'.
				'<div>Status : '.$metadata['bhaa_runner_status'][0].'</div>'.
				'<div>dateofrenewal : '.$metadata['bhaa_runner_dateofrenewal'][0].'</div>'.
				'<div><form action="" method="POST">'.
				'	<input type="hidden" name="renew-form" value="true"/>'.
				'	<input type="submit" value="Renew"/>'.
				'</form></div>'.
				'</div>';
		echo $content;
		
		//var_dump(Connections::get_instance()->p2pDetails($user->ID));
		//var_dump(Connections::get_instance()->getRunnerConnections($user->ID));
	}
	
	if( current_user_can('manage_options') )
	{
		//var_dump(get_user_meta($user->ID));
	}
	echo RaceResult_List_Table::get_instance()->renderRunnerTable($user->ID);
}
else
	echo 'You have not selected a runner!.';
?>