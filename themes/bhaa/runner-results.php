<?php
/**
 * Template Name: User Profile
 * 
 * @package BHAA
 * @subpackage 2012
 * 
 * http://wordpress.stackexchange.com/questions/9775/how-to-edit-a-user-profile-on-the-front-end
 */

get_header(); 
get_currentuserinfo();
?>

		<div id="container">
			<div id="content" role="main">

<form name="profile" action="" method="post" enctype="multipart/form-data">
  <?php wp_nonce_field('update-profile_' . $user_ID) ?>
  <input type="hidden" name="from" value="profile" />
  <input type="hidden" name="action" value="update" />
  <input type="hidden" name="checkuser_id" value="<?php echo $user_ID ?>" />
  <input type="hidden" name="dashboard_url" value="<?php echo get_option("dashboard_url"); ?>" />
  <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id; ?>" />
  <table width="100%" cellspacing="0" cellpadding="0" border="0">
	<?php if ( isset($_GET['updated']) ):
$d_url = $_GET['d'];?>
	<tr>
	  <td align="center" colspan="2"><span style="color: #FF0000; font-size: 11px;">Your profile changed successfully</span></td>
	</tr>
	<?php elseif($errmsg!=""): ?>
	<tr>
	  <td align="center" colspan="2"><span style="color: #FF0000; font-size: 11px;"><?php echo $errmsg;?></span></td>
	</tr>
	<?php endif;?>
	<tr>
		<td colspan="2" align="center"><h2>Update profile</h2></td>
	</tr>
	<tr>
	  <td>First Name</td>
	  <td><input type="text" name="first_name" id="first_name" value="<?php echo $userdata->first_name ?>" style="width: 300px;" /></td>
	</tr>
	<tr>
	  <td>Last Name</td>
	  <td><input type="text" name="last_name" class="mid2" id="last_name" value="<?php echo $userdata->last_name ?>" style="width: 300px;" /></td>
	</tr>
	<tr>
	  <td>Email <span style="color: #F00">*</span></td>
	  <td><input type="text" name="email" class="mid2" id="email" value="<?php echo $userdata->user_email ?>" style="width: 300px;" /></td>
	</tr>
	<tr>
	  <td>New Password </td>
	  <td><input type="password" name="pass1" class="mid2" id="pass1" value="" style="width: 300px;" /></td>
	</tr>
	<tr>
	  <td>New Password Confirm </td>
	  <td><input type="password" name="pass2" class="mid2" id="pass2" value="" style="width: 300px;" /></td>
	</tr>
	<tr>
	  <td align="right" colspan="2"><span style="color: #F00">*</span> <span style="padding-right:40px;">mandatory fields</span></td>
	</tr>
	<tr><td colspan="2"><h3>Extra profile information</h3></td></tr>
	<tr>
		<td>Facebook URL</td>
		<td><input type="text" name="facebook" id="facebook" value="<?php echo esc_attr( get_the_author_meta( 'facebook', $userdata->ID ) ); ?>" style="width: 300px;" /></td>
	</tr>
	<tr>
		<td>Twitter</td>
		<td><input type="text" name="twitter" id="twitter" value="<?php echo esc_attr( get_the_author_meta( 'twitter', $userdata->ID ) ); ?>" style="width: 300px;" /></td>
	</tr>
	<tr>
		<td>Date Of Birth</td>
		<td><input type="text" name="dob" id="dob" value="<?php echo esc_attr( get_the_author_meta( 'dob', $userdata->ID ) ); ?>" style="width: 300px;" /></td>
	</tr>
	<tr>
		<td>Phone</td>
		<td><input type="text" name="phone" id="phone" value="<?php echo esc_attr( get_the_author_meta( 'phone', $userdata->ID ) ); ?>" style="width: 300px;" /></td>
	</tr>
	<tr>
		<td>Address</td>
		<td><input type="text" name="address" id="address" value="<?php echo esc_attr( get_the_author_meta( 'address', $userdata->ID ) ); ?>" style="width: 300px;" /></td>
	</tr>
	<tr>
		<td>City</td>
		<td><input type="text" name="city" id="city" value="<?php echo esc_attr( get_the_author_meta( 'city', $userdata->ID ) ); ?>" style="width: 300px;" /></td>
	</tr>
	<tr>
		<td>Province</td>
		<td><input type="text" name="province" id="province" value="<?php echo esc_attr( get_the_author_meta( 'province', $userdata->ID ) ); ?>" style="width: 300px;" /></td>
	</tr>
	<tr>
		<td>Postal Code</td>
		<td><input type="text" name="postalcode" id="postalcode" value="<?php echo esc_attr( get_the_author_meta( 'postalcode', $userdata->ID ) ); ?>" style="width: 300px;" /></td>
	</tr>
	<tr>
	  <td align="center" colspan="2"><input type="submit" value="Update" /></td>
	</tr>
  </table>
  <input type="hidden" name="action" value="update" />
</form>
			</div><!-- #content -->
		</div><!-- #container -->
		
<?php get_sidebar(); ?>
<?php get_footer(); ?>