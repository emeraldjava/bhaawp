<section id="primary">
<?php 
//while ( have_posts() ) : the_post(); 
?>

<!-- http://wpsnipp.com/index.php/template/create-multiple-search-templates-for-custom-post-types/ -->
<!-- http://www.studionashvegas.com/development/search-specific-post-type-wordpress/ -->
<!-- HIDE AJAX SEARCH
<div class="ui-widget">
	<label for="humm">Search:</label><input id="house_search" />
</div>-->

	<!-- 
	<nav id="nav-single">
		<h3 class="assistive-text">
			<php _e( 'Post navigation', 'twentyeleven' ); ?>
		</h3>
		<span class="nav-previous"><php //previous_post_link( '%link', __( '<span class="meta-nav">&larr;</span> Previous', 'twentyeleven' ) ); ?>
		</span> <span class="nav-next"><php //next_post_link( '%link', __( 'Next <span class="meta-nav">&rarr;</span>', 'twentyeleven' ) ); ?>
		</span>
	</nav>
	-->
	
		<!-- #nav-single -->
<?php 
if ( has_post_thumbnail() ) 
{ // check if the post has a Post Thumbnail assigned to it.
	//echo '<a href="'.get_post_meta(get_the_ID(),'bhaa_company_website',true).'" title="'.the_title_attribute().'" >';
	//echo get_the_post_thumbnail(get_the_ID(), 'thumbnail');
 	//the_post_thumbnail('thumbnail');
   	//echo '</a>';
}

echo do_shortcode( 
	'[one_third last="no"]<h1>BHAA '.get_the_title().'</h1>'.
	'<p>'.get_the_term_list(get_the_ID(), 'sector', 'Sector : ', ', ', '').'</p>'.
	'[/one_third]');
//echo $content;

echo '[one_third last="no"]<a target="new" href="'.get_post_meta(get_the_ID(),'bhaa_company_website',true).'">'.get_the_title().' Website</a>[/one_third]'.
	'[one_third last="yes"]'.get_the_post_thumbnail(get_the_ID(), 'thumbnail').'[/one_third]';
//	'[one_third last="yes"]'.isset(has_post_thumbnail())?the_post_thumbnail():''.'[/one_third]');
//echo $content;
?>

<?php
	// Find connected users
	// https://github.com/scribu/wp-posts-to-posts/wiki/Posts-2-Users
	// http://scribu.net/wordpress/the-magic-of-wp_user.html
	// http://mattvarone.com/wordpress/list-users-with-wp_user_query/
	echo '<h2>Team ID :'.$post->ID.'</h2>';
	$teamtype = wp_get_post_terms($post->ID,'teamtype');
	$connected_type = Connections::HOUSE_TO_RUNNER;
	if($teamtype[0]->name=='sector')
	{
		echo '<h3>Sector Team</h2>';
		$connected_type = Connection::SECTORTEAM_TO_RUNNER;
	}
	else
	{	
		echo '<h3>Company Team</h2>';
	}
	$users = get_users( array(
			'connected_type' => $connected_type,
			'connected_items' => $post,
			'fields' => 'all_with_meta',
			'orderby' => 'display_name',
			'order' => 'ASC'
	));

?>
<h4>Runners</h4>
<table class="table-1">
	<tr>
		<th>Name</th>
		<th>Gender</th>
		<th>Standard</th>
		<th>Membership Status</th>
	</tr>
	<?php foreach ( $users AS $user ) : ?>
	<tr>
	<?php 
	$page = get_page_by_title('runner');
	$permalink = get_permalink( $page );
	echo sprintf('<td><b><a href="%s">%s</a></b></td><td>%s</td><td>%s</td><td>%s</td>',
		add_query_arg(array('id'=>$user->ID), $permalink ),
		$user->display_name,
		$user->get('bhaa_runner_gender'),
		$user->get('bhaa_runner_standard'),
		$user->get('bhaa_runner_status')
	);
	?>
	</tr>
	<?php endforeach; ?>
</table>

<?php 
//echo BHAA::get_instance()->getTeamResultsForHouse(get_the_ID());
?>

</section>