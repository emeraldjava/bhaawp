<?php
//////////////////////////////////////////////////////////////////
// Raw Shortcode
//////////////////////////////////////////////////////////////////
function my_formatter($content) {
	$new_content = '';
	$pattern_full = '{(\[raw\].*?\[/raw\])}is';
	$pattern_contents = '{\[raw\](.*?)\[/raw\]}is';
	$pieces = preg_split($pattern_full, $content, -1, PREG_SPLIT_DELIM_CAPTURE);

	foreach ($pieces as $piece) {
		if (preg_match($pattern_contents, $piece, $matches)) {
			$new_content .= $matches[1];
		} else {
			$new_content .= wptexturize(wpautop($piece));
		}
	}

	return $new_content;
}

remove_filter('the_content', 'wpautop');
remove_filter('the_content', 'wptexturize');

add_filter('the_content', 'my_formatter', 11);
add_filter('widget_text', 'my_formatter', 11);

//////////////////////////////////////////////////////////////////
// Youtube shortcode
//////////////////////////////////////////////////////////////////
add_shortcode('youtube', 'shortcode_youtube');
	function shortcode_youtube($atts) {
		$atts = shortcode_atts(
			array(
				'id' => '',
				'width' => 600,
				'height' => 360
			), $atts);

			return '<div class="video-shortcode"><iframe title="YouTube video player" width="' . $atts['width'] . '" height="' . $atts['height'] . '" src="http://www.youtube.com/embed/' . $atts['id'] . '" frameborder="0" allowfullscreen></iframe></div>';
	}
	
//////////////////////////////////////////////////////////////////
// Vimeo shortcode
//////////////////////////////////////////////////////////////////
add_shortcode('vimeo', 'shortcode_vimeo');
	function shortcode_vimeo($atts) {
		$atts = shortcode_atts(
			array(
				'id' => '',
				'width' => 600,
				'height' => 360
			), $atts);
		
			return '<div class="video-shortcode"><iframe src="http://player.vimeo.com/video/' . $atts['id'] . '" width="' . $atts['width'] . '" height="' . $atts['height'] . '" frameborder="0"></iframe></div>';
	}
	
//////////////////////////////////////////////////////////////////
// SoundCloud shortcode
//////////////////////////////////////////////////////////////////
add_shortcode('soundcloud', 'shortcode_soundcloud');
	function shortcode_soundcloud($atts) {
		$atts = shortcode_atts(
			array(
				'url' => '',
				'width' => '100%',
				'height' => 81,
				'comments' => 'true',
				'auto_play' => 'true',
				'color' => 'ff7700',
			), $atts);
		
			return '<object height="' . $atts['height'] . '" width="' . $atts['width'] . '"><param name="movie" value="http://player.soundcloud.com/player.swf?url=' . urlencode($atts['url']) . '&amp;show_comments=' . $atts['comments'] . '&amp;auto_play=' . $atts['auto_play'] . '&amp;color=' . $atts['color'] . '"></param><param name="allowscriptaccess" value="always"></param><embed allowscriptaccess="always" height="' . $atts['height'] . '" src="http://player.soundcloud.com/player.swf?url=' . urlencode($atts['url']) . '&amp;show_comments=' . $atts['comments'] . '&amp;auto_play=' . $atts['auto_play'] . '&amp;color=' . $atts['color'] . '" type="application/x-shockwave-flash" width="' . $atts['width'] . '"></embed></object>';
	}
	
//////////////////////////////////////////////////////////////////
// Button shortcode
//////////////////////////////////////////////////////////////////
add_shortcode('button', 'shortcode_button');
	function shortcode_button($atts, $content = null) {
			return '[raw]<a class="button ' . $atts['size'] . ' ' . $atts['color'] . '" href="' . $atts['link'] . '" target="' . $atts['target'] . '">' .do_shortcode($content). '</a>[/raw]';
	}
	
//////////////////////////////////////////////////////////////////
// Dropcap shortcode
//////////////////////////////////////////////////////////////////
add_shortcode('dropcap', 'shortcode_dropcap');
	function shortcode_dropcap( $atts, $content = null ) {  
		
		return '<span class="dropcap">' .do_shortcode($content). '</span>';  
		
}
	
//////////////////////////////////////////////////////////////////
// Highlight shortcode
//////////////////////////////////////////////////////////////////
add_shortcode('highlight', 'shortcode_highlight');
	function shortcode_highlight($atts, $content = null) {
		$atts = shortcode_atts(
			array(
				'color' => 'yellow',
			), $atts);
			
			if($atts['color'] == 'black') {
				return '<span class="highlight2">' .do_shortcode($content). '</span>';
			} else {
				return '<span class="highlight1">' .do_shortcode($content). '</span>';
			}

	}
	
