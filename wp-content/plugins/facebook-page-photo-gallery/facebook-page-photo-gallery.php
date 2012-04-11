<?php
/*
Plugin Name: Facebook Page Photo Gallery
Plugin URI: http://zoxion.com/facebook-page-photo-gallery/
Description: This plugins embeds a Facebook Page Album  photos into wordpress content. Just get the Album Id and use [fbphotos id=x] anywhere in your post. If you would like to embed Facebook Events, User and Friends Albums then consider <a href="http://zoxion.com/walleria">Facebook Walleria</a>
Author:  Freeman Chari
Version: 2.0.2
Author URI: http://www.zoxion.com
*/

//ini_set('display_errors','On'); error_reporting(E_ALL);
define( 'FPPG_VERSION', '2.0.2' );
define('FACEBOOK_PAGE_URL', site_url(). '/wp-content/plugins/facebook-page-photo-gallery');
// Define paths
define('FACEBOOK_PAGE_PATH', dirname(__FILE__));
define( 'FPPG_FANCYBOX_VERSION', '1.3.4' );
define( 'FPPG_MOUSEWHEEL_VERSION', '3.0.4' );
define( 'FPPG_EASING_VERSION', '1.3' );


/*Set the CSS rules for the gallery. These can be overridden by 
 * adding custom css in basic.css
 * 
 */
function fppg_dynamic_css(){
    $settings=fppg_get_settings();
        $style='<style type="text/css">';
	$style .= ".fbMedThumb{ margin:3px;background:".$settings['fppg_thumbnailBgColor'].";border:".$settings['fppg_thumbnailBorder'].";padding:".$settings['fppg_thumbnailPadding']."px; vertical-align:bottom; box-shadow:".$settings['fppg_thumbnailShaddow'].";-moz-box-shadow:".$settings['fppg_thumbnailShaddow'].";-webkit-box-shadow:".$settings['fppg_thumbnailHeight'].";}";
        $style .=" .fbthumb, .fbalbumpics .fbthumb {  width:".$settings['fppg_thumbnailWidth']."px; height:".$settings['fppg_thumbnailHeight']."px; }";
        $style .= '</style>';
       echo $style;
}

/*Function to hook to an embedding shortcode 
 * @param $album array passed through shortcode
 * 
 */

function fppg_embed_photos($album) {

static $count=0;
$count++;
extract( shortcode_atts( array(
		'id' => '',
		'limit' => 300,
                'rand'=>false
	), $album ) );
$div='<div id="'. $count."_".$id.'" class="fbPhotoGallery fbClear">'.fppg_get_photos($id,$limit,$rand).'</div>';

return($div);

}

/*
 * Retrieve embeddable  html from facebook for a limited number of photos
 * This function can be called directly from a template/theme page
 * 
 * @param $albumid Facebook Album Id
 * @param $n  number of photos to show
 * $param $rand return the photos in random order
 * @return html to embed the photos
 *
 */
 function fppg_get_photos($albumid,$n,$rand=false) {
      $settings = fppg_get_settings();
     if($rand==false){ if(
             //limit 300 if not set to avoid slow loading in huge albums
             !isset($n)|| $n==''){$n=300;}
             //query graph
             $url = "https://graph.facebook.com/$albumid/photos?limit=$n";
             //get json as array
             $fb_photos= fppg_json_to_array($url);
                                
              if(isset($fb_photos->data)) {
		$return = '<div class="fbboxbody">';
               //each photo detail
               foreach($fb_photos->data as $key=>$photo) {
                       if($key<$n){ 
                            $photo= $fb_photos->data[$key];
                            $name=isset($photo->name)?$photo->name:"";
                                $return .= '<a id="" class="fbMedThumb viewable" href="'.$photo->source.'" rel="'.$albumid.'fp-gallery" title="'.$name.'"><i class="fbthumb" style="background-image:url('.fppg_check_thumbnail($photo->images[1]->source).');"></i></a>';
                                       
                                }
			}
                   }
				$return .= '</div>';
     }
     //if getting random photos
         if($rand==true){
                $url = "https://graph.facebook.com/$albumid/photos?limit=100";
                //get json as array
                $fb_photos= fppg_json_to_array($url);
                 //shuffle           
                shuffle($fb_photos->data); 
		if(isset($fb_photos->data)) {
		$return = '<div class="fbboxbody">';
         foreach($fb_photos->data as $key=>$photo) { 
            if($key<$n){ 
                    $name=isset($photo->name)?$photo->name:"";
                     $photo= $fb_photos->data[$key];                
                     $return .= '<a id="" class="fbMedThumb viewable" href="'.$photo->source.'" rel="'.$albumid.'fp-gallery" title="'.$name.'"><i class="fbthumb" style="background-image:url('.fppg_check_thumbnail($photo->images[1]->source).');"></i></a>';
                                       
                         }
			}
                      $return .= '</div>';
                      }   
                    }
        return $return;
}

