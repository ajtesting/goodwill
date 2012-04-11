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
 * Template Name: Page with Footer
 */

get_header(); ?>
<link rel="stylesheet" href="/wp-content/themes/boilerplate/css/page.css" />
<link rel="stylesheet" href="/wp-content/themes/boilerplate/css/page-w-footer.css" />

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
				<!-- BEGIN: Constant Contact Email Newsletter Form -->
				<div id="con-contact">
				    <form name="ccoptin" action="http://visitor.r20.constantcontact.com/d.jsp"target="_blank" method="post">
				    	<input id="emailaddy" type="text" name="ea" value="Email Address" >
				    	<!-- <input type="submit" name="go" value="SUBSCRIBE" class="submit">-->
				    	<input type="image" src="/wp-content/themes/boilerplate/images/clr-button.png" alt="Submit"  class="submit"  value="" />
				    	<input type="hidden" name="llr" value="hh8gtsdab">
				    	<input type="hidden" name="m" value="1103356980455">
				    	<input type="hidden" name="p" value="oi">
				    </form>
				    <span id="con-errors">You must enter a Valid Email Address.</span>
				</div>
				<!-- END: Constant Contact Email Newsletter Form -->
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
				$media_items = get_attachments_by_media_tags('media_tags=' . $post_name . '-banner&numberposts=1&size=full&return_type=li');
				if ($media_items) {
				    echo "<ul id='ban-ad'>". $media_items. "</ul>";
				} else {
				    echo '<img id="banner-ad" src="/wp-content/themes/boilerplate/images/interior-banner.png" />';
				}
			?>
					
					<div class="entry-content">
					    <?php the_content(); ?>
					    <?php wp_link_pages( array( 'before' => '' . __( 'Pages:', 'boilerplate' ), 'after' => '' ) ); ?>
					    <?php edit_post_link( __( 'Edit', 'boilerplate' ), '', '' ); ?>
					</div><!-- .entry-content -->
				</article><!-- #post-## -->
			<?php endwhile; ?>
		</div>
	</div>
	<div id="partners">
		<div id="partners-left">
			<a target="_blank" href="https://www.stl.unitedway.org/"></a>
			<a target="_blank" href="http://reconnectpartnership.com/"></a>
			<a target="_blank" href="http://www.dhs.state.il.us/page.aspx?"></a>
			<a target="_blank" href="http://dese.mo.gov/vr/vocrehab.htm"></a>
			<a target="_blank" href="http://www.plboard.com/infobase/default.asp"></a>
			<a target="_blank" href="http://dss.mo.gov/"></a>
		</div>
		<div id="partners-right">
			<a target="_blank" href="http://www2.va.gov/directory/guide/state.asp?STATE=MO"></a>
			<a target="_blank" href="http://www.mrdd.org/"></a>
			<a target="_blank" href="http://www.mocadsv.org/"></a>
			<a target="_blank" href="http://dmh.mo.gov/"></a>
			<a target="_blank" href="http://www.ddrb.org/"></a>
			<a target="_blank" href="http://www.jewishinstlouis.org/JFed.aspx"></a>
		</div>
	</div>	
</div>

<?php get_footer(); ?>