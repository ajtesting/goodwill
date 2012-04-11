<?php
global $frm_form, $frm_field, $frm_entry, $frm_entry_meta, $user_ID, $frm_settings;
$form_name = $form->name;
$form->options = stripslashes_deep(maybe_unserialize($form->options));

$submit = isset($form->options['submit_value']) ? $form->options['submit_value'] : $frm_settings->submit_value;
$saved_message = isset($form->options['success_msg']) ? $form->options['success_msg'] : $frm_settings->success_msg;

$params = FrmEntriesController::get_params($form);
$message = $errors = '';

FrmEntriesHelper::enqueue_scripts($params);

if($params['action'] == 'create' and $params['posted_form_id'] == $form->id and isset($_POST)){
    $errors = $frm_entry->validate($_POST);

    if( !empty($errors) ){
        $fields = FrmFieldsHelper::get_form_fields($form->id, true);
        $values = FrmEntriesHelper::setup_new_vars($fields, $form);
        require('new.php'); 
?>
<script type="text/javascript">window.onload = function(){var frm_pos=jQuery('#form_<?php echo $form->form_key ?>').offset();
window.scrollTo(frm_pos.left,frm_pos.top);}</script><?php        
    }else{
        $fields = FrmFieldsHelper::get_form_fields($form->id);
        do_action('frm_validate_form_creation', $params, $fields, $form, $title, $description);
        if (apply_filters('frm_continue_to_create', true, $form->id)){
            $values = FrmEntriesHelper::setup_new_vars($fields, $form, true);
            $created = $frm_entry->create( $_POST );
            $saved_message = apply_filters('frm_content', $saved_message, $form, $created);
            $conf_method = apply_filters('frm_success_filter', 'message', $form, $form->options);
            if (!$created or !is_numeric($created) or $conf_method == 'message'){
                $message = '<div class="frm_message" id="message">'.(($created and is_numeric($created)) ? wpautop(do_shortcode($saved_message)) : $frm_settings->failed_msg).'</div>';
                if (!isset($form->options['show_form']) or $form->options['show_form']){
                    require('new.php');
                }else{ 
                    global $frm_forms_loaded, $frm_load_css, $frm_css_loaded;
                    $frm_forms_loaded[] = $form; 
                    if($values['custom_style']) $frm_load_css = true;

                    if(!$frm_css_loaded and $frm_load_css){
                        echo FrmAppController::footer_js('header');
                        $frm_css_loaded = true;
                    }
?>
<div class="frm_forms<?php echo ($values['custom_style']) ? ' with_frm_style' : ''; ?>" id="frm_form_<?php echo $form->id ?>_container"><?php echo $message ?></div>
<?php
                }
            }else
                do_action('frm_success_action', $conf_method, $form, $form->options, $created);
        }
    }
}else{
    $fields = FrmFieldsHelper::get_form_fields($form->id);
    do_action('frm_display_form_action', $params, $fields, $form, $title, $description);
    if (apply_filters('frm_continue_to_new', true, $form->id, $params['action'])){
        $values = FrmEntriesHelper::setup_new_vars($fields, $form);
        require('new.php');
    }
}

?>