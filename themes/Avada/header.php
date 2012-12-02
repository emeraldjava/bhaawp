<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="google-site-verification" content="OngMyA7rGzuKbMMkPl3_M_lgjB_GNCwmL6bPqNa4W4A" />
	<title><?php bloginfo('name'); ?> <?php wp_title(' - ', true, 'left'); ?></title>

	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" />
	
	<!--[if lt IE 9]>
	<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/respond.min.js"></script>
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/ie.css" />
	<![endif]-->
	<?php global $data; ?>
	<?php if($data['responsive']): ?>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/media.css" />
	<?php endif; ?>

	<?php if($data['google_body'] && $data['google_body'] != 'Select Font'): ?>
	<link href='http://fonts.googleapis.com/css?family=<?php echo urlencode($data['google_body']); ?>:400,400italic,700,700italic&amp;subset=latin,greek-ext,cyrillic,latin-ext,greek,cyrillic-ext,vietnamese' rel='stylesheet' type='text/css' />
	<?php endif; ?>

	<?php if($data['google_nav'] && $data['google_nav'] != 'Select Font'): ?>
	<link href='http://fonts.googleapis.com/css?family=<?php echo urlencode($data['google_nav']); ?>:400,400italic,700,700italic&amp;subset=latin,greek-ext,cyrillic,latin-ext,greek,cyrillic-ext,vietnamese' rel='stylesheet' type='text/css' />
	<?php endif; ?>

	<?php if($data['google_headings'] && $data['google_headings'] != 'Select Font'): ?>
	<link href='http://fonts.googleapis.com/css?family=<?php echo urlencode($data['google_headings']); ?>:400,400italic,700,700italic&amp;subset=latin,greek-ext,cyrillic,latin-ext,greek,cyrillic-ext,vietnamese' rel='stylesheet' type='text/css' />
	<?php endif; ?>

	<?php if($data['google_footer_headings'] && $data['google_footer_headings'] != 'Select Font'): ?>
	<link href='http://fonts.googleapis.com/css?family=<?php echo urlencode($data['google_footer_headings']); ?>:400,400italic,700,700italic&amp;subset=latin,greek-ext,cyrillic,latin-ext,greek,cyrillic-ext,vietnamese' rel='stylesheet' type='text/css' />
	<?php endif; ?>

	<?php if($data['favicon']): ?>
	<link rel="shortcut icon" href="<?php echo of_get_option('favicon'); ?>" type="image/x-icon" />
	<?php endif; ?>
	
	<?php wp_head(); ?>

	<!--[if IE 8]>
	<script type="text/javascript">
	jQuery(document).ready(function() {
	var imgs, i, w;
	var imgs = document.getElementsByTagName( 'img' );
	for( i = 0; i < imgs.length; i++ ) {
	    w = imgs[i].getAttribute( 'width' );
	    if ( 615 < w ) {
	        imgs[i].removeAttribute( 'width' );
	        imgs[i].removeAttribute( 'height' );
	    }
	}
	});
	</script>
	<![endif]-->
	
	<script type="text/javascript">
	jQuery(window).load(function() {
		jQuery('.flexslider').flexslider();

		jQuery('.video, .wooslider .slide-content, .video-shortcode').fitVids();

		if(jQuery('.fullwidthbanner-container').length >=1 && jQuery('.tp-bannershadow').length == 0) {
			jQuery('<div class="shadow-left">').appendTo('.rev_slider_wrapper');
			jQuery('<div class="shadow-right">').appendTo('.rev_slider_wrapper');

			jQuery('.fullwidthbanner-container').addClass('avada-skin-rev');
		}
	});
	jQuery(document).ready(function($) {
		function onAfter(curr, next, opts, fwd) {
		  var $ht = $(this).height();

		  //set the container's height to that of the current slide
		  $(this).parent().animate({height: $ht});
		}

	    $('.reviews').cycle({
			fx: 'fade',
			after: onAfter,
			timeout: <?php echo $data['testimonials_speed']; ?>
		});

		<?php if($data['image_rollover']): ?>
		/*$('.image').live('mouseenter', function(e) {
			if(!$(this).hasClass('slided')) {
				$(this).find('.image-extras').show().stop(true, true).animate({opacity: '1', left: '0'}, 400);
				$(this).addClass('slided');
			} else {
				$(this).find('.image-extras').stop(true, true).fadeIn('normal');
			}
		});
		$('.image-extras').mouseleave(function(e) {
			$(this).fadeOut('normal');
		});*/
		<?php endif; ?>

		var ppArgs = {
			animation_speed: '<?php echo strtolower($data["lightbox_animation_speed"]); ?>',
			overlay_gallery: <?php if($data["lightbox_gallery"]) { echo 'true'; } else { echo 'false'; } ?>,
			autoplay_slideshow: <?php if($data["lightbox_autoplay"]) { echo 'true'; } else { echo 'false'; } ?>,
			slideshow: <?php echo $data['lightbox_slideshow_speed']; ?>,
			opacity: <?php echo $data['lightbox_opacity']; ?>,
			show_title: <?php if($data["lightbox_title"]) { echo 'true'; } else { echo 'false'; } ?>,
			<?php if(!$data["lightbox_social"]) { echo 'social_tools: "",'; } ?>
		};

		$("a[rel^='prettyPhoto']").prettyPhoto(ppArgs);

		<?php if($data['lightbox_post_images']): ?>
		$('.single-post .post-content a').has('img').prettyPhoto(ppArgs);
		<?php endif; ?>

		var mediaQuery = 'desk';

		if (Modernizr.mq('only screen and (max-width: 600px)') || Modernizr.mq('only screen and (max-height: 520px)')) {

			mediaQuery = 'mobile';
			$("a[rel^='prettyPhoto']").unbind('click');
			<?php if($data['lightbox_post_images']): ?>
			$('.single-post .post-content a').has('img').unbind('click');
			<?php endif; ?>
		} 

		// Disables prettyPhoto if screen small
		$(window).resize(function() {
			if ((Modernizr.mq('only screen and (max-width: 600px)') || Modernizr.mq('only screen and (max-height: 520px)')) && mediaQuery == 'desk') {
				$("a[rel^='prettyPhoto']").unbind('click.prettyphoto');
				<?php if($data['lightbox_post_images']): ?>
				$('.single-post .post-content a').has('img').unbind('click.prettyphoto');
				<?php endif; ?>
				mediaQuery = 'mobile';
			} else if (!Modernizr.mq('only screen and (max-width: 600px)') && !Modernizr.mq('only screen and (max-height: 520px)') && mediaQuery == 'mobile') {
				$("a[rel^='prettyPhoto']").prettyPhoto(ppArgs);
				<?php if($data['lightbox_post_images']): ?>
				$('.single-post .post-content a').has('img').prettyPhoto(ppArgs);
				<?php endif; ?>
				mediaQuery = 'desk';
			}
		});
	});
	</script>

	<style type="text/css">
	<?php if($data['primary_color']): ?>
	a:hover,
	#nav ul .current_page_item a, #nav ul .current-menu-item a, #nav ul > .current-menu-parent a,
	.footer-area ul li a:hover,
	.side-nav li.current_page_item a,
	.portfolio-tabs li.active a, .faq-tabs li.active a,
	.project-content .project-info .project-info-box a:hover,
	.about-author .title a,
	span.dropcap,.footer-area a:hover,.copyright a:hover,
	#sidebar .widget_categories li a:hover,
	#main .post h2 a:hover{
		color:<?php echo $data['primary_color']; ?> !important;
	}
	#nav ul .current_page_item a, #nav ul .current-menu-item a, #nav ul > .current-menu-parent a,
	#nav ul ul,#nav li.current-menu-ancestor a,
	.reading-box,
	.portfolio-tabs li.active a, .faq-tabs li.active a,
	.tab-holder .tabs li.active a,
	.post-content blockquote,
	.progress-bar-content,
	.pagination .current,
	.pagination a.inactive:hover{
		border-color:<?php echo $data['primary_color']; ?> !important;
	}
	.side-nav li.current_page_item a{
		border-right-color:<?php echo $data['primary_color']; ?> !important;	
	}
	h5.toggle.active span.arrow,
	.post-content ul.arrow li::before,
	.progress-bar-content,
	.pagination .current{
		background-color:<?php echo $data['primary_color']; ?> !important;
	}
	<?php endif; ?>

	<?php if($data['pricing_box_color']): ?>
	.sep-boxed-pricing ul li.title-row{
		background-color:<?php echo $data['pricing_box_color']; ?> !important;
		border-color:<?php echo $data['pricing_box_color']; ?> !important;
	}
	<?php endif; ?>
	<?php if($data['image_gradient_top_color'] && $data['image_gradient_bottom_color']): ?>
	.image .image-extras{
		background-image: linear-gradient(top, <?php echo $data['image_gradient_top_color']; ?> 0%, <?php echo $data['image_gradient_bottom_color']; ?> 100%);
		background-image: -o-linear-gradient(top, <?php echo $data['image_gradient_top_color']; ?> 0%, <?php echo $data['image_gradient_bottom_color']; ?> 100%);
		background-image: -moz-linear-gradient(top, <?php echo $data['image_gradient_top_color']; ?> 0%, <?php echo $data['image_gradient_bottom_color']; ?> 100%);
		background-image: -webkit-linear-gradient(top, <?php echo $data['image_gradient_top_color']; ?> 0%, <?php echo $data['image_gradient_bottom_color']; ?> 100%);
		background-image: -ms-linear-gradient(top, <?php echo $data['image_gradient_top_color']; ?> 0%, <?php echo $data['image_gradient_bottom_color']; ?> 100%);

		background-image: -webkit-gradient(
			linear,
			left top,
			left bottom,
			color-stop(0, <?php echo $data['image_gradient_top_color']; ?>),
			color-stop(1, <?php echo $data['image_gradient_bottom_color']; ?>)
		);
	}
	.no-cssgradients .image .image-extras{
		background:<?php echo $data['image_gradient_top_color']; ?>;
	}
	<?php endif; ?>
	<?php if($data['button_gradient_top_color'] && $data['button_gradient_bottom_color'] && $data['button_gradient_text_color']): ?>
	#main .reading-box .button,
	#main .continue.button,
	#main .portfolio-one .button,
	#main .comment-submit{
		color: <?php echo $data['button_gradient_text_color']; ?> !important;
		background-image: linear-gradient(top, <?php echo $data['button_gradient_top_color']; ?> 0%, <?php echo $data['button_gradient_bottom_color']; ?> 100%);
		background-image: -o-linear-gradient(top, <?php echo $data['button_gradient_top_color']; ?> 0%, <?php echo $data['button_gradient_bottom_color']; ?> 100%);
		background-image: -moz-linear-gradient(top, <?php echo $data['button_gradient_top_color']; ?> 0%, <?php echo $data['button_gradient_bottom_color']; ?> 100%);
		background-image: -webkit-linear-gradient(top, <?php echo $data['button_gradient_top_color']; ?> 0%, <?php echo $data['button_gradient_bottom_color']; ?> 100%);
		background-image: -ms-linear-gradient(top, <?php echo $data['button_gradient_top_color']; ?> 0%, <?php echo $data['button_gradient_bottom_color']; ?> 100%);

		background-image: -webkit-gradient(
			linear,
			left top,
			left bottom,
			color-stop(0, <?php echo $data['button_gradient_top_color']; ?>),
			color-stop(1, <?php echo $data['button_gradient_bottom_color']; ?>)
		);
		border:1px solid <?php echo $data['button_gradient_bottom_color']; ?>;
	}
	.no-cssgradients #main .reading-box .button,
	.no-cssgradients #main .continue.button,
	.no-cssgradients #main .portfolio-one .button,
	.no-cssgradients #main .comment-submit{
		background:<?php echo $data['button_gradient_top_color']; ?>;
	}
	#main .reading-box .button:hover,
	#main .continue.button:hover,
	#main .portfolio-one .button:hover,
	#main .comment-submit:hover{
		color: <?php echo $data['button_gradient_text_color']; ?> !important;
		background-image: linear-gradient(top, <?php echo $data['button_gradient_bottom_color']; ?> 0%, <?php echo $data['button_gradient_top_color']; ?> 100%);
		background-image: -o-linear-gradient(top, <?php echo $data['button_gradient_bottom_color']; ?> 0%, <?php echo $data['button_gradient_top_color']; ?> 100%);
		background-image: -moz-linear-gradient(top, <?php echo $data['button_gradient_bottom_color']; ?> 0%, <?php echo $data['button_gradient_top_color']; ?> 100%);
		background-image: -webkit-linear-gradient(top, <?php echo $data['button_gradient_bottom_color']; ?> 0%, <?php echo $data['button_gradient_top_color']; ?> 100%);
		background-image: -ms-linear-gradient(top, <?php echo $data['button_gradient_bottom_color']; ?> 0%, <?php echo $data['button_gradient_top_color']; ?> 100%);

		background-image: -webkit-gradient(
			linear,
			left top,
			left bottom,
			color-stop(0, <?php echo $data['button_gradient_bottom_color']; ?>),
			color-stop(1, <?php echo $data['button_gradient_top_color']; ?>)
		);
		border:1px solid <?php echo $data['button_gradient_bottom_color']; ?>;
	}
	.no-cssgradients #main .reading-box .button:hover,
	.no-cssgradients #main .continue.button:hover,
	.no-cssgradients #main .portfolio-one .button:hover,
	.no-cssgradients #main .comment-submit:hover{
		background:<?php echo $data['button_gradient_bottom_color']; ?>;
	}
	<?php endif; ?>

	<?php if($data['layout'] == 'Boxed'): ?>
	body{
		<?php if(get_post_meta($post->ID, 'pyre_page_bg_color', true)): ?>
		background-color:<?php echo get_post_meta($post->ID, 'pyre_page_bg_color', true); ?>;
		<?php else: ?>
		background-color:<?php echo $data['bg_color']; ?>;
		<?php endif; ?>

		<?php if(get_post_meta($post->ID, 'pyre_page_bg', true)): ?>
		background-image:url(<?php echo get_post_meta($post->ID, 'pyre_page_bg', true); ?>);
		background-repeat:<?php echo get_post_meta($post->ID, 'pyre_page_bg_repeat', true); ?>;
			<?php if(get_post_meta($post->ID, 'pyre_page_bg_full', true) == 'yes'): ?>
			background-attachment:fixed;
			background-position:center center;
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
			<?php endif; ?>
		<?php elseif($data['bg_image']): ?>
		background-image:url(<?php echo $data['bg_image']; ?>);
		background-repeat:<?php echo $data['bg_repeat']; ?>;
			<?php if($data['bg_full']): ?>
			background-attachment:fixed;
			background-position:center center;
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
			<?php endif; ?>
		<?php endif; ?>

		<?php if($data['bg_pattern_option'] && $data['bg_pattern'] && !(get_post_meta($post->ID, 'pyre_page_bg_color', true) || get_post_meta($post->ID, 'pyre_page_bg', true))): ?>
		background-image:url("<?php echo get_bloginfo('template_directory') . '/images/patterns/' . $data['bg_pattern'] . '.png'; ?>");
		background-repeat:repeat;
		<?php endif; ?>
	}
	#wrapper{
		background:#fff;
		width:1000px;
		margin:0 auto;
	}
	#layerslider-container{
		overflow:hidden;
	}
	<?php endif; ?>

	<?php if(get_post_meta($post->ID, 'pyre_page_title_bar_bg', true)): ?>
	.page-title-container{
		background-image:url(<?php echo get_post_meta($post->ID, 'pyre_page_title_bar_bg', true); ?>) !important;
	}
	<?php elseif($data['page_title_bg']): ?>
	.page-title-container{
		background-image:url(<?php echo $data['page_title_bg']; ?>) !important;
	}
	<?php endif; ?>

	<?php
	if(
		$data['custom_font_woff'] && $data['custom_font_ttf'] &&
		$data['custom_font_svg'] && $data['custom_font_eot']
	):
	?>
	@font-face {
		font-family: 'MuseoSlab500Regular';
		src: url('<?php echo $data['custom_font_eot']; ?>');
		src: url('<?php echo $data['custom_font_eot']; ?>?#iefix') format('embedded-opentype'),
			url('<?php echo $data['custom_font_svg']; ?>#MuseoSlab500Regular') format('svg'),
			url('<?php echo $data['custom_font_woff']; ?>') format('woff'),
			url('<?php echo $data['custom_font_ttf']; ?>') format('truetype');
		font-weight: normal;
		font-style: normal;
	}
	<?php $custom_font = true; endif; ?>

	<?php
	if($data['google_body'] != 'Select Font') {
		$font = '"'.$data['google_body'].'", Arial, Helvetica, sans-serif !important';
	} elseif($data['standard_body'] != 'Select Font') {
		$font = $data['standard_body'].' !important';
	}
	?>

	body,#nav ul li ul li a,
	.more,
	.avada-container h3,
	.meta .date,
	.review blockquote q,
	.review blockquote div strong,
	.image .image-extras .image-extras-content h4,
	.project-content .project-info h4,
	.post-content blockquote,
	.button.large,
	.button.small{
		font-family:<?php echo $font; ?>;
	}
	.avada-container h3,
	.review blockquote div strong,
	.footer-area  h3,
	.button.large,
	.button.small{
		font-weight:bold;
	}
	.meta .date,
	.review blockquote q,
	.post-content blockquote{
		font-style:italic;
	}

	<?php
	if(!$custom_font && $data['google_nav'] != 'Select Font') {
		$nav_font = '"'.$data['google_nav'].'", Arial, Helvetica, sans-serif !important';
	} elseif(!$custom_font && $data['standard_nav'] != 'Select Font') {
		$nav_font = $data['standard_nav'].' !important';
	}
	if($nav_font):
	?>

	#nav,
	.side-nav li a{
		font-family:<?php echo $nav_font; ?>;
	}
	<?php endif; ?>

	<?php
	if(!$custom_font && $data['google_headings'] != 'Select Font') {
		$headings_font = '"'.$data['google_headings'].'", Arial, Helvetica, sans-serif !important';
	} elseif(!$custom_font && $data['standard_headings'] != 'Select Font') {
		$headings_font = $data['standard_headings'].' !important';
	}
	if($headings_font):
	?>

	#main .reading-box h2,
	#main h2,
	.page-title h1,
	.image .image-extras .image-extras-content h3,
	#main .post h2,
	#sidebar .widget h3,
	.tab-holder .tabs li a,
	.share-box h4,
	.project-content h3,
	h5.toggle a,
	.full-boxed-pricing ul li.title-row,
	.full-boxed-pricing ul li.pricing-row,
	.sep-boxed-pricing ul li.title-row,
	.sep-boxed-pricing ul li.pricing-row,
	.person-author-wrapper,
	.post-content h1, .post-content h2, .post-content h3, .post-content h4, .post-content h5, .post-content h6{
		font-family:<?php echo $headings_font; ?>;
	}
	<?php endif; ?>

	<?php
	if($data['google_footer_headings'] != 'Select Font') {
		$font = '"'.$data['google_footer_headings'].'", Arial, Helvetica, sans-serif !important';
	} elseif($data['standard_footer_headings'] != 'Select Font') {
		$font = $data['standard_footer_headings'].' !important';
	}
	?>

	.footer-area  h3{
		font-family:<?php echo $font; ?>;
	}

	<?php if($data['body_font_size']): ?>
	body{
		font-size:<?php echo $data['body_font_size']; ?>px;
		<?php
		$line_height = round((1.5 * $data['body_font_size']), 0, PHP_ROUND_HALF_UP);
		?>
		line-height:<?php echo $line_height; ?>px;
	}
	.project-content .project-info h4{
		font-size:<?php echo $data['body_font_size']; ?>px !important;
		<?php
		$line_height = round((1.5 * $data['body_font_size']), 0, PHP_ROUND_HALF_UP);
		?>
		line-height:<?php echo $line_height; ?>px !important;
	}
	<?php endif; ?>

	<?php if($data['nav_font_size']): ?>
	#nav{font-size:<?php echo $data['nav_font_size']; ?>px !important;}
	<?php endif; ?>

	<?php if($data['breadcrumbs_font_size']): ?>
	.page-title ul li,page-title ul li a{font-size:<?php echo $data['breadcrumbs_font_size']; ?>px !important;}
	<?php endif; ?>

	<?php if($data['side_nav_font_size']): ?>
	.side-nav li a{font-size:<?php echo $data['side_nav_font_size']; ?>px !important;}
	<?php endif; ?>

	<?php if($data['sidew_font_size']): ?>
	#sidebar .widget h3{font-size:<?php echo $data['sidew_font_size']; ?>px !important;}
	<?php endif; ?>

	<?php if($data['footw_font_size']): ?>
	.footer-area h3{font-size:<?php echo $data['footw_font_size']; ?>px !important;}
	<?php endif; ?>

	<?php if($data['copyright_font_size']): ?>
	.copyright{font-size:<?php echo $data['copyright_font_size']; ?>px !important;}
	<?php endif; ?>

	<?php if($data['responsive']): ?>
	#header .avada-row, #main .avada-row, .footer-area .avada-row, #footer .avada-row{ max-width:940px; }
	<?php endif; ?>

	<?php if($data['h1_font_size']): ?>
	.post-content h1{
		font-size:<?php echo $data['h1_font_size']; ?>px !important;
		<?php
		$line_height = round((1.5 * $data['h1_font_size']), 0, PHP_ROUND_HALF_UP);
		?>
		line-height:<?php echo $line_height; ?>px !important;
	}
	<?php endif; ?>

	<?php if($data['h2_font_size']): ?>
	.post-content h2,.title h2,#main .post-content .title h2,.page-title h1,#main .post h2 a{
		font-size:<?php echo $data['h2_font_size']; ?>px !important;
		<?php
		$line_height = round((1.5 * $data['h2_font_size']), 0, PHP_ROUND_HALF_UP);
		?>
		line-height:<?php echo $line_height; ?>px !important;
	}
	<?php endif; ?>

	<?php if($data['h3_font_size']): ?>
	.post-content h3,.project-content h3{
		font-size:<?php echo $data['h3_font_size']; ?>px !important;
		<?php
		$line_height = round((1.5 * $data['h3_font_size']), 0, PHP_ROUND_HALF_UP);
		?>
		line-height:<?php echo $line_height; ?>px !important;
	}
	<?php endif; ?>

	<?php if($data['h4_font_size']): ?>
	.post-content h4{
		font-size:<?php echo $data['h4_font_size']; ?>px !important;
		<?php
		$line_height = round((1.5 * $data['h4_font_size']), 0, PHP_ROUND_HALF_UP);
		?>
		line-height:<?php echo $line_height; ?>px !important;
	}
	h5.toggle a,.tab-holder .tabs li a,.share-box h4,.person-author-wrapper{
		font-size:<?php echo $data['h4_font_size']; ?>px !important;
	}
	<?php endif; ?>

	<?php if($data['h5_font_size']): ?>
	.post-content h5{
		font-size:<?php echo $data['h5_font_size']; ?>px !important;
		<?php
		$line_height = round((1.5 * $data['h5_font_size']), 0, PHP_ROUND_HALF_UP);
		?>
		line-height:<?php echo $line_height; ?>px !important;
	}
	<?php endif; ?>

	<?php if($data['h6_font_size']): ?>
	.post-content h6{
		font-size:<?php echo $data['h6_font_size']; ?>px !important;
		<?php
		$line_height = round((1.5 * $data['h6_font_size']), 0, PHP_ROUND_HALF_UP);
		?>
		line-height:<?php echo $line_height; ?>px !important;
	}
	<?php endif; ?>

	<?php if($data['body_text_color']): ?>
	body,.post .post-content,.post-content blockquote,.tab-holder .news-list li .post-holder .meta,#sidebar #jtwt,.meta,.review blockquote div,.search input,.project-content .project-info h4{color:<?php echo $data['body_text_color']; ?> !important;}
	<?php endif; ?>

	<?php if($data['headings_color']): ?>
	.post-content h1, .post-content h2, .post-content h3,
	.post-content h4, .post-content h5, .post-content h6,
	#sidebar .widget h3,h5.toggle a, .tab-holder .tabs li a,
	.page-title h1,.full-boxed-pricing ul li.title-row,
	.image .image-extras .image-extras-content h3,.project-content .project-info h4,.project-content h3,.share-box h4,.title h2,.person-author-wrapper,#sidebar .tab-holder .tabs li a{
		color:<?php echo $data['headings_color']; ?> !important;
	}
	<?php endif; ?>

	<?php if($data['link_color']): ?>
	body a,.project-content .project-info .project-info-box a,#sidebar .widget li a, #sidebar .widget .recentcomments, #sidebar .widget_categories li, #main .post h2 a{color:<?php echo $data['link_color']; ?> !important;}
	<?php endif; ?>

	<?php if($data['breadcrumbs_text_color']): ?>
	.page-title ul li,.page-title ul li a{color:<?php echo $data['breadcrumbs_text_color']; ?> !important;}
	<?php endif; ?>

	<?php if($data['footer_headings_color']): ?>
	.footer-area h3{color:<?php echo $data['footer_headings_color']; ?> !important;}
	<?php endif; ?>

	<?php if($data['footer_text_color']): ?>
	.footer-area,.footer-area #jtwt,.copyright{color:<?php echo $data['footer_text_color']; ?> !important;}
	<?php endif; ?>

	<?php if($data['footer_link_color']): ?>
	.footer-area a,.copyright a{color:<?php echo $data['footer_link_color']; ?> !important;}
	<?php endif; ?>

	<?php if($data['menu_first_color']): ?>
	#nav ul a,.side-nav li a{color:<?php echo $data['menu_first_color']; ?> !important;}
	<?php endif; ?>

	<?php if($data['menu_sub_bg_color']): ?>
	#nav ul ul{background-color:<?php echo $data['menu_sub_bg_color']; ?>;}
	<?php endif; ?>

	<?php if($data['menu_sub_color']): ?>
	#wrapper #nav ul li ul li a,.side-nav li li a,.side-nav li.current_page_item li a{color:<?php echo $data['menu_sub_color']; ?> !important;}
	<?php endif; ?>

	<?php if(get_post_meta($post->ID, 'pyre_fimg_width')): ?>
	.post-slideshow,.post-slideshow img{width:<?php echo get_post_meta($post->ID, 'pyre_fimg_width', true); ?> !important;}
	<?php endif; ?>

	<?php if(get_post_meta($post->ID, 'pyre_fimg_height')): ?>
	.post-slideshow,.post-slideshow img{height:<?php echo get_post_meta($post->ID, 'pyre_fimg_height', true); ?> !important;}
	<?php endif; ?>

	<?php if(!$data['flexslider_circles']): ?>
	.main-flex .flex-control-nav{display:none !important;}
	<?php endif; ?>
	
	<?php if(!$data['breadcrumb_mobile']): ?>
	@media only screen and (max-width: 940px){
		.breadcrumbs{display:none !important;}
	}
	@media only screen and (min-device-width: 768px) and (max-device-width: 1024px) and (orientation: portrait){
		.breadcrumbs{display:none !important;}
	}
	<?php endif; ?>

	<?php echo $data['custom_css']; ?>
	</style>

	<style type="text/css" id="ss">
	</style>

	<link rel="stylesheet" id="style_selector_ss" href="" />

	<?php echo $data['space_head']; ?>
