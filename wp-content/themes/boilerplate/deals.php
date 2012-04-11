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
 * Template Name: Deals
 */

get_header();


?>
<link rel="stylesheet" href="/wp-content/themes/boilerplate/css/deals.css" />

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
				    	<input id="emailaddy" type="text" name="ea" value="Your Email Address" >
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
					    <?php
							$custom_fields = get_post_custom(44);
							if ($custom_fields) {
    							
    							$deals = explode(",", $custom_fields['current-deals-list'][0]);
    							$iter = 0;
    							$today = date('n/j/Y');
    							foreach ( $deals as $deal ) {
    								if( strtotime($deal) == strtotime($today) ) {
    									if($deals[$iter + 1] == "no sale"){
    										//set show on homepage false
    										update_post_meta(44, 'current-deal', 'Everyday Low Prices!');
    										update_post_meta(44, 'show-on-homepage', 'true');
    									} else {
    										//set show on homepage true
    										update_post_meta(44, 'current-deal', ucwords($deals[$iter + 1]));
    										update_post_meta(44, 'show-on-homepage', 'true');
    									}
    								}
    								
    								$iter++;
    							}
    							$lineheight = 109;
    							
    							if(strlen($custom_fields['current-deal'][0]) > 24){
    								$lineheight = 45;
    							}
    							echo "<ul id='current-deal'><h1 style='line-height: " . $lineheight . "px;'>" . addslashes($custom_fields['current-deal'][0]) . "</h1></ul>";
							} 
						?> 

					    <?php edit_post_link( __( 'Edit', 'boilerplate' ), '', '' ); ?>
					</div><!-- .entry-content -->
								</article><!-- #post-## -->
				
				
				
				<?php endwhile; ?>
		
		</div>
	</div>
	
</div>

<?php get_footer(); ?>