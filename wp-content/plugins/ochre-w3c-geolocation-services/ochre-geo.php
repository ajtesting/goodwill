<?php
/*
Plugin Name: Ochre Geolocation Services
Plugin URI: http://www.ochrelabs.com/wordpress-plugins/ochre-geolocation
Description: Geolocation Services for Wordpress
Author: Ochre Development Labs
Author URI: http://www.ochrelabs.com
Version: 0.04
*/

class OCHRELABS_WP_Geolocation
{
  public $debug = FALSE;
  
  private $_status = FALSE;
  private $_latitude = FALSE;
  private $_longitude = FALSE;
  private $_elevation = FALSE;
  private $_accuracy = FALSE;
  
  private $_postid;
  
  private $_ypfcacheprecision = 5;
  private $_ypfcachettl = 600;		// Cache placefinder results for up to 10 minutes.
  private $_tablepfx;
  
  const VERSION = 0.04;  
    
  const WP_EXTEND_URL = 'http://wordpress.org/extend/plugins/ochre-w3c-geolocation-services/';
  const PLUGIN_URL = 'http://www.ochrelabs.com/wordpress-plugins/ochre-geolocation'; 

  const STATUS_QUERY = 0;		// waiting for update from client
  const STATUS_UPDATED = 1;		// received coordinate update from client
  const STATUS_NOTSUPPORTED = 2;	// client does not support geo location
  const STATUS_ERROR = 3;		// an error was returned from the client
  const STATUS_UNKNOWNPOS = 4;		// location was unknown
  const STATUS_DISABLED = 5;		// module has been told not to present geolocation query
  
  const OPMODE_DISABLED = '';
  const OPMODE_GLOBAL = 'global';
  const OPMODE_PERPOST = 'perpost';
  
  const POST_ACTION_DEFAULT = '';
  const POST_ACTION_DISABLED = 'disabled';
  const POST_ACTION_INTERNAL = 'internal';
  const POST_ACTION_REDIRECT = 'redir';
  const POST_ACTION_REFRESH = 'refresh';
  const POST_ACTION_EXECJS = 'execjs';

  function __construct($params=false)
  {
    global $wpdb;
    if($params!=false){
      if(isset($params['debug']))
        $this->debug=TRUE;
    }
    $this->_status = OCHRELABS_WP_Geolocation::STATUS_QUERY;
    $this->_tablepfx = $wpdb->prefix.'ochregeo';
    
    $this->geocoder_cache_gc();
  }
  
  function get_status()
  {
    return($this->_status);
  }
  
  function set_coordinates($latitude,$longitude,$elevation=0,$accuracy=0)
  {	
    $this->_latitude=$latitude;
    $this->_longitude=$longitude;
    $this->_elevation=$elevation;
    $this->_accuracy=$accuracy;
    $this->_status = OCHRELABS_WP_Geolocation::STATUS_UPDATED;    
  }
  