/*
 * Get JSON data as array
 * @param $url source file of json data
 * @return array
 */

 function fppg_json_to_array($url){
        $json=  fppg_get_json($url);
	if(function_exists("json_decode")){
        $array= json_decode($json);
                                }
        return $array;
        }
  /* private function
   * Algorithm to get file contents 
   */      
function fppg_get_json($url){

 if (function_exists("curl_init")){
$json=  fppg_curl_get_file_contents($url);

                                }
 # plan B is to use file_get_contents
  elseif (function_exists('file_get_contents')) {
    $json = @file_get_contents($url);
  }
  # fallback is to use fopen
  else {
    if ($fh = fopen($url, 'rb')) {
      clearstatcache();
      if ($fsize = @filesize($url)) {
        $json = fread($fh, $fsize);
      }
      else {
          while (!feof($fh)) {
            $json .= fread($fh, 8192);
          }
      }
      fclose($fh);
    }
  }return $json;
}


  /* note this wrapper function exists in order to circumvent PHP?s 
   *strict obeying of HTTP error codes.  In this case, Facebook 
   *returns error code 400 which PHP obeys and wipes out 
   *the response.
   * 
   */
   function fppg_curl_get_file_contents($URL) {
    $c = curl_init();
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($c, CURLOPT_URL, $URL);
    $contents = curl_exec($c);
    $err  = curl_getinfo($c,CURLINFO_HTTP_CODE);
    curl_close($c);
    if ($contents) return $contents;
    else return FALSE;
  }

 
/*
 * Check if thumbnail is the right one 
 * Facebook uses photos that have _a.jpg as album thumbnails
 * @param $photo facebook photo url
 * @return $photo adjusted photo url
 * 
 */
function fppg_check_thumbnail($photo){
    $lastportion= substr($photo, -5);
   if(substr($lastportion,1)=="a"){
       return $photo;
   }
   else{
       return fppg_adjust_fb_photoname($photo);
   }
}
/*
 * Adjust photo name to have an _a.ext ending
 * @param $old the link to the photo
 * @return $new new photo
 */
function fppg_adjust_fb_photoname($old){
    $lastportion= substr($old, -5);
    $firstportion=substr($old,0,-5);
    $lastportion= substr_replace($lastportion, 'a', 0, 1);
    $new=$firstportion.$lastportion;
  
    return $new;
}

// When plugin is activated, update version, and set any new settings to default
function fppg_install() {

		update_option('fppg_active_version', '2.0');
                //options for the photos
                add_option('fppg_thumbnailWidth', '161'                );
                add_option('fppg_thumbnailHeight', '120'               );
                add_option('fppg_thumbnailBorder', '1px solid #ccc'    );
                add_option('fppg_thumbnailBgColor', '#FFFFFF'          );
                add_option('fppg_thumbnailPadding', '4'                );
                add_option('fppg_thumbnailShaddow', '1px 1px 5px #ccc' );
		add_option('fppg_showTitle',                       'on');
                add_option('fppg_titlePosition', 'outside'             );
		add_option('fppg_border',                            '');
                add_option('fppg_cyclic',			'on'   );
		add_option('fppg_borderColor',                '#BBBBBB');
		add_option('fppg_closeHorPos',			'right');
		add_option('fppg_closeVerPos',			'top'  );
		add_option('fppg_paddingColor',		'#FFFFFF'      );
		add_option('fppg_padding',			'10'    );
		add_option('fppg_overlayShow',			'on'    );
		add_option('fppg_overlayColor',		'#666666'       );
		add_option('fppg_overlayOpacity',		'0.3'   );
		add_option('fppg_zoomOpacity',			'on'    );
		add_option('fppg_zoomSpeedIn',			'500'   );
		add_option('fppg_zoomSpeedOut',			'500'   );
		add_option('fppg_zoomSpeedChange',		'300'   );
		add_option('fppg_easing',			''      );
		add_option('fppg_easingIn',		'easeOutBack'   );
		add_option('fppg_easingOut',                'easeInBack');
		add_option('fppg_easingChange',		'easeInOutQuart');
		add_option('fppg_imageScale',                       'on');
		add_option('fppg_enableEscapeButton',               'on');
		add_option('fppg_showCloseButton',                  'on');
		add_option('fppg_centerOnScroll',                   'on');
		add_option('fppg_hideOnOverlayClick',               'on');
		add_option('fppg_hideOnContentClick',                 '');
		add_option('fppg_loadAtFooter',                       '');
		add_option('fppg_frameWidth',			   '560');
		add_option('fppg_frameHeight',                     '340');
                add_option('fppg_callbackOnStart',			'');
		add_option('fppg_callbackOnShow',			'');
		add_option('fppg_callbackOnClose',			'');
		add_option('fppg_galleryType',                      'all');
		add_option('fppg_customExpression',                    '');
		add_option('fppg_nojQuery',				'');
		add_option('fppg_jQnoConflict',                       'on');
		add_option('fppg_uninstall',				'');
}


