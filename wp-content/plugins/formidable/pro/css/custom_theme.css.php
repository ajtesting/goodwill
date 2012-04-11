<?php 
if(!isset($saving))
    header("Content-type: text/css");

if (isset($_GET['frm_weight'])){
    $form_align = $_GET['frm_form_align'];
    $fieldset = $_GET['frm_fieldset'];
    $fieldset_color = $_GET['frm_fieldset_color'];
    $fieldset_padding = $_GET['frm_fieldset_padding'];
    
    $font = $_GET['frm_font'];
    $font_size = $_GET['frm_font_size'];
    $label_color = $_GET['frm_label_color'];
    $weight = $_GET['frm_weight']; 
    $position = $_GET['frm_position'];
    $align = $_GET['frm_align'];
    $width = $_GET['frm_width'];
    $required_color = $_GET['frm_required_color'];
    $required_weight = $_GET['frm_required_weight'];
    
    $description_font = $_GET['frm_description_font'];
    $description_font_size = $_GET['frm_description_font_size'];
    $description_color = $_GET['frm_description_color'];
    $description_weight = $_GET['frm_description_weight'];
    $description_style = $_GET['frm_description_style']; 
    $description_align = $_GET['frm_description_align'];
    
    $field_font_size = $_GET['frm_field_font_size'];
    $field_width = $_GET['frm_field_width'];
    $auto_width = isset($_GET['frm_auto_width']) ? $_GET['frm_auto_width'] : 0;
    $field_pad = $_GET['frm_field_pad'];
    $field_margin = $_GET['frm_field_margin'];
    
    $text_color = $_GET['frm_text_color'];
    $bg_color = $_GET['frm_bg_color'];
    $border_color = $_GET['frm_border_color'];
    $field_border_width = $_GET['frm_field_border_width'];
    $field_border_style = $_GET['frm_field_border_style'];
    
    //$bg_color_hv = $_GET['frm_bg_color_hv'];
    //$border_color_hv = $_GET['frm_border_color_hv'];
    
    $bg_color_active = $_GET['frm_bg_color_active'];
    $border_color_active = $_GET['frm_border_color_active'];
    
    $text_color_error = $_GET['frm_text_color_error'];
    $bg_color_error = $_GET['frm_bg_color_error'];
    $border_color_error = $_GET['frm_border_color_error'];
    $border_width_error = $_GET['frm_border_width_error'];
    $border_style_error = $_GET['frm_border_style_error'];
    
    $radio_align = $_GET['frm_radio_align'];
    $check_align = $_GET['frm_check_align'];
    $check_font = $_GET['frm_check_font'];
    $check_font_size = $_GET['frm_check_font_size'];
    $check_label_color = $_GET['frm_check_label_color'];
    $check_weight = $_GET['frm_check_weight'];
    
    $submit_style = isset($_GET['frm_submit_style']) ? $_GET['frm_submit_style'] : 0;
    $submit_font_size = $_GET['frm_submit_font_size'].' !important';
    $submit_width = $_GET['frm_submit_width'];
    $submit_height = $_GET['frm_submit_height'];
    $submit_bg_color = $_GET['frm_submit_bg_color'];
    $submit_bg_color2 = $_GET['frm_submit_bg_color2'];
    $submit_bg_img = $_GET['frm_submit_bg_img'];
    $submit_border_color = $_GET['frm_submit_border_color'];
    $submit_border_width = $_GET['frm_submit_border_width'];
    $submit_text_color = $_GET['frm_submit_text_color'];
    $submit_weight = $_GET['frm_submit_weight'];
    $submit_border_radius = $_GET['frm_submit_border_radius'];
    $submit_margin = $_GET['frm_submit_margin'];
    $submit_padding = $_GET['frm_submit_padding'].' !important';
    $submit_shadow_color = $_GET['frm_submit_shadow_color'];
    
    $border_radius = $_GET['frm_border_radius'];
    
    $error_bg = $_GET['frm_error_bg'];
    $error_border = $_GET['frm_error_border'];
    $error_text = $_GET['frm_error_text'];
    $error_font_size = $_GET['frm_error_font_size'];
    
    $success_bg_color = $_GET['frm_success_bg_color'];
    $success_border_color = $_GET['frm_success_border_color'];
    $success_text_color = $_GET['frm_success_text_color'];
    $success_font_size = $_GET['frm_success_font_size'];
}else{    
    global $frmpro_settings;
    extract((array)$frmpro_settings);
}
$label_margin = (int)$width + 15; ?>
.with_frm_style, .with_frm_style form{text-align:<?php echo $form_align ?>;}
.with_frm_style fieldset{border:<?php echo $fieldset ?> solid #<?php echo $fieldset_color ?>;margin:0;padding:<?php echo $fieldset_padding ?>;}
.with_frm_style label.frm_primary_label{font-family:<?php echo stripslashes($font) ?>;font-size:<?php echo $font_size ?>;color:#<?php echo $label_color ?>;font-weight:<?php echo $weight ?>;text-align:<?php echo $align ?>;margin:0;padding:0;width:auto;display:block;}
.with_frm_style .form-field{margin-bottom:<?php echo $field_margin ?>;}
.with_frm_style .form-field.frm_col_field{clear:none;float:left;margin-right:20px;}
.with_frm_style p.description, .with_frm_style div.description, .with_frm_style div.frm_description, .with_frm_style .frm_error{margin:0;padding:0;font-family:<?php echo stripslashes($description_font) ?>;font-size:<?php echo
$description_font_size ?>;color:#<?php echo $description_color ?>;font-weight:<?php echo $description_weight ?>;text-align:<?php echo $description_align ?>;font-style:<?php echo $description_style ?>;}
.with_frm_style .frm_left_container p.description, .with_frm_style .frm_left_container div.description, .with_frm_style .frm_left_container div.frm_description, .with_frm_style .frm_left_container .frm_error{margin-left:<?php echo $label_margin ?>px;}
.with_frm_style .form-field.frm_col_field div.frm_description{width:<?php echo ($field_width == '') ? 'auto' : $field_width ?>;}
.with_frm_style .frm_left_container .attachment-thumbnail{clear:both;margin-left:<?php echo $label_margin ?>px;}
.with_frm_style .frm_right_container p.description, .with_frm_style .frm_right_container div.description, .with_frm_style .frm_right_container div.frm_description, .with_frm_style .frm_right_container .frm_error{margin-right:<?php echo $label_margin ?>px;}
.with_frm_style .frm_top_container label.frm_primary_label, .with_frm_style .frm_hidden_container label.frm_primary_label, .with_frm_style .frm_pos_top{display:block;float:none;width:auto;}
.with_frm_style .frm_left_container label.frm_primary_label{display:inline;float:left;margin-right:10px;width:<?php echo $width; ?>;}
.with_frm_style .frm_right_container label.frm_primary_label, .with_frm_style .frm_pos_right{display:inline;float:right;margin-left:10px;width:<?php echo $width; ?>;}
.with_frm_style .frm_none_container label.frm_primary_label, .with_frm_style .frm_pos_none{display:none;}
.with_frm_style .frm_hidden_container label.frm_primary_label, .with_frm_style .frm_pos_hidden{visibility:hidden;}
.with_frm_style .frm_10radio{margin-right:10px;text-align:center;float:left;}
.with_frm_style .frm_required{color:#<?php echo $required_color; ?>;font-weight:<?php echo $required_weight; ?>;}
.with_frm_style .frm_form_fields input[type=text], .with_frm_style .frm_form_fields input[type=password], .with_frm_style .frm_form_fields input[type=email], .with_frm_style .frm_form_fields input[type=number], .with_frm_style .frm_form_fields input[type=url], .with_frm_style .frm_form_fields select, .with_frm_style .frm_form_fields textarea, #content .with_frm_style .frm_form_fields input:not([type=submit]), #content .with_frm_style .frm_form_fields select, #content .with_frm_style textarea{font-family:<?php echo stripslashes($font) ?>;font-size:<?php echo $field_font_size ?>;margin-bottom:0;}
.with_frm_style .frm_form_fields input[type=text], .with_frm_style .frm_form_fields input[type=password], .with_frm_style .frm_form_fields input[type=email], .with_frm_style .frm_form_fields input[type=number], .with_frm_style .frm_form_fields input[type=url], .with_frm_style .frm_form_fields select, .with_frm_style .frm_form_fields textarea, .frm_form_fields_style, .frm_form_fields_active_style, .frm_form_fields_error_style{color:#<?php echo $text_color ?>;background-color:#<?php echo $bg_color ?>;border-color:#<?php echo $border_color ?>;border-width:<?php echo $field_border_width ?>;border-style:<?php echo $field_border_style ?>;-moz-border-radius:<?php echo $border_radius ?>;-webkit-border-radius:<?php echo $border_radius ?>;border-radius:<?php echo $border_radius ?>;width:<?php echo ($field_width == '') ? 'auto' : $field_width ?>;font-size:<?php echo $field_font_size ?>;padding:<?php echo $field_pad ?>;}
.with_frm_style .frm_form_fields select{width:<?php echo ($auto_width) ? 'auto' : $field_width ?>;}
.with_frm_style .frm_form_fields input[type="radio"], .with_frm_style .frm_form_fields input[type="checkbox"]{width:auto;border:none;background:transparent;padding:0;}
.with_frm_style .frm_catlevel_2, .with_frm_style .frm_catlevel_3, .with_frm_style .frm_catlevel_4, .with_frm_style .frm_catlevel_5{margin-left:18px;}
/*.with_frm_style .form-field table td, .with_frm_style .form-field table th{color:#<?php echo $text_color ?>;background-color:#<?php echo $bg_color ?>;border-color:#<?php echo $border_color ?>;}*/
.with_frm_style .wp-editor-wrap{width:<?php echo $field_width ?>;}
.with_frm_style .quicktags-toolbar input{font-size:12px !important;}
.with_frm_style .nicEdit-selectTxt{line-height:14px;}
.with_frm_style .nicEdit-panelContain{border-color:#<?php echo $border_color ?> !important;}
.with_frm_style .nicEdit-main{margin:0 !important;padding:4px;width:auto !important;outline:none;color:#<?php echo $text_color ?>;background-color:#<?php echo $bg_color ?>;border-color:#<?php echo $border_color ?> !important;border-width:1px;border-style:<?php echo $field_border_style ?>;border-top:none;}
.with_frm_style .frm_form_fields input.auto_width, .with_frm_style .frm_form_fields select.auto_width, .with_frm_style .frm_form_fields textarea.auto_width{width:auto;}
.with_frm_style input[disabled], .with_frm_style select[disabled], .with_frm_style textarea[disabled], .with_frm_style input[readonly], .with_frm_style select[readonly], .with_frm_style textarea[readonly]{opacity:.5;filter:alpha(opacity=50);}
.frm_set_select .with_frm_style select, .frm_set_select .with_frm_style select.auto_width{width:100%;}
.with_frm_style .frm_form_fields input:focus, .with_frm_style .frm_form_fields select:focus, .with_frm_style .frm_form_fields textarea:focus, .with_frm_style .frm_focus_field input[type=text], .with_frm_style .frm_focus_field input[type=password], .with_frm_style .frm_focus_field input[type=email], .with_frm_style .frm_focus_field input[type=number], .with_frm_style .frm_focus_field input[type=url], .frm_form_fields_active_style{background-color:#<?php echo $bg_color_active ?>;border-color:#<?php echo $border_color_active ?>;}
<?php if(!$submit_style){ ?>
.with_frm_style .submit input[type="submit"], .frm_form_submit_style{width:<?php echo ($submit_width == '') ? 'auto' : $submit_width ?>;font-family:<?php echo stripslashes($font) ?>;font-size:<?php echo $submit_font_size; ?>;height:<?php echo $submit_height ?>;text-align:center;background:#<?php echo $submit_bg_color ?> url(<?php echo $submit_bg_img ?>);border-width:<?php echo $submit_border_width ?>;border-color:#<?php echo $submit_border_color ?>;border-style:solid;color:#<?php echo $submit_text_color ?>;cursor:pointer;font-weight:<?php echo $submit_weight ?>;-moz-border-radius:<?php echo $submit_border_radius ?>;-webkit-border-radius:<?php echo $submit_border_radius ?>;border-radius:<?php echo $submit_border_radius ?>;text-shadow:none;padding:<?php echo $submit_padding ?>;-moz-box-sizing:content-box;box-sizing:content-box;-ms-box-sizing:content-box;<?php if(empty($submit_bg_img)){ ?>filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#<?php echo $submit_bg_color ?>', endColorstr='#<?php echo $submit_bg_color2 ?>');background:-webkit-gradient(linear, left top, left bottom, from(#<?php echo $submit_bg_color ?>), to(#<?php echo $submit_bg_color2 ?>));background:-moz-linear-gradient(top, #<?php echo $submit_bg_color ?>, #<?php echo $submit_bg_color2 ?>);<?php } ?>-moz-box-shadow:1px 2px 3px #<?php echo $submit_shadow_color; ?>;-webkit-box-shadow:1px 2px 3px #<?php echo $submit_shadow_color; ?>;box-shadow:1px 2px 3px #<?php echo $submit_shadow_color; ?>;-ms-filter:"progid:DXImageTransform.Microsoft.Shadow(Strength=3, Direction=135, Color='#<?php echo $submit_shadow_color; ?>')";filter:progid:DXImageTransform.Microsoft.Shadow(Strength=3, Direction=135, Color='#<?php echo $submit_shadow_color; ?>');}
.with_frm_style p.submit{padding-top:<?php echo $submit_margin ?>;padding-bottom:<?php echo $submit_margin ?>}
<?php if(empty($submit_bg_img)){ ?>.with_frm_style .submit input[type="submit"]:focus{filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#<?php echo $submit_bg_color2 ?>', endColorstr='#<?php echo $submit_bg_color ?>');background:-webkit-gradient(linear, left top, left bottom, from(#<?php echo $submit_bg_color2 ?>), to(#<?php echo $submit_bg_color ?>));background:-moz-linear-gradient(top, #<?php echo $submit_bg_color2 ?>, #<?php echo $submit_bg_color ?>);}
<?php } ?>
<?php } ?>
.frm_form_submit_style{height:auto;}
.with_frm_style .frm_radio{display:<?php echo $radio_align ?>;}
.with_frm_style .frm_left_container .frm_radio{margin<?php echo ($radio_align == 'block') ? "-left:{$label_margin}px;" : ':0'; ?>}
.with_frm_style .frm_right_container .frm_radio{margin<?php echo ($radio_align == 'block') ? "-right:{$label_margin}px;" : ':0'; ?>}
.with_frm_style .frm_checkbox{display:<?php echo $check_align ?>;}
.with_frm_style .frm_left_container .frm_checkbox{margin<?php echo ($check_align == 'block') ? "-left:{$label_margin}px;" : ':0'; ?>}
.with_frm_style .frm_right_container .frm_checkbox{margin<?php echo ($check_align == 'block') ? "-right:{$label_margin}px;" : ':0'; ?>}
.with_frm_style .vertical_radio .frm_checkbox, .with_frm_style .vertical_radio .frm_radio, .vertical_radio .frm_catlevel_1{display:block;}
.with_frm_style .horizontal_radio .frm_checkbox, .with_frm_style .horizontal_radio .frm_radio, .horizontal_radio .frm_catlevel_1{display:inline;}
.with_frm_style .frm_radio label, .with_frm_style .frm_checkbox label{font-family:<?php echo $check_font ?>;font-size:<?php echo $check_font_size ?>;color:#<?php echo $check_label_color ?>;font-weight:<?php echo $check_weight ?>;display:inline;}
.with_frm_style .frm_radio input[type="radio"], .with_frm_style .frm_checkbox input[type="checkbox"]{margin-right:5px;width:auto;}
.with_frm_style input[type="radio"],.with_frm_style input[type="checkbox"]{width:auto;}
.with_frm_style .frm_blank_field input[type=text], .with_frm_style .frm_blank_field input[type=password], .with_frm_style .frm_blank_field input[type=url], .with_frm_style .frm_blank_field input[type=number], .with_frm_style .frm_blank_field input[type=email], .with_frm_style .frm_blank_field textarea, .with_frm_style .frm_blank_field select, .frm_form_fields_error_style, .with_frm_style *:invalid, .with_frm_style *:-moz-submit-invalid, .with_frm_style *:-moz-ui-invalid, .with_frm_style .frm_blank_field #recaptcha_area{color:#<?php echo $text_color_error ?>;background-color:#<?php echo $bg_color_error ?>;border-color:#<?php echo $border_color_error ?>;border-width:<?php echo $border_width_error ?>;border-style:<?php echo $border_style_error ?>;}
.with_frm_style :invalid, .with_frm_style :-moz-submit-invalid, .with_frm_style :-moz-ui-invalid {box-shadow:none;}
.with_frm_style .frm_error{font-weight:<?php echo $weight ?>;}
.with_frm_style .frm_blank_field label, .with_frm_style .frm_error{color:#<?php echo $border_color_error ?>;}
.with_frm_style .frm_error_style{background-color:#<?php echo $error_bg ?>;border:2px solid #<?php echo $error_border ?>;color:#<?php echo $error_text ?>;font-size:<?php echo $error_font_size ?>;margin:0;margin-bottom:<?php echo $field_margin ?>;padding:5px 10px;}
.with_frm_style .frm_error_style img{padding-right:10px;vertical-align:middle;}
.with_frm_style .frm_trigger{cursor:pointer;}
.with_frm_style .frm_message, .frm_success_style{border:1px solid #<?php echo $success_border_color ?>;background-color:#<?php echo $success_bg_color ?>;color:#<?php echo $success_text_color ?>;}
.with_frm_style .frm_message{padding:5px 10px;margin:5px 0 15px;font-size:<?php echo $success_font_size ?>;}
.frm_form_fields_style, .frm_form_fields_active_style, .frm_form_fields_error_style, .frm_form_submit_style{width:auto;}
.with_frm_style .frm_trigger span{float:left;}
.with_frm_style table.frm-grid, #content .with_frm_style table.frm-grid{border-collapse:collapse;border:none;}
.with_frm_style .frm-grid td, .frm-grid th{padding:5px;border-width:1px;border-style:solid;border-color:#<?php echo $border_color ?>;border-top:none;border-left:none;border-right:none;}
.form_results.with_frm_style{border-color:<?php echo $field_border_width ?> solid #<?php echo $border_color ?>;}
.form_results.with_frm_style tr td{text-align:left;color:#<?php echo $text_color ?>;padding:7px 9px;border-top:<?php echo $field_border_width ?> solid #<?php echo $border_color ?>;}
.form_results.with_frm_style tr.frm_even{background-color:#<?php echo $bg_color ?>;}
.form_results.with_frm_style tr.frm_odd{background-color:#<?php echo $bg_color_active ?>;}
div.time-picker{position:absolute;height:191px;width:5em;overflow:auto;background:#fff;border:1px solid #aaa;z-index:99;margin:0;}
div.time-picker-12hours{width:8.5em;}
div.time-picker ul{list-style-type:none;margin:0;padding:0;}
div.time-picker li{color:#000;cursor:pointer;font-size:<?php echo $font_size ?>;font-family:<?php echo stripslashes($font) ?>;padding:0 3px;}
div.time-picker li.selected{background:#3875d7;color:#fff;}
#frm_loading{display:none;position:fixed;top:0;left:0;width:100%;height:100%;}
#frm_loading h3{font-weight:bold;padding-bottom:15px;}
#frm_loading_content{position:fixed;top:20%;left:33%;width:33%;text-align:center;color:#<?php echo $text_color ?>;background:#<?php echo $bg_color_active ?>;border:2px solid #<?php echo $border_color_active ?>;padding:30px;font-weight:bold;}
.frmcal-title{font-size:116%;}
.frmcal table.frmcal-calendar{margin-top:20px;border:none;color:#<?php echo $text_color ?>;}
.frmcal table.frmcal-calendar, .frmcal, .frmcal-header{width:100%;}
.frmcal-header{text-align:center;}
.frmcal-prev{float:left;}
.frmcal-next{float:right;}
.frmcal table.frmcal-calendar thead tr th{text-align:center;padding:2px 4px;}
.frmcal table.frmcal-calendar tbody tr td{height:110px;width:14.28%;vertical-align:top;padding:0 !important;border:1px solid #<?php echo $border_color ?>;color:#<?php echo $text_color ?>;font-size:12px;}
table.frmcal-calendar .frmcal_date{background-color:#<?php echo $bg_color ?>;padding:0 5px;text-align:right;-moz-box-shadow:0 2px 5px #<?php echo $border_color ?>;-webkit-box-shadow:0 2px 5px #<?php echo $border_color ?>;box-shadow:0 2px 5px #<?php echo $border_color ?>;-ms-filter:"progid:DXImageTransform.Microsoft.Shadow(Strength=4, Direction=180, Color='#<?php echo $border_color ?>')";filter:progid:DXImageTransform.Microsoft.Shadow(Strength=4, Direction=180, Color='#<?php echo $border_color ?>');}
.frmcal-content{padding:2px 4px;}
.frm-loading-img{background:url(<?php echo FRM_IMAGES_URL ?>/ajax_loader.gif) no-repeat center center;padding:6px 12px;}
#ui-datepicker-div{display:none;z-index:999 !important;}
.frm_form_fields div.rating-cancel, .frm_form_fields div.star-rating{float:left;width:17px;height:15px;text-indent:-999em;cursor:pointer;display:block;background:transparent;overflow:hidden;}
.frm_form_fields div.rating-cancel a{background:url(<?php echo FRMPRO_IMAGES_URL ?>/delete.png) no-repeat 0 -16px}
.frm_form_fields div.star-rating, .frm_form_fields div.star-rating a{background:url(<?php echo FRMPRO_IMAGES_URL ?>/star.png) no-repeat 0 0px}
.frm_form_fields div.rating-cancel a, .frm_form_fields div.star-rating a{display:block;width:16px;height:100%;background-position:0 0px;border:0}
.frm_form_fields div.star-rating-on a{background-position:0 -16px!important}
.frm_form_fields div.star-rating-hover a{background-position:0 -32px}
.frm_form_fields div.rating-cancel.star-rating-hover a{background-position:0 -16px}
.frm_form_fields div.star-rating-readonly a{cursor:default !important}
.frm_form_fields div.star-rating{background:transparent!important;overflow:hidden!important}