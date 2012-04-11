<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content
 * after.  Calls sidebar-footer.php for bottom widgets.
 *
 * @package WordPress
 * @subpackage Boilerplate
 * @since Boilerplate 1.0
 */
?>
		</section><!-- #main -->
		<link rel="stylesheet" href="/wp-content/themes/boilerplate/css/footer.css" />
		<footer role="contentinfo">
			
			<div id = "footer-wrapper">
				<?php
				/* A sidebar in the footer? Yep. You can can customize
				 * your footer with four columns of widgets.
				 */
				get_sidebar( 'footer' );
				?>
			</div>
		</footer>
		<?php 
		   /* Always have wp_footer() just before the closing </body>
			* tag of your theme, or you will break many plugins, which
			* generally use this hook to reference JavaScript files.
			*/
			wp_footer();
		?>
		<script type="text/javascript">
			$(document).ready(function () {
				$('ul#menu-top_nav li a:first-child').hover(function() {
					$('ul#menu-top_nav li ul.sub-menu').hide();
					children = $(this).parents('li').find('ul.sub-menu');
					
					children.show();
					
				});
			});
			$(document).mousemove(function(e){
				//console.log( $(e.target).parents('ul#menu-top_nav').length );
				if($(e.target).parents('ul#menu-top_nav').length == 0){
					$('ul#menu-top_nav li ul.sub-menu').hide();
				}
				//console.log(mloc.length);
			});
		</script>
	</body>
</html>