//////////////////////////////////////////////////////////////////
// Check list shortcode
//////////////////////////////////////////////////////////////////
add_shortcode('checklist', 'shortcode_checklist');
	function shortcode_checklist( $atts, $content = null ) {
	
	$content = str_replace('<ul>', '<ul class="arrow">', do_shortcode($content));
	$content = str_replace('<li>', '<li>', do_shortcode($content));
	
	return $content;
	
}

//////////////////////////////////////////////////////////////////
// Tabs shortcode
//////////////////////////////////////////////////////////////////
add_shortcode('tabs', 'shortcode_tabs');
	function shortcode_tabs( $atts, $content = null ) {
	extract(shortcode_atts(array(
    ), $atts));

    $out .= '[raw]<div class="tab-holder shortcode-tabs">[/raw]';

	$out .= '[raw]<div class="tab-hold tabs-wrapper">[/raw]';
	
	$out .= '<ul id="tabs" class="tabset tabs">';
	foreach ($atts as $key => $tab) {
		$out .= '<li><a href="#' . $key . '">' . $tab . '</a></li>';
	}
	$out .= '</ul>';
	
	$out .= '<div class="tab-box tabs-container">';

	$out .= do_shortcode($content) .'[raw]</div></div></div>[/raw]';
	
	return $out;
}

add_shortcode('tab', 'shortcode_tab');
	function shortcode_tab( $atts, $content = null ) {
	extract(shortcode_atts(array(
    ), $atts));
	
	$out .= '[raw]<div id="tab' . $atts['id'] . '" class="tab tab_content">[/raw]' . do_shortcode($content) .'</div>';
	
	return $out;
}

//////////////////////////////////////////////////////////////////
// Accordian
//////////////////////////////////////////////////////////////////
add_shortcode('accordian', 'shortcode_accordian');
	function shortcode_accordian( $atts, $content = null ) {
	$out .= '<div class="accordian">';
	$out .= do_shortcode($content);
	$out .= '</div>';
	
   return $out;
}	

//////////////////////////////////////////////////////////////////
// Toggle shortcode
//////////////////////////////////////////////////////////////////
add_shortcode('toggle', 'shortcode_toggle');
	function shortcode_toggle( $atts, $content = null ) {
	extract(shortcode_atts(array(
        'title'      => '',
        'open' => 'no'
    ), $atts));

	if($open == 'yes'){
		$toggleclass = "active";
		$toggleclass2 = "default-open";
		$togglestyle = "display: block;";
	}

	$out .= '<h5 class="toggle '.$toggleclass.'"><a href="#"><span class="arrow"></span>' .$title. '</a></h5>';
	$out .= '<div class="toggle-content '.$toggleclass2.'" style="'.$togglestyle.'">';
	$out .= do_shortcode($content);
	$out .= '</div>';
	
   return $out;
}
	
//////////////////////////////////////////////////////////////////
// Column one_half shortcode
//////////////////////////////////////////////////////////////////
add_shortcode('one_half', 'shortcode_one_half');
	function shortcode_one_half($atts, $content = null) {
		$atts = shortcode_atts(
			array(
				'last' => 'no',
			), $atts);
			
			if($atts['last'] == 'yes') {
				return '<div class="one_half last">' .do_shortcode($content). '</div><div class="clearboth"></div>';
			} else {
				return '<div class="one_half">' .do_shortcode($content). '</div>';
			}

	}
	
//////////////////////////////////////////////////////////////////
// Column one_third shortcode
//////////////////////////////////////////////////////////////////
add_shortcode('one_third', 'shortcode_one_third');
	function shortcode_one_third($atts, $content = null) {
		$atts = shortcode_atts(
			array(
				'last' => 'no',
			), $atts);
			
			if($atts['last'] == 'yes') {
				return '<div class="one_third last">' .do_shortcode($content). '</div><div class="clearboth"></div>';
			} else {
				return '<div class="one_third">' .do_shortcode($content). '</div>';
			}

	}
	
//////////////////////////////////////////////////////////////////
// Column two_third shortcode
//////////////////////////////////////////////////////////////////
add_shortcode('two_third', 'shortcode_two_third');
	function shortcode_two_third($atts, $content = null) {
		$atts = shortcode_atts(
			array(
				'last' => 'no',
			), $atts);
			
			if($atts['last'] == 'yes') {
				return '<div class="two_third last">' .do_shortcode($content). '</div><div class="clearboth"></div>';
			} else {
				return '<div class="two_third">' .do_shortcode($content). '</div>';
			}

	}
	
