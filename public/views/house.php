<section id="primary">
<?php 
if ( has_post_thumbnail() ) 
{ // check if the post has a Post Thumbnail assigned to it.
	//echo '<a href="'.get_post_meta(get_the_ID(),'bhaa_company_website',true).'" title="'.the_title_attribute().'" >';
	//echo get_the_post_thumbnail(get_the_ID(), 'thumbnail');
 	//the_post_thumbnail('thumbnail');
   	//echo '</a>';
}

$teamtype = wp_get_post_terms($post->ID,'teamtype');
$connected_type = Connections::HOUSE_TO_RUNNER;
if($teamtype[0]->name=='sector') {
	$connected_type = Connection::SECTORTEAM_TO_RUNNER;
}
$users = get_users( array(
		'connected_type' => $connected_type,
		'connected_items' => $post,
		'fields' => 'all_with_meta',
		'orderby' => 'display_name',
		'order' => 'ASC'
));

echo do_shortcode( 
	'[two_third last="no"]'.
	'<p>'.get_the_term_list(get_the_ID(), 'sector', 'Sector : ', ', ', '').'</p>'.
	'<p>Team ID: '.$post->ID.'</p>'.
	'<p>Team Type: '.$teamtype[0]->name.'</p>'.
	'<a target="new" href="'.get_post_meta(get_the_ID(),'bhaa_company_website',true).'">'.get_the_title().' Website</a>'.
	'[/two_third]'.
	'[one_third last="yes"]'.get_the_post_thumbnail(get_the_ID(), 'thumbnail').'[/one_third]');
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