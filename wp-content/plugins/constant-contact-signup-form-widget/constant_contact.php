<?php
/*
Plugin Name: Constant Contact Widget 2.0
Plugin URI: http://www.seodenver.com/constant-contact-wordpress-widget/
Description: Adds Constant Contact signup form to your sidebar or content without touching code.
Author: Katz Web Services, Inc.
Version: 2.0.3
Author URI: http://www.katzwebservices.com
*/

/*
Copyright 2011 Katz Web Services, Inc.  (email: info@katzwebservices.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/


if(class_exists('WP_Widget') && function_exists('register_widget')) {
	
	add_action( 'widgets_init', 'load_constant_contact_widget' );
	
	function load_constant_contact_widget() {
		register_widget( 'ConstantContactWidget' );
	}
	
	class ConstantContactWidget extends WP_Widget {
		
		var $version = '2.0';
		
	 	function ConstantContactWidget() {
	    	$control_options = array('width'=>400); // 600 px wide please
	        $widget_options = array('description'=>'Add a Constant Contact form to your website', 'classname' => 'constantcontact');
	        parent::WP_Widget(false, $name = 'Constant Contact Widget 2.0', $widget_options, $control_options);    
	    	
	    	$this->url = WP_PLUGIN_URL . "/" . basename(dirname(__FILE__));    
	        $this->defaults = array(
					'id' => '',
					'title' => 'Sign up for our Email Newsletter',
				    'code' => '',
				    'preface' => '',
#				    'headline' => false,
				    'button' => 'Subscribe',
				    'show_cc_code' =>'yes',
				    'style' =>'1',
				    'show_safe_subscribe' =>'yes',
				    'show_fieldset' => '',
				    'credit' => '',
				    'email_image' => '',
				    'legend' => 'Sign Up',
				    'bg_color' => '#ffffcc',
				    'email_label' => 'Email:',
				    'border_color' => '#000000',
				    'border_width' => 2,
				    'width' => '100%',
				    'input_size' => '20',
				    'safesubscribe'=>'light',
				    'initial_value_email' => 'example@subscribe.com', 
				    'https' => is_ssl(),
				    'target' => 'yes',
				    'hide' => 'no',
				    'show_widget' => 'yes',
				    'uid' => '',
				    'visible' => '',
				    // Depreciated
				    'unit' => '',
				    'wrap' => '',
				    'form_code' => '',
				    'form_title' => '',
				    'form_preface' => '',
				    'form_wrap' => ''
			);
			
			// Added in 2.0.2 to transfer settings from previous (< 2.0) widgets
			if($this->defaults['email_image'] === '') {
				$previous = get_option('widget_cc');
				if($previous) {
					$this->defaults = shortcode_atts($this->defaults, $previous);
				}
			}
	        
	        add_action('wp_print_styles', array(&$this, 'print_styles'));
	       	add_filter('cc_widget_style', array(&$this, 'remove_whitespace'));
	        add_action('wp_print_footer_scripts', array(&$this, 'print_scripts'));
	        
	        add_action('wp_footer', array(&$this, 'footer'));
	        add_action('admin_print_styles-widgets.php', array(&$this, 'widget_styles'));
	        add_action('admin_print_footer_scripts', array(&$this, 'widget_scripts'), 9999);
	        
	        // Implement the shortcodes
	        add_shortcode('ConstantContact', array(&$this, 'shortcode'));
			add_shortcode('constantcontact', array(&$this, 'shortcode'));
			add_shortcode('Constant Contact', array(&$this, 'shortcode'));
			add_shortcode('constant contact', array(&$this, 'shortcode'));
	    }
		
		function remove_whitespace($content = null) {
			return trim(preg_replace('/\s+/ism', ' ', $content))."\n";
		}
		
		function footer() {
			$settings = $this->get_settings();
			$credit = false;
			foreach($settings as $s) { if($s['credit'] == 'yes') { $credit = true; } }
	       	if($credit) {
				echo apply_filters('constant_contact_link', $this->add_link());
			} else {
				echo "\n".'<!-- Constant Contact Widget by Katz Web Services, Inc. | http://www.seodenver.com/constant-contact-wordpress-widget/ -->'."\n\n";
			}
			
		}
		
		function widget_js() {
			global $pagenow;
			if(is_admin() && $pagenow == 'widgets.php') {
			}
		}
		
		function widget_styles() {
			$path = get_bloginfo('wpurl');
			$style = <<<EOD
			<style type="text/css">
				.cc_widget_container .accordion .section { padding:10px; }
				.cc_widget_container .accordion .cc-arrow { background-position: 0 -110px; }
				.cc_widget_container .accordion h3 { 
					padding:0 0 0 5px; 
					min-height:26px;
					line-height:26px;
					margin:0;
					color:#606060; 
					cursor:pointer;
				}
				.cc_widget_container .accordion h3 .cc-arrow {
					background: url($path/wp-admin/images/menu-bits.gif) no-repeat scroll 0px -110px;
					float:right;
					height: 26px;
					width:24px;
				}
				.cc_widget_container .accordion h3:hover .cc-arrow,
				.cc_widget_container .accordion h3.open .cc-arrow { background-position:0 -207px; }
				.cc_widget_container fieldset {
					margin-left:12px; 
					padding:10px 10px 0 10px;
					border:1px solid #eee; 
					background-color:#fefefe;
					margin-bottom:10px; 
					display:none;
				}
				.cc_widget_container .style_0_options,
				.cc_widget_container .style_1_options,
				.cc_widget_container .style_3_options { display:none;}
				.cc_widget_container legend {
					font-weight:bold;
					font-size: 1.2em;
					margin:0; 
					padding:0;
					border:none;
				}
				
			</style>
EOD;
			echo trim(preg_replace('/\s+/ism', ' ', $style))."\n";
		}
		
		function print_scripts() {
			// For future use
			return;
		}
		
		function print_styles() {
			global $constantContactWidgetPrintedStyles;
			if(is_admin() || isset($constantContactWidgetPrintedStyles)) { return; }
			$settings = $this->get_settings();
			$usedStyles = array();
			foreach($settings as $key => $instance) {
				$width_style = false;
				
				$setting = shortcode_atts($this->defaults, $instance);	
				extract($setting);
				$bg_color = $this->process_color($bg_color);
				$border_color = $this->process_color($border_color);
				if((in_array($style, $usedStyles) && $style != 1) || $style == 0) { continue; }
				unset($settings[$key]);
				$usedStyles[] = $style; // We don't need to echo the same styles twice
				$cssOut = '';
				if($style == 1) {
					$cssOut .= '
					.cc_form_wrapper_'.$key.' { 
						font-size:10px;
						color:#999999; 
						margin:0 auto; 
						text-align:center;
						padding:3px; 
						background-color:'.$bg_color.'; 
						border:'.$border_width.'px solid '.$border_color.';
						'.$width_style.' 
					}
						.cc_style_1 * { font-family:Arial,Helvetica,sans-serif;text-align:center; }
						.cc_style_1 .cc_widget_title { font-weight: bold; font-size:12px; color:#000000;} 
						.cc_style_1 form {  margin-bottom:2px; border-top:'.$border_width.'px solid #'.$border_color.' } 
							.cc_style_1 .cc_label_email { font-weight: normal; font-family:Arial; font-size:12px; color:#000000; } 
							.cc_style_1 .cc_input_email { font-size:10pt; border:1px solid #999999; text-align:left; } 
							.cc_style_1 .cc_submit {font-family:Verdana,Geneva,Arial,Helvetica,sans-serif; font-size:10pt; }
						.cc_safesubscribe { text-align:center; margin:0 auto; width:168px; padding-top:5px; }
						.cc_email_marketing { display:block; text-align:center; margin:0 auto; font-family:Arial,Helvetica,sans-serif;font-size:10px;color:#999999; }
						.cc_email_marketing a { text-decoration:none;font-family:Arial,Helvetica,sans-serif;font-size:10px;color:#999999; }';
				} elseif($style == 2) { 
					$cssOut .= '
						.cc_style_2 { text-align:center; width:'.$this->get_width($width, $unit, $style).'; margin:0 auto; padding:0; background-color:white; }
						.cc_style_2 * { font-family:Arial,Helvetica,sans-serif; }
						.cc_style_2 table { border-collapse:separate; border-spacing: 0; width:100%;}
						.cc_style_2 .cc_top, 
						.cc_style_2 .cc_bottom { background-color:#006699; }
						.cc_style_2 .cc_top td {  vertical-align:top;  }
						.cc_style_2 .cc_middle td.cc_main { 
							border-left: 1px solid #006699;border-right: 1px solid #006699; background-color:#ffffff;
						}
						.cc_style_2 .cc_bottom td {  vertical-align:bottom; }
						.cc_style_2 .cc_top img,
						.cc_style_2 .cc_bottom img { width:100%; max-width:11px; max-height:9px;}
						.cc_style_2 .cc_widget_title { font-weight: bold; font-size:18px; color:#006699; }
						.cc_style_2 form {  margin-bottom:3px; } 
							.cc_style_2 .cc_widget_title { font-weight: bold; font-family:Arial; font-size:18px; color:#006699; }
							.cc_style_2 .cc_label_email { font-weight: normal; font-size:10px; color:#000000; } 
							.cc_style_2 .cc_input_email { font-family: Arial; font-size:10px; border:1px solid #999999; } 
							.cc_style_2 .cc_submit {font-family:Arial,Helvetica,sans-serif; font-size:11px; }
						.cc_safesubscribe { padding-top:5px; }
						.cc_email_marketing { font-family:Arial,Helvetica,sans-serif;font-size:10px;color:#999999; }
						.cc_email_marketing a { text-decoration:none;font-size:10px;color:#999999; }';
				} elseif($style == 3) { 
					$cssOut .= '
					.cc_style_3 { 
						width:'.$width.$unit.'; 
						background-color: #ffffff;
						text-align:center; 
						margin:0 auto; 
					} 
						.cc_style_3 * { font-family:Verdana,Geneva,Arial,Helvetica,sans-serif; }
						.cc_style_3 .cc_widget_title { font-weight: bold; font-family:Arial; font-size:16px; color:#006699; display:block; padding-bottom:.2em; }
						.cc_style_3 form {  margin-bottom:3px; } 
							.cc_style_3 .cc_label_email { font-weight: normal; font-family:Arial; font-size:10px; color:#000000; } 
							.cc_style_3 .cc_input_email { font-size:10pt; border:1px solid #999999; font-size:10px; border:1px solid #999999; color: #666666; } 
							.cc_style_3 .cc_submit {  font-size:10px; }
						.cc_safesubscribe { padding-top:5px; }
						.cc_email_marketing { font-family:Arial,Helvetica,sans-serif;font-size:10px;color:#999999; }
						.cc_email_marketing a { text-decoration:none;font-family:Arial,Helvetica,sans-serif;font-size:10px;color:#999999; }';
				}
				if(!empty($cssOut)) {
					$cssOut .= '
					.cc_widget_container .cc_preface p { margin:0.25em 0; padding:0; }
					.cc_widget_container .cc_input_email { max-width:95%; }
					.cc_widget_container .cc_email_image { 
						float:right;
						margin-right:5px;
						margin-top:3px; 
						display:block; 
						padding:0; 
						overflow:hidden; 
					}
					.cc_widget_container .cc_email_image img {
						background-color: #006699;
						float:right;
					}
					';
					
					
					if($email_image == 1) {
						$cssOut .= ' .cc_widget_container .cc_email_image_1, .cc_widget_container .cc_email_image_1 img { width:19px; height:14px; }';
					} elseif($email_image == 2) {
						$cssOut .= ' .cc_widget_container .cc_email_image_2, .cc_widget_container .cc_email_image_2 img { width:17px; height:12px; }';
					} elseif($email_image == 3) {
						$cssOut .= ' .cc_widget_container .cc_email_image_3, .cc_widget_container .cc_email_image_3 img { width:12px; height:9px; }';
					} elseif($email_image == 4) {
						$cssOut .= ' .cc_widget_container .cc_email_image_4, .cc_widget_container .cc_email_image_4 img { width:19px; height:14px; }';
					} elseif($email_image == 5) {
						$cssOut .= ' .cc_widget_container .cc_email_image_5, .cc_widget_container .cc_email_image_5 img { width:19px; height:11px; }';
					}
					$cssOut .= ' .cc_widget_container .cc_email_image img { border:0; padding:0; margin:0; }';
	
					$cssOut = apply_filters('cc_widget_style', apply_filters('cc_widget_style_'.$this->number, '<style type="text/css">'.trim($cssOut).'</style>'));
					echo $cssOut;
				}
			}
			$constantContactWidgetPrintedStyles = true;
 		}
 		
 		function widget_scripts() {
 			global $pagenow;
 			if($pagenow == 'widgets.php') {
				?>
				<script type="text/javascript">
				jQuery.noConflict();
				function not_empty(value) {
					if(value && value != '' && value != 'undefined' && value != 'null') { return true; } return false;
				}
				function is_empty(value) {
					if(not_empty(value)) { return false; } return true;
				}
				
				jQuery(document).ready(function($) { 
					
					$('input[name*=style], input[name*=show_fieldset]', $('div[id*="constantcontactwidget"]')).live('click change', function() {
						var div = $(this).parents('div[id*="constantcontactwidget"]');
						styleoptions(div);
					});
					
					$('div[id*="constantcontactwidget"] .accordion h3').live('click', function(e) {
						var div = $(this).attr('rel');
						
						if(is_empty(div)) { return false; }
						
						var $container = $(div).parents('div[id*="constantcontactwidget"]');
						var $that = $(this);
						if ( $(div).is('.open') || $(div).is(':visible') ) {
							$that.addClass('open');
							$(div).addClass('open');
							$('input[name*=constantcontactwidget][class=visible]', $container).val(div);  // This is necessary because a click triggers on load
							return false;
						} else {
							$('.accordion .section:not('+div+')', $container).slideUp('fast').removeClass('open');
							if(e.type == 'loading') {
								$(div).show().addClass('open');
								$('.accordion h3', $container).removeClass('open');
								$that.addClass('open');
								$('input[name*=constantcontactwidget][class=visible]', $container).val(div);
							} else {
								$(div).slideDown('fast', function() {
									$('.accordion h3', $container).removeClass('open');
									$that.addClass('open');
									$('input[name*=constantcontactwidget][class=visible]', $container).val(div);
								}).addClass('open');
							}
							return false;
						}
						return false;
					});
					
					$('div[id*="constantcontactwidget"]').live('cc_save', function(e) {
						if(not_empty($(this).attr('id')) && $(this).attr('id').match(/\_\_\i\_\_/ism)) { return; }// This is not in a sidebar.
						
						$('.accordion .section', $(this)).hide();

						var visible = $('input[type=hidden][class=visible]', $(this)).val();
						if(visible == '' || visible == 'undefined') {
							$('.accordion h3:eq(0)', $(this)).click();
						} else {
							$('.accordion h3[rel='+visible+']', $(this)).click();
						}
						
						styleoptions($(this));
					});
					
					
					
					function styleoptions($this) {
						if($('input[id*=style_0]:checked', $this).attr('checked')) {
							$('.style_0_options', $this).slideDown('fast');
						} else {
							$('.style_0_options', $this).slideUp('fast');
						}
						if($('input[id*=style_1]', $this).attr('checked') == true) {
							$('.style_1_options', $this).slideDown('fast');
						} else {
							$('.style_1_options', $this).slideUp('fast');
						}
						if($('input[id*=style_3]', $this).attr('checked') == true) {
							$('.style_3_options', $this).slideDown('fast');
						} else {
							$('.style_3_options', $this).slideUp('fast');
						}
						if($('input[id*=show_fieldset]', $this).attr('checked') == true) {
							$('p[class*=legend]', $this).slideDown('fast');
						} else {
							$('p[class*=legend]', $this).slideUp('fast');
						}						
					}
					
					$('div[id*="constantcontactwidget"] a.toggle_formcode').live('click', function(e) {
						e.preventDefault();
						$($(this).attr('href')).slideToggle();
						return false;
					});
					
					$('div[id*="constantcontactwidget"]').each(function() { $(this).trigger('cc_save'); });
				});

				jQuery(document).ajaxSuccess(function(e, xhr, settings) {
					var widget_id_base = 'constantcontactwidget';
					if((settings.data.search('action=save-widget') != -1 && settings.data.search('id_base=' + widget_id_base) != -1) ||
					   (settings.data.search('action=add-widget') != -1 && settings.data.search('id_base=' + widget_id_base) != -1)) {
					   	var widgetId = String(settings.data.match(/constantcontactwidget\-[0-9]+/ism));
						if(widgetId != '' && widgetId != 'undefined' && widgetId != 'null') {
					 		jQuery('div[id*="'+widgetId+'"]').trigger('cc_save');
					 	}
					}
						
				});
			</script>
				<?php
 			}
 		}
 	 	 
 	 	function shortcode($atts) {
 	 		global $post; // prevent before content
			if(!is_admin()) {
				$atts = extract(shortcode_atts($this->defaults, $atts));
				$settings = $this->get_settings();
				if(!is_numeric($id) && !empty($id)) { return; }
				if(empty($id) && sizeof($settings) > 0) { // For users who didn't have multiple widgets before.
					foreach($settings as $key => $s) { $id = $key; }
				}
				if(isset($settings[$id])) { $instance = $settings[$id]; } else { return; }

				return $this->process_form($instance,array());
			}
		}
	
	    function update($new_instance, $old_instance) {
			$instance = $new_instance;
			
			if(isset($instance['code'])) {
				if(preg_match('/name\=\"m\"\ value\=\"([0-9]+)\"/ism', $instance['code'], $matches)) {
					$instance['uid'] = $matches[1];
				} else {
					$instance['uid'] = '';
				}
			}
			
			return $instance;

	    }
	    
	    function widget($args, $instance) {      
	        $output = '';
	        $processform = $this->process_form($instance,$args);
	        if(!$processform) { return false; }
	        
	        extract( $args );
	        $settings = shortcode_atts($this->defaults, $instance);
	       	extract($settings);
	       	
	    	if($hide === 'yes' || $show_widget === 'no' || empty($code)) { return; }
			
			$output .= $before_widget;
            $output .= "\n\t".$processform."\n\t";
			$output .=  $after_widget; 
			
			$output = apply_filters('cc_signup_form_widget', $output);
			echo $output;
			return;
	    }
	    
	    function process_color($color) {
    		$color = str_replace('#', '', $color);
			// HEX
			if(preg_match('/#?[0-9A-Fa-f]{6}/ism', $color)) {
				$color = str_replace('#', '', $color);
				$color = strtolower('#'.$color);
			}
			// Named
			else {
				$color = strtolower($color);
			}
			return $color;
	    }
	    function get_width($width, $unit, $style = 0) {
	    	// We've taken out the Width Type option and switched to 
			// typing it in. Not hard. If not yet set up, then this happens.
			if(empty($width) || $width == '0px') {
				if($style == 2) {
					$width = '170px';
				} else {
					$width = '100%';
				}
			} else if(!preg_match('/(px|per|\%)/ism', $width, $matches)) {
				$unit = esc_attr($unit);
				$unit = ($unit == 'per') ? '%' : 'px';
				$width = $width.$unit;
			}
			
			return $width;
	    }
	    function process_form($instance, $args) {
	    	extract( $args );
	    	$settings = shortcode_atts($this->defaults, $instance); 
	    	extract($settings);
	    	
	    	if($safesubscribe == 'no') {
				$safe_subscribe_img = '';
				$safe_subscribe_link = '';
			} else {
				$safe_subscribe_img = '<img src="'.$this->url.'/images/safesubscribe-'.$safesubscribe.'.gif" width="168" height="14" alt="SafeSubscribe with Constant Contact"  style="border:0;padding:0; margin:0;" />';
				$safe_subscribe_link = '<a href="http://conta.cc/safe-subscribe" id="cc_safesubscribe" rel="nofollow">'.$safe_subscribe_img.'</a>';
			}
			
			$uid = (empty($uid) && !empty($code)) ? $code : $uid;
			if(empty($uid)) { return false; }
			
			$inital_value_email = wptexturize(stripslashes($initial_value_email));
			
#			$width = false;
			$unit = false;
			$width_style = false;
#			if(isset($width)) {
#				$width = intval(stripslashes($width));
#			}
			
			
			$width = $this->get_width($width, $unit, $style);
			
			$fieldset = ($show_fieldset !== 'no') ? true : false;
			$bg_color = $this->process_color($bg_color);
			$border_color = $this->process_color($border_color);
			if(strtolower($email_label) == 'none') { $email_label = false; }
			
			if($email_image === '') {
				if($style == 3) { $email_image = 1;}
				else { $email_image = 0;}
			}
			$email_image_code = $email_image_size = '';
			if(!empty($email_image)) {
				if($email_image == 1) { $email_image_size = ' width="19" height="14"'; }
				elseif($email_image == 2) { $email_image_size = ' width="17" height="12"'; }
				elseif($email_image == 3) { $email_image_size = ' width="12" height="9"'; }
				elseif($email_image == 4) { $email_image_size = ' width="19" height="14"'; }
				elseif($email_image == 5) { $email_image_size = ' width="19" height="11"'; }
				$email_image_code = '<span class="cc_email_image cc_email_image_'.$email_image.'"><img src="'.$this->url.'/images/email'.$email_image.'_trans.gif" alt="Email icon"'.$email_image_size.' /></span>';
			}
			
			
			// If '0' or 'none', show no initial value JS switching
			if(empty($initial_value_email) || $initial_value_email == 'none') { $show_initial_value = false; } else { $show_initial_value = true; }
			
			$title = wptexturize(esc_attr($title));
			if(!empty($preface)) { $preface = wpautop($preface); }
			
			if($style == 1) {
				// Basic design (Image at http://img.constantcontact.com/ui/images1/signupbox_form_basicS.gif)
				$pre = '
				<div class="cc_widget_container cc_style_1 cc_form_wrapper_'.$this->number.'">
				'.$email_image_code.'
				<label class="cc_widget_title" for="'.$this->get_field_id('cc_label_email').'">'.$title.'</label>
				<div class="cc_preface">'.$preface.'</div>';
				
				$form = '<form name="ccoptin" action="http://visitor.constantcontact.com/d.jsp" %%target%% method="post" onsubmit="if (this.ea.value == \''.$inital_value_email.'\' || this.ea.value == \'\') { alert(\'Please enter your email.\'); return false;}">
				<!-- Constant Contact Widget by Katz Web Services, Inc. -->
				<input type="hidden" name="m" value="'.$uid.'" />
				<input type="hidden" name="p" value="oi" />';
				if($email_label) {
				$form .= '<label class="cc_label_email" for="'.$this->get_field_id('cc_label_email').'">'.$email_label.'</label>';
				}
				$form .= '<input type="text" id="'.$this->get_field_id('cc_label_email').'" class="cc_input_email" name="ea" size="'.$input_size.'" ';
				if($show_initial_value) { 
					$form .= 'value="'.$inital_value_email.'" onfocus="if (this.value == \''.$inital_value_email.'\') {this.value = \'\';}" onblur="if (this.value == \'\') {this.value = \''.$inital_value_email.'\';}"';
				} else {
					$form .= 'value=""';
				}
				$form .= ' />
				<input type="submit" id="cc_go" id="go" value="'.$button.'" class="submit cc_submit" />
				</form>';
				
				$post = '
				</div>';
				
				$safe = '<div class="cc_safesubscribe">'.$safe_subscribe_link.'</div>';
				
				$link = '<div class="cc_email_marketing">
				For <a href="http://conta.cc/email-marketing-trust" rel="nofollow" target="_blank">Email Newsletters</a> you can trust
				</div>';		
			}
			elseif($style == 2) { 
				// Bubble design (Image at http://img.constantcontact.com/ui/images1/signupbox_form_bubbleS.gif)
				$width2 = $width - 18;
				$pre = '<div class="cc_widget_container cc_style_2">
				<table width="'.$width.'" cellpadding="0" >
				<tr class="cc_top" height="1">
					<td width="9" rowspan="2" height="1"><img src="'.$this->url.'/images/tl_brdr2_trans.gif" width="9" height="9" /></td>
					<td width="'.$width2.'" height="1" bgcolor="#006699"><img src="'.$this->url.'/images/spacer.gif" border="0" width="1" height="1"></td>
					<td width="9" rowspan="2" height="1" align="right"><img src="'.$this->url.'/images/tr_brdr2_trans.gif" /></td>
				</tr>
				<tr>
					<td width="'.$width2.'" height="8" bgcolor="#ffffff" colspan="1"><img src="'.$this->url.'/images/spacer.gif" border="0" width="1" height="1"></td>
				</tr>
				<tr class="cc_middle">
				<td width="'.$width.'" colspan="3" class="cc_main">';
				
				$form = '
				<form name="ccoptin" action="http://visitor.constantcontact.com/d.jsp" %%target%% method="post" onsubmit="if (this.ea.value == \''.$inital_value_email.'\' || this.ea.value == \'\') { alert(\'Please enter your email.\'); return false;}">'.$email_image_code.'
				<!-- Constant Contact Widget by Katz Web Services, Inc. -->
				<label class="cc_widget_title" for="'.$this->get_field_id('cc_label_email').'">'.$title.'</label>';
				$form .= '<div class="cc_preface">'.$preface.'</div>';
				if($email_label) {
				$form .= '<label class="cc_label_email" for="'.$this->get_field_id('cc_label_email').'">'.$email_label.'</label>';
				}
				$form .= '<input type="text" id="'.$this->get_field_id('cc_label_email').'" name="ea" size="'.$input_size.'" ';
				if($show_initial_value) { 
					$form .= 'value="'.$inital_value_email.'" onfocus="if (this.value == \''.$inital_value_email.'\') {this.value = \'\';}" onblur="if (this.value == \'\') {this.value = \''.$inital_value_email.'\';}" onblur="if (this.value == \'\') {this.value = \''.$inital_value_email.'\';}"';
				} else {
					$form .= 'value=""';
				}
				$form .= ' class="cc_input_email" />&nbsp;
				<input type="submit" id="cc_go" id="go" value="'.$button.'" class="cc_submit submit" /> 
				<input type="hidden" name="m" value="'.$uid.'" />
				<input type="hidden" name="p" value="oi" />
				</form>
				';
				
				$post = '</td>
				</tr>
				<tr class="cc_bottom">
				<td rowspan="2"><img src="'.$this->url.'/images/bl_brdr2_trans.gif" width="9" height="9" border="0" /></td>
				<td width="152" height="8" bgcolor="#ffffff"><img src="'.$this->url.'/images/spacer.gif" border="0" width="1" height="1"></td>
				<td rowspan="2" align="right"><img src="'.$this->url.'/images/br_brdr2_trans.gif" width="9" height="9" border="0"></td>
				</tr>
				<tr>
				<td width="152" bgcolor="#006699"><img src="'.$this->url.'/images/spacer.gif" border="0" width="1" height="1"></td>
				</tr>
				</table>
				</div>';
				
				$safe = '<div align="center" class="cc_safesubscribe">'.$safe_subscribe_link.'</div>';
				
				$link = '<div align="center" class="cc_email_marketing">
				For <a href="http://conta.cc/email-marketing-trust" rel="nofollow" target="_blank">Email Newsletters</a> you can trust
				</div>';		
			}
			elseif($style == 3) {
				// The 'Stylish' design (Image at http://img.constantcontact.com/ui/images1/signupbox_form_stylishS.gif)
				if(!$width) {
					$width = 160;
				}
				$pre = '<div class="cc_widget_container cc_style_3">';
				$form = '';
				$form .= '<form name="ccoptin" action="http://visitor.constantcontact.com/d.jsp" %%target%% method="post" onsubmit="if (this.ea.value == \''.$inital_value_email.'\' || this.ea.value == \'\') { alert(\'Please enter your email.\'); return false;}">'.$email_image_code.'
				<label for="ea" class="cc_widget_title">'.$title.'</label>
				<div class="cc_preface">'.$preface.'</div>
				<input type="text" id="ea" name="ea" size="'.$input_size.'" ';
				if($show_initial_value) { 
					$form .= 'value="'.$inital_value_email.'" onfocus="if (this.value == \''.$inital_value_email.'\') {this.value = \'\';}" onblur="if (this.value == \'\') {this.value = \''.$inital_value_email.'\';}" ';
				} else {
					$form .= 'value="" style="font-family:Verdana,Geneva,Arial,Helvetica,sans-serif; font-size:10px; border:1px solid #999999; color: #666666;"';
				}
				$form .= ' class="cc_input_email" />
				<input type="submit" id="go"  id="go" name="go" value="'.$button.'" class="submit cc_submit">
				<input type="hidden" id="m" name="m" value="'.$uid.'" />
				<input type="hidden" id="p" name="p" value="oi" />
				</form>';
				
				$post = '</div>';
			
				$link = '<div align="center" class="cc_email_marketing">
							For <a href="http://conta.cc/email-marketing-trust" rel="nofollow" target="_blank">Email Newsletters</a> you can trust
						</div>';
				
				$safe = '<div align="center" class="cc_safesubscribe">'.$safe_subscribe_link.'</div>';
			
			}
			else {
			// No style
				
				$pre = $before_title."\n".$title."\n".$after_title;
				
				$form = '
				<form name="ccoptin" action="http://visitor.constantcontact.com/d.jsp" %%target%% method="post" onsubmit="if(this.ea.value == \''.$inital_value_email.'\' || this.ea.value == \'\') { alert(\'Please enter your email.\'); document.getElementById(\''.$this->get_field_id('cc_label_email').'\').focus(); return false;}">
				<!-- Constant Contact Widget by Katz Web Services, Inc. -->';
				
				if($fieldset) {
				$form .= '
				<fieldset>';
				}
				if(htmlspecialchars(stripslashes($legend)) != '') { $form .= '<legend>'.htmlspecialchars(stripslashes($legend)).'</legend>'; };
				if($email_label) {
				$form .= '
				<label for="ea">'.$email_label.'</label>';
				}
				$form .= '
				<input type="text" name="ea" size="'.$input_size.'" ';
				if($show_initial_value) { 
					$form .= 'value="'.$inital_value_email.'" onfocus="if (this.value == \''.$inital_value_email.'\') {this.value = \'\';}" onblur="if (this.value == \'\') {this.value = \''.$inital_value_email.'\';}"';
				} else {
					$form .= 'value=""';
				}
				$form .= ' />
				<button type="submit" class="submit" id="go" name="go">'.$button.'</button>
				<input type="hidden" id="m" name="m" value="'.$uid.'" />
				<input type="hidden" id="p" name="p" value="oi" />';
				if($fieldset) {
				$form .= '
				</fieldset>';
				}
				$form .= '
				</form>'."\n";
				
				$post = '';
				
				$link = '<p>For <a href="http://conta.cc/email-marketing-trust" rel="external nofollow">Email Newsletters</a> you can trust</p>';
				
				$safe = $safe_subscribe_link;	
			}

			$widget = $pre;
			if($https === 'yes') { $form = str_replace('http://', 'https://', $form); }
			if($target && $target === 'yes') { $form = str_replace('%%target%%', 'target="_blank"', $form);} else { $form = str_replace('%%target%%', '', $form);}
			$widget .= $form;
			$widget .= $post;
			
			if(isset($show_safesubscribe) && $show_safesubscribe == 'yes' || (empty($show_safesubscribe) || $show_safesubscribe != 'no') && $safesubscribe != 'no') {
				$widget .= $safe;
			}
			if($show_cc_code == 'yes' || $show_cc_code == '') {
				$widget .= $link;
			}

			$widget = apply_filters('cc_signup_form_widget', $widget);
			
			return $widget;
	    }
	     
	    function form($instance = array()) {
	    	$settings = shortcode_atts($this->defaults, $instance); 
	    	
			extract($settings);
			
			// Backward compatibility
			if(!empty($form_title)) { $title = $form_title; }
			if(!empty($form_wrap)) { $wrap = $form_wrap; }
			if(!empty($form_preface)) { $preface = $form_preface; }
			if(!empty($form_button)) { $button = $form_button; }
			if(!empty($form_code)) { $uid = $form_code; }
			$code = trim($code);
			if(!empty($code) && preg_match('/^([0-9]+)$/ism', $code)) { $uid = $code; } // If they enter the UID, or upgrade.
			if($email_image === '') {
				if($style == 3) { $email_image = 1;}
				else { $email_image = 0;}
			}
			
	        if(is_int($this->number) || !$this->number) { $kwd_number = $this->number; } else { $kwd_number = '#';}
	        
	        ?>
<div class="cc_widget_container">
	        <div id="cccodediv<?php echo $this->number; ?>" <?php if(!empty($uid) && !empty($code)) { echo ' style="display:none;"'; } ?>>
		        <h3>1. Enter the HTML Code</h3>
				<p>Paste the HTML Code from Constant Contact (Contacts > Join My Mailing List > Start Wizard to generate HTML code). <a href="http://www.constantcontact.com/display_media.jsp?id=15t" target="_blank">View tutorial video</a>.</p>
				<p>Don't have Constant Contact? <a href="http://conta.cc/email-marketing-trust">Try a 60 day free trial</a>, then you can use this widget.</p>
				<p>
					<label for="<?php echo $this->get_field_id('code'); ?>"><span><?php _e('Form HTML Code (Required!)'); ?></span><span class="howto">You can also enter the Unique ID if you know it (a string of numbers ~14 digits long).</span>
					<textarea class="widefat textarea" id="<?php echo $this->get_field_id('code'); ?>" cols="20" rows="5"  name="<?php echo $this->get_field_name('code'); ?>"><?php echo htmlspecialchars($code); ?></textarea></label>				
				</p>
				
				<?php if(!empty($uid)) { $this->make_textfield($uid, 'uid', 'uid', '<span>Unique ID</span><span class="howto">Only change if you know what you are doing.</span>'); } ?>
		        
	        </div>
	        
	        <?php
	        if(!empty($uid)) { 
	        	echo '<p><a href="#cccodediv'.$this->number.'" class="toggle_formcode">Show/Hide HTML Form Code</a></p>'; 
	        ?>
	        
	    
 		<div class="accordion stuffbox">
 		
 			<h3 class="in-widget-title" rel="#cc-settings-<?php echo $this->number; ?>">Text &amp; Input Settings<span class="cc-arrow"></span></h3>
 			<div class="section" id="cc-settings-<?php echo $this->number; ?>">
 			
 			<?php $this->make_textfield($title, 'title', 'title', '<span>Widget Title</span><span class="howto">Shown above the form.</span>'); ?>
 			
 			<?php #$this->make_textfield($headline, 'headline', 'headline', '<span>Form Headline</span><span class="howto">This is the headline shown inside the form.</span>'); ?>
			
			<p><label for="<?php echo $this->get_field_id('preface'); ?>"><span>Widget Description</span>
 			<span class="howto">Text displayed below Widget Title. Plain text will be turned into paragraphs. <code>HTML</code> accepted.</span>
 			<textarea class="widefat textarea" cols="20" rows="3" id="<?php echo $this->get_field_id('preface'); ?>" name="<?php echo $this->get_field_name('preface'); ?>"><?php 
 				if(!empty($wrap) && $email_image === '') { $preface = "<$wrap>".trim($preface)."</$wrap>"; }
 				echo wpautop($preface);
 			?></textarea>
 			</label></p>
	 		<?php
 			$this->make_textfield($initial_value_email, 'initial_value_email', 'initial_value_email', '<span>Email field initial value:</span><span class="howto">This is shown inside the email address input field and disappears when users click to enter their email address. To turn off this functionality, leave blank.</span>'); 
			
			$this->make_textfield($email_label, 'email_label', 'email_label', '<span>Email Label:</span><span class="howto">What would you like the label to be for the email input? To hide, leave blank.');
			
			$this->make_textfield($button, 'button', 'button', '<span>Button Text:</span>');
 
 			?>
 				<p>
			        <label for="<?php echo $this->get_field_id('input_size'); ?>"><span>Form Field Width</span>
					<select id="<?php echo $this->get_field_id('input_size'); ?>" name="<?php echo $this->get_field_name('input_size'); ?>">
						  <option value="10"<?php selected($input_size, 10);?>>10</option>
						  <option value="11"<?php selected($input_size, 11);?>>11</option>
						  <option value="12"<?php selected($input_size, 12);?>>12</option>
						  <option value="13"<?php selected($input_size, 13);?>>13</option>
						  <option value="14"<?php selected($input_size, 14);?>>14</option>
						  <option value="15"<?php selected($input_size, 15);?>>15</option>
						  <option value="16"<?php selected($input_size, 16);?>>16</option>
						  <option value="17"<?php selected($input_size, 17);?>>17</option>
						  <option value="18"<?php selected($input_size, 18);?>>18</option>
						  <option value="19"<?php selected($input_size, 19);?>>19</option>
						  <option value="20"<?php selected($input_size, 20);?>>20</option>
						  <option value="21"<?php selected($input_size, 21);?>>21</option>
						  <option value="22"<?php selected($input_size, 22);?>>22</option>
						  <option value="23"<?php selected($input_size, 23);?>>23</option>
						  <option value="24"<?php selected($input_size, 24);?>>24</option>
						  <option value="25"<?php selected($input_size, 25);?>>25</option>
						  <option value="26"<?php selected($input_size, 26);?>>26</option>
						  <option value="27"<?php selected($input_size, 27);?>>27</option>
						  <option value="28"<?php selected($input_size, 28);?>>28</option>
						  <option value="29"<?php selected($input_size, 29);?>>29</option>
						  <option value="30"<?php selected($input_size, 30);?>>30</option>
						  <option value="31"<?php selected($input_size, 31);?>>31</option>
						  <option value="32"<?php selected($input_size, 32);?>>32</option>
						  <option value="33"<?php selected($input_size, 33);?>>33</option>
						  <option value="34"<?php selected($input_size, 34);?>>34</option>
						  <option value="35"<?php selected($input_size, 35);?>>35</option>
						  <option value="36"<?php selected($input_size, 36);?>>36</option>
						  <option value="37"<?php selected($input_size, 37);?>>37</option>	  
						  <option value="38"<?php selected($input_size, 38);?>>38</option>
						  <option value="39"<?php selected($input_size, 39);?>>39</option>
						  <option value="40"<?php selected($input_size, 40);?>>40</option>
					  </select>
					  <span class="howto">Width of form inputs (in characters)</span></label>
				</p>
 			</div>
 			
 			
 			<h3 class="in-widget-title" rel="#cc-design-<?php echo $this->number; ?>">Design Preset Settings<span class="cc-arrow"></span></h3>
 			<div class="section" id="cc-design-<?php echo $this->number; ?>">
 				<?php
 					$this->make_radio($style, 'style_0','style', '0', '<span>No Style</span><span class="howto">Output only the form HTML, with no styling.</span>');
 				?>
 				<fieldset class="style_0_options">
 					<legend>Additional Settings</legend>
 				<?php 
 					
 					$this->make_checkbox($show_fieldset, 'show_fieldset','show_fieldset', 'Use <code>&lt;fieldset&gt;</code>?<span class="howto">This groups the form elements together.</span>'); 
 					$this->make_textfield($legend, 'legend', 'legend', '<span>Legend</span><span class="howto">Leave empty to hide. Otherwise, it will be a form <code>&lt;legend&gt;</code>; a label for the <code>&lt;fieldset&gt;</code>.</span>');
 					?>
 				</fieldset><?php
 					
 					$this->make_radio($style, 'style_1','style', '1', '<span>Basic Design</span><span class="howto">This is also the most valid HTML of the choices.</span><img src="'.$this->url.'/images/signupbox_form_basicS.gif" width="125" height="32" style="padding-left:1.25em; padding-bottom:.5em;" />');
 					
 				?>
 				<fieldset class="style_1_options">
 					<legend>Additional Settings</legend>
 			<p class="<?php echo $this->get_field_id('border_width'); ?>">
				<label><span>Border Width:</span><span class="howto">Modify the thickness of the form's border.</span>
				
				<select name="<?php echo $this->get_field_name('border_width'); ?>" id="<?php echo $this->get_field_id('border_width'); ?>">
					<option value="0"<?php selected($border_width, 0); ?>>No Border</option>
					<option value="1"<?php selected($border_width, 1); ?>>1px</option>
					<option value="2"<?php selected($border_width, 2); ?>>2px</option>
					<option value="3"<?php selected($border_width, 3); ?>>3px</option>
					<option value="4"<?php selected($border_width, 4); ?>>4px</option>
					<option value="5"<?php selected($border_width, 5); ?>>5px</option>
					<option value="6"<?php selected($border_width, 6); ?>>6px</option>
					<option value="7"<?php selected($border_width, 7); ?>>7px</option>
					<option value="8"<?php selected($border_width, 8); ?>>8px</option>
					<option value="9"<?php selected($border_width, 9); ?>>9px</option>
					<option value="10"<?php selected($border_width, 10); ?>>10px</option>
					<option value="11"<?php selected($border_width, 11); ?>>11px</option>
					<option value="12"<?php selected($border_width, 12); ?>>12px</option>
					<option value="13"<?php selected($border_width, 13); ?>>13px</option>
					<option value="14"<?php selected($border_width, 14); ?>>14px</option>
					<option value="15"<?php selected($border_width, 15); ?>>15px</option>
					<option value="20"<?php selected($border_width, 20); ?>>20px</option>
					<option value="25"<?php selected($border_width, 25); ?>>25px</option>
					<option value="30"<?php selected($border_width, 30); ?>>30px</option>
				</select>
				</label>
			</p>
 					
 					<?php $this->make_textfield($bg_color, 'bg_color', 'bg_color', '<span>Change Background Color:</span><span class="howto">If you know the <a href="http://en.wikipedia.org/wiki/List_of_colors" target="_blank" title="Go to a Wikipedia article with a list of colors. Opens in new window.">HEX value</a> for the background color you want, enter it here. Ex: <code>#F4C2C2</code>.</span>'); ?>
 				
					<?php $this->make_textfield($border_color, 'border_color', 'border_color', '<span>Change Border Color:</span><span class="howto">If you know the <a href="http://en.wikipedia.org/wiki/List_of_colors" target="_blank" title="Go to a Wikipedia article with a list of colors. Opens in new window.">HEX value</a> for the border color you want, enter it here. Ex: <code>#F4C2C2</code>.</span>'); ?>

				</fieldset>
				<?php
 					
 					$this->make_radio($style, 'style_2','style', '2', '<span>Bubble Design</span><span class="howto">Only suitable for websites with white background.</span><img src="'.$this->url.'/images/signupbox_form_bubbleS.gif" width="125" height="47" style="padding-left:1.25em; padding-bottom:.5em;" />');
 					
 					$this->make_radio($style, 'style_3','style', '3', '<span>Stylish Design</span><span class="howto"><img src="'.$this->url.'/images/signupbox_form_stylishS.gif" width="125" height="47" style="padding-left:1.25em; padding-bottom:.5em;" /></span>');?>
 			</div>
			
			<h3 class="in-widget-title" rel="#cc-visual-<?php echo $this->number; ?>">Visual Settings<span class="cc-arrow"></span></h3>
 			<div class="section" id="cc-visual-<?php echo $this->number; ?>">
 			
	 			<?php $this->make_textfield($width, 'width', 'width', '<span>Custom Width:</span><span class="howto">Enter the form width in <span title="pixels">px</span> or <span title="percent">%</span>. Examples: <code>250px</code> or <code>100%</code>. <em>% is default</em>. <strong>You can also leave blank.</strong></span>'); ?>
	 			
	 		<p><label><span>Email Image:</span></label>
	 		<span class="howto">This image will be displayed to the right of the Widget Title.</span></p>
 			<?php	
 			
 			$this->make_radio($email_image, 'email_image_1','email_image', '1', '<img src="'.$this->url.'/images/email1_trans.gif" style="background-color: #006699;" width="19" height="14" />'); 
 			$this->make_radio($email_image, 'email_image_2','email_image', '2', '<img src="'.$this->url.'/images/email2_trans.gif" style="background-color: #006699;" width="17" height="12" />'); 
 			$this->make_radio($email_image, 'email_image_3','email_image', '3', '<img src="'.$this->url.'/images/email3_trans.gif" style="background-color: #006699;" width="12" height="9" />'); 
 			$this->make_radio($email_image, 'email_image_4','email_image', '4', '<img src="'.$this->url.'/images/email4_trans.gif" style="background-color: #006699;" width="19" height="14" />'); 
 			$this->make_radio($email_image, 'email_image_5','email_image', '5', '<img src="'.$this->url.'/images/email5_trans.gif" style="background-color: #006699;" width="19" height="11" />'); 
 			$this->make_radio($email_image, 'email_image_0','email_image', '0', 'No image'); 
 			?>
	 			
					<p><label><span><?php _e('Show SafeSubscribe:'); ?></span></label>
					<span class="howto">SafeSubscribe logo re-assures potential subscribers that it will be painless to unsubscribe if they choose to. Choose which image will look best with your theme.</span></p>
					<ul>
					  	<li><label for="<?php echo $this->get_field_id('safesubscribelight'); ?>"><input type="radio" value="light" name="<?php echo $this->get_field_name('safesubscribe'); ?>" id="<?php echo $this->get_field_id('safesubscribelight'); ?>" <?php checked($safesubscribe, 'light'); checked($safesubscribe, ''); ?> /> <small>Gray Text</small> <img src="<?php echo $this->url.'/images/';?>safesubscribe-light.gif" alt="SafeSubscribe Light" width="168" height="14" id="safesubscribelightimg" title="Gray"/></label></li>
					  	<li><label for="<?php echo $this->get_field_id('safesubscribedark'); ?>"><input type="radio" value="dark" name="<?php echo $this->get_field_name('safesubscribe'); ?>" id="<?php echo $this->get_field_id('safesubscribedark'); ?>"  <?php checked($safesubscribe, 'dark'); ?>/> <small>White Text</small> <img src="<?php echo $this->url.'/images/';?>safesubscribe-dark.gif" alt="SafeSubscribe Dark" width="168" height="14" id="safesubscribedarkimg" title="White" style="background:black;"/></label></li>
					  	<li><label for="<?php echo $this->get_field_id('safesubscribeblack'); ?>"><input type="radio" value="black" name="<?php echo $this->get_field_name('safesubscribe'); ?>" id="<?php echo $this->get_field_id('safesubscribeblack'); ?>" <?php checked($safesubscribe, 'black'); ?>/> <small>Black Text</small> <img src="<?php echo $this->url.'/images/';?>safesubscribe-black.gif" alt="SafeSubscribe Black" width="168" height="14" id="safesubscribeblackimg" title="Black"  style="background:white;"/></label></li>
					  	<li><label for="<?php echo $this->get_field_id('safesubscribeno'); ?>"><input type="radio" value="no" name="<?php echo $this->get_field_name('safesubscribe'); ?>" id="<?php echo $this->get_field_id('safesubscribeno'); ?>" <?php checked($safesubscribe, 'no'); ?>/> <small>Do Not Display</small></label></li>
					</ul>
				
			<?php
		            $this->make_checkbox($show_cc_code, 'show_cc_code','show_cc_code', 'Show "For Email Newsletters you can trust" <span class="howto">Showing this text lets users know that you are not a spammer and will not abuse their trust.</span>');
		            	             
		           ?>
		   	</div>
		   	
		    <h3 class="in-widget-title" rel="#cc-form-<?php echo $this->number; ?>">Form Settings<span class="cc-arrow"></span></h3>
 			<div class="section" id="cc-form-<?php echo $this->number; ?>">
	 			
				<?php
					$this->make_checkbox($https, 'https','https', 'Make Form HTTPS <span class="howto">If you are on a secure site (eCommerce, for example), you should check this.</span>');
		            
		            $this->make_checkbox($target, 'target','target', 'Open form in new window when submitted'); 
		            
		            
		            $this->make_checkbox($credit, 'credit','credit', 'Give Thanks &amp; Credit <span class="howto">If you want to show thanks to the widget author, this will add a text link to the website\'s footer. <strong>It is much appreciated</strong>, and you can always turn it off.</span>');
		            
		            $this->make_checkbox($hide, 'hide','hide', 'Do not display widget in sidebar. <span class="howto">If you are exclusively using the <code>[constantcontact id='.$kwd_number.']</code> shortcode, not the sidebar widget. <strong>Note:</strong> you can use a widget in <em>both</em> sidebar and shortcode at the same time.</span>');
		        ?>
			</div>
	  </div><!-- End .accordion -->
	  <input type="hidden" value="<?php echo $visible; ?>" class="visible" name="<?php echo $this->get_field_name('visible'); ?>" />
	           <?php
	        }
	        echo '</div> <!-- // End .cc_widget_container -->';
	    }
	    
	    function make_textfield($setting = '', $fieldid = '', $fieldname='', $title = '', $error = '') {
			$input = '';
		    $fieldid = $this->get_field_id($fieldid);
		    $fieldname = $this->get_field_name($fieldname);
		    if(!empty($error)) {
		    	 $input .= '<div style="background-color: rgb(255, 235, 232);border-color: rgb(204, 0, 0);-webkit-border-bottom-left-radius: 3px 3px;-webkit-border-bottom-right-radius: 3px 3px;-webkit-border-top-left-radius: 3px 3px;-webkit-border-top-right-radius: 3px 3px;border-style: solid;border-width: 1px;margin: 5px 0px 15px;padding: 10px 0.6em 0;"><div class="wrap"><label for="'.$fieldid.'">'.wpautop($error).'</label></div></div>';
		    }
		    
			$input .= '
			<p class="'.$fieldid.'">
				<label for="'.$fieldid.'">'.__($title).'
				<input type="text" class="widefat" id="'.$fieldid.'" name="'.$fieldname.'" value="'.$setting.'"/>
				</label>
			</p>';
			
			echo $input;
		}    
		function make_checkbox($setting = '', $fieldid = '', $fieldname='', $title = '') {
			$fieldid = $this->get_field_id($fieldid);
		    $fieldname = $this->get_field_name($fieldname);
		    
			$checkbox = '
			<p class="'.$fieldid.'">
				<input type="hidden" name="'.$fieldname.'" value="no" />
				<input type="checkbox" id="'.$fieldid.'" name="'.$fieldname.'" value="yes"'.checked($setting, 'yes', false).' class="checkbox" />
				<label for="'.$fieldid.'">'.__($title).'</label>
			</p>';
		    echo $checkbox;
		}
		function make_radio($setting = '', $fieldid = '', $fieldname='', $value = '', $title = '') {
			$fieldid = $this->get_field_id($fieldid);
		    $fieldname = $this->get_field_name($fieldname);
		    
			$checkbox = '
			<p class="'.$fieldid.'">
				<input type="radio" id="'.$fieldid.'" name="'.$fieldname.'" value="'.$value.'"'.checked($setting, $value, false).' class="radio" />
				<label for="'.$fieldid.'">'.__($title).'</label>
			</p>';
		    echo $checkbox;
		}
		
		function is_valid_url($location, $default = '') {
			return $location;
	    	if(preg_match('/^(http\:\/\/|https\:\/\/)(([a-z0-9]([-a-z0-9]*[a-z0-9]+)?){1,63}\.)+[a-z]{2,6}/ism', $location) && parse_url($location)) {
	    		return $location;
	    	}
	    	if(empty($default)) { return false; } else { return $default; }
	    }
		
		function add_link($code=null) {
	    	$attr = $this->attr();
			if(!is_wp_error($attr) && !empty($attr)) { $code .= $attr; } else { $code .= $link; }
			return $code;
	    }
	    
	    function attr() {
			global $post;// prevents calling before <HTML>
			if($post && !is_admin()) {
				$default = '<span class="kwd_cc" style="text-align:center; display:block; margin:0 auto; line-height:2;"><a href="http://bit.ly/constant-contact-email" rel="nofollow">Email Newsletters with Constant Contact</a></span>';
				$url = 'http://www.katzwebservices.com/development/attribution.php?site='.htmlentities(substr(get_bloginfo('url'), 7)).'&from=cc_widget&version='.$this->version;
				// > 2.8
				if(function_exists('fetch_feed')) {
					include_once(ABSPATH . WPINC . '/feed.php');
					if ( !$rss = fetch_feed($url) ) { return false; }
					if(!is_wp_error($rss)) {
						// This list is only missing 'style', 'id', and 'class' so that those don't get stripped.
						// See http://simplepie.org/wiki/reference/simplepie/strip_attributes for more information.
						$strip = array('bgsound','expr','onclick','onerror','onfinish','onmouseover','onmouseout','onfocus','onblur','lowsrc','dynsrc');
						$rss->strip_attributes($strip);
						$rss->set_cache_duration(60*60*24*60); // Fetch every 60 days
						$rss_items = $rss->get_items(0, 1);	
						foreach ( $rss_items as $item ) {
							return str_replace(array("\n", "\r"), ' ', $item->get_description());
						}
					}
					return $default;
				} else { // < 2.8
					require_once(ABSPATH . WPINC . '/rss.php');
					if ( !$rss = fetch_rss($url) )
						return;
					$items = 1;
					if ( is_array( $rss->items ) && !empty( $rss->items ) ) {
						$rss->items = array_slice($rss->items, 0, $items);
						foreach ($rss->items as $item ) {
							if ( isset( $item['description'] ) && is_string( $item['description'] ) )
								$summary = $item['description'];
							$desc = str_replace(array("\n", "\r"), ' ', $summary);
							$summary = '';
							return $desc;
						}
					}
					return $default;
				}
			}
		}
	    
	} 	
}

?>