  function get_coordinates()
  {
    return(array(
      'latitude'=>$this->_latitude,
      'longitude'=>$this->_longitude,
      'elevation'=>$this->_elevation,
      'accuracy'=>$this->_accuracy
    ));
  }
  
  
  function handle_ajax()
  {
    if($this->debug){
      error_log("Received ajax: ".print_r($_REQUEST,TRUE));
    }
    if(isset($_POST['_ochregeo_result'])){
      switch($_POST['_ochregeo_result']){
    
        case('nosupport');
          $this->_status = OCHRELABS_WP_Geolocation::STATUS_NOTSUPPORTED;
          do_action('ochregeo_received_nosupport',$this);
        break;
        
        case('unknown');
          $this->_status = OCHRELABS_WP_Geolocation::STATUS_UNKNOWNPOS;
          do_action('ochregeo_received_unknownpos',$this);        
        break;
        
        default;
          $garr=explode(':',$_POST['_ochregeo_result']);
          $lat=$garr[0];	
          $long=$garr[1];	
          $elev=$garr[2];	
          $acc=$garr[3];	
          if($this->debug){
            error_log('New lat/long is '.print_r($garr,TRUE));          
          }
          
          $this->_postid = $_POST['_ochregeo_postid'];
          $this->set_coordinates($lat,$long,$elev,$acc);
          
          if(get_option('ochregeo_rgeocode')=='Y'){
            $rg=$this->geocode();
          }
          do_action('ochregeo_received_location',$this);

          $opmode = get_option('ochregeo_opmode');          
          
          if($opmode == OCHRELABS_WP_Geolocation::OPMODE_GLOBAL){
            $act = OCHRELABS_WP_Geolocation::POST_ACTION_EXECJS;
            $actp = get_option('ochregeo_globalactionjs');
          } else if($opmode == OCHRELABS_WP_Geolocation::OPMODE_PERPOST){
          
            $act = get_post_meta($this->_postid,'ochregeo_action',true);
            $actp = get_post_meta($this->_postid,'ochregeo_actionp',true);
          
            if($act==OCHRELABS_WP_Geolocation::POST_ACTION_REDIRECT){
              $actp=str_replace('#la#',$this->_latitude,$actp);
              $actp=str_replace('#lo#',$this->_longitude,$actp);
              $actp=str_replace('#ev#',$this->_elevation,$actp);
              $actp=str_replace('#ac#',$this->_accuracy,$actp);
              
              if($rg!=FALSE){
                $actp=str_replace('#country#',$rg['country'],$actp);
                $actp=str_replace('#countryc',$rg['countryc'],$actp);
                $actp=str_replace('#state#',$rg['state'],$actp);
                $actp=str_replace('#statec#',$rg['statec'],$actp);
                $actp=str_replace('#city#',$rg['city'],$actp);
                $actp=str_replace('#county#',$rg['county'],$actp);                
              }
            }
          }	// Per post handling
          
          if($this->debug){
            error_log('OchreGeo response for '.$this->_postid." is '$act' '$actp'");
          }
          
          $jsonr = "";
          if($act!=FALSE && $act != OCHRELABS_WP_Geolocation::POST_ACTION_DISABLED && $act != OCHRELABS_WP_Geolocation::POST_ACTION_INTERNAL){
            $res=array(
              'r'=>$act,
              'rr'=>$actp,
              'la'=>$this->_latitude,
              'lo'=>$this->_longitude,
              'ev'=>$this->_elevation,
              'ac'=>$this->_accuracy
           );
           if($rg!=FALSE){
             $res['city'] = $rg['city'];
             $res['country'] = $rg['country'];
             $res['countryc'] = $rg['countryc'];
             $res['state'] = $rg['state'];
             $res['statec'] = $rg['statec'];
           }
           $jsonr = json_encode($res);
          }
          die( $jsonr);
      }
    }
  }
  
  function enqueue_scripts()
  {
    global $post;  

    $action = get_post_meta($post->ID,'ochregeo_action',true);
    $opmode = get_option('ochregeo_opmode');
    
    if($opmode == OCHRELABS_WP_Geolocation::OPMODE_DISABLED) {
      return;
    } else if( $action == OCHRELABS_WP_Geolocation::POST_ACTION_DISABLED){
      return;
    }
    
    $mypath=plugin_basename(__FILE__);
    $mypath = "ochre-w3c-geolocation-services";
    wp_enqueue_script('ochregeo_get_coordinates', plugins_url().'/'.$mypath . '/js/ochregeo.js', array( 'jquery' ));
    wp_localize_script( 'ochregeo_get_coordinates', 'OCHREGEO', array( 
     'postid'=> is_object($post) ? $post->ID : '-1',
     'ajaxurl' => admin_url( 'admin-ajax.php' ),
   ));
  }

