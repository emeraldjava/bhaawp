<?php

// get_header();
// http://wp.tutsplus.com/tutorials/the-complete-guide-to-the-wordpress-settings-api-part-5-tabbed-navigation-for-your-settings-page/
// http://stackoverflow.com/questions/16228883/wordpress-two-forms-in-contact-us-page
// http://bavotasan.com/2009/processing-multiple-forms-on-one-page-with-php/
// http://www.wpbeginner.com/wp-themes/default-wordpress-generated-css-cheat-sheet-for-beginners/

// http://stackoverflow.com/questions/7998050/submitting-multiple-forms-with-ajax
// http://stackoverflow.com/questions/17438057/submitting-multiple-forms-with-jquery-for-wordpress-registration
// http://wp.smashingmagazine.com/2012/05/01/wordpress-shortcodes-complete-guide/

// http://wordpress.stackexchange.com/questions/21237/plugin-form-submission-best-practice
// http://www.zunisoft.com/2013/02/wordpress-plugin-form-submissions.html
// http://wordpress.stackexchange.com/questions/3902/best-practices-for-creating-and-handling-forms-with-plugins
?>

<!-- Create a header in the default WordPress 'wrap' container -->
	<div class="wrap">
	
		<div id="icon-themes" class="icon32"></div>
		<h2>Raceday</h2>
		<?php settings_errors(); ?>
		
		<h2 class="nav-tab-wrapper">
			<a href="#" class="nav-tab">Display Options</a>
			<a href="#" class="nav-tab">Social Options</a>
		</h2>
		
		<form method="post" action="options.php">

			<?php settings_fields( 'sandbox_theme_display_options' ); ?>
			<?php do_settings_sections( 'sandbox_theme_display_options' ); ?>	
			
			<?php settings_fields( 'sandbox_theme_social_options' ); ?>
			<?php do_settings_sections( 'sandbox_theme_social_options' ); ?>	
		
			<?php submit_button(); ?>
			
		</form>
		
	</div>
	


<?php 
//get_footer();
?>