// If requested, when plugin is deactivated, remove settings
function fppg_uninstall() {
	if (get_option('fppg_uninstall')){
		delete_option('fppg_active_version');
                delete_option('fppg_thumbnailWidth');
                delete_option('fppg_thumbnailHeight');
                delete_option('fppg_thumbnailBorder');
                delete_option('fppg_thumbnailBgColor');
                delete_option('fppg_thumbnailPadding');
                delete_option('fppg_thumbnailShaddow');
                delete_option('fppg_cyclic');
		delete_option('fppg_showTitle');
                delete_option('fppg_titlePosition');
		delete_option('fppg_border');
		delete_option('fppg_borderColor');
		delete_option('fppg_closeHorPos');
		delete_option('fppg_closeVerPos');
		delete_option('fppg_paddingColor');
		delete_option('fppg_padding');
		delete_option('fppg_overlayShow');
		delete_option('fppg_overlayColor');
		delete_option('fppg_overlayOpacity');
		delete_option('fppg_zoomOpacity');
		delete_option('fppg_zoomSpeedIn');
		delete_option('fppg_zoomSpeedOut');
		delete_option('fppg_zoomSpeedChange');
		delete_option('fppg_easing');
		delete_option('fppg_easingIn');
		delete_option('fppg_easingOut');
		delete_option('fppg_easingChange');
		delete_option('fppg_imageScale');
		delete_option('fppg_enableEscapeButton');
		delete_option('fppg_showCloseButton');
		delete_option('fppg_centerOnScroll');
		delete_option('fppg_hideOnOverlayClick');
		delete_option('fppg_hideOnContentClick');
		delete_option('fppg_loadAtFooter');
		delete_option('fppg_frameWidth');
		delete_option('fppg_frameHeight');
		delete_option('fppg_callbackOnStart');
		delete_option('fppg_callbackOnShow');
		delete_option('fppg_callbackOnClose');
		delete_option('fppg_galleryType');
		delete_option('fppg_customExpression');
		delete_option('fppg_nojQuery');
		delete_option('fppg_jQnoConflict');
		delete_option('fppg_uninstall');
}
}


