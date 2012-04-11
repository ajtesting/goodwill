<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the wordpress construct of pages
 * and that other 'pages' on your wordpress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Boilerplate
 * @since Boilerplate 1.0
 */

get_header(); ?>
<link rel="stylesheet" href="/wp-content/themes/boilerplate/css/page.css" />

<div id="page-wrap">
	<div id="page-content">
		<div id="left-col">
			<?php get_sidebar(); ?>
			<div id="read-more-posts"><a href="/posts">See Full Blog...</a></div>
			<?php
			// A second sidebar for widgets, just because.
			if ( is_active_sidebar( 'secondary-widget-area' ) ) : ?>

			<ul class="xoxo">
				<?php dynamic_sidebar( 'secondary-widget-area' ); ?>
			</ul>

			<?php endif; ?>
			<span id="side-nav-footer"></span>
			<div id="signup">
			<?php
				echo(FrmAppController::get_form_shortcode(array('id' => '7', 'key' => '', 'title' => false, 'description' => false, 'readonly' => false, 'entry_id' => false)));
			?>
			</div>
		</div>
		<div id="right-col">
		
		<h1><?php
					printf( __( 'Category Archives: %s', 'boilerplate' ), '' . single_cat_title( '', false ) . '' );
				?></h1>
				<?php
					$category_description = category_description();
					if ( ! empty( $category_description ) )
						echo '' . $category_description . '';

				/* Run the loop for the category page to output the posts.
				 * If you want to overload this in a child theme then include a file
				 * called loop-category.php and that will be used instead.
				 */
				get_template_part( 'loop', 'category' );
				?>
		
			
		</div>
	</div>
	
</div>

<?php get_footer(); ?>