</head>
<body <?php body_class($class); ?>>
	<div id="wrapper">
	<header id="header">
		<div class="avada-row" style="padding-top:<?php echo $data['margin_header_top']; ?>; padding-bottom:<?php echo $data['margin_header_bottom']; ?>;">
			<div class="logo" style="margin-left:<?php echo $data['margin_logo_left']; ?>;"><a href="<?php bloginfo('siteurl'); ?>"><img src="<?php echo $data['logo']; ?>" alt="<?php bloginfo('name'); ?>" /></a></div>
			<nav id="nav" class="nav-holder">
				<?php wp_nav_menu(array('theme_location' => 'main_navigation', 'depth' => 4, 'container' => false, 'menu_id' => 'nav')); ?>
			</nav>
		</div>
	</header>
	<div id="sliders-container">
	<?php
	// Layer Slider
	$slider_page_id = $post->ID;
	if(is_home() && !is_front_page()){
		$slider_page_id = get_option('page_for_posts');
	}
	if(get_post_meta($slider_page_id, 'pyre_slider_type', true) == 'layer' && (get_post_meta($slider_page_id, 'pyre_slider', true) || get_post_meta($slider_page_id, 'pyre_slider', true) != 0)): ?>
	<?php
	$slides = get_option('layerslider-slides');
	$slides = is_array($slides) ? $slides : unserialize($slides);
	$slides = $slides[(get_post_meta($slider_page_id, 'pyre_slider', true)-1)];
	?>
	<style type="text/css">
	#layerslider-container{max-width:<?php echo layerslider_check_unit($slides['properties']['width']); ?>;}
	</style>
	<div id="layerslider-container">
		<div id="layerslider-wrapper">
		<?php if($slides['properties']['skin'] == 'avada'): ?>
		<div class="ls-shadow-top"></div>
		<?php endif; ?>
		<?php echo do_shortcode('[layerslider id="'.get_post_meta($slider_page_id, 'pyre_slider', true).'"]'); ?>
		<?php if($slides['properties']['skin'] == 'avada'): ?>
		<div class="ls-shadow-bottom"></div>
		<?php endif; ?>
		</div>
	</div>
	<?php endif; ?>
	<?php
	// Flex Slider
	if(get_post_meta($slider_page_id, 'pyre_slider_type', true) == 'flex' && (get_post_meta($slider_page_id, 'pyre_wooslider', true) || get_post_meta($slider_page_id, 'pyre_wooslider', true) != 0)) {
		echo do_shortcode('[wooslider slide_page="'.get_post_meta($slider_page_id, 'pyre_wooslider', true).'" slider_type="slides" limit="5"]');
	}
	?>
	<?php
	if(get_post_meta($slider_page_id, 'pyre_slider_type', true) == 'rev' && get_post_meta($slider_page_id, 'pyre_revslider', true)) {
		putRevSlider(get_post_meta($slider_page_id, 'pyre_revslider', true));
	}
	?>
	<?php
	if(get_post_meta($slider_page_id, 'pyre_slider_type', true) == 'flex2' && get_post_meta($slider_page_id, 'pyre_flexslider', true)) {
		include_once('flexslider.php');
	}
	?>
	</div>
	<?php if(get_post_meta($slider_page_id, 'pyre_fallback', true)): ?>
	<style type="text/css">
	@media only screen and (max-width: 940px){
		#sliders-container{display:none;}
		#fallback-slide{display:block;}
	}
	@media only screen and (min-device-width: 768px) and (max-device-width: 1024px) and (orientation: portrait){
		#sliders-container{display:none;}
		#fallback-slide{display:block;}
	}
	</style>
	<div id="fallback-slide">
		<img src="<?php echo get_post_meta($slider_page_id, 'pyre_fallback', true); ?>" alt="" />
	</div>
	<?php endif; ?>
	<?php if($data['page_title_bar']): ?>
	<?php if(((is_page() || is_single() || is_singular('avada_portfolio')) && get_post_meta($post->ID, 'pyre_page_title', true) == 'yes') && !is_front_page()): ?>
	<div class="page-title-container">
		<div class="page-title">
			<h1><?php the_title(); ?></h1>
			<?php if($data['breadcrumb']): ?>
			<?php if($data['page_title_bar_bs'] == 'Breadcrumbs'): ?>
			<?php kriesi_breadcrumb(); ?>
			<?php else: ?>
			<?php get_search_form(); ?>
			<?php endif; ?>
			<?php endif; ?>
		</div>
	</div>
	<?php endif; ?>
	<?php if(is_home() && !is_front_page() && get_post_meta($slider_page_id, 'pyre_page_title', true) == 'yes'): ?>
	<div class="page-title-container">
		<div class="page-title">
			<h1><?php echo $data['blog_title']; ?></h1>
			<?php if($data['breadcrumb']): ?>
			<?php if($data['page_title_bar_bs'] == 'Breadcrumbs'): ?>
			<?php kriesi_breadcrumb(); ?>
			<?php else: ?>
			<?php get_search_form(); ?>
			<?php endif; ?>
			<?php endif; ?>
		</div>
	</div>
	<?php endif; ?>
	<?php if(is_search()): ?>
	<div class="page-title-container">
		<div class="page-title">
			<h1><?php echo __('Search results for:', 'Avada'); ?> <?php echo get_search_query(); ?></h1>
		</div>
	</div>
	<?php endif; ?>
	<?php if(is_404()): ?>
	<div class="page-title-container">
		<div class="page-title">
			<h1>Error 404 Page</h1>
		</div>
	</div>
	<?php endif; ?>
	<?php if(is_archive()): ?>
	<div class="page-title-container">
		<div class="page-title">
			<h1><?php single_cat_title(); ?></h1>
			<?php if($data['breadcrumb']): ?>
			<?php if($data['page_title_bar_bs'] == 'Breadcrumbs'): ?>
			<?php kriesi_breadcrumb(); ?>
			<?php else: ?>
			<?php get_search_form(); ?>
			<?php endif; ?>
			<?php endif; ?>
		</div>
	</div>
	<?php endif; ?>
	<?php endif; ?>
	<div id="main" style="overflow:hidden !important;">
		<div class="avada-row">