  function init_admin_menu()
  {
    add_submenu_page('options-general.php','Ochre Geolocation Services Configuration','Ochre Geolocation','manage_options','ochregeo_admin',array($this,'admin_screen'));
  }
  
  
  function admin_screen()
  {
    if($_SERVER['REQUEST_METHOD']=='POST' && check_admin_referer('ochregeo_admin_update')){

      update_option('ochregeo_opmode',$_POST['ochregeo_opmode']);
      update_option('ochregeo_globalactionjs',stripslashes_deep($_POST['ochregeo_globalactionjs']));
      
      if(isset($_POST['ochregeo_rgeocode'])){
        update_option('ochregeo_rgeocode','Y');
      } else {
        update_option('ochregeo_rgeocode','N');
      }
      
      if(isset($_POST['ochregeo_useypf']))
        update_option('ochregeo_useypf','Y');
      else
        update_option('ochregeo_useypf','N');
        
      update_option('ochregeo_ypfappid',$_POST['ochregeo_ypfappid']);
      
        
//      echo "saved";
    }  
    
    $opmode = get_option('ochregeo_opmode');
    $globaljs = get_option('ochregeo_globalactionjs');
    $ypf = get_option('ochregeo_useypf');
    $ypfappid = get_option('ochregeo_ypfappid');
    $rgeocode = get_option('ochregeo_rgeocode');
?>
<h1>Ochre's Geolocation Services plugin for Wordpress</h1>
Version <?php echo OCHRELABS_WP_Geolocation::VERSION;?>

<div class="wrap">
<div style="float: left; width: 80%;">
<form method="post">
<h2>Operating settings</h2>
<table>
<tr><td>Operating Mode:</td><td>
<select name="ochregeo_opmode">
<option value="<?php echo OCHRELABS_WP_Geolocation::OPMODE_DISABLED;?>" <?php if($opmode==OCHRELABS_WP_Geolocation::OPMODE_DISABLED) echo "selected"; ?> >Disable completely</option>
<option value="<?php echo OCHRELABS_WP_Geolocation::OPMODE_PERPOST;?>" <?php if($opmode==OCHRELABS_WP_Geolocation::OPMODE_PERPOST) echo "selected"; ?> >Per post
<option value="<?php echo OCHRELABS_WP_Geolocation::OPMODE_GLOBAL;?>" <?php if($opmode==OCHRELABS_WP_Geolocation::OPMODE_GLOBAL) echo "selected"; ?> >Globally
</select>
</td></tr>
<tr><td>Global action javascript:</td><td><textarea cols=80 rows=10 name="ochregeo_globalactionjs"><?php echo htmlentities($globaljs);?></textarea></td></tr>
</table>
<h2>Geocoder Settings</h2>
Use of the Yahoo! Placefinder API for Geocoding requires agreement with the <a href="http://developer.yahoo.com/terms" target="_blank">Yahoo! Developer Terms of Service</a>.
<table>
<tr><td>Return reverse Geo Coded results</td><td><input type="checkbox" name="ochregeo_rgeocode" value="Y" <?php if($rgeocode=="Y") echo "checked";?>></td></tr>
<tr><td>Use Yahoo! Placefinder for Geocoding:</td><td><input type="checkbox" name="ochregeo_useypf" value="Y" <?php if($ypf == "Y") echo "checked";?>></td></tr>
<tr><td>Yahoo! AppID</td><td><input type="text" name="ochregeo_ypfappid" value="<?php echo $ypfappid;?>"></td></tr>
</table>
<input type="submit" value="Save settings"><input type="reset" value="Undo changes">
    <?php wp_nonce_field('ochregeo_admin_update'); ?>
</form>

<?php
//  $this->test_geocoder();
?>
<div style="border-top: thin 1px solid">
<h1>General usage</h1>
<h2>Global defaults</h2>
The operating mode determines how the Geolocation plugin behaves.  Your options are:
<ul>
<li>Disable completely : Don't try to get the geolocation of the visitor</li>
<li>Per post (the default): Only try to retrieve the geolocation of the visitor on a per-page/post basis</li>
<li>Globally: Try to retrieve geolocation information for <i>every</i> post and page</li>
</ul>

<h2>Per post/page settings</h2>
You can set Geolocation options on individual posts and pages by specifying a "Geolocation Action" and "Geolocation action parameter".  Refer to the Geolocation meta box in the page or post editor
for details.
<h1>Need help?</h1>
This plugin is Free Software and is subject to the terms of the <a target="license" href="<?php echo plugin_dir_url(__FILE__);?>/license.txt">license</a>.  While we will endevour to respond to support and feature requests made through the <a target="_new" href="<?php echo OCHRELABS_WP_Geolocation::WP_EXTEND_URL;?>">Wordpress Plugin Information page</a>, more sophisticated needs are better 
suited for our commercial support.  <a target="_new" href="http://www.ochrelabs.com/contact/">For commercial assistance with this plugin, contact Ochre Development Labs</a> directly.
</div>

</div>
<div style="float: right; width: 20%">
Like this plugin? <br/>Buy us a coffee or two!
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="PMLVVYMVWLJQW">
<input type="image" src="<?php echo plugins_url('i/btn_donate_LG.gif',__FILE__);?>" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
</form>

&middot; <a href="http://www.ochrelabs.com/wordpress-plugins/ochre-geolocation">Commercial Support</a><br/>
&middot; <a href="<?php echo OCHRELABS_WP_Geolocation::WP_EXTEND_URL;?>">Wordpress Profile</a><br/>
<p/>
<a href="http://www.ochrelabs.com">Ochre Development Labs</a>
</div>
<div style="clear: both"></div>
</div>
<?php
  }  

