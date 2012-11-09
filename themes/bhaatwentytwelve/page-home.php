<?php
/**
 * Template Name: Home
 *
 * Description: Twenty Twelve loves the no-sidebar look as much as
 * you do. Use this page template to remove the sidebar from any page.
 *
 * Tip: to remove the sidebar from all posts and pages simply remove
 * any active widgets from the Main Sidebar area, and the sidebar will
 * disappear everywhere.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
global $EM_Events, $EM_Event;

get_header(); 
?>

	<div id="primary" class="site-content">
		<div id="content" role="main">

		<!-- events -->
	<section class="row">
	
		<!-- 
		http://wp-events-plugin.com/documentation/advanced-usage/
		http://blackhillswebworks.com/2011/06/09/modifying-the-events-manager-for-wordpress-widget-to-filter-events-by-category/
		-->
		<div class="span6 columns">
			<h2>Next Event</h2>
			<?php 
			// http://wp-events-plugin.com/documentation/event-search-attributes/
			echo EM_Events::output( array('limit'=>1,'orderby'=>'event_start_date','order'=>'ASC') );
				
			
// 			$events = EM_Events::get(array('limit',1));
// 			/* @var $EM_Event EM_Event */
// 			echo $events[0]->output(
// 					'<div style="float:right; margin:0px 0px 15px 15px;">#_MAP</div>'.
// 					'<p>'.
// 					'<strong>Date/Time</strong><br/>'.
// 					'Date(s) - #_EVENTDATES<br /><i>#_EVENTTIMES</i>'.
// 					'</p>'
// 					);
			?>
		</div>
	
		<div class="span6 columns">
			<h2>Last Event</h2>
			<?php echo EM_Events::output( array('limit'=>1,'orderby'=>'event_start_date','order'=>'DESC'));?>
		</div>
		
	</section>
		
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content', 'page' ); ?>
				<?php comments_template( '', true ); ?>
			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer(); ?>