<?php

/**
 * Template Name: BHAA Race Template
 * @package WordPress
 * @subpackage BHAA
 * @since Twenty Ten 1.0
 * 
 * http://sixrevisions.com/wordpress/wordpress-custom-post-types-guide/
 */

get_header(); ?>


<div id="container">

<div id="content" role="main">


<div id="bbp-user" class="bbp-single-user">

<div class="entry-content">

<!--  http://codex.wordpress.org/Function_Reference/the_ID -->

Race
ID : <?php echo the_ID();?>

<p>Meta information for this post:</p>
<?php the_meta(); ?>

<p>Shortcode : <?php echo get_the_ID();?></p>
<?php //echo do_shortcode('[bhaa type=raceresult id='.get_post_meta(get_the_ID(),'bhaa-race-id',true).']'); ?>

<?php 
global $wpdb;
$result = $wpdb->get_results(
	$wpdb->prepare('SELECT * FROM '.$wpdb->raceresult.' where race=%d',get_post_meta(get_the_ID(),'bhaa_race_id',true)));

?>

<table id="mylist" class="sortable">
    <thead>
        <tr>
        	<th>Position</th>
            <th>Runner</th>
            <th>Time</th>
            <th>Number</th>
            <th>Standard</th>
            <th>Pace</th>
            <th>Category</th>
        </tr>
    </thead>
<tbody id="the-list">
<?php foreach ( $result AS $row ) : $class = ('alternate' == $class) ? '' : 'alternate'; ?>
<?php 
$url = get_permalink();
$url = add_query_arg('type', 'race', $url);
$url = add_query_arg('id', $row->race, $url);
?>
<tr class="<?php echo $class ?>">
	<td><?php echo $row->position ?></td>
	<td><?php
	$user = new WP_User( $row->runner );
	echo $user->display_name; ?></td>
	<td><?php echo $row->racetime ?></td>
	<td><?php echo $row->racenumber ?></td>
	<td><?php echo $row->standard ?></td>
	<td><?php echo $row->paceKM ?></td>
	<td><?php echo $row->category ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

</div><!-- .entry-content -->

</div><!-- #bbp-user-->

</div><!-- #content -->

</div><!-- #container -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>
