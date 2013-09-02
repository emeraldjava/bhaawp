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

global $BHAA;
//$raceday = $BHAA->getRaceday();

//echo "<pre>GET "; print_r($_GET); echo "</pre>";
//echo "<pre>POST "; print_r($_POST); echo "</pre>";
?>

<!-- Create a header in the default WordPress 'wrap' container -->
<div class="wrap">

	<h2>Raceday</h2>
	
	<div id="tabbable">
	<ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
	  <li><a href="#a" data-toggle="tab">Home</a></li>
	  <li><a href="#b" data-toggle="tab">Profile</a></li>
	  <li><a href="#c" data-toggle="tab">Messages</a></li>
	</ul>
	<div id="my-tab-content" class="tab-content">
		<div id="a" class="tab-pane">
		<div class="navbar-search pull-left" align="left">
			<input size="35" type="text" placeholder="Search BHAA Member by Name OR ID" id="memberfilter"/>
		</div>
		
		<?php echo wp_get_form('my-unique-form-id')?></div>
		<div id="b" class="tab-pane">b <?php echo wp_get_form('my-unique-form-id')?></div>
		<div id="c" class="tab-pane">c <?php echo wp_get_form('my-unique-form-id')?></div>
	</div>
	</div>
	
	<?php 
	echo 'wp-form';
	//echo wp_get_form('my-unique-form-id');
	?>
	<hr/>
	<form name="<?php echo Raceday::BHAA_RACEDAY_FORM_REGISTER; ?>" method="post">
		<input type="text" name="name" />
		<input type="hidden" name="action" value="<?php echo Raceday::BHAA_RACEDAY_FORM_REGISTER; ?>" />
		<input type="submit" name="<?php echo Raceday::BHAA_RACEDAY_FORM_REGISTER; ?>" value="<?php echo Raceday::BHAA_RACEDAY_FORM_REGISTER; ?>" />
	</form>
	<hr/>
	<form name="<?php echo Raceday::BHAA_RACEDAY_FORM_NEWMEMBER; ?>" method="post">
		<input type="text" name="name" />
		<input type="hidden" name="action" value="<?php echo Raceday::BHAA_RACEDAY_FORM_NEWMEMBER; ?>" />
		<input type="submit" name="<?php echo Raceday::BHAA_RACEDAY_FORM_NEWMEMBER; ?>" value="<?php echo Raceday::BHAA_RACEDAY_FORM_NEWMEMBER; ?>" />
	</form>
	<hr/>
	<form name="<?php echo Raceday::BHAA_RACEDAY_FORM_PREREG; ?>" method="post">
		<input type="text" name="name" />
		<input type="hidden" name="action" value="<?php echo Raceday::BHAA_RACEDAY_FORM_PREREG; ?>" />
		<input type="submit" name="<?php echo Raceday::BHAA_RACEDAY_FORM_PREREG; ?>" value="<?php echo Raceday::BHAA_RACEDAY_FORM_PREREG; ?>" />
	</form>
	
</div>
<?php 
//get_footer();
?>