// Store plugin options in an array and return that array
function fppg_get_settings() {

	$SettingsArray=array(
                'fppg_thumbnailWidth'                       =>   get_option('fppg_thumbnailWidth'),
                'fppg_thumbnailHeight'                      =>   get_option('fppg_thumbnailHeight'),
                'fppg_thumbnailBorder'                      =>   get_option('fppg_thumbnailBorder' ),
                'fppg_thumbnailBgColor'                     =>   get_option('fppg_thumbnailBgColor'),
                'fppg_thumbnailPadding'                     =>   get_option('fppg_thumbnailPadding'),
                'fppg_thumbnailShaddow'                     =>   get_option('fppg_thumbnailShaddow' ),
		'fppg_active_version'                       => get_option('fppg_active_version'),
                'fppg_cyclic'                               => get_option('fppg_cyclic'),
		'fppg_showTitle'                            => get_option('fppg_showTitle'),
		'fppg_titlePosition'                        => get_option('fppg_titlePosition'),
                'fppg_border'                               => get_option('fppg_border'),
		'fppg_borderColor'                          => get_option('fppg_borderColor'),
		'fppg_closeHorPos'                          => get_option('fppg_closeHorPos'),
		'fppg_closeVerPos'                          => get_option('fppg_closeVerPos'),
		'fppg_paddingColor'                         => get_option('fppg_paddingColor'),
		'fppg_padding'                              => get_option('fppg_padding'),
		'fppg_overlayShow'                          => get_option('fppg_overlayShow'),
		'fppg_overlayColor'                         => get_option('fppg_overlayColor'),
		'fppg_overlayOpacity'                       => get_option('fppg_overlayOpacity'),
		'fppg_zoomOpacity'                          => get_option('fppg_zoomOpacity'),
		'fppg_zoomSpeedIn'                          => get_option('fppg_zoomSpeedIn'),
		'fppg_zoomSpeedOut'                         => get_option('fppg_zoomSpeedOut'),
		'fppg_zoomSpeedChange'                      => get_option('fppg_zoomSpeedChange'),
		'fppg_easing'                               => get_option('fppg_easing'),
		'fppg_easingIn'                             => get_option('fppg_easingIn'),
		'fppg_easingOut'                            => get_option('fppg_easingOut'),
		'fppg_easingChange'                         => get_option('fppg_easingChange'),
		'fppg_imageScale'                           => get_option('fppg_imageScale'),
		'fppg_enableEscapeButton'                   => get_option('fppg_enableEscapeButton'),
		'fppg_showCloseButton'                      => get_option('fppg_showCloseButton'),
		'fppg_centerOnScroll'                       => get_option('fppg_centerOnScroll'),
		'fppg_hideOnOverlayClick'                   => get_option('fppg_hideOnOverlayClick'),
		'fppg_hideOnContentClick'                   => get_option('fppg_hideOnContentClick'),
		'fppg_loadAtFooter'                         => get_option('fppg_loadAtFooter'),
		'fppg_frameWidth'                           => get_option('fppg_frameWidth'),
		'fppg_frameHeight'                          => get_option('fppg_frameHeight'),
            	'fppg_callbackOnStart'                      => get_option('fppg_callbackOnStart'),
		'fppg_callbackOnShow'                       => get_option('fppg_callbackOnShow'),
		'fppg_callbackOnClose'                      => get_option('fppg_callbackOnClose'),
		'fppg_galleryType'                          => get_option('fppg_galleryType'),
		'fppg_customExpression'                     => get_option('fppg_customExpression'),
		'fppg_nojQuery'                             => get_option('fppg_nojQuery'),
		'fppg_jQnoConflict'                         => get_option('fppg_jQnoConflict'),
		'fppg_uninstall'							=> get_option('fppg_uninstall')
	);

	return $SettingsArray;
}