//////////////////////////////////////////////////////////////////
// Column one_fourth shortcode
//////////////////////////////////////////////////////////////////
add_shortcode('one_fourth', 'shortcode_one_fourth');
	function shortcode_one_fourth($atts, $content = null) {
		$atts = shortcode_atts(
			array(
				'last' => 'no',
			), $atts);
			
			if($atts['last'] == 'yes') {
				return '<div class="one_fourth last">' .do_shortcode($content). '</div><div class="clearboth"></div>';
			} else {
				return '<div class="one_fourth">' .do_shortcode($content). '</div>';
			}

	}
	
//////////////////////////////////////////////////////////////////
// Column three_fourth shortcode
//////////////////////////////////////////////////////////////////
add_shortcode('three_fourth', 'shortcode_three_fourth');
	function shortcode_three_fourth($atts, $content = null) {
		$atts = shortcode_atts(
			array(
				'last' => 'no',
			), $atts);
			
			if($atts['last'] == 'yes') {
				return '<div class="three_fourth last">' .do_shortcode($content). '</div><div class="clearboth"></div>';
			} else {
				return '<div class="three_fourth">' .do_shortcode($content). '</div>';
			}

	}

//////////////////////////////////////////////////////////////////
// Tagline box shortcode
//////////////////////////////////////////////////////////////////
add_shortcode('tagline_box', 'shortcode_tagline_box');
	function shortcode_tagline_box($atts, $content = null) {
		$str = '';
		$str .= '<section class="reading-box">';
			if($atts['link'] && $atts['button']):
			$str .= '[raw]<a href="'.$atts['link'].'" class="continue button large green">'.$atts['button'].'</a>[/raw]';
			endif;
			if($atts['title']):
			$str .= '[raw]<h2>'.$atts['title'].'</h2>[/raw]';
			endif;
			if($atts['description']):
			$str.= '[raw]<p>'.$atts['description'].'</p>[/raw]';
			endif;
			if($atts['link'] && $atts['button']):
			$str .= '[raw]<a href="'.$atts['link'].'" class="continue mobile-button button large green">'.$atts['button'].'</a>[/raw]';
			endif;
		$str .= '</section>';

		return $str;
	}

//////////////////////////////////////////////////////////////////
// Pricing table
//////////////////////////////////////////////////////////////////
add_shortcode('pricing_table', 'shortcode_pricing_table');
	function shortcode_pricing_table($atts, $content = null) {
		$str = '';
		if($atts['type'] == '2') {
			$type = 'sep';
		} else {
			$type = 'full';
		}
		$str .= '[raw]<div class="'.$type.'-boxed-pricing">[/raw]';
		$str .= do_shortcode($content);
		$str .= '</div><div class="clear"></div>';

		return $str;
	}

//////////////////////////////////////////////////////////////////
// Pricing Column
//////////////////////////////////////////////////////////////////
add_shortcode('pricing_column', 'shortcode_pricing_column');
	function shortcode_pricing_column($atts, $content = null) {
		$str = '<div class="column">';
		$str .= '<ul>';
		if($atts['title']):
		$str .= '<li class="title-row">'.$atts['title'].'</li>';
		endif;
		$str .= do_shortcode($content);
		$str .= '</ul>';
		$str .= '</div>';

		return $str;
	}

//////////////////////////////////////////////////////////////////
// Pricing Row
//////////////////////////////////////////////////////////////////
add_shortcode('pricing_price', 'shortcode_pricing_price');
	function shortcode_pricing_price($atts, $content = null) {
		$str = '';
		$str .= '<li class="pricing-row">';
		if($atts['currency'] && $atts['price']) {
			$str .= '<div class="price">';
				$str .= '<strong>'.$atts['currency'].'</strong>';
				$price = explode('.', $atts['price']);
				$str .= '<em class="exact_price">'.$price[0].'</em>';
				if($price[1]){
					$str .= '<sup>'.$price[1].'</sup>';
				}
				if($atts['time']) {
					$str .= '<em class="time">'.$atts['time'].'</em>';
				}
			$str .= '</div>';
		} else {
			$str .= do_shortcode($content);
		}
		$str .= '</li>';

		return $str;
	}

//////////////////////////////////////////////////////////////////
// Pricing Row
//////////////////////////////////////////////////////////////////
add_shortcode('pricing_row', 'shortcode_pricing_row');
	function shortcode_pricing_row($atts, $content = null) {
		$str = '';
		$str .= '<li class="normal-row">';
		$str .= do_shortcode($content);
		$str .= '</li>';

		return $str;
	}

