
<script type="text/javascript">
//<![CDATA[
<?php 
if($frm_rules){
    echo "__FRMRULES=".json_encode($frm_rules) .";\n";
    echo "__FRMURL='". FRM_SCRIPT_URL ."';\n";
}

if(!empty($frm_timepicker_loaded)){ ?>
if(typeof(timepicker) == 'undefined') document.write(unescape("%3Cscript src='<?php echo FRMPRO_URL ?>/js/jquery.timePicker.min.js' type='text/javascript'%3E%3C/script%3E"));
<?php }

if(!empty($frm_rte_loaded)){
    foreach($frm_rte_loaded as $rte_field_id){ ?>
bkLib.onDomLoaded(function(){new nicEditor({fullPanel:true,iconsPath:'<?php echo FRMPRO_IMAGES_URL ?>/nicEditIcons.gif'<?php do_action('frm_rte_js', $rte_field_id) ?>}).panelInstance("<?php echo $rte_field_id ?>",{hasPanel:true});});
<?php }
} 

?>
jQuery(document).ready(function($){
$('.frm-show-form').submit(function(e){e.preventDefault();frmGetFormErrors(this,'<?php echo FRM_SCRIPT_URL ?>');});
<?php
if(!empty($frm_hidden_fields) or (!empty($frm_datepicker_loaded) and is_array($frm_datepicker_loaded))
or (isset($load_lang) and !empty($load_lang)) or !empty($frm_timepicker_loaded) or !empty($frm_calc_fields)){
if(!empty($frm_hidden_fields)){
global $frm_field;
    $checked_fields = array();
    foreach($frm_hidden_fields as $field){
        foreach($field['hide_field'] as $i => $hide_field){
            if(!is_numeric($hide_field) or in_array($hide_field, $checked_fields))
                continue;
             
            $checked_fields[] = $hide_field; 
            
            $observed_field = $frm_field->getOne($hide_field);
            
            if($observed_field){
                $observed_field->field_options = maybe_unserialize($observed_field->field_options);
                if (isset($field['hide_opt'][$i]) and $field['hide_opt'][$i] != ''){
                    switch($observed_field->type){
                    case "radio":
                    case "10radio":
                    case "scale":
                        require(FRMPRO_VIEWS_PATH.'/frmpro-fields/show-radio-js.php');
                        break;
                    case "checkbox":
                        require(FRMPRO_VIEWS_PATH.'/frmpro-fields/show-checkbox-js.php');
                        break;
                    case "data":
                        if (in_array($observed_field->field_options['data_type'], array('radio', 'checkbox', 'select'))) 
                            require(FRMPRO_VIEWS_PATH.'/frmpro-fields/show-'.$observed_field->field_options['data_type'].'-js.php');
                        break;
                    default:
                        require(FRMPRO_VIEWS_PATH.'/frmpro-fields/show-select-js.php');
                    }
                }else if ($observed_field->type == 'data'){
                    $observed_options = maybe_unserialize($observed_field->field_options);
                    require(FRMPRO_VIEWS_PATH.'/frmpro-fields/show-data-js.php');
                }
            }
        }
        

    }
}


if(!empty($frm_datepicker_loaded) and is_array($frm_datepicker_loaded)){
    global $frmpro_settings; 
    $load_lang = array();
    reset($frm_datepicker_loaded);
    $datepicker = key($frm_datepicker_loaded); 
    
foreach($frm_datepicker_loaded as $date_field_id => $options){ ?>  
$.datepicker.setDefaults($.datepicker.regional['']);
$("#<?php echo $date_field_id ?>").datepicker($.extend($.datepicker.regional['<?php echo $options['locale'] ?>'], {dateFormat:'<?php echo $frmpro_settings->cal_date_format ?>',changeMonth:true,changeYear:true,yearRange:'<?php echo $options['start_year'] .':'. $options['end_year'] ?>'<?php do_action('frm_date_field_js', $date_field_id, $options)?>}));
<?php 
if(!empty($options['locale'])) $load_lang[] = $options['locale'];
} 
} 

if(!empty($frm_timepicker_loaded)){
    $time_keys = array_keys($frm_timepicker_loaded);
    foreach($frm_timepicker_loaded as $time_field_id => $options){ ?>$("#<?php echo $time_field_id ?>").timePicker({show24Hours:<?php echo (isset($options['clock']) and $options['clock']) ? 'true' : 'false'; ?>,step:<?php echo (isset($options['step']) and $options['step']) ? $options['step'] : '30'; ?>,startTime:"<?php echo (isset($options['start_time']) and $options['start_time']) ? $options['start_time'] : '00:00'; ?>",endTime:"<?php echo (isset($options['end_time']) and $options['end_time']) ? $options['end_time'] : '23:59'; ?>"});

<?php if($options['unique'] and isset($datepicker)){ ?>
$("#<?php echo $datepicker ?>").change(function(){
    jQuery.ajax({
    	type:'POST',url:'<?php echo FRM_SCRIPT_URL ?>',dataType:'json',
    	data:'controller=fields&action=ajax_time_options&time_field=<?php echo $time_field_id ?>&date_field=<?php echo $datepicker ?>&step=<?php echo $options["step"] ?>&start=<?php echo $options["start_time"] ?>&end=<?php echo $options["end_time"] ?>&clock=<?php echo $options["clock"] ?>&entry_id=<?php echo $options["entry_id"] ?>&date='+$(this).val(),
    	success:function(opts){
    	    var timenum=<?php echo array_search($time_field_id, $time_keys) ?>;
    		if(opts && opts!=''){
    		    $('.time-picker:eq('+parseInt(timenum)+') ul').html('');
    		    for(var opt in opts){$('.time-picker:eq('+parseInt(timenum)+') ul').append('<li>'+opt+'</li>');}
    		}
		}
	});
});
<?php }
    }
}

if(!empty($frm_calc_fields)){ 
global $frmdb; 

foreach($frm_calc_fields as $result => $calc){ 
    preg_match_all("/\[(.?)\b(.*?)(?:(\/))?\]/s", $calc, $matches, PREG_PATTERN_ORDER);

    //if (!isset($matches[0])) return $value;
    $field_keys = $calc_fields = array();
    
    foreach ($matches[0] as $match_key => $val){
        $val = trim(trim($val, '['), ']');
        $calc_fields[$val] = FrmField::getOne($val); //get field
        
        if($calc_fields[$val] and in_array($calc_fields[$val]->type, array('radio', 'scale'))){
            $field_keys[$calc_fields[$val]->id] = 'input[name="item_meta['. $calc_fields[$val]->id .']"]';
        }else if($calc_fields[$val]->type == 'checkbox'){
            $field_keys[$calc_fields[$val]->id] = 'input[name="item_meta['. $calc_fields[$val]->id .'][]"]';
        }else{
            $field_keys[$calc_fields[$val]->id] = ($calc_fields[$val]) ? '#field_'. $calc_fields[$val]->field_key : '#field_'. $val; 
        }
        
        $calc = str_replace($matches[0][$match_key], 'vals[\''.$calc_fields[$val]->id.'\']', $calc);
    }
?>
$('<?php echo implode(",", $field_keys)?>').change(function(){
var vals=new Array();
<?php foreach($calc_fields as $calc_field){ 
if($calc_field->type == "checkbox"){
?>$('<?php echo $field_keys[$calc_field->id] ?>:checked').each(function(){ 
    if(isNaN(vals['<?php echo $calc_field->id ?>'])){vals['<?php echo $calc_field->id ?>']=0;}
    vals['<?php echo $calc_field->id ?>'] += parseFloat($(this).val().match(/\d*(\.\d*)?$/)); });
<?php }else{
?>vals['<?php echo $calc_field->id ?>']=$('<?php 
echo $field_keys[$calc_field->id]; 
if($calc_field->type == "radio" or $calc_field->type == "scale")
    echo ":checked";
else if($calc_field->type == "select")
    echo " option:selected";
?>').val();
if(typeof(vals['<?php echo $calc_field->id ?>'])=='undefined'){vals['<?php echo $calc_field->id ?>']=0;}else{ vals['<?php echo $calc_field->id ?>']=parseFloat(vals['<?php echo $calc_field->id ?>'].match(/\d*(\.\d*)?$/)); }
<?php } 
?>if(isNaN(vals['<?php echo $calc_field->id ?>'])){vals['<?php echo $calc_field->id ?>']=0;}
<?php }
?>
var total=parseFloat(<?php echo $calc ?>);if(isNaN(total)){total=0;}
$("#field_<?php echo $result ?>").val(total);
});
<?php } 
}
} ?>
});

<?php if(isset($load_lang) and !empty($load_lang)){ ?>
var frmJsHost=(("https:"==document.location.protocol)?"https://":"http://");
<?php foreach($load_lang as $lang){ ?>
document.write(unescape("%3Cscript src='"+frmJsHost+"ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/i18n/jquery.ui.datepicker-<?php echo $lang ?>.js' type='text/javascript'%3E%3C/script%3E"));
<?php }
} ?>
//]]>
</script>
