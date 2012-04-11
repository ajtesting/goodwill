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
			<div id="read-more-posts"><a href="/posts">See Full Blog…</a></div>
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
			
			<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<?php if ( is_front_page() ) { ?>
				<h2 class="entry-title"><?php the_title(); ?></h2>
			<?php } else { ?>	
				<h1 class="entry-title"><?php the_title(); ?></h1>
			<?php }
				rdfa_breadcrumb(array('separator' => " -> "));
				$post_obj = $wp_query->get_queried_object();
				$post_ID = $post_obj->ID;
				$post_title = $post_obj->post_title;
				$post_name = $post_obj->post_name;
				
			?>
					
					<div class="entry-content">
					    <?php the_content(); ?>
					    <?php wp_link_pages( array( 'before' => '' . __( 'Pages:', 'boilerplate' ), 'after' => '' ) ); ?>
					    <?php edit_post_link( __( 'Edit', 'boilerplate' ), '', '' ); ?>
					</div><!-- .entry-content -->
				</article><!-- #post-## -->
				
				<!--<nav id="nav-below" class="navigation">
					<div class="nav-previous"><?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'boilerplate' ) . '</span> %title' ); ?></div>
					<div class="nav-next"><?php next_post_link( '%link', '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'boilerplate' ) . '</span>' ); ?></div>
				</nav>--><!-- #nav-below -->
				<?php comments_template( '', true ); ?>

				
			<?php endwhile; ?>
		</div>
	</div>
	
</div>

<?php get_footer(); ?>