  function save_post($postid)
  {
    if(!wp_verify_nonce($_POST['ochregeo_pageopt'],plugin_basename(__FILE__))){
      return;
    }
    
    if($_POST['post_type'] == 'page'){
      if(!current_user_can('edit_page',$postid))
        return;
    } else {
      if(!current_user_can('edit_post',$postid))
        return;
    }
    
//    update_post_meta($postid,'ochregeo_javascriptcb',$_POST['ochregeo_javascriptcb']);
    update_post_meta($postid,'ochregeo_action',$_POST['ochregeo_action']);
    update_post_meta($postid,'ochregeo_actionp',$_POST['ochregeo_actionp']);    
  }  
  
  function post_meta_box($post)
  {
  
    $action = get_post_meta($post->ID,'ochregeo_action',true);
    $actionp = get_post_meta($post->ID,'ochregeo_actionp',true);

    wp_nonce_field(plugin_basename(__FILE__),'ochregeo_pageopt');
?>
<table>
<tr><td>Geolocation action</td><td><select name="ochregeo_action">
<option value="<?php echo OCHRELABS_WP_Geolocation::POST_ACTION_DEFAULT;?>" <?php if($action==OCHRELABS_WP_Geolocation::POST_ACTION_DEFAULT) echo "selected";?>> Global default</option>
<option value="<?php echo OCHRELABS_WP_Geolocation::POST_ACTION_DISABLED;?>" <?php if($action==OCHRELABS_WP_Geolocation::POST_ACTION_DISABLED) echo "selected";?>> Disabled</option>
<option value="<?php echo OCHRELABS_WP_Geolocation::POST_ACTION_INTERNAL;?>" <?php if($action==OCHRELABS_WP_Geolocation::POST_ACTION_INTERNAL) echo "selected";?>> No front end action</option>
<option value="<?php echo OCHRELABS_WP_Geolocation::POST_ACTION_REDIRECT;?>" <?php if($action==OCHRELABS_WP_Geolocation::POST_ACTION_REDIRECT) echo "selected";?> >Redirect to a URL
<option value="<?php echo OCHRELABS_WP_Geolocation::POST_ACTION_REFRESH;?>" <?php if($action==OCHRELABS_WP_Geolocation::POST_ACTION_REFRESH) echo "selected";?> >Refresh current page
<option value="<?php echo OCHRELABS_WP_Geolocation::POST_ACTION_EXECJS;?>" <?php if($action==OCHRELABS_WP_Geolocation::POST_ACTION_EXECJS) echo "selected";?> >Execute Javascript
</select></td></tr>
<tr><td>Geolocation action parameters</td><td><textarea cols=60 rows=5 id="ochregeo_actionp"  type="text" name="ochregeo_actionp"><?php echo $actionp;?></textarea></td></tr>
</table>
<h2>Parameter settings</h2>
<div id="ochregeo_help_t"><a onClick="jQuery('#ochregeo_help').show(); jQuery('#ochregeo_help_t').hide();">Show help</a></div>
<div id="ochregeo_help" style="display: none">

<ul style="padding: 20px !important">
<li>Disabled : Do not attempt to retrieve the location of the visitor on this page</li>
<li>Nothing : Performs a Geolocation query and fires do_action calls, but performs no front end action</li>
<li>Redirect to a url: Take the visitor to another url.  Can include the following template variables:
  <ul style="padding: 20px !important">
    <li>#la# : Latitude</li>
    <li>#lo# : Longitude</li>
    <li>#ev# : Elevation</li>
    <li>#ac# : Accuracy</li>
    <li>#country# or #countryc# : Country or Country Code</li>
    <li>#state# or #statec# : State or State code</li>
    <li>#city# : City</li>
    <li>#county# : Country or local Region</li>
  </ul>
</li>
<li>Refresh current page: Refresh the current page</li>
<li>Execute javascript: Execute javascript.  </li>
</ul>

</div>
<?php
  }
  