//////////////////////////////////////////////////////////////////
// Pricing Footer
//////////////////////////////////////////////////////////////////
add_shortcode('pricing_footer', 'shortcode_pricing_footer');
	function shortcode_pricing_footer($atts, $content = null) {
		$str = '';
		$str .= '<li class="footer-row">';
		$str .= do_shortcode($content);
		$str .= '</li>';

		return $str;
	}

//////////////////////////////////////////////////////////////////
// Content box shortcode
//////////////////////////////////////////////////////////////////
add_shortcode('content_boxes', 'shortcode_content_boxes');
	function shortcode_content_boxes($atts, $content = null) {
		$str = '';
		$str .= '<section class="columns content-boxes">';
		$str .= do_shortcode($content);
		$str .= '</section>';

		return $str;
	}

//////////////////////////////////////////////////////////////////
// Content box shortcode
//////////////////////////////////////////////////////////////////
add_shortcode('content_box', 'shortcode_content_box');
	function shortcode_content_box($atts, $content = null) {
		$str = '';
		if($atts['last'] == 'yes'):
		$str .= '<article class="col last">';
		else:
		$str .= '<article class="col">';
		endif;

		if($atts['image'] || $atts['title']):
		$str .=	'<div class="heading">';
		if($atts['image']):
		$str .= '[raw]<img src="'.$atts['image'].'" width="35" height="35" alt="">[/raw]';
		endif;
		if($atts['icon']):
			$str .= '[raw]'.do_shortcode('[fontawesome icon="'.$atts['icon'].'" circle="yes" size="medium"]').'[/raw]';
		endif;
		if($atts['title']):
		$str .= '[raw]<h2>'.$atts['title'].'</h2>[/raw]';
		endif;
		$str .= '</div>';
		endif;

		$str .= do_shortcode($content);
		
		if($atts['link'] && $atts['linktext']):
		$str .= '[raw]<span class="more"><a href="'.$atts['link'].'">'.$atts['linktext'].'</a></span>[/raw]';
		endif;
		
		$str .= '</article>';

		return $str;
	}

//////////////////////////////////////////////////////////////////
// Slider
//////////////////////////////////////////////////////////////////
add_shortcode('slider', 'shortcode_slider');
	function shortcode_slider($atts, $content = null) {
		$str = '';
		$str .= '<div class="flexslider">';
		$str .= '<ul class="slides">';
		$str .= do_shortcode($content);
		$str .= '</ul>';
		$str .= '</div>';

		return $str;
	}

//////////////////////////////////////////////////////////////////
// Slide
//////////////////////////////////////////////////////////////////
add_shortcode('slide', 'shortcode_slide');
	function shortcode_slide($atts, $content = null) {
		$str = '';
		if($atts['type'] == 'video') {
			$str .= '<li class="video">';
		} else {
			$str .= '<li class="image">';
		}
		if($atts['link']):
		$str .= '<a href="'.$atts['link'].'">';
		endif;
		if($atts['type'] == 'video') {
			$str .= $content;
		} else {
			$str .= '<img src="'.$content.'" alt="" />';
		}
		if($atts['link']):
		$str .= '</a>';
		endif;
		$str .= '</li>';

		return $str;
	}

//////////////////////////////////////////////////////////////////
// Testimonials
//////////////////////////////////////////////////////////////////
add_shortcode('testimonials', 'shortcode_testimonials');
	function shortcode_testimonials($atts, $content = null) {
		$str = '';
		$str .= '<div class="reviews">';
		$str .= do_shortcode($content);
		$str .= '</div>';

		return $str;
	}

//////////////////////////////////////////////////////////////////
// Testimonial
//////////////////////////////////////////////////////////////////
add_shortcode('testimonial', 'shortcode_testimonial');
	function shortcode_testimonial($atts, $content = null) {
		if($atts['gender'] != 'male' && $atts['gender'] != 'female') {
			$atts['gender'] = 'male';
		}
		$str = '';
		$str .= '<div class="review '.$atts['gender'].'">';
		$str .= '<blockquote>';
		$str .= '[raw]<q>[/raw]';
		$str .= do_shortcode('[raw]'.$content.'[/raw]');
		$str .= '[raw]</q>[/raw]';
		if($atts['name']):
			$str .= '[raw]<div><span class="company-name">[/raw]';
			$str .= '[raw]<strong>'.$atts['name'].'</strong>[/raw]';
			if($atts['company']):
				$str .= '[raw]<span>, '.$atts['company'].'</span>[/raw]';
			endif;
			$str .= '[raw]</span></div>[/raw]';
		endif;
		$str .= '</blockquote>';
		$str .= '</div>';

		return $str;
	}

	
