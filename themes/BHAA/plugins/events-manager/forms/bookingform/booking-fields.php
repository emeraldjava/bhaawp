<?php
/* 
 * This file generates the default booking form fields. Events Manager Pro does not use this file.
 */
/* @var $EM_Event EM_Event */ 
//Here we have extra information required for the booking. 
?>
<?php if( !is_user_logged_in() && apply_filters('em_booking_form_show_register_form',true) ): ?>
	<?php //User can book an event without registering, a username will be created for them based on their email and a random password will be created. 
	echo do_shortcode('[user-meta type="registration" form="register"');	
	//echo do_shortcode('[dayrunnerform]');
	?>
	//careful if making an add-on, this will only be used if you're not using custom booking forms ?>					
<?php endif; ?>		
<!-- <p>
	<label for='booking_comment'><php _e('Comment', 'dbem') ?></label>
	<textarea name='booking_comment'><php echo !empty($_POST['booking_comment']) ? $_POST['booking_comment']:'' ?></textarea>
</p>-->	