  function add_meta_boxes()
  {
      add_meta_box('ochregeo_postopt','Geolocation',array($this,'post_meta_box'),'page','advanced');
  }
  
  function get_ajax_coordinates()
  {
    echo json_encode(array(
      'status'=>$this->get_status(),
      'coordinates'=>$this->get_coordinates()
    ));
  }

  function activate()
  {
    update_option('ochregeo_opmode',OCHRELABS_WP_Geolocation::OPMODE_PERPOST);
    global $wpdb;
    
    if($wpdb->get_var("SHOW TABLES LIKE '".$this->_tablepfx."_geocoder_cache") != $this->_tablepfx."_geocoder_cache"){
      $wpdb->query("CREATE TABLE ".$this->_tablepfx."_geocoder_cache ( 
        id serial,
        la decimal(10,5),
        ll decimal(10,5),
        tt bigint(11),
        dat text,
        primary key(id),
        index(la),
        index(ll),
        index(tt)
        )");
    }
  }
  
  function deactivate()
  {
    global $wpdb;  

    update_option('ochregeo_opmode',OCHRELABS_WP_Geolocation::OPMODE_DISABLED);
    $wpdb->query("DROP TABLE ".$this->_tablepfx."_geocoder_cache");
  }  
    
    
  function geocoder_cache_get($ll,$la)
  {
    global $wpdb;
    $tdiff = time() - $this->_ypfcachettl;    
    $val = $wpdb->get_var($wpdb->prepare("SELECT dat FROM ".$this->_tablepfx."_geocoder_cache WHERE la=%f AND ll=%f AND tt > %d",round($ll,$this->_ypfcacheprecision),round($la,$this->_ypfcacheprecision),$tdiff));
    if($val!=FALSE)
      return(unserialize($val));
    return(FALSE);
  }
  
  function geocoder_cache_put($ll,$la,$dat)
  {
      global $wpdb;
      $wpdb->query($wpdb->prepare("INSERT INTO ".$this->_tablepfx."_geocoder_cache (id,la,ll,tt,dat) VALUES(0,%f,%f,%d,%s)", round($ll,$this->_ypfcacheprecision),round($la,$this->_ypfcacheprecision),time(),serialize($dat)));
  }
  
