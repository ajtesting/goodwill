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
				//Menu stuff
				$('ul#menu-top_nav li a:first-child').hover(function() {
					$('ul#menu-top_nav li ul.sub-menu').hide();
					children = $(this).parents('li').find('ul.sub-menu');
					
					children.show();
					
				});
				//Constant Contact Quicky
				var default_val = "Your Email Address";
				var previous_val;
				var eobj;
				var error_span = $('#con-contact span#con-errors');
				$('#con-contact input#emailaddy').focus(function() {
					email_addy = $(this);
					email_addy_val = email_addy.val();
					email_addy.css({"background-color": "#ffffff"});
					if(email_addy_val == "Your Email Address" || email_addy_val == "") {
						previous_val = email_addy_val;
						email_addy.val("");
					}
					
					email_addy.blur(function() {
						var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;

						if ( email_addy_val == "" || !emailReg.test(email_addy_val) ) {
							//something was entered already - revert to that
							if( previous_val != default_val ) {
								email_addy.val(previous_val);
							} else {
								email_addy.val(default_val);
							}
							
							email_addy.css({"background-color": "#fbbbbb"});
							error_span.show();
						} else {
							error_span.hide();
						}
					});
				});
				
				//Contact Page
				if($('body').hasClass('page-template-contact-php')) {
					var default_email = "VCAROTHERS@MERSGOODWILL.ORG";
					var new_email = "";
					$('select#field_category').change(function () {
						var selected = $(this).find(':selected').val();
						selected = selected.replace(/(^\s*)|(\s*$)/gi,"");
						selected = selected.replace(/[ ]{2,}/gi," ");
						selected = selected.replace(/\n /,"\n");
						
						switch (selected) {
							case "CARF":
								new_email = "jcartnal@mersgoodwill.org";
								break;
											
							case "Assembly and Packaging Services":
								new_email = "dgore@mersgoodwill.org";
								break;
								
							case "Auto Donations":
								new_email = "rvetter@mersgoodwill.org";
								break;
								
							case "Custodial and Business Services":
								new_email = "kevers@mersgoodwill.org";
								break;
								
							case "Daycare Services":
								new_email = "bkeno@mersgoodwill.org";
								break;
								
							case "Donations":
								new_email = "klance@mersgoodwill.org";
								break;
								
							case "Health & Safety":
								new_email = "dkutchback@mersgoodwill.org";
								break;
								
							case "Home Pick-Up Services":
								new_email = "ssummers@mersgoodwill.org";
								break;
								
							case "Human Resources":
								new_email = "pwhite@mersgoodwill.org";
								break;
								
							case "Programs and Vocational Services":
								new_email = "hwagner@mersgoodwill.org";
								break;
								
							case "Retail Questions and Suggestions":
								new_email = "klance@mersgoodwill.org";
								break;
								
							case "Request a Quote for Business Services":
								new_email = "kevers@mersgoodwill.org";
								break;
								
							case "Other":
								new_email = default_email;
								break;
								
							default:
								new_email = default_email;
								break;
						}
						
						$('input#field_rcvr_email').val(new_email);
						
					});
				}
				
				$('#main-wrap #main-content #main-left').click(function() {
					console.log('here');
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