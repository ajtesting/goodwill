<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package WordPress
 * @subpackage Boilerplate
 * @since Boilerplate 1.0
 */

get_header(); ?>
<link rel="stylesheet" href="/wp-content/themes/boilerplate/css/home.css" />
<?
				
				$media_items = get_attachments_by_media_tags('media_tags=search-banner&numberposts=1&size=full&return_type=li');
				if ($media_items) {
				    echo "<ul id='ban-ad'>". $media_items. "</ul>";
				} else {
				    //echo '<img id="banner-ad" src="/wp-content/themes/boilerplate/images/interior-banner.png" />';
				}
?>
<div id="main-wrap">
	<div id="main-content">
		<div id="main-left-search">
			<?php if ( have_posts() ) : ?>
				<h1><?php printf( __( 'Search Results for: %s', 'boilerplate' ), '' . get_search_query() . '' ); ?></h1>
				<?php
				/* Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called loop-search.php and that will be used instead.
				 */
				 get_template_part( 'loop', 'search' );
				?>
			<?php else : ?>
				<h2><?php _e( 'Nothing Found', 'boilerplate' ); ?></h2>
				<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'boilerplate' ); ?></p>
				<?php get_search_form(); ?>
			<?php endif; ?>
		</div>
		<div id="main-right">
			<?php get_sidebar(); ?>
		</div>
	</div>
</div>






<?php get_footer(); ?>