  function geocoder_cache_gc()
  {
    global $wpdb;
    
    $lastgc = intval(get_option('ochregeo_lastgeocodergc'));
    if($lastgc == 0 || time() - $lastgc > $this->_ypfcachettl){
      $tdiff = time() - $this->_ypfcachettl;
      $wpdb->query("DELETE FROM ".$this->_tablepfx."_geocoder_cache WHERE tt < ".$tdiff);
      update_option("ochregeo_lastgeocodergc",time());
      if($this->debug){
        error_log("Performed cache expiration on the geocoder cache");
      }
    }
  }
  
  private function test_geocoder()
  {
    $oll = $this->_longitude;
    $ola = $this->_latitude;
    $ols = $this->_status;
    
    $this->_latitude = "49.244977";
    $this->_longitude = "-123.110518";
    $this->_status = OCHRELABS_WP_Geolocation::STATUS_UPDATED;
    echo "Geocoder returned ";
    echo "<PRE>";    
    $res=$this->geocode();
    print_r($res);
    echo "</pRE>";
    
    $this->_longitude = $oll;
    $this->_latitude = $ola;
    $this->_status = $ols;
  
  }
  
  function geocode()
  {
    
    if($this->_status != OCHRELABS_WP_Geolocation::STATUS_UPDATED){
	return(FALSE);
    }
    
    if(($cache=$this->geocoder_cache_get($this->_latitude,$this->_longitude))!=FALSE) {
      if($this->debug){
        error_log("Using cache for ".$this->_latitude." ".$this->_longitude);
      }
      return($cache);
    }
      
    $appid = get_option('ochregeo_ypfappid');
    
    $req = 'http://where.yahooapis.com/geocode?flags=P&gflags=R';
    if($appid!=FALSE)
      $req.='&appid=yourappid';
    
    $req.='&q='.urlencode($this->_latitude.','.$this->_longitude);
    $ctx=stream_context_create(array(
      'http'=>array(
        'method'=>'GET',
        'user_agent'=>'OchreGeolocationforWordpress/'.OCHRELABS_WP_Geolocation::VERSION,
        'timeout'=>2
      )
    ));
    
    $dat = file_get_contents($req,false,$ctx);
    if($dat==FALSE) {
      return(FALSE);
    }

    $dat = unserialize($dat);
    if(!isset($dat['ResultSet'])){
      return(FALSE);
    }
    
    $ret=array(
      'city'=>$dat['ResultSet']['Result'][0]['city'],
      'county'=>$dat['ResultSet']['Result'][0]['county'],
      'state'=>$dat['ResultSet']['Result'][0]['state'],
      'statec'=>$dat['ResultSet']['Result'][0]['statecode'],
      'country'=>$dat['ResultSet']['Result'][0]['country'],
      'countryc'=>$dat['ResultSet']['Result'][0]['countrycode'],
    );
    $this->geocoder_cache_put($this->_latitude,$this->_longitude,$ret);
    return($ret);
  }
}

$ochre_geo = new OCHRELABS_WP_Geolocation();

add_action('admin_menu',array($ochre_geo,'init_admin_menu'));
add_action('add_meta_boxes',array($ochre_geo,'add_meta_boxes'));
add_action('save_post',array($ochre_geo,'save_post'));

add_action('wp_enqueue_scripts', array($ochre_geo,'enqueue_scripts'));

add_action('wp_ajax_ochregeo_get_coordinates', array($ochre_geo,'get_ajax_coordinates'));
add_action('wp_ajax_ochregeos',array($ochre_geo,'handle_ajax'));

add_action('wp_ajax_nopriv_ochregeo_get_coordinates', array($ochre_geo,'get_ajax_coordinates'));
add_action('wp_ajax_nopriv_ochregeos',array($ochre_geo,'handle_ajax'));


register_activation_hook(__FILE__,array($ochre_geo, 'activate'));
register_deactivation_hook(__FILE__,array($ochre_geo, 'deactivate'));