// Load FancyBox with the settings set
function do_fancybox() {

	$settings = fppg_get_settings();
      	echo "\n"."\n"."<!-- Facebook Page Photo Gallery version: ". $settings['fppg_active_version'] ." -->"."\n";

	?>

<script type="text/javascript">

	<?php if ($settings['fppg_jQnoConflict']) { ?>jQuery.noConflict();<?php } echo "\n" ?>


		// Now we call fancybox and apply it on any link with a rel atribute that starts with "fancybox", with the options set on the admin panel ?>


               
jQuery(document).ready(function($){
    $(".entry_content a,a.viewable").fancybox({
                        'titleShow':<?php if ($settings['fppg_showTitle']) { echo "true"; } else { echo "false"; } ?>,
                        'cyclic':<?php if ($settings['fppg_cyclic']) { echo "true"; } else { echo "false"; } ?>,
                        'titlePosition':<?php echo '"'.$settings['fppg_titlePosition'].'"'?>,
			'padding': <?php echo $settings['fppg_padding']?$settings['fppg_padding']:'10'; ?>,
			'autoScale': <?php if ($settings['fppg_imageScale']) { echo "true"; } else { echo "false"; } ?>,
			'padding': <?php echo $settings['fppg_padding']; ?>,
			'opacity': <?php if ($settings['fppg_zoomOpacity']) { echo "true"; } else { echo "false"; } ?>,
			'speedIn': <?php echo $settings['fppg_zoomSpeedIn']; ?>,
			'speedOut': <?php echo $settings['fppg_zoomSpeedOut']; ?>,
			'changeSpeed': <?php echo $settings['fppg_zoomSpeedChange']; ?>,
			'overlayShow': <?php if ($settings['fppg_overlayShow']) { echo "true"; } else { echo "false"; } ?>,
			'overlayColor': <?php echo '"' . $settings['fppg_overlayColor'] . '"'; ?>,
			'overlayOpacity': <?php echo $settings['fppg_overlayOpacity']; ?>,
			'enableEscapeButton': <?php if ($settings['fppg_enableEscapeButton']) { echo "true"; } else { echo "false"; } ?>,
			'showCloseButton': <?php if ($settings['fppg_showCloseButton']) { echo "true"; } else { echo "false"; } ?>,
			'hideOnOverlayClick': <?php if ($settings['fppg_hideOnOverlayClick']) { echo "true"; } else { echo "false"; } ?>,
			'hideOnContentClick': <?php if ($settings['fppg_hideOnContentClick']) { echo "true"; } else { echo "false"; } ?>,
			'width':  <?php echo $settings['fppg_frameWidth']?  $settings['fppg_frameWidth']: "560"; ?>,
			'height':  <?php echo $settings['fppg_frameHeight']?$settings['fppg_frameHeight']:"340"; ?>,
			<?php if ($settings['fppg_callbackOnStart']) { echo "'OnStart': ". $settings['fppg_callbackOnStart'] .","."\n"; } ?>
                        <?php if ($settings['fppg_callbackOnShow']) { echo "'OnComplete': ". $settings['fppg_callbackOnShow'] .","."\n"; } ?>
                        <?php if ($settings['fppg_callbackOnClose']) { echo "'OnClosed': ". $settings['fppg_callbackOnClose'] .","."\n"; } ?>
			'centerOnScroll': <?php if ($settings['fppg_centerOnScroll']) { echo "true"; } else { echo "false"; } ?>
                        <?php if ($settings['fppg_easing']) {?>,
			'easingIn': <?php echo '"' . $settings['fppg_easingIn'] . '"'; ?>,
			'easingOut': <?php echo '"' . $settings['fppg_easingOut'] . '"';} ?>



})
})

</script>
<?php echo "<!-- END Facebook Page Photo Gallery -->"."\n";

}


// Load text domain
function fppg_admin_init() {
                register_setting('fppg-options', 'fppg_showTitle');
                register_setting('fppg-options', 'fppg_active_version');
                register_setting('fppg-options', 'fppg_thumbnailShaddow');
                register_setting('fppg-options', 'fppg_thumbnailPadding');
		register_setting('fppg-options', 'fppg_thumbnailBorder');
		register_setting('fppg-options', 'fppg_thumbnailBgColor');
		register_setting('fppg-options', 'fppg_thumbnailHeight');
		register_setting('fppg-options', 'fppg_thumbnailWidth');
		register_setting('fppg-options', 'fppg_showTitle');
                register_setting('fppg-options', 'fppg_titlePosition');
		register_setting('fppg-options', 'fppg_border');
		register_setting('fppg-options', 'fppg_borderColor');
		register_setting('fppg-options', 'fppg_closeHorPos');
		register_setting('fppg-options', 'fppg_closeVerPos');
		register_setting('fppg-options', 'fppg_paddingColor');
		register_setting('fppg-options', 'fppg_padding');
		register_setting('fppg-options', 'fppg_overlayShow');
		register_setting('fppg-options', 'fppg_overlayColor');
		register_setting('fppg-options', 'fppg_overlayOpacity');
		register_setting('fppg-options', 'fppg_zoomOpacity');
		register_setting('fppg-options', 'fppg_zoomSpeedIn');
		register_setting('fppg-options', 'fppg_zoomSpeedOut');
		register_setting('fppg-options', 'fppg_zoomSpeedChange');
		register_setting('fppg-options', 'fppg_easing');
		register_setting('fppg-options', 'fppg_easingIn');
		register_setting('fppg-options', 'fppg_easingOut');
		register_setting('fppg-options', 'fppg_easingChange');
		register_setting('fppg-options', 'fppg_imageScale');
		register_setting('fppg-options', 'fppg_centerOnScroll');
		register_setting('fppg-options', 'fppg_enableEscapeButton');
		register_setting('fppg-options', 'fppg_showCloseButton');
		register_setting('fppg-options', 'fppg_hideOnOverlayClick');
		register_setting('fppg-options', 'fppg_hideOnContentClick');
		register_setting('fppg-options', 'fppg_loadAtFooter');
		register_setting('fppg-options', 'fppg_frameWidth');
		register_setting('fppg-options', 'fppg_frameHeight');
		register_setting('fppg-options', 'fppg_callbackOnStart');
		register_setting('fppg-options', 'fppg_callbackOnShow');
		register_setting('fppg-options', 'fppg_callbackOnClose');
		register_setting('fppg-options', 'fppg_galleryType');
		register_setting('fppg-options', 'fppg_customExpression');
		register_setting('fppg-options', 'fppg_nojQuery');
		register_setting('fppg-options', 'fppg_jQnoConflict');
		register_setting('fppg-options', 'fppg_uninstall');

}
// Admin options page
function fppg_admin_menu() {

include_once FACEBOOK_PAGE_PATH . '/admin.php';

	$fppgadmin = add_submenu_page('options-general.php', 'Facebook Page Photo Gallery options', 'Facebook Page Photo','activate_plugins' , 'facebook-page-photo-gallery', 'fppg_options_page');
}

