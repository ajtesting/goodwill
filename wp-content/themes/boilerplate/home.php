<?php
/**
 * The template for the Home page.
 *
 * @package WordPress
 * @subpackage Boilerplate
 * @since Boilerplate 1.0
 */
if(!empty($_GET['s']) && $_GET['s'] != ""){
	include_once('../search.php');
}else{
get_header(); ?>
<link rel="stylesheet" href="/wp-content/themes/boilerplate/css/home.css" />
<?php
	$post_obj = $wp_query->get_queried_object();
	$post_ID = $post_obj->ID;
	$post_title = $post_obj->post_title;
	$post_name = $post_obj->post_name;
	$media_items = get_attachments_by_media_tags('media_tags=home-banner&numberposts=1&size=full&return_type=li');
	$custom_fields = get_post_custom(4);
	if ($media_items) {
	    echo "<ul id='ban-ad'><a href='" . $custom_fields['banner-url'][0] . "'>" . $media_items . "</a></ul>";
	} else {
	    echo '<img id="banner-ad" src="/wp-content/themes/boilerplate/images/banner-ad.png" />';
	}
?>
<div id="main-wrap">
	<div id="main-content">
		<div id="main-left">
			<?php
				wp_cycle();
			?>
			<div id="rotate-btns"></div>
			
		</div>
		<div id="main-right">
			<?php get_sidebar(); ?>
			<div id="read-more-posts"><a href="/posts">See Full Blog…</a></div>
		</div>
		<div id="main-bottom">
			<div id="main-bottom-left">
				<?php
					$p = get_post( $page_id = 4 );
					$ptitle = $p->post_title;
				?>
				<article id="post-<?= $p->ID ?>">
					<h2 class="entry-title"><?= $ptitle; ?></h2>
					<div class="entry-content">
				    	<?= $p->post_content ?>
				    	<?php edit_post_link( __( 'Edit', 'boilerplate' ), '', '', $id = 4 ); ?>
				    </div><!-- .entry-content -->
				</article><!-- #post-## -->
			</div>
			<div id="main-bottom-right">
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
				
				<!-- <?php
					//echo(FrmAppController::get_form_shortcode(array('id' => '7', 'key' => '', 'title' => false, 'description' => false, 'readonly' => false, 'entry_id' => false)));
				?> -->
				<!-- END: Above was the Formidible code. --> 
				
				<div id="three-images">
					<a id="three-imgs-bbb" href="http://www.bbb.org/us/charity/" target="_blank">
					</a>
					<a id="three-imgs-carf" href="http://www.carf.org/home/" target="_blank">
					</a>
					<a id="three-imgs-navigator" href="http://www.charitynavigator.org/" target="_blank">
					</a>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$(document).ready(function() {
			var redir = "";
			$('#three-btn #employment').click(function() {
				redir = $('#three-btn #employment a.btn').attr('href');
				window.location.href = redir;
			});
			
			$('#three-btn #shop').click(function() {
				redir = $('#three-btn #shop a.btn').attr('href');
				window.location.href = redir;
			});

			$('#three-btn #youth').click(function() {
				redir = $('#three-btn #youth a.btn').attr('href');
				window.location.href = redir;
			});
		});
	</script>
	<div id="three-btn">
		<!-- First Button -->
		<div id="employment">
		<?php	
			$media_items = get_attachments_by_media_tags('media_tags=home-employment&numberposts=1&return_type=li');
			if ($media_items) {
			    echo "<ul>". $media_items. "</ul>";
			}
		?>
			<a href="<?= $custom_fields['bottom-url1'][0] ?>" class="btn"><?= addslashes($custom_fields['bottom-header1'][0]) ?></a>
			<?php
		    $current_deal = get_post_custom(44);
		    $deals = explode(",", $current_deal['current-deals-list'][0]);
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
		    if ($current_deal) {
		    	if($current_deal['show-on-homepage'][0] == "true") {
    	    		?>
    	    		<span><?= addslashes($custom_fields['bottom-blurb1'][0]) ?><br /><?= addslashes($current_deal['current-deal'][0]) ?></span>
    	    		<?
		    	} else {
		    		?>
		    		<span><?= addslashes($custom_fields['bottom-blurb1'][0]) ?></span>
		    		<?
		    	}
		    } else {
		    	?>
		    	<span><?= addslashes($custom_fields['bottom-blurb1'][0]) ?></span>
		    	<?
		    }
			?>
		</div>
		<!-- Second Button -->
		<div id="shop">
		<?php	
			$media_items2 = get_attachments_by_media_tags('media_tags=home-shop&numberposts=1&return_type=li');
			if ($media_items2) {
			    echo "<ul>". $media_items2. "</ul>";
			}
		?>
			<a href="<?= $custom_fields['bottom-url2'][0] ?>" class="btn"><?= addslashes($custom_fields['bottom-header2'][0]) ?></a>
			<span><?= addslashes($custom_fields['bottom-blurb2'][0]) ?></span>
		</div>
		<!-- Third Button -->
		<div id="youth">
		<?php	
			$media_items3 = get_attachments_by_media_tags('media_tags=home-youth&numberposts=1&return_type=li');
			if ($media_items3) {
			    echo "<ul>". $media_items3. "</ul>";
			}
		?>
			<a href="<?= $custom_fields['bottom-url3'][0] ?>" class="btn"><?= addslashes($custom_fields['bottom-header3'][0]) ?></a>
			<span><?= addslashes($custom_fields['bottom-blurb3'][0]) ?></span>
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

<?php
get_footer();
//close search tag
}
?>