//////////////////////////////////////////////////////////////////
// Progess Bar
//////////////////////////////////////////////////////////////////
add_shortcode('progress', 'shortcode_progress');
function shortcode_progress($atts, $content = null) {
	$html = '';
	$html .= '<div class="progress-bar">';
	$html .= '<div class="progress-bar-content" data-percentage="'.$atts['percentage'].'" style="width: ' . $atts['percentage'] . '%">';
	$html .= '</div>';
	$html .= '[raw]<span class="progress-title">' . $content . ' ' . $atts['percentage'] . '%</span>[/raw]';
	$html .= '</div>';

	return $html;
}

//////////////////////////////////////////////////////////////////
// Person
//////////////////////////////////////////////////////////////////
add_shortcode('person', 'shortcode_person');
function shortcode_person($atts, $content = null) {
	$html = '';
	$html .= '<div class="person">';
	$html .= '<img class="person-img" src="' . $atts['picture'] . '" alt="' . $atts['name'] . '" />';
	if($atts['name'] || $atts['title'] || $atts['facebooklink'] || $atts['twitterlink'] || $atts['linkedinlink'] || $content) {
		$html .= '<div class="person-desc">';
			$html .= '<div class="person-author clearfix">';
				$html .= '<div class="person-author-wrapper"><span class="person-name">' . $atts['name'] . '</span>';
				$html .= '<span class="person-title">' . $atts['title'] . '</span></div>';
				if($atts['facebook']) {
					$html .= '[raw]<span class="social-icon"><a href="' . $atts['facebook'] . '" class="facebook">Facebook</a><div class="popup">
						<div class="holder">
							<p>Facebook</p>
						</div>
					</div></span>[/raw]';
				}
				if($atts['twitter']) {
					$html .= '[raw]<span class="social-icon"><a href="' . $atts['twitter'] . '" class="twitter">Twitter</a><div class="popup">
						<div class="holder">
							<p>Twitter</p>
						</div>
					</div></span>[/raw]';
				}
				if($atts['linkedin']) {
					$html .= '[raw]<span class="social-icon"><a href="' . $atts['linkedin'] . '" class="linkedin">LinkedIn</a><div class="popup">
						<div class="holder">
							<p>LinkedIn</p>
						</div>
					</div></span>[/raw]';
				}
				if($atts['dribbble']) {
					$html .= '[raw]<span class="social-icon"><a href="' . $atts['dribbble'] . '" class="dribbble">Dribbble</a><div class="popup">
						<div class="holder">
							<p>Dribbble</p>
						</div>
					</div></span>[/raw]';
				}
			$html .= '<div class="clear"></div></div>';
			$html .= '<div class="person-content">' . $content . '</div>';
		$html .= '</div>';
	}
	$html .= '</div>';

	return $html;
}