// Load Admin JS
function fppg_admin_head() {
    
	wp_enqueue_script('jquery-ui-tabs', array('jquery-ui-core')); // Load jQuery UI Tabs for Admin Page
	wp_enqueue_script('fancyboxadmin',FACEBOOK_PAGE_URL . '/fancybox/admin.js', array('jquery')); // Load specific JS for Admin Page
}


function fppg_add_scripts(){

if(!is_admin()){
  //wp_deregister_script('jquery');
       wp_enqueue_script('jquery');
       //deregister any easing
       wp_deregister_script('easing');
       //register
       wp_register_script('easing', FACEBOOK_PAGE_URL.'/fancybox/jquery.easing-'.FPPG_EASING_VERSION.'.pack.js', array('jquery'), FPPG_EASING_VERSION);
       //enqueue
       wp_enqueue_script('easing');
       //deregister mousewheel
       wp_deregister_script('mousewheel');
       //re-register
       wp_register_script('mousewheel', FACEBOOK_PAGE_URL.'/fancybox/jquery.mousewheel-'.FPPG_MOUSEWHEEL_VERSION.'.pack.js', array('jquery'), FPPG_MOUSEWHEEL_VERSION);
       //enqueue
       wp_enqueue_script('mousewheel');
       //deregister any fancybox
       wp_deregister_script('fancybox');
       //register fancybox
       wp_register_script('fancybox',FACEBOOK_PAGE_URL.'/fancybox/jquery.fancybox-'.FPPG_FANCYBOX_VERSION.'.pack.js', array('jquery'), FPPG_FANCYBOX_VERSION);
       //enqueue for use
       wp_enqueue_script('fancybox');
      
       

      
}
}

//Enqueue styles
function fppg_add_styles(){
    if(!is_admin()){
wp_register_style('fppg', FACEBOOK_PAGE_URL.'/css/basic.css',FPPG_VERSION);
wp_enqueue_style('fppg');
wp_deregister_style('fancybox');
wp_register_style('fancybox', FACEBOOK_PAGE_URL.'/fppg-fancybox.css.php', false, FPPG_FANCYBOX_VERSION, 'screen');
wp_enqueue_style('fancybox');
}
}

//add shortcode
add_shortcode('fbphotos','fppg_embed_photos' );


//==================Wordpress Actions=========================

add_action('admin_menu', 'fppg_admin_menu');     // Admin Panel Page
add_action('admin_init', 'fppg_admin_init');     // Register options
//hook the styles
add_action('init','fppg_add_styles');
//hook the scripts
add_action('init','fppg_add_scripts');
//hook dynamic style
add_action('wp_head','fppg_dynamic_css');
add_action('wp_head', 'do_fancybox');
add_action( "admin_menu", 'fppg_admin_head' );

// Install and Uninstall
register_activation_hook(__FILE__,'fppg_install');
register_deactivation_hook(__FILE__,'fppg_uninstall');

?>