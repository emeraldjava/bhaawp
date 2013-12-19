<?php 

echo "<pre>GET "; print_r($_GET); echo "</pre>";
echo "<pre>POST "; print_r($_POST); echo "</pre>";

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
	echo 'is_user_logged_in() '.is_user_logged_in();
	echo '$current_user->ID '.$current_user->ID;
	echo 'current_user_can(manage_options) '.current_user_can('manage_options');
	echo 'current_user_can(edit_users)' .current_user_can('edit_users');
	echo '<ht/>';
	
	if(isset($_POST['std-form'])) {
		if(trim($_POST['std']) === '') {
			$runnerError = 'Please enter a runner ID.';
			$hasError = true;
		} else {
			$std = trim($_POST['std']);
			update_user_meta($user->ID,'bhaa_runner_standard',$std);
		}
	}
	if(isset($_POST['dob-form'])) {
		if(trim($_POST['dob']) === '') {
			$runnerError = 'Please enter a runner ID.';
			$hasError = true;
		} else {
			$dob = trim($_POST['dob']);
			update_user_meta($user->ID,'bhaa_runner_dateofbirth',$dob);
		}
	}
	if(isset($_POST['email-form'])) {
		if(trim($_POST['email']) === '') {
			$runnerError = 'Please enter a runner ID.';
			$hasError = true;
		} else {
			$email = trim($_POST['email']);
			$return = wp_update_user( array ( 'ID' => $user->ID, 'user_email' => $email ) ) ;
			if ( is_wp_error($return) )
   				error_log($return->get_error_message());
			$user = $user = get_user_by('id',$user->ID);
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
	
	if(isset($_REQUEST['merge'])&&current_user_can('edit_users')) {
		Runner_Manager::get_instance()->mergeRunner($_REQUEST['id'],$_REQUEST['merge']);
	}
	
	// renew-form
	if(isset($_REQUEST['renew-form'])&&current_user_can('edit_users')) {
		$runner = new Runner($user->ID);
		$runner->renew();
	}
	
	$metadata = get_user_meta($user->ID);
	$status = $metadata['bhaa_runner_status'][0];
	$company = $metadata['bhaa_runner_company'][0];
	
	echo '<h1>'.$user->display_name.'</h1>';
	
	// first section - general info
	$content = apply_filters(
			'the_content',
			'[one_third last="no"]'.
			'<h2>BHAA Details</h2>'.
			'<ul>'.
			'<li><b>BHAA ID</b> : '.$user->ID.'</li>'.
			'<li>Standard : '.$metadata['bhaa_runner_standard'][0].'</li>'.
			(isset($company) ? '<li>Company : '.sprintf('<a href="/?post_type=house&p=%d"><b>%s</b></a>',$company,get_post($company)->post_title).'</li>':'').
			'</ul>'.
			'[/one_third]');
	echo $content;
	
	// second section - personal
	if( ( is_user_logged_in()&&($current_user->ID==$user->ID) ) ||current_user_can('manage_options')) {
		$content = 
				'<h2>Your Details</h2>'.
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
				'<div><form action="" method="POST"><input type="text" size=2 name="std" id="std" placeholder="std" value="'.$metadata['bhaa_runner_standard'][0].'"/><input type="hidden" name="std-form" value="true"/><input type="submit" value="Update Std"/></form></div>'.
				'<div><form action="" method="POST"><input type="text" size=10 name="dob" id="dob" placeholder="dob" value="'.$metadata['bhaa_runner_dateofbirth'][0].'"/><input type="hidden" name="dob-form" value="true"/><input type="submit" value="Update DOB"/></form></div>'.
				'<div><form action="" method="POST"><input type="text" size=20 name="email" id="email" value="'.$user->user_email.'"/><input type="hidden" name="email-form" value="true"/><input type="submit" value="Update Email"/></form></div>'.
				'<div><form action="" method="POST"><input type="text" size=10 name="mobilephone" id="mobilephone" value="'.$metadata['bhaa_runner_mobilephone'][0].'"/><input type="hidden" name="mobilephone-form" value="true"/><input type="submit" value="Update Mobile"/></form></div>'.
				'<div><form action="" method="POST"><input type="text" size=2 name="gender" id="gender" value="'.$metadata['bhaa_runner_gender'][0].'"/><input type="hidden" name="gender-form" value="true"/><input type="submit" value="Update Gender"/></form></div>'.
				'<div>Status : '.$metadata['bhaa_runner_status'][0].'</div>'.
				'<div>dateofrenewal : '.$metadata['bhaa_runner_dateofrenewal'][0].'</div>'.
				'<div><form action="" method="POST">'.
				'	<input type="hidden" name="renew-form" value="true"/>'.
				'	<input type="submit" value="Renew"/>'.
				'</form></div>'.
				'</div>';
		echo $content;
	
		// get current users details
		$user_info = get_userdata($user->ID);
		$bhaa_runner_dateofbirth = get_user_meta($user->ID,'bhaa_runner_dateofbirth',true);
	
		$queryMatchAll = new WP_User_Query(
				array(
						'exclude' => array($user->ID),
						'fields' => 'all_with_meta',
						'meta_query' => array(
								array(
										'key' => 'last_name',
										'value' => $user_info->user_lastname,
										'compare' => '='),
								array(
										'key' => 'first_name',
										'value' => $user_info->user_firstname,
										'compare' => '='),
								array(
										'key' => 'bhaa_runner_dateofbirth',
										'value' => $bhaa_runner_dateofbirth,
										'compare' => '='
								))));
	
		$queryMatchName = new WP_User_Query(
				array(
						'exclude' => array($user->ID),
						'fields' => 'all_with_meta',
						'meta_query' => array(
								array(
										'key' => 'last_name',
										'value' => $user_info->user_lastname,
										'compare' => '='),
								array(
										'key' => 'first_name',
										'value' => $user_info->user_firstname,
										'compare' => '='
								))));
	
		$queryMatchLastDob = new WP_User_Query(
				array(
						'exclude' => array($user->ID),
						'fields' => 'all_with_meta',
						'meta_query' => array(
								array(
										'key' => 'last_name',
										'value' => $user_info->user_lastname,
										'compare' => '='),
								array(
										'key' => 'bhaa_runner_dateofbirth',
										'value' => $bhaa_runner_dateofbirth,
										'compare' => '='
								))));
	
		// 	var_dump($query->query_where);
		// 	echo '<hr/>';
		// 	var_dump($query->get_results());
		// 	echo '<hr/>';
	
		$users = array_merge( $queryMatchAll->get_results(), $queryMatchName->get_results(), $queryMatchLastDob->get_results());
		//var_dump($users);
		foreach($users as $matcheduser)
		{
			echo sprintf('<div>%d <a href="%s">%s</a> DOB:%s, Status:%s, Email:%s <a href="%s">Delete %d and merge to %d</a></div>',
					$matcheduser->ID,
					add_query_arg(array('id'=>$matcheduser->ID),'/runner'),$matcheduser->display_name,
					$matcheduser->bhaa_runner_dateofbirth,$matcheduser->bhaa_runner_status,$matcheduser->user_email,
					add_query_arg(array('id'=>$user->ID,'merge'=>$matcheduser->ID),'/runner'),$matcheduser->ID,$user->ID
			);
		}
		echo '<hr/>';
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