<?php
/**
 * @package Formidable
 */
 
class FrmEntriesController{
    
    function FrmEntriesController(){
        add_action('admin_menu', array( &$this, 'menu' ), 20);
    }
    
    function menu(){
        global $frmpro_is_installed;
        if(!$frmpro_is_installed){
            add_submenu_page('formidable', 'Formidable |'. __('Entries', 'formidable'), '<span style="opacity:.5;filter:alpha(opacity=50);">'. __('Entries', 'formidable') .'</span>', 'administrator', 'formidable-entries',array(&$this, 'list_entries'));
        }
    }
    
    function list_entries(){
        global $frm_form, $frm_entry;
        $form_select = $frm_form->getAll("is_template=0 AND (status is NULL OR status = '' OR status = 'published')", ' ORDER BY name');
        $form_id = FrmAppHelper::get_param('form', false);
        if($form_id)
            $form = $frm_form->getOne($form_id);
        else
            $form = (isset($form_select[0])) ? $form_select[0] : 0;
        
        if($form)
            $entry_count = $frm_entry->getRecordCount($form->id);
            
        require(FRM_VIEWS_PATH.'/frm-entries/list.php');
    }
    
    function show_form($id='', $key='', $title=false, $description=false){
        global $frm_form, $user_ID, $frm_settings, $post;
        if ($id) $form = $frm_form->getOne((int)$id);
        else if ($key) $form = $frm_form->getOne($key);
        
        $form = apply_filters('frm_pre_display_form', $form);
        
        if(!$form or 
            (($form->is_template or $form->status == 'draft') and !isset($_GET) and !isset($_GET['form']) and 
                (!isset($_GET['preview']) or $post and $post->ID != $frm_settings->preview_page_id))
            ){
            return __('Please select a valid form', 'formidable');
        }else if ($form->logged_in and !$user_ID){
            global $frm_settings;
            return $frm_settings->login_msg;
        }

        $form->options = stripslashes_deep(maybe_unserialize($form->options));
        if($form->logged_in and $user_ID and isset($form->options['logged_in_role']) and $form->options['logged_in_role'] != ''){
            if(FrmAppHelper::user_has_permission($form->options['logged_in_role'])){
                return FrmEntriesController::get_form(FRM_VIEWS_PATH.'/frm-entries/frm-entry.php', $form, $title, $description);
            }else{
                global $frm_settings;
                return $frm_settings->login_msg;
            }
        }else    
            return FrmEntriesController::get_form(FRM_VIEWS_PATH.'/frm-entries/frm-entry.php', $form, $title, $description);
    }
    
    function get_form($filename, $form, $title, $description) {
        if (is_file($filename)) {
            ob_start();
            include $filename;
            $contents = ob_get_contents();
            ob_end_clean();
            return $contents;
        }
        return false;
    }
    
    function get_params($form=null){
        global $frm_form;

        if(!$form)
            $form = $frm_form->getAll(array(), 'name', 1);
            
        $action = apply_filters('frm_show_new_entry_page', FrmAppHelper::get_param('action', 'new'), $form);
        $default_values = array('id' => '', 'form_name' => '', 'paged' => 1, 'form' => $form->id, 'form_id' => $form->id, 'field_id' => '', 'search' => '', 'sort' => '', 'sdir' => '', 'action' => $action);
            
        $values['posted_form_id'] = FrmAppHelper::get_param('form_id');
        if (!is_numeric($values['posted_form_id']))
            $values['posted_form_id'] = FrmAppHelper::get_param('form');

        if ($form->id == $values['posted_form_id']){ //if there are two forms on the same page, make sure not to submit both
            foreach ($default_values as $var => $default){
                $values[$var] = FrmAppHelper::get_param($var, $default);
                unset($var);
                unset($default);
            }
        }else{
            foreach ($default_values as $var => $default){
                $values[$var] = $default;
                unset($var);
                unset($default);
            }
        }
        
        if(in_array($values['action'], array('create', 'update')) and (!isset($_POST) or !isset($_POST['action'])))
            $values['action'] = 'new';

        return $values;
    }
    
}
?>