//////////////////////////////////////////////////////////////////
// Recent Posts
//////////////////////////////////////////////////////////////////
add_shortcode('recent_posts', 'shortcode_recent_posts');
function shortcode_recent_posts($atts, $content = null) {
	global $data;

	if(!$atts['columns']) {
		$atts['columns'] = 3;
	}

	if(!$atts['number_excerpts']) {
		$atts['number_excerpts'] = 15;
	}

	if(!$atts['number_posts']) {
		$atts['number_posts'] = 3;
	}

	$attachment = '';
	$html = '<div class="avada-container">';
	$html .= '<section class="columns columns-'.$atts['columns'].'" style="width:100%">';
	$html .= '<div class="holder">';
	if($atts['cat_id']){
		$recent_posts = new WP_Query('showposts='.$atts['number_posts'].'&category_name='.$atts['cat_id']);
	} else {
		$recent_posts = new WP_Query('showposts='.$atts['number_posts']);
	}
	$count = 1;
	while($recent_posts->have_posts()): $recent_posts->the_post();
	$html .= '<article class="col">';
	if($atts['thumbnail'] == "yes"):
	if($data['legacy_posts_slideshow']):
	$args = array(
	    'post_type' => 'attachment',
	    'numberposts' => $data['posts_slideshow_number'],
	    'post_status' => null,
	    'post_mime_type' => 'image',
	    'post_parent' => get_the_ID(),
		'orderby' => 'menu_order',
		'order' => 'ASC',
		'exclude' => get_post_thumbnail_id()
	);
	$attachments = get_posts($args);
	if($attachments || has_post_thumbnail() || get_post_meta(get_the_ID(), 'pyre_video', true)):
	$html .= '<div class="flexslider floated-post-slideshow">';
		$html .= '<ul class="slides">';
			if($data['posts_slideshow']):
			if(get_post_meta(get_the_ID(), 'pyre_video', true)):
			$html .= '<li class="video">';
				$html .= get_post_meta(get_the_ID(), 'pyre_video', true);
			$html .= '</li>';
			endif;
			endif;
			if(has_post_thumbnail()):
			$attachment_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'recent-posts');
			$full_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
			$attachment_data = wp_get_attachment_metadata(get_post_thumbnail_id());
			$html .= '[raw]<li>
				<a href="'.get_permalink(get_the_ID()).'" rel=""><img src="'.$attachment_image[0].'" alt="'.get_the_title().'" /></a>
			</li>[/raw]';
			endif;
			if($data['posts_slideshow']):
			foreach($attachments as $attachment):
			$attachment_image = wp_get_attachment_image_src($attachment->ID, 'recent-posts');
			$full_image = wp_get_attachment_image_src($attachment->ID, 'full');
			$attachment_data = wp_get_attachment_metadata($attachment->ID);
			$html .= '[raw]<li>
				<a href="'.get_permalink(get_the_ID()).'" rel=""><img src="'. $attachment_image[0].'" alt="'.$attachment->post_title.'" /></a>
			</li>[/raw]';
			endforeach;
			endif;
		$html .= '[raw]</ul>
	</div>[/raw]';
	endif;
	else:
	if(kd_mfi_get_featured_image_id('featured-image-2', 'post') || kd_mfi_get_featured_image_id('featured-image-3', 'post') || kd_mfi_get_featured_image_id('featured-image-4', 'post') || kd_mfi_get_featured_image_id('featured-image-5', 'post') || has_post_thumbnail() || get_post_meta(get_the_ID(), 'pyre_video', true)):
	$html .= '<div class="flexslider floated-post-slideshow">';
		$html .= '<ul class="slides">';
			if($data['posts_slideshow']):
			if(get_post_meta(get_the_ID(), 'pyre_video', true)):
			$html .= '<li class="video">';
				$html .= get_post_meta(get_the_ID(), 'pyre_video', true);
			$html .= '</li>';
			endif;
			endif;
			if(has_post_thumbnail()):
			$attachment_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'recent-posts');
			$full_image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
			$attachment_data = wp_get_attachment_metadata(get_post_thumbnail_id());
			$html .= '[raw]<li>
				<a href="'.get_permalink(get_the_ID()).'" rel=""><img src="'.$attachment_image[0].'" alt="'.get_the_title().'" /></a>
			</li>[/raw]';
			endif;
			if($data['posts_slideshow']):
			$i = 2;
			while($i <= $data['posts_slideshow_number']):
			$attachment->ID = kd_mfi_get_featured_image_id('featured-image-'.$i, 'post');
			if($attachment->ID):
			$attachment_image = wp_get_attachment_image_src($attachment->ID, 'recent-posts');
			$full_image = wp_get_attachment_image_src($attachment->ID, 'full');
			$attachment_data = wp_get_attachment_metadata($attachment->ID);
			$html .= '[raw]<li>
				<a href="'.get_permalink(get_the_ID()).'" rel=""><img src="'. $attachment_image[0].'" alt="'.$attachment->post_title.'" /></a>
			</li>[/raw]';
			endif; $i++; endwhile;
			endif;
		$html .= '[raw]</ul>
	</div>[/raw]';
	endif;
	endif;
	endif;
	if($atts['title'] == "yes"):
	$html .= '<h3><a href="'.get_permalink(get_the_ID()).'">'.get_the_title().'</a></h3>';
	endif;
	if($atts['meta'] == "yes"):
	$html .= '<ul class="meta">';
	$html .= '<li><em class="date">'.get_the_time('F j, Y', get_the_ID()).'</em></li>';
	if(get_comments_number(get_the_ID()) >= 1):
	$html .= '<li>'.get_comments_number(get_the_ID()).' Comments</li>';
	endif;
	$html .= '</ul>';
	endif;
	if($atts['excerpt'] == "yes"):
	$html .= '<p>'.string_limit_words(get_the_excerpt(), $atts['number_excerpts']).'</p>';
	endif;
	$html .= '</article>';
	$count++;
	endwhile;
	$html .= '</div>';
	$html .= '</section>';
	$html .= '</div>';

	return $html;
}

