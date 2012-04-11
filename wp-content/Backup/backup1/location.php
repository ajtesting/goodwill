<?php
/**
 * Template Name: Location
 * 
 */

get_header(); ?>
<link rel="stylesheet" href="/wp-content/themes/boilerplate/css/page.css" />

<div id="page-wrap">
	<div id="page-content">
		<div id="left-col">
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
				<?php } ?>
					<div class="entry-content">
						<?php the_content(); ?>
						<?php wp_link_pages( array( 'before' => '' . __( 'Pages:', 'boilerplate' ), 'after' => '' ) ); ?>
						<?php edit_post_link( __( 'Edit', 'boilerplate' ), '', '' ); ?>
					</div><!-- .entry-content -->
				</article><!-- #post-## -->
				<?php endwhile; ?>
		</div>
	</div>
	
</div>

<?php get_footer(); ?>