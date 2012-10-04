<?php
/*
 Template Name: BHAA Home
*/
?>

<?php get_header(); ?>

	<section class="featuredEvents row">
	
		<div class="row">
			<div class="span4 columns">
				<h2>Next Event</h2>
				<?php echo do_shortcode('[events_list limit="3" orderby="event_start_date" order="ASC"][/events_list]');?>
			</div>
		
			<div class="span4 columns">
				<h2>Last Event</h2>
				<?php echo do_shortcode('[events_list limit="3" orderby="event_end_date" order="ASC"]#_EVENTLINK</br>[/events_list]');?>
			</div>
		
			<div class="span4 columns">
				<h2>stuff</h2>
			</div>
		</div>
	</section>

<?php get_footer(); ?>