//////////////////////////////////////////////////////////////////
// Recent Works
//////////////////////////////////////////////////////////////////
add_shortcode('recent_works', 'shortcode_recent_works');
function shortcode_recent_works($atts, $content = null) {
	$html = '';
	$html .= '<div class="related-posts related-projects">';
	$html .= '<div id="carousel" class="es-carousel-wrapper">';
	$html .= '<div class="es-carousel">';
	$html .= '<ul class="">';
					$args = array(
						'post_type' => 'avada_portfolio',
						'paged' => 1,
						'posts_per_page' => 10,
					);
					if($atts['cat_id']){
						$cat_id = explode(',', $atts['cat_id']);
						$args['tax_query'] = array(
							array(
								'taxonomy' => 'portfolio_category',
								'field' => 'slug',
								'terms' => $cat_id
							)
						);
					}
					$works = new WP_Query($args);
					while($works->have_posts()): $works->the_post();
					if(has_post_thumbnail()):
					$html .= '<li>';
						$html .= '<div class="image">';
								$html .= get_the_post_thumbnail(get_the_ID(), 'related-img');
								$html .= '<div class="image-extras">';
									$html .= '<div class="image-extras-content">';
									$html .= '<a class="icon" style="margin-right:3px;" href="'.get_permalink(get_the_ID()).'">';
										$html .= '<img src="'.get_bloginfo('template_directory').'/images/link-ico.png" alt="'.get_the_title().'"/>';
									$html .= '</a>';
									$full_image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
									if(get_post_meta($post->ID, 'pyre_video_url', true)) {
										$full_image[0] = get_post_meta($post->ID, 'pyre_video_url', true);
									}
									$html .= '<a class="icon" href="'.$full_image[0].'" rel="prettyPhoto[gallery]"><img src="'.get_bloginfo('template_directory').'/images/finder-ico.png" alt="'.get_the_title().'" /></a>';
									$html .= '<h3>'.get_the_title().'</h3>';
									$html .= '[raw]</div>
								</div>
						</div>
					</li>[/raw]';
					endif; endwhile;
				$html .= '[raw]</ul>
			</div>
		</div>
	</div>[/raw]';

	return $html;
}


//////////////////////////////////////////////////////////////////
// Alert Message
//////////////////////////////////////////////////////////////////
add_shortcode('alert', 'shortcode_alert');
function shortcode_alert($atts, $content = null) {
	$html = '';
	$html .= '[raw]<div class="alert '.$atts['type'].'">[/raw]';
		$html .= '[raw]<div class="msg">'.do_shortcode($content).'</div>[/raw]';
		$html .= '[raw]<a href="#" class="toggle-alert">Toggle</a>[/raw]';
	$html .= '[raw]</div>[/raw]';

	return $html;
}

//////////////////////////////////////////////////////////////////
// FontAwesome Icons
//////////////////////////////////////////////////////////////////
add_shortcode('fontawesome', 'shortcode_fontawesome');
function shortcode_fontawesome($atts, $content = null) {
	$html .= '<i class="fontawesome-icon '.$atts['size'].' circle-'.$atts['circle'].' icon-'.$atts['icon'].'"></i>';

	return $html;
}

//////////////////////////////////////////////////////////////////
// Social Links
//////////////////////////////////////////////////////////////////
add_shortcode('social_links', 'shortcode_social_links');
function shortcode_social_links($atts, $content = null) {
	$html = '<div class="social_links_shortcode">';
	$html .= '<ul class="social">';
	foreach($atts as $key => $link) {
		if($link) {
			if($key == 'youtube' || $key == 'pinterest' || $key == 'digg' || $key == 'flickr' ||
				$key == 'forrst' || $key == 'myspace' || $key == 'skype' || $key == 'dribbble') {
				$html .= '[raw]<li>
				<a class="custom" href="'.$link.'"><img src="'.get_bloginfo('template_directory').'/images/'.$key.'.png" alt="" /></a>
				<div class="popup">
					<div class="holder">
						<p>'.ucwords($key).'</p>
					</div>
				</div>
				</li>[/raw]';
			} else {
				$html .= '[raw]<li>
					<a class="'.$key.'" href="'.$link.'">'.$key.'</a>
					<div class="popup">
						<div class="holder">
							<p>'.$key.'</p>
						</div>
					</div>
				</li>[/raw]';
			}
		}
	}
	$html .= '</ul>';
	$html .= '</div>';

	return $html;
}

//////////////////////////////////////////////////////////////////
// Clients container
//////////////////////////////////////////////////////////////////
add_shortcode('clients', 'shortcode_clients');
function shortcode_clients($atts, $content = null) {
	$html = '[raw]<div class="related-posts related-projects"><div id="carousel" class="clients-carousel es-carousel-wrapper"><div class="es-carousel"><ul>[/raw]';
	$html .= do_shortcode($content);
	$html .= '[raw]</ul></div></div></div>[/raw]';
	return $html;
}

//////////////////////////////////////////////////////////////////
// Client
//////////////////////////////////////////////////////////////////
add_shortcode('client', 'shortcode_client');
function shortcode_client($atts, $content = null) {
	$html = '[raw]<li>[/raw]';
	$html .= '[raw]<a href="'.$atts['link'].'"><img src="'.$atts['image'].'" alt="" /></a>[/raw]';
	$html .= '[raw]</li>[/raw]';
	return $html;
}

