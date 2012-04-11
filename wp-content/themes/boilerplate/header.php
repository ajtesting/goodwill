<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Boilerplate
 * @since Boilerplate 1.0
 */
?><!DOCTYPE html>
<!--[if lt IE 7 ]><html <?php language_attributes(); ?> class="no-js ie ie6 lte7 lte8 lte9"><![endif]-->
<!--[if IE 7 ]><html <?php language_attributes(); ?> class="no-js ie ie7 lte7 lte8 lte9"><![endif]-->
<!--[if IE 8 ]><html <?php language_attributes(); ?> class="no-js ie ie8 lte8 lte9"><![endif]-->
<!--[if IE 9 ]><html <?php language_attributes(); ?> class="no-js ie ie9 lte9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<title><?php
			/*
			 * Print the <title> tag based on what is being viewed.
			 * We filter the output of wp_title() a bit -- see
			 * boilerplate_filter_wp_title() in functions.php.
			 */
			wp_title( '|', true, 'right' );
		?></title>
		<link rel="profile" href="http://gmpg.org/xfn/11" />
		<!-- <link href='http://fonts.googleapis.com/css?family=Nobile:400,700,400italic,700italic' rel='stylesheet' type='text/css'> -->
		<link href='http://fonts.googleapis.com/css?family=Istok+Web:400,400italic,700,700italic' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
		<link rel="stylesheet" href="/wp-content/themes/boilerplate/css/main.css" />
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
		<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
<?php
		/* We add some JavaScript to pages with the comment form
		 * to support sites with threaded comments (when in use).
		 */
		if ( is_singular() && get_option( 'thread_comments' ) )
			wp_enqueue_script( 'comment-reply' );

		/* Always have wp_head() just before the closing </head>
		 * tag of your theme, or you will break many plugins, which
		 * generally use this hook to add elements to <head> such
		 * as styles, scripts, and meta tags.
		 */
		wp_head();
?>
	</head>
	<body <?php body_class(); ?>>
		<header role="banner">
			<div id="header-inner">
				<a id="logo" href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"></a>
				<div id="search-social">
					<div id="search-top">
						<div id="search">
							<?php get_search_form(); ?>
						</div>
						<div id="location">
							<a id="findtoggle" href="#">Find a Location</a>
							<div id="findlocation">
								<a href="/shop/locations">Retail Store</a>
								<a href="/donate/donation-centers">Donation Location</a>
								<a href="/mission-in-action/find-a-career-center/">Career Center</a>
							</div>
							<script type="text/javascript">
								$(document).ready(function() {
									$('a#findtoggle').click(function() {
										$('#findlocation').slideToggle();
									});
								});
							</script>
						</div>
					</div>
					<div id="social-top">
						<span>Connect With Us:</span>
						<a id="fb-icon" href="https://www.facebook.com/mers.goodwill" target="_blank"></a>
						<a id="twit-icon" href="https://twitter.com/#!/mersgoodwill" target="_blank"></a>
						<a id="rss-icon" href="/feed"></a>
					</div>
				</div>
				<nav id="access" role="navigation">
					<?php /* Our navigation menu.  If one isn't filled out, wp_nav_menu falls back to wp_page_menu.  The menu assiged to the primary position is the one used.  If none is assigned, the menu with the lowest ID is used.  */ ?>
					<?php wp_nav_menu( array( 'container_class' => 'menu-header', 'theme_location' => 'primary' ) ); ?>
				</nav><!-- #access -->
			</div>
		</header>
		
		<section id="content" role="main">
