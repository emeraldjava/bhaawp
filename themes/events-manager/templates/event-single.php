<?php
/*
 * Remember that this file is only used if you have chosen to override event pages with formats in your event settings!
 * You can also override the single event page completely in any case (e.g. at a level where you can control sidebars etc.), as described here - http://codex.wordpress.org/Post_Types#Template_Files
 * Your file would be named single-event.php
 */
/*
 * This page displays a single event, called during the em_content() if this is an event page.
 * You can override the default display settings pages by copying this file to yourthemefolder/plugins/events-manager/templates/ and modifying it however you need.
 * You can display events however you wish, there are a few variables made available to you:
 *
 * $args - the args passed onto EM_Events::output()
 */
global $EM_Event;
/* @var $EM_Event EM_Event */
if (strpos($EM_Event->name, 'Annual Membership') === 0)
{
	echo '<h1>The BHAA Annual membership event/ticket</h1>';
	echo $EM_Event->output_single();
}
else
{
	

if($EM_Event->end >= time())
{
	echo '<h1>A future BHAA event</h1>';
	echo $EM_Event->output_single();
}
else
{
	echo '<h1>A past BHAA event</h1>';
	
	
	// Find connected pages
	$connected = new WP_Query( array(
		'connected_type' => 'event_to_race',
		'connected_items' => get_queried_object(),
		'nopaging' => true,
	));
	
	if ( $connected->have_posts() ) :
	
		echo '<h2>See the Full Race Results</h2><ul>';
		while ( $connected->have_posts() ) : 			
			$connected->the_post();
			echo '<li><a href="';
			the_permalink();
			echo '">';
			the_title();
			echo '</a></li>';
		endwhile;
		echo '</ul>';
		
		// Prevent weirdness
		wp_reset_postdata();
	else :
		echo "No races have been linked to this event yet.";
	endif;
	echo $EM_Event->output_single();
	echo '<ul>TODO<li>embed Youtube</li><li>embed flickr</li><li>Show top 3 three and total number of runners</li></ul>';
}

}
?>