//////////////////////////////////////////////////////////////////
// Title
//////////////////////////////////////////////////////////////////
add_shortcode('title', 'shortcode_title');
function shortcode_title($atts, $content = null) {
	$html .= '<div class="title"><h'.$atts['size'].'>'.do_shortcode($content).'</h'.$atts['size'].'></div>';
	return $html;
}

//////////////////////////////////////////////////////////////////
// Separator
//////////////////////////////////////////////////////////////////
add_shortcode('separator', 'shortcode_separator');
function shortcode_separator($atts, $content = null) {
	$html .= '<div class="demo-sep" style="margin-top: '.$atts['top'].'px;"></div>';
	return $html;
}

//////////////////////////////////////////////////////////////////
// Add buttons to tinyMCE
//////////////////////////////////////////////////////////////////
add_action('init', 'add_button');

function add_button() {  
   if ( current_user_can('edit_posts') &&  current_user_can('edit_pages') )  
   {  
     add_filter('mce_external_plugins', 'add_plugin');  
     add_filter('mce_buttons_3', 'register_button');  
   }  
}  

function register_button($buttons) {  
   array_push($buttons, "youtube", "vimeo", "soundcloud", "button", "dropcap", "highlight", "checklist", "tabs", "toggle", "one_half", "one_third", "two_third", "one_fourth", "three_fourth", "slider", "testimonial", "progress", "person", "alert", "pricing_table", "recent_works", "tagline_box", "content_boxes", "recent_posts", "fontawesome", "social_links", "clients", "title", "separatoor");  
   return $buttons;  
}  

function add_plugin($plugin_array) {  
   $plugin_array['youtube'] = get_template_directory_uri().'/tinymce/customcodes.js';
   $plugin_array['vimeo'] = get_template_directory_uri().'/tinymce/customcodes.js';
   $plugin_array['soundcloud'] = get_template_directory_uri().'/tinymce/customcodes.js';
   $plugin_array['button'] = get_template_directory_uri().'/tinymce/customcodes.js';
   $plugin_array['dropcap'] = get_template_directory_uri().'/tinymce/customcodes.js';
   $plugin_array['highlight'] = get_template_directory_uri().'/tinymce/customcodes.js';
   $plugin_array['checklist'] = get_template_directory_uri().'/tinymce/customcodes.js';
   $plugin_array['tabs'] = get_template_directory_uri().'/tinymce/customcodes.js';
   $plugin_array['toggle'] = get_template_directory_uri().'/tinymce/customcodes.js';
   $plugin_array['one_half'] = get_template_directory_uri().'/tinymce/customcodes.js';
   $plugin_array['one_third'] = get_template_directory_uri().'//tinymce/customcodes.js';
   $plugin_array['two_third'] = get_template_directory_uri().'/tinymce/customcodes.js';
   $plugin_array['one_fourth'] = get_template_directory_uri().'/tinymce/customcodes.js';
   $plugin_array['three_fourth'] = get_template_directory_uri().'/tinymce/customcodes.js';
   $plugin_array['slider'] = get_template_directory_uri().'/tinymce/customcodes.js';
   $plugin_array['testimonial'] = get_template_directory_uri().'/tinymce/customcodes.js';
   $plugin_array['progress'] = get_template_directory_uri().'/tinymce/customcodes.js';
   $plugin_array['person'] = get_template_directory_uri().'/tinymce/customcodes.js';
   $plugin_array['alert'] = get_template_directory_uri().'/tinymce/customcodes.js';
   $plugin_array['pricing_table'] = get_template_directory_uri().'/tinymce/customcodes.js';
   $plugin_array['recent_works'] = get_template_directory_uri().'/tinymce/customcodes.js';
   $plugin_array['tagline_box'] = get_template_directory_uri().'/tinymce/customcodes.js';
   $plugin_array['content_boxes'] = get_template_directory_uri().'/tinymce/customcodes.js';
   $plugin_array['recent_posts'] = get_template_directory_uri().'/tinymce/customcodes.js';
   $plugin_array['fontawesome'] = get_template_directory_uri().'/tinymce/customcodes.js';
   $plugin_array['social_links'] = get_template_directory_uri().'/tinymce/customcodes.js';
   $plugin_array['clients'] = get_template_directory_uri().'/tinymce/customcodes.js';
   $plugin_array['title'] = get_template_directory_uri().'/tinymce/customcodes.js';
   $plugin_array['separatoor'] = get_template_directory_uri().'/tinymce/customcodes.js';

   return $plugin_array;  
}