<?php

class FrmProEntriesController{
    
    function FrmProEntriesController(){
        add_action('admin_menu', array( &$this, 'menu' ), 20);
        add_filter('frm_nav_array', array( &$this, 'frm_nav'), 1);
        add_action('admin_init', array(&$this, 'admin_js'), 1);
        add_action('init', array(&$this, 'register_scripts'));
        add_action('wp_enqueue_scripts', array(&$this, 'add_js'));
        add_action('wp_footer', array(&$this, 'footer_js'), 1);
        add_action('admin_footer', array(&$this, 'footer_js'));
        add_action('frm_before_table', array( &$this, 'before_table'), 10, 2);
        add_action('wp_ajax_frm_import_csv', array( &$this, 'import_csv_entries') );
        add_action('frm_display_form_action',array(&$this, 'edit_update_form'), 10, 5);
        add_action('frm_submit_button_action', array($this, 'ajax_submit_button'), 10, 2);
        add_filter('frm_success_filter', array(&$this, 'get_confirmation_method'), 10, 2);
        add_action('frm_success_action', array(&$this, 'confirmation'), 10, 4);
        add_action('deleted_post', array(&$this, 'delete_entry'));
        
        //Shortcodes
        add_shortcode('formresults', array(&$this, 'get_form_results'));
        add_shortcode('frm-search', array(&$this, 'get_search'));
        add_shortcode('frm-entry-links', array(&$this, 'entry_link_shortcode'));
        add_shortcode('frm-entry-edit-link', array(&$this, 'entry_edit_link'));
        add_shortcode('frm-entry-update-field', array(&$this, 'entry_update_field'));
        add_shortcode('frm-entry-delete-link', array(&$this, 'entry_delete_link'));
    }
    
    function menu(){
        global $frm_settings;
        if(current_user_can('administrator') and !current_user_can('frm_view_entries')){
            global $wp_roles;
            $frm_roles = FrmAppHelper::frm_capabilities();
            foreach($frm_roles as $frm_role => $frm_role_description){
                if(!in_array($frm_role, array('frm_view_forms', 'frm_edit_forms', 'frm_delete_forms', 'frm_change_settings')))
                    $wp_roles->add_cap( 'administrator', $frm_role );
            }
        }
        add_submenu_page('formidable', $frm_settings->menu .' | '. __('Form Entries', 'formidable'), __('Form Entries', 'formidable'), 'frm_view_entries', 'formidable-entries', array($this, 'route'));
        
        add_filter('manage_'. sanitize_title($frm_settings->menu) .'_page_formidable-entries_columns', array(&$this, 'manage_columns'));
        add_filter('get_user_option_manage'. sanitize_title($frm_settings->menu) .'_page_formidable-entriescolumnshidden', array(&$this, 'hidden_columns'));
        add_action('admin_head-'. sanitize_title($frm_settings->menu) .'_page_formidable-entries', array(&$this, 'head'));
    }
    
    function frm_nav($nav){
        if(current_user_can('frm_view_entries'))
            $nav['formidable-entries'] = __('Entries', 'formidable');
        //if(current_user_can('frm_create_entries'))
        //    $nav['formidable-entries&action=new'] = __('Add New Entry', 'formidable');
        return $nav;
    }
    
    function head(){
        global $frmpro_settings;
        $css_file = array();
        $uploads = wp_upload_dir();
        if(file_exists($uploads['basedir'] .'/formidable/css/formidablepro.css')){
            if(is_ssl() and !preg_match('/^https:\/\/.*\..*$/', $uploads['baseurl']))
                $uploads['baseurl'] = str_replace('http://', 'https://', $uploads['baseurl']);
            $css_file[] = $uploads['baseurl'] .'/formidable/css/formidablepro.css';
        }else
            $css_file[] = FRM_SCRIPT_URL . '&amp;controller=settings';
        
        $css_file[] = FrmProAppHelper::jquery_css_url($frmpro_settings->theme_css);
        
        require(FRM_VIEWS_PATH . '/shared/head.php');
    }
    
    function admin_js(){
        if (isset($_GET) and isset($_GET['page']) and ($_GET['page'] == 'formidable-entries' or $_GET['page'] == 'formidable-entry-templates' or $_GET['page'] == 'formidable-import')){
            
            if(!function_exists('wp_editor')){
                    add_action( 'admin_print_footer_scripts', 'wp_tiny_mce', 25 );
                add_filter('tiny_mce_before_init', array(&$this, 'remove_fullscreen'));
                if ( user_can_richedit() ){
            	    wp_enqueue_script('editor');
            	    wp_enqueue_script('media-upload');
            	}
            	wp_enqueue_script('common');
            	wp_enqueue_script('post');
        	}
        	if($_GET['page'] == 'formidable-entries')
        	    wp_enqueue_script('jquery-ui-datepicker');
        }
    }
    
    function remove_fullscreen($init){
        if(isset($init['plugins'])){
            $init['plugins'] = str_replace('wpfullscreen,', '', $init['plugins']);
            $init['plugins'] = str_replace('fullscreen,', '', $init['plugins']);
        }
        return $init;
    }
    
    function register_scripts(){
        wp_register_script('jquery-rating', FRMPRO_URL . '/js/jquery.rating.min.js', array('jquery'), '3.13', true);
        wp_register_script('jquery-star-metadata', FRMPRO_URL . '/js/jquery.MetaData.js', array('jquery'), '', true);
        wp_register_script('nicedit', FRMPRO_URL . '/js/nicedit.js', array(), '', true);

        $date_ver = FrmProAppHelper::datepicker_version();
        wp_register_script('jquery-ui-datepicker', FRMPRO_URL . '/js/jquery.ui.datepicker'. $date_ver .'.js', array('jquery', 'jquery-ui-core'), empty($date_ver) ? '1.8.16' : trim($date_ver, '.'), true);
    }
    
    function add_js(){        
        if(is_admin())
            return;
         
        wp_enqueue_script('jquery-ui-core');
        
        global $frm_settings;
        if($frm_settings->accordion_js){
            wp_enqueue_script('jquery-ui-widget');
            wp_enqueue_script('jquery-ui-accordion', FRMPRO_URL.'/js/jquery.ui.accordion.js', array('jquery', 'jquery-ui-core'), '1.8.16', true);
        }
    }
    
    function footer_js(){
        global $frm_rte_loaded, $frm_datepicker_loaded, $frm_timepicker_loaded, $frm_star_loaded;
        global $frm_hidden_fields, $frm_forms_loaded, $frm_calc_fields, $frm_rules;
        
        if(empty($frm_forms_loaded))
            return;
            
        $form_ids = '';
        foreach($frm_forms_loaded as $form){
            if(!is_object($form))
                continue;
                
            if($form_ids != '')
                $form_ids .= ',';
            $form_ids .= '#form_'. $form->form_key;
        }
        
        $scripts = array();
        
        if(!empty($frm_rte_loaded))
            $scripts[] = 'nicedit';

        if(!empty($frm_datepicker_loaded))
            $scripts[] = 'jquery-ui-datepicker';

        if($frm_star_loaded){ 
            $scripts[] = 'jquery-rating';

            if(is_array($frm_star_loaded) and in_array('split', $frm_star_loaded))
                $scripts[] = 'jquery-star-metadata'; //needed for spliting stars
        }
        
        if(!empty($scripts)){
            global $wp_scripts;
            $wp_scripts->do_items( $scripts );
        }
        
        unset($scripts);
        
        include_once(FRMPRO_VIEWS_PATH.'/frmpro-entries/footer_js.php');
    }
    
    function before_table($footer, $form_id=false){
        FrmProEntriesHelper::before_table($footer, $form_id);
    }
    
    /* Back End CRUD */
    function show($id = false){
        if(!current_user_can('frm_view_entries'))
            wp_die('You are not allowed to view entries');
            
        global $frm_entry, $frm_field, $frm_entry_meta, $user_ID;
        if(!$id)
            $id = FrmAppHelper::get_param('id');
        if(!$id)
            $id = FrmAppHelper::get_param('item_id');
        
        $entry = $frm_entry->getOne($id, true);
        $data = maybe_unserialize($entry->description);

        $fields = $frm_field->getAll("fi.type not in ('captcha','html') and fi.form_id=". (int)$entry->form_id, 'fi.field_order');
        $date_format = get_option('date_format');
        $time_format = get_option('time_format');
        $show_comments = true;
        
        if(isset($_POST) and isset($_POST['frm_comment']) and !empty($_POST['frm_comment'])){
            FrmEntryMeta::add_entry_meta($_POST['item_id'], 0, '', serialize(array('comment' => $_POST['frm_comment'], 'user_id' => $user_ID)));
            //send email notifications
        }
        
        if($show_comments){
            $comments = $frm_entry_meta->getAll("item_id=$id and field_id=0", ' ORDER BY it.created_at ASC');
            $to_emails = apply_filters('frm_to_email', array(), $entry, $entry->form_id);
        }
            
        require(FRMPRO_VIEWS_PATH.'/frmpro-entries/show.php');
    }
    
    function list_entries(){
        $params = $this->get_params();
        return $this->display_list($params);
    }
    
    function new_entry(){
        global $frm_form;
        if($form_id = FrmAppHelper::get_param('form')){
            $form = $frm_form->getOne($form_id);
            $this->get_new_vars('', $form); 
        }else
             require(FRMPRO_VIEWS_PATH.'/frmpro-entries/new-selection.php'); 
    }
    
    function create(){
        global $frm_form, $frm_entry;
        
        $params = $this->get_params();
        if($params['form'])
            $form = $frm_form->getOne($params['form']);
            
        $errors = $frm_entry->validate($_POST);

        if( count($errors) > 0 ){
            $this->get_new_vars($errors, $form);
        }else{
            if (isset($_POST['frm_page_order_'.$form->id])){
                $this->get_new_vars('',$form); 
            }else{
                $record = $frm_entry->create( $_POST );

                if ($record)
                    $message = __('Entry was Successfully Created', 'formidable');
                $this->display_list($params, $message, '', 1);
            }
        }
    }
    
    function edit(){
        $id = FrmAppHelper::get_param('id');
        return $this->get_edit_vars($id);
    }
    
    
    function update(){
        global $frm_entry;
        $message = '';
        $errors = $frm_entry->validate($_POST);
        $id = FrmAppHelper::get_param('id');

        if( empty($errors) ){
            if (isset($_POST['form_id']) and isset($_POST['frm_page_order_'. $_POST['form_id']])){
                return $this->get_edit_vars($id);
            }else{
                $record = $frm_entry->update( $id, $_POST );
                //if ($record)
                $message = __('Entry was Successfully Updated', 'formidable') . "<br/> <a href='?page=formidable-entries&form=". $_POST['form_id'] ."'>&#171; ". __('Back to Entries', 'formidable') ."</a>";
            }
        }
        
        return $this->get_edit_vars($id,$errors,$message);
    }
    
    function import(){
        global $frm_field;
        
        if(!current_user_can('frm_create_entries'))
            wp_die($frm_settings->admin_permission);
            
        $step = FrmAppHelper::get_param('step', 'One');
        $csv_del = FrmAppHelper::get_param('csv_del', ',');
        $form_id = FrmAppHelper::get_param('form_id');
        
        if($step != 'One'){
            if($step == 'Two'){
                //validate 
                if(empty($_POST['form_id']) or (empty($_POST['csv']) and (!isset($_FILES) or !isset($_FILES['csv']) or empty($_FILES['csv']['name']) or (int)$_FILES['csv']['size'] <= 0))){
                    $errors = array(__('All Fields are required', 'formidable'));
                    $step = 'One';
                }else{
                    
                    //upload
                    $media_id = ($_POST['csv'] and is_numeric($_POST['csv'])) ? $_POST['csv'] : FrmProAppHelper::upload_file('csv');
                    if($media_id and !is_wp_error($media_id)){
                        $current_path = get_attached_file($media_id);
                        $row = 1;
                        
                        $headers = $example = '';

                        
                        if (($f = fopen($current_path, "r")) !== FALSE) {
                            $row = 0;
                            while (($data = fgetcsv($f, 100000, $csv_del)) !== FALSE) {
                                $row++;
                                
                                if($row == 1)
                                    $headers = $data;
                                else if($row == 2)
                                    $example = $data;
                                else
                                    continue;
                            }
                            fclose($f);
                        }
                        
                        
                        $fields = $frm_field->getAll("fi.type not in ('break','divider','captcha','html') and fi.form_id=". (int)$form_id, 'fi.field_order');
                        
                    }else if(is_wp_error($media_id)){
                        echo $media_id->get_error_message();
                        $step = 'One';
                    }
                }
            }else if($step == 'import'){
                global $frm_ajax_url;
                //IMPORT NOW
                $media_id = FrmAppHelper::get_param('csv');
                $current_path = get_attached_file($media_id);
                $row = FrmAppHelper::get_param('row');
                
                $opts = get_option('frm_import_options');
                
                $left = ($opts and isset($opts[$media_id])) ? ((int)$row - (int)$opts[$media_id]['imported'] - 1) : ($row-1);
                    
                $mapping = FrmAppHelper::get_param('data_array');
                $url_vars = "&csv_del=". urlencode($csv_del) ."&form_id={$form_id}&csv={$media_id}&row={$row}";
                foreach($mapping as $mkey => $map)
                    $url_vars .= "&data_array[$mkey]=$map";
            }
        }
        
        $next_step = ($step == 'One') ? __('Step Two', 'formidable') : __('Import', 'formidable');
        
        if($step == 'One')
            $csvs = get_posts( array('post_type' => 'attachment', 'post_mime_type' => 'text/csv') );
        
        
        include(FRMPRO_VIEWS_PATH.'/frmpro-entries/import.php');
    }
    
    function import_csv_entries(){
        if(!current_user_can('frm_create_entries'))
            wp_die($frm_settings->admin_permission);
            
        extract($_POST);
        
        $opts = get_option('frm_import_options');
        if(!$opts)
            $opts = array();
          
        $current_path = get_attached_file($csv);
        $start_row = (isset($opts[$csv])) ? $opts[$csv]['imported'] : 1;
        $imported = FrmProAppHelper::import_csv($current_path, $form_id, $data_array, 0, $start_row+1, $csv_del);

        $opts[$csv] = compact('row', 'imported');
        echo $remaining = ((int)$row - (int)$imported);
        
        if(!$remaining)
            unset($opts[$csv]);
            
        update_option('frm_import_options', $opts);
        
        die();
    }
    
    function duplicate(){
        global $frm_entry, $frm_form;
        
        $params = $this->get_params();
        if($params['form'])
            $form = $frm_form->getOne($params['form']);
        
        $message = $errors = '';
        $record = $frm_entry->duplicate( $params['id'] );
        if ($record)
            $message = __('Entry was Successfully Duplicated', 'formidable');
        else
            $errors = __('There was a problem duplicating that entry', 'formidable');
        
        if(!empty($errors))
            return $this->display_list($params, $errors);
        else
            return $this->get_edit_vars($record, '', $message);
    }
    
    function destroy(){
        if(!current_user_can('frm_delete_entries')){
            global $frm_settings;
            wp_die($frm_settings->admin_permission);
        }
        
        global $frm_entry, $frm_form;
        $params = $this->get_params();
        if($params['form'])
            $form = $frm_form->getOne($params['form']);
        
        $message = '';    
        if ($frm_entry->destroy( $params['id'] ))
            $message = __('Entry was Successfully Destroyed', 'formidable');
        $this->display_list($params, $message, '', 1);
    }
    
    function destroy_all(){
        if(!current_user_can('frm_delete_entries')){
            global $frm_settings;
            wp_die($frm_settings->admin_permission);
        }
        
        global $frm_entry, $frm_form, $frmdb;
        $params = $this->get_params();
        $message = '';    
        $errors = array();
        if($params['form']){
            $form = $frm_form->getOne($params['form']);
            $entry_ids = $frmdb->get_col($frmdb->entries, array('form_id' => $form->id));
            
            foreach($entry_ids as $entry_id){
                if ($frm_entry->destroy( $entry_id ))
                    $message = __('Entries were Successfully Destroyed', 'formidable');
            }
        }else{
            $errors = __('No entries were specified', 'formidable');
        }
        $this->display_list($params, $message, '', 0, $errors);
    }
    
    function bulk_actions(){
        global $frm_entry, $frm_settings;
        $params = $this->get_params();
        $errors = array();
        $bulkaction = '-1';
        if($_POST['bulkaction'] != '-1')
            $bulkaction = $_POST['bulkaction'];
        else if($_POST['bulkaction2'] != '-1')
            $bulkaction = $_POST['bulkaction2'];

        if (!isset($_POST['item-action']) or $_POST['item-action'] == ''){
            $errors[] = __('No entries were specified', 'formidable');
        }else{
            $items = array_keys($_POST['item-action']);
            
            if($bulkaction == 'delete'){
                if(!current_user_can('frm_delete_entries')){
                    $errors[] = $frm_settings->admin_permission;
                }else{
                    if(is_array($items)){
                        foreach($items as $item_id)
                            $frm_entry->destroy($item_id);
                    }
                }
            }else if($bulkaction == 'export'){
                $controller = 'items';
                $ids = $items;
                $ids = implode(',', $ids);
                include_once(FRMPRO_VIEWS_PATH.'/shared/xml.php');
            }else if($bulkaction == 'csv'){
                if(!current_user_can('frm_view_entries'))
                    wp_die($frm_settings->admin_permission);

                global $frm_form;
                
                $form_id = $params['form'];
                if($form_id){
                    $form = $frm_form->getOne($form_id);
                }else{
                    $form = $frm_form->getAll("is_template=0 AND (status is NULL OR status = '' OR status = 'published')", ' ORDER BY name', ' LIMIT 1');
                    if($form)
                        $form_id = $form->id;
                    else
                        $errors[] = __('No form was found', 'formidable');
                }
                
                if($form_id and is_array($items)){
                    echo '<script type="text/javascript">window.onload=function(){location.href="'. FRM_SCRIPT_URL .'&controller=entries&form='. $form_id .'&action=csv&item_id='. implode(',', $items) .'";}</script>';
                }
            }
        }
        $this->display_list($params, '', false, false, $errors);
    }
    
    /* Front End CRUD */
    
    function edit_update_form($params, $fields, $form, $title, $description){
        global $frmdb, $wpdb, $frm_entry, $frm_entry_meta, $user_ID, $frm_editing_entry, $frmpro_settings, $frm_saved_entries;
        
        $message = '';
        $continue = true;
        $form->options = stripslashes_deep(maybe_unserialize($form->options));
        
        if($params['action'] == 'update' and in_array((int)$params['id'], (array)$frm_saved_entries)){
            if(isset($_POST['item_meta']))
                unset($_POST['item_meta']);

            add_filter('frm_continue_to_new', create_function('', "return $continue;"), 15);
            return;
        }

        if ($params['action'] == 'edit'){
            $entry_key = FrmAppHelper::get_param('entry');
            
            $entry = FrmProEntry::user_can_edit($entry_key, $form);

            if($entry and !is_array($entry)){
                $where = "fr.id='$form->id'";
                if ($entry_key){
                    if(is_numeric($entry_key))
                        $where .= ' and it.id='. $entry_key;
                    else
                        $where .= " and item_key='" . $entry_key ."'";
                }

                $entry = $frm_entry->getAll( $where, '', 1, true);   
            }
            
            if ($entry and !empty($entry)){
                $entry = reset($entry);
                $frm_editing_entry = $entry->id;
                $this->show_responses($entry, $fields, $form, $title, $description);
                $continue = false;
            }
        }else if ($params['action'] == 'update' and ($params['posted_form_id'] == $form->id)){
            
            $errors = $frm_entry->validate($_POST);

            if (empty($errors)){
                if (isset($form->options['editable_role']) and !FrmAppHelper::user_has_permission($form->options['editable_role'])){
                    global $frm_settings;
                    wp_die($frm_settings->login_msg);
                }else if (!isset($_POST['frm_page_order_'. $form->id])){
                    $frm_entry->update( $params['id'], $_POST );
                    
                    //check confirmation method 
                    $conf_method = apply_filters('frm_success_filter', 'message', $form);
                    
                    if ($conf_method == 'message'){
                        global $frmpro_settings;
                        $message = '<div class="frm_message" id="message">'. do_shortcode(isset($form->options['edit_msg']) ? $form->options['edit_msg'] : $frmpro_settings->edit_msg).'</div>';
                    }else{
                        do_action('frm_success_action', $conf_method, $form, $form->options, $params['id']);
                        add_filter('frm_continue_to_new', create_function('', "return false;"), 15);
                        return;
                    }
                }
            }else{
                $fields = FrmFieldsHelper::get_form_fields($form->id, true);
            }

            $this->show_responses($params['id'], $fields, $form, $title, $description, $message, $errors);
            $continue = false;
            
        }else if ($params['action'] == 'destroy'){
            //if the user who created the entry is deleting it
            $message = $this->ajax_destroy($form->id, false);
        }else if($frm_editing_entry){
            if(is_numeric($frm_editing_entry)){
                $entry_id = $frm_editing_entry; //get entry from shortcode
            }else{
                $entry_ids = $wpdb->get_col("SELECT id FROM $frmdb->entries WHERE user_id='$user_ID' and form_id='$form->id'");
                
                if (isset($entry_ids) and !empty($entry_ids)){
                    $where_options = $frm_editing_entry;
                    if(!empty($where_options))
                        $where_options .= ' and ';
                    $where_options .= "it.item_id in (".implode(',', $entry_ids).")";
                    
                    $get_meta = $frm_entry_meta->getAll($where_options, ' ORDER BY it.created_at DESC', ' LIMIT 1');
                    $entry_id = ($get_meta) ? $get_meta->item_id : false;
                }
            }

            if(isset($entry_id) and $entry_id){
                if($form->editable and isset($form->options['open_editable']) and $form->options['open_editable'] and isset($form->options['open_editable_role']) and FrmAppHelper::user_has_permission($form->options['open_editable_role']))
                    $meta = true;
                else
                    $meta = $frmdb->get_var($frmdb->entries, array('user_id' => $user_ID, 'id' => $entry_id, 'form_id' => $form->id ));

                if($meta){
                    $frm_editing_entry = $entry_id;
                    $this->show_responses($entry_id, $fields, $form, $title, $description);
                    $continue = false;
                }
            }
        }else{
            //check to see if use is allowed to create another entry
            $can_submit = true;
            if (isset($form->options['single_entry']) and $form->options['single_entry']){
                if ($form->options['single_entry_type'] == 'cookie' and isset($_COOKIE['frm_form'. $form->id . '_' . COOKIEHASH])){
                    $can_submit = false;
                }else if ($form->options['single_entry_type'] == 'ip'){
                    $prev_entry = $frm_entry->getAll(array('it.form_id' => $form->id, 'it.ip' => $_SERVER['REMOTE_ADDR']), '', 1);
                    if ($prev_entry)
                        $can_submit = false;
                }else if ($form->options['single_entry_type'] == 'user' and !$form->editable and $user_ID){
                    $meta = $frmdb->get_var($frmdb->entries, array('user_id' => $user_ID, 'form_id' => $form->id ));
                    if ($meta)
                        $can_submit = false;
                }

                if (!$can_submit){
                    echo stripslashes($frmpro_settings->already_submitted);//TODO: DO SOMETHING IF USER CANNOT RESUBMIT FORM
                    $continue = false;
                }
            }
        }

        add_filter('frm_continue_to_new', create_function('', "return $continue;"), 15);
    }

    function show_responses($id, $fields, $form, $title=false,$description=false, $message='', $errors=''){
        global $frm_form, $frm_field, $frm_entry, $frmpro_entry, $frm_entry_meta, $user_ID, $frmpro_settings, $frm_next_page, $frm_prev_page, $frm_load_css;

        if(is_object($id)){
            $item = $id;
            $id = $item->id;
        }else
            $item = $frm_entry->getOne($id, true);

        $values = FrmAppHelper::setup_edit_vars($item, 'entries', $fields);

        if($values['custom_style']) $frm_load_css = true;
        $show_form = true;
        $submit = (isset($frm_next_page[$form->id])) ? $frm_next_page[$form->id] : (isset($values['edit_value']) ? $values['edit_value'] : $frmpro_settings->update_value);
        
        if(!isset($frm_prev_page[$form->id]) and isset($_POST['item_meta']) and empty($errors) and $form->id == FrmAppHelper::get_param('form_id')){
            $form->options = stripslashes_deep(maybe_unserialize($form->options));
            $show_form = (isset($form->options['show_form'])) ? $form->options['show_form'] : true;
            $conf_method = apply_filters('frm_success_filter', 'message', $form);
            if ($conf_method != 'message')
                do_action('frm_success_action', $conf_method, $form, $form->options, $id);
        }else if(isset($frm_prev_page[$form->id]) or !empty($errors)){
            $jump_to_form = true;
        }

        require(FRMPRO_VIEWS_PATH.'/frmpro-entries/edit-front.php');
        add_filter('frm_continue_to_new', array($frmpro_entry, 'frmpro_editing'), 10, 3);
    }
    
    function ajax_submit_button($form, $action='create'){
        global $frm_novalidate;
        
        if($frm_novalidate)
            echo ' formnovalidate="formnovalidate"';
        //if form ajax submit
        //echo 'onsubmit="return false;" onclick="frm_submit_form(\''.FRM_SCRIPT_URL.'\',jQuery(\'#form_'. $form->form_key .'\').serialize(), \'form_'. $form->form_key .'\')"';

    }
    
    function get_confirmation_method($method, $form){
        $method = (isset($form->options['success_action']) and !empty($form->options['success_action'])) ? $form->options['success_action'] : $method;
        return $method;
    }
    
    function confirmation($method, $form, $form_options, $entry_id){
        //fire the alternate confirmation options ('page' or 'redirect')
        if($method == 'page' and is_numeric($form_options['success_page_id'])){
            global $post;
            if($form_options['success_page_id'] != $post->ID){
                $page = get_post($form_options['success_page_id']);
                $old_post = $post;
                $post = $page;
                $content = apply_filters('frm_content', $page->post_content, $form, $entry_id);
                echo apply_filters('the_content', $content);
                $post = $old_post;
            }
        }else if($method == 'redirect'){
            $success_url = apply_filters('frm_content', $form_options['success_url'], $form, $entry_id);
            $success_msg = isset($form_options['success_msg']) ? stripslashes($form_options['success_msg']) : __('Please wait while you are redirected.', 'formidable'); 
            $redirect_msg = '<div class="frm-redirect-msg frm_message">'. $success_msg .'<br/>'.
                sprintf(__('%1$sClick here%2$s if you are not automatically redirected.', 'formidable'), '<a href="'. esc_url($success_url) .'">', '</a>') .
                '</div>';
            echo apply_filters('frm_redirect_msg', $redirect_msg, array(
                'entry_id' => $entry_id, 'form_id' => $form->id, 'form' => $form
            ));
            die("<script type='text/javascript'>window.location='". $success_url ."'</script>");
        }
    }
    
    function delete_entry($post_id){
        global $frmdb;
        $entry = $frmdb->get_one_record($frmdb->entries, array('post_id' => $post_id), 'id');
        if($entry){
            global $frm_entry;
            $frm_entry->destroy($entry->id);
        }
    }

    /* Export to CSV */
    function csv($form_id, $search = '', $fid = ''){
        if(!current_user_can('frm_view_entries')){
            global $frm_settings;
            wp_die($frm_settings->admin_permission);
        }
        
        global $current_user, $frm_form, $frm_field, $frm_entry, $frm_entry_meta, $wpdb, $frmpro_settings;
        
        $form = $frm_form->getOne($form_id);
        $form_name = sanitize_title_with_dashes($form->name);
        $form_cols = $frm_field->getAll("fi.type not in ('divider', 'captcha', 'break', 'html') and fi.form_id=".$form->id, 'field_order ASC');
        $item_id = FrmAppHelper::get_param('item_id', false);
        $where_clause = "it.form_id=". (int)$form_id;
        
        if($item_id)
            $where_clause .= " and it.id in ($item_id)";
        else if(!empty($search))
            $where_clause = $this->get_search_str($where_clause, $search, $form_id, $fid);
          
        $where_clause = apply_filters('frm_csv_where', $where_clause, compact('form_id'));

        $entries = $frm_entry->getAll($where_clause, '', '', true, false);

        $filename = date("ymdHis",time()) . '_' . $form_name . '_formidable_entries.csv';
        $wp_date_format = apply_filters('frm_csv_date_format', 'Y-m-d H:i:s');
        $charset = get_option('blog_charset');
        
        $to_encoding = $frmpro_settings->csv_format;
        
        require(FRMPRO_VIEWS_PATH.'/frmpro-entries/csv.php');
    }

    /* Display in Back End */
    
    function manage_columns($columns){
        global $frm_field, $frm_cols;
        /* $form_id = FrmAppHelper::get_param('form_id', false);
        if(!$form_id){
            global $frm_current_form, $frm_form;
            $frm_current_form = $frm_form->getAll("is_template=0 AND (status is NULL OR status = '' OR status = 'published')", ' ORDER BY name', ' LIMIT 1');
            $form_id = $frm_current_form ? $frm_current_form->id : 0;
        }
        
        $form_cols = $frm_field->getAll("fi.type not in ('divider', 'captcha', 'break', 'html') and fi.form_id=". $form_id, 'field_order ASC');
        foreach($form_cols as $form_col){
            $columns['frm_'. $form_col->field_key] = $form_col->name;
        } 
        $frm_cols = $columns; */
        //add_screen_option( 'per_page', array('label' => 'Entries', 'default' => 20) );
        
        return $columns;
    }
    
    function hidden_columns($result){
        global $frm_cols, $frm_hidden_cols;
        /* if(count($frm_cols) > 10){ //and empty($result)){
            global $frm_current_form;
            $frm_current_form->options = maybe_unserialize($frm_current_form->options);
            if(isset($frm_current_form->options['hidden_cols']) and !empty($frm_current_form->options['hidden_cols'])){
                $result = $frm_current_form->options['hidden_cols'];
            }else{
                $i = 1;
                foreach($frm_cols as $frm_col_key => $frm_col){
                    if($i > 10)
                        $result[$frm_col_key] = $frm_col; //remove some columns by default
                    $i++;
                }
            }
        } */
        $frm_hidden_cols = $result;
        return $result;
    }
    
    function display_list($params=false, $message='', $page_params_ov = false, $current_page_ov = false, $errors = array()){
        global $wpdb, $frm_app_helper, $frm_form, $frm_entry, $frm_entry_meta, $frm_page_size, $frm_field, $frm_hidden_cols;

        if(!$params)
            $params = $this->get_params();
   
        $errors = array();

        $form_select = $frm_form->getAll("is_template=0 AND (status is NULL OR status = '' OR status = 'published')", ' ORDER BY name');

        if($params['form'])
            $form = $frm_form->getOne($params['form']);
        else
            $form = (isset($form_select[0])) ? $form_select[0] : 0;
    
		if($form){
			$form_cols = $frm_field->getAll("fi.type not in ('divider', 'captcha', 'break', 'html') and fi.form_id=". (int)$form->id, 'field_order ASC', ' LIMIT 7');

	        /* //remove the hidden field columns
	        foreach($form_cols as $col_key => $form_col){
	            if(array_key_exists('frm_'. $form_col->field_key, $frm_hidden_cols))
	                unset($form_cols[$col_key]);
	        }
	        */

	        $where_clause = " it.form_id=$form->id";
	        $page_params = "&action=0&form=" . $form->id;	
	
	        /*if(!empty($params['field_id'])){ //only get items where meta_value for field_id is not null
	            $where_clause = " it.field_id={$params['field_id']}";
	            $page_params = "&field_id=" . $params['field_id'];
	          }*/
        }else{
			$form_cols = array();
			$where_clause = '';
			$page_params = "&action=0&form=0";
		}

        $item_vars = $this->get_sort_vars($params, $where_clause);

        if($current_page_ov)
          $current_page = $current_page_ov;
        else
          $current_page = $params['paged'];

        if($page_params_ov)
          $page_params .= $page_params_ov;
        else
          $page_params .= $item_vars['page_params'];

        $sort_str = $item_vars['sort_str'];
        $sdir_str = $item_vars['sdir_str'];
        $search_str = $item_vars['search_str'];
        $fid = $item_vars['fid'];

		if($form)
        	$record_where = ($item_vars['where_clause'] == " it.form_id=$form->id") ? $form->id : $item_vars['where_clause'];
		else
			$record_where = $item_vars['where_clause'];
			
        $record_count = $frm_entry->getRecordCount($record_where);
        $page_count = $frm_entry->getPageCount($frm_page_size, $record_count);
        $items = $frm_entry->getPage($current_page, $frm_page_size, $item_vars['where_clause'], $item_vars['order_by']);
        $page_last_record = $frm_app_helper->getLastRecordNum($record_count,$current_page,$frm_page_size);
        $page_first_record = $frm_app_helper->getFirstRecordNum($record_count,$current_page,$frm_page_size);
        require_once(FRMPRO_VIEWS_PATH.'/frmpro-entries/list.php');
    }
    
    function get_sort_vars($params=false,$where_clause = ''){
        global $frm_entry_meta, $frm_current_form;
        
        if(!$params)
            $params = $this->get_params($frm_current_form);
 
        $order_by = '';
        $page_params = '';

        // These will have to work with both get and post
        $sort_str = $params['sort'];
        $sdir_str = $params['sdir'];
        $search_str = $params['search'];
        $fid = $params['fid'];

        // make sure page params stay correct
        if(!empty($sort_str))
            $page_params .="&sort=$sort_str";

        if(!empty($sdir_str))
            $page_params .= "&sdir=$sdir_str";

        if(!empty($search_str)){
            $where_clause = $this->get_search_str($where_clause, $search_str, $params['form'], $fid);
            $page_params .= "&search=$search_str";
            if(is_numeric($fid))
                $page_params .= "&fid=$fid";
        }

        // Add order by clause
        if(is_numeric($sort_str))
            $order_by .= " ORDER BY ID"; //update this to order by item meta
        else if ($sort_str == "item_key")
            $order_by .= " ORDER BY item_key";
        else
            $order_by .= " ORDER BY ID";


        // Toggle ascending / descending
        if((empty($sort_str) and empty($sdir_str)) or $sdir_str == 'desc'){
            $order_by .= ' DESC';
            $sdir_str = 'desc';
        }else{
            $order_by .= ' ASC';
            $sdir_str = 'asc';
        }
        
        return compact('order_by', 'sort_str', 'sdir_str', 'fid', 'search_str', 'where_clause', 'page_params');
    }
    
    function get_search_str($where_clause='', $search_str, $form_id=false, $fid=false){
        global $frm_entry_meta;
        
        $where_item = '';
        $join = ' (';
        
        foreach(explode(" ", $search_str) as $search_param){
            $search_param = esc_sql( like_escape( $search_param ) );
			
            if(!is_numeric($fid)){
                $where_item .= (empty($where_item)) ? ' (' : ' OR';
                    
                if(in_array($fid, array('created_at'))){
                    $where_item .= " it.created_at like '%$search_param%'";
                }else{
                    $where_item .= " it.name like '%$search_param%' OR it.item_key like '%$search_param%' OR it.description like '%$search_param%' OR it.created_at like '%$search_param%'";
                }
            }
            
            if(empty($fid) or is_numeric($fid)){
                $where_entries = "(meta_value LIKE '%$search_param%'";
                if($data_fields = FrmProForm::has_field('data', $form_id, false)){
                    foreach((array)$data_fields as $data_field){
                        //search the joined entry too
                        $data_field->field_options = maybe_unserialize($data_field->field_options);
                        if (is_numeric($data_field->field_options['form_select'])){
                            global $wpdb, $frmdb;
                            $data_form_id = $wpdb->get_var("SELECT form_id FROM $frmdb->fields WHERE id=".$data_field->field_options['form_select']);
                            $data_entry_ids = $frm_entry_meta->getEntryIds("fi.form_id=$data_form_id and meta_value LIKE '%".$search_param."%'");
                            if(!empty($data_entry_ids))
                                $where_entries .= " OR meta_value in (".implode(',', $data_entry_ids).")";
                        }
                    }
                }
                
                $where_entries .= ")";

                if(is_numeric($fid))
                    $where_entries .= " AND fi.id=$fid";

                $meta_ids = $frm_entry_meta->getEntryIds($where_entries);
                if (!empty($meta_ids)){
                    if(!empty($where_clause)){
                        $where_clause .= " AND" . $join;
                        if(!empty($join)) $join = '';
                    }
                    $where_clause .= " it.id in (".implode(',', $meta_ids).")";
                }else{
                    if(!empty($where_clause)){
                        $where_clause .= " AND" . $join;
                        if(!empty($join)) $join = '';
                    }
                    $where_clause .= " it.id=0";
                }
            }
        }
        
        if(!empty($where_item)){
            $where_item .= ')';
            if(!empty($where_clause))
                $where_clause .= empty($fid) ? ' OR' : ' AND';
            $where_clause .= $where_item;
            if(empty($join))
                $where_clause .= ')';
        }else{
            if(empty($join))
                $where_clause .= ')';
        }

        return $where_clause;
    }

    function get_new_vars($errors = '', $form = '',$message = ''){
        global $frm_form, $frm_field, $frm_entry, $frm_settings, $frm_next_page;
        $title = true;
        $description = true;
        $fields = FrmFieldsHelper::get_form_fields($form->id, !empty($errors));
        $values = FrmEntriesHelper::setup_new_vars($fields, $form);
        $submit = (isset($frm_next_page[$form->id])) ? $frm_next_page[$form->id] : (isset($values['submit_value']) ? $values['submit_value'] : $frm_settings->submit_value);  
        require_once(FRMPRO_VIEWS_PATH.'/frmpro-entries/new.php');
    }

    function get_edit_vars($id, $errors = '', $message= ''){
        if(!current_user_can('frm_edit_entries'))
            return $this->show($id);

        global $frm_form, $frm_entry, $frm_field, $frm_next_page, $frmpro_settings, $frm_editing_entry;
        $title = $description = true;
        $record = $frm_entry->getOne( $id, true );
        $frm_editing_entry = $id;
        
        $form = $frm_form->getOne($record->form_id);
        $fields = FrmFieldsHelper::get_form_fields($form->id, !empty($errors));
        $values = FrmAppHelper::setup_edit_vars($record, 'entries', $fields);

        $submit = (isset($frm_next_page[$form->id])) ? $frm_next_page[$form->id] : (isset($values['edit_value']) ? $values['edit_value'] : $frmpro_settings->update_value); 
        require(FRMPRO_VIEWS_PATH.'/frmpro-entries/edit.php');
    }
    
    function get_params($form=null){
        global $frm_form;

        if(!$form)
            $form = $frm_form->getAll("is_template=0 AND (status is NULL OR status = '' OR status = 'published')", ' ORDER BY name', ' LIMIT 1');
        
        $values = array();
        foreach (array('id' => '', 'form_name' => '', 'paged' => 1, 'form' => (($form) ? $form->id : 0), 'field_id' => '', 'search' => '', 'sort' => '', 'sdir' => '', 'fid' => '') as $var => $default)
            $values[$var] = FrmAppHelper::get_param($var, $default);

        return $values;
    }

    function route(){
        $action = FrmAppHelper::get_param('action');
        if($action=='show')
            return $this->show();
        else if($action=='new')
            return $this->new_entry();
        else if($action=='create')
            return $this->create();
        else if($action=='edit')
            return $this->edit();
        else if($action=='update')
            return $this->update();
        else if($action=='import')
            return $this->import();
        else if($action=='duplicate')
            return $this->duplicate();
        else if($action == 'destroy')
            return $this->destroy();
        else if($action == 'destroy_all')
            return $this->destroy_all();
        else if($action == 'list-form')
            return $this->bulk_actions();
        else
            return $this->display_list();
    }
    
    function get_form_results($atts){
        extract(shortcode_atts(array('id' => false, 'cols' => 99, 'style' => true, 'no_entries' => __('No Entries Found', 'formidable'), 'fields' => false, 'clickable' => false, 'user_id' => false), $atts));
        if (!$id) return;
        
        global $frm_form, $frm_field, $frm_entry, $frm_entry_meta, $frmpro_settings;
        $form = $frm_form->getOne($id);
        if (!$form) return;
        $where = "fi.type not in ('divider', 'captcha', 'break', 'html') and fi.form_id=". (int)$form->id;
        if($fields)
            $where .= " and (fi.id in ($fields) or fi.field_key in ($fields))";
        
        $form_cols = $frm_field->getAll($where, 'field_order ASC', $cols);
        unset($where);
        
        $where = array('it.form_id' => $form->id);
        if($user_id){
            if(is_numeric($user_id)){
                $where['user_id'] = $user_id;
            }else if($user_id == 'current'){
                global $user_ID;
                $where['user_id'] = $user_ID;
            }else{
                $user = get_userdatabylogin($user_id);
                if($user)
                    $where['user_id'] = $user->ID;
            }
        }
        $entries = $frm_entry->getAll($where, '', '', true, false);
        
        if($style){
            global $frm_load_css;
            $frm_load_css = true;
        }
        
        ob_start();
        include(FRMPRO_VIEWS_PATH .'/frmpro-entries/table.php');
        $contents = ob_get_contents();
        ob_end_clean();
        
        if($clickable)
            $contents = make_clickable($contents);
        return $contents;
    }
    
    function get_search($atts){
        extract(shortcode_atts(array('id' => false, 'post_id' => '', 'label' => __('Search', 'formidable')), $atts));
        //if (!$id) return;
        if($post_id == ''){
            global $post;
            if($post)
                $post_id = $post->ID;
        }
        
        if($post_id != '')
            $action_link = get_permalink($post_id);
        else
            $action_link = '';
        
        ob_start();
        include(FRMPRO_VIEWS_PATH .'/frmpro-entries/search.php');
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    }
    
    function entry_link_shortcode($atts){
        global $user_ID, $frm_entry, $frm_entry_meta, $post;
        extract(shortcode_atts(array('id' => false, 'field_key' => 'created_at', 'type' => 'list', 'logged_in' => true, 'edit' => true, 'class' => '', 'link_type' => 'page', 'blank_label' => '', 'param_name' => 'entry', 'param_value' => 'key', 'page_id' => false, 'show_delete' => false), $atts));
        
        if (!$id or ($logged_in && !$user_ID)) return;
        $id = (int)$id;
        
        $s = FrmAppHelper::get_param('frm_search', false);

        if($s)
            $entry_ids = FrmProEntriesHelper::get_search_ids($s, $id);
        else
            $entry_ids = $frm_entry_meta->getEntryIds("fi.form_id='$id'");
        
        if ($entry_ids){
            $id_list = implode(',', $entry_ids);
            $order = ($type == 'collapse') ? ' ORDER BY it.created_at DESC' : '';
            
            $where = "it.id in ($id_list)";
            if ($logged_in)
                $where .= " and it.form_id='". $id ."' and it.user_id='". (int)$user_ID ."'";
            
            $entries = $frm_entry->getAll($where, $order, '', true);
        }

        if (!empty($entries)){
            if ($type == 'list'){
                $content = "<ul class='frm_entry_ul $class'>\n";
            }else if($type == 'collapse'){
                $content = '<div class="frm_collapse">';
                $year = $month = '';
                $prev_year = $prev_month = false;
            }else{
                $content = "<select id='frm_select_form_$id' name='frm_select_form_$id' class='$class' onchange='location=this.options[this.selectedIndex].value;'>\n <option value='".get_permalink($post->ID)."'>$blank_label</option>\n";
            }
            
            global $frm_field;
            if($field_key != 'created_at')
                $field = $frm_field->getOne($field_key);
            
            foreach ($entries as $entry){
                if(isset($_GET) and isset($_GET['action']) and $_GET['action'] == 'destroy'){
                    if(isset($_GET['entry']) and ($_GET['entry'] == $entry->item_key or $_GET['entry'] == $entry->id))
                        continue;
                }
                
                if($entry->post_id){
                    global $wpdb;
                    $post_status = $wpdb->get_var("SELECT post_status FROM $wpdb->posts WHERE ID=".$entry->post_id);
                    if($post_status != 'publish')
                        continue;
                }
                $value = '';
                $meta = false;
                if ($field_key && $field_key != 'created_at'){
                    if($entry->post_id and (($field and $field->field_options['post_field']) or $field->type == 'tag'))
                        $value = FrmProEntryMetaHelper::get_post_value($entry->post_id, $field->field_options['post_field'], $field->field_options['custom_field'], array('type' => $field->type, 'form_id' => $field->form_id, 'field' => $field));
                    else
                        $meta = isset($entry->metas[$field_key]) ? $entry->metas[$field_key] : '';
                }else
                    $meta = reset($entry->metas);
                
                $value = ($field_key == 'created_at' or !isset($meta) or !$meta) ? $value : (is_object($meta) ? $meta->meta_value : $meta);
                
                if(empty($value))
                    $value = date_i18n(get_option('date_format'), strtotime($entry->created_at));
                else
                    $value = FrmProEntryMetaHelper::display_value($value, $field, array('type' => $field->type, 'show_filename' => false));
                
                if($param_value == 'key')
                    $args = array($param_name => $entry->item_key);
                else
                    $args = array($param_name => $entry->id);
                    
                if ($edit)
                    $args['action'] = 'edit';
                
                if ($link_type == 'scroll')
                    $link = '#'.$entry->item_key;
                else if ($link_type == 'admin')
                    $link = add_query_arg($args, $_SERVER['REQUEST_URI']);
                else{
                    if($page_id)
                        $permalink = get_permalink($page_id);
                    else
                        $permalink = get_permalink($post->ID);
                    $link = add_query_arg($args, $permalink);
                }
                
                $current = (isset($_GET['entry']) && $_GET['entry'] == $entry->item_key) ? true : false;
                if ($type == 'list'){
                    $content .= "<li><a href='$link'>".stripslashes($value)."</a></li>\n";
                }else if($type == 'collapse'){
                    $new_year = strftime('%G', strtotime($entry->created_at));
                    $new_month = strftime('%B', strtotime($entry->created_at));
                    if ($new_year != $year){
                        if($prev_year){
                            if($prev_month) $content .= '</ul></div>';
                            $content .= '</div>';
                            $prev_month = false;
                        }
                        $style = ($prev_year) ? " style='display:none'" : '';
                        $triangle = ($prev_year) ? "e" : "s";
                        $content .= "\n<div class='frm_year_heading frm_year_heading_$id'>
                            <span class='ui-icon ui-icon-triangle-1-$triangle'></span>\n
                            <a>$new_year</a></div>\n
                            <div class='frm_toggle_container' $style>\n";
                        $prev_year = true;
                    }
                    if ($new_month != $month){
                        if($prev_month)
                            $content .= '</ul></div>';
                        $style = ($prev_month) ? " style='display:none'" : '';
                        $triangle = ($prev_month) ? "e" : "s";
                        $content .= "<div class='frm_month_heading frm_month_heading_$id'>
                            <span class='ui-icon ui-icon-triangle-1-$triangle'></span>\n
                            <a>$new_month</a>\n</div>\n
                            <div class='frm_toggle_container frm_month_listing' $style><ul>\n";
                        $prev_month = true;
                    }
                    $content .= "<li><a href='$link'>".stripslashes($value)."</a></li>";
                    $year = $new_year;
                    $month = $new_month;
                }else{
                    $selected = $current ? ' selected="selected"' : '';
                    $content .= "<option value='$link'$selected>" .stripslashes($value) . "</option>\n";
                }
            }

            if ($type == 'list')
                $content .= "</ul>\n";
            else if($type == 'collapse'){
                if($prev_year) $content .= '</div>';
                if($prev_month) $content .= '</ul></div>';
                $content .= '</div>';
                $content .= "<script type='text/javascript'>jQuery(document).ready(function($){ $('.frm_month_heading_". $id . ", .frm_year_heading_". $id ."').toggle(function(){ $(this).children('.ui-icon-triangle-1-e').addClass('ui-icon-triangle-1-s'); $(this).children('.ui-icon-triangle-1-s').removeClass('ui-icon-triangle-1-e'); $(this).next('.frm_toggle_container').fadeIn('slow');},function(){ $(this).children('.ui-icon-triangle-1-s').addClass('ui-icon-triangle-1-e'); $(this).children('.ui-icon-triangle-1-e').removeClass('ui-icon-triangle-1-s'); $(this).next('.frm_toggle_container').hide();});})</script>\n";
            }else{
                $content .= "</select>\n";
                if($show_delete and isset($_GET) and isset($_GET['entry']) and $_GET['entry'])
                    $content .= " <a href='".add_query_arg(array('action' => 'destroy', 'entry' => $_GET['entry']), $permalink) ."'>$show_delete</a>\n";
            }
            
        }else
            $content = '';
        
        return $content;
    }
    
    function entry_edit_link($atts){
        global $frm_editing_entry, $post, $frm_forms_loaded;
        extract(shortcode_atts(array(
            'id' => $frm_editing_entry, 'label' => __('Edit', 'formidable'), 'cancel' => __('Cancel', 'formidable'), 
            'class' => '', 'page_id' => (($post) ? $post->ID : 0), 'html_id' => false,
            'prefix' => '', 'form_id' => false
        ), $atts));

        $link = '';
        $entry_id = ($id and is_numeric($id)) ? $id : FrmAppHelper::get_param('entry', false);
            
        if($entry_id and !empty($entry_id)){
            if(!$form_id){
                global $frmdb;
                $form_id = (int)$frmdb->get_var($frmdb->entries, array('id' => $entry_id), 'form_id');
            }
            
            //if user is not allowed to edit, then don't show the link
            if(!FrmProEntry::user_can_edit($entry_id, $form_id))
                return $link;
                
            if(empty($prefix)){
               $link = add_query_arg(array('action' => 'edit', 'entry' => $entry_id), get_permalink($page_id));
               
               if($label)
                   $link = '<a href="'. $link .'" class="'. $class.'">'. $label .'</a>';
                   
               return $link;
            }
            
            if (isset($_POST) and isset($_POST['action']) and ($_POST['action'] =='update') and isset($_POST['form_id']) and ($_POST['form_id'] == $form_id) and isset($_POST['id']) and ($_POST['id'] == $entry_id)){
                global $frm_entry;
                $errors = $frm_entry->validate($_POST);
                
                if($errors)
                    return FrmAppController::get_form_shortcode(array('id' => $form_id, 'entry_id' => $entry_id));
                
                $frm_entry->update( $entry_id, $_POST );
                
                $link .= "<script type='text/javascript'>window.onload= function(){var frm_pos=jQuery('#". $prefix . $entry_id ."').offset();window.scrollTo(frm_pos.left,frm_pos.top);}</script>";
            }

                
            if(!$html_id)
                $html_id = "frm_edit_{$entry_id}";
              
            $frm_forms_loaded[] = true;  
            $link .= "<a href='javascript:frmEditEntry($entry_id,\"". FRM_SCRIPT_URL."\",\"$prefix\",$page_id,$form_id,\"$cancel\",\"$class\")' class='frm_edit_link $class' id='$html_id'>$label</a>\n";
        }

        return $link;
    }
    
    function entry_update_field($atts){
        global $frm_editing_entry, $post, $frmdb;
        
        extract(shortcode_atts(array(
            'id' => $frm_editing_entry, 'field_id' => false, 'form_id' => false, 
            'label' => 'Update', 'class' => '', 'value' => '', 'message' => ''
        ), $atts));
        
        $link = '';
        $entry_id = (int)($id and is_numeric($id)) ? $id : FrmAppHelper::get_param('entry', false);
        
        if(!$entry_id or empty($entry_id))
            return $link;
            
        if(!$form_id)
            $form_id = (int)$frmdb->get_var($frmdb->entries, array('id' => $entry_id), 'form_id');
        
        if(!FrmProEntry::user_can_edit($entry_id, $form_id))
            return $link;
        
        if(!is_numeric($field_id))
            $field_id = $frmdb->get_var($frmdb->fields, array('field_key' => $field_id));
        
        if(!$field_id)
            return 'no field'. $link;
        
        $link = "<a href='javascript:frmUpdateField($entry_id,$field_id,\"$value\",\"$message\",\"". FRM_SCRIPT_URL."\")' id='frm_update_field_{$entry_id}_{$field_id}' class='frm_update_field_link $class'>$label</a>";
        
        return $link;
    }
    
    function entry_delete_link($atts){
        global $frm_editing_entry, $post, $frm_forms_loaded;
        extract(shortcode_atts(array(
            'id' => $frm_editing_entry, 'label' => 'Delete', 
            'confirm' => __('Are you sure you want to delete that entry?', 'formidable'), 
            'class' => '', 'page_id' => (($post) ? $post->ID : 0), 'html_id' => false, 'prefix' => ''
        ), $atts));
        
        $frm_forms_loaded[] = true;
        
        $link = '';
        $entry_id = ($id and is_numeric($id)) ? $id : (is_admin() ? FrmAppHelper::get_param('id', false) : FrmAppHelper::get_param('entry', false));

        if($entry_id and !empty($entry_id)){
            if(empty($prefix)){
                $link = "<a href='". add_query_arg(array('action' => 'destroy', 'entry' => $entry_id), get_permalink($page_id)) ."' class='$class' onclick='return confirm(\"". $confirm ."\")'>$label</a>\n";
            }else{
                if(!$html_id)
                    $html_id = "frm_delete_{$entry_id}";
              
                $link = "<a href='javascript:frmDeleteEntry($entry_id,\"". FRM_SCRIPT_URL."\",\"$prefix\")' class='frm_delete_link $class' id='$html_id' onclick='return confirm(\"". $confirm ."\")'>$label</a>\n";
            }
        }
            
        return $link;
    }
    
    /* AJAX */
    function set_cookie($entry_id, $form_id){
        setcookie('frm_form'.$form_id.'_' . COOKIEHASH, current_time('mysql', 1), time() + 30000000, COOKIEPATH, COOKIE_DOMAIN);
        die();
    }
    
    function ajax_create(){
        global $frm_entry;
        
        $errors = $frm_entry->validate($_POST, array('file','rte','captcha'));
        if(empty($errors)){
            echo false;
        }else{
            $errors = str_replace('"', '&quot;', stripslashes_deep($errors));
            $obj = array();
            foreach($errors as $field => $error){
                $field_id = str_replace('field', '', $field);
                $obj[$field_id] = $error;
            }
            echo json_encode($obj);
        }
        
        die();
    }
    
    function ajax_update(){
        return $this->ajax_create();
    }
    
    function ajax_destroy($form_id=false, $ajax=true){
        global $user_ID, $frmdb, $frm_entry;
        
        $entry_key = FrmAppHelper::get_param('entry');
        if(!$form_id)
            $form_id = FrmAppHelper::get_param('form_id');
        
        if(!$entry_key)
            return;
            
        $where = array();
        if($form_id and is_numeric($form_id))
            $where['form_id'] = $form_id;
            
        if(!current_user_can('frm_delete_entries'))
            $where['user_id'] = $user_ID;
            
        if(is_numeric($entry_key))
            $where['id'] = $entry_key;
        else
            $where['item_key'] = $entry_key;
        
        $entry_id = $frmdb->get_var( $frmdb->entries, $where );
        
        apply_filters('frm_allow_delete', $entry_id, $entry_key, $form_id);

        if(!$entry_id){
            $message = __('There was a error deleting that entry', 'formidable');
            echo '<div class="frm_message">'. $message .'</div>';
        }else{
            $frm_entry->destroy( $entry_id );
            if($ajax){
                echo $message = 'success';
            }else{
                $message = __('Your entry was successfully deleted', 'formidable');
                echo '<div class="frm_message">'. $message .'</div>';
            }
        }
        
        return $message;
    }
    
    function edit_entry_ajax($id, $entry_id=false, $post_id=false){
        global $frm_ajax_edit;
        $frm_ajax_edit = ($entry_id) ? $entry_id : true;

        if($post_id and is_numeric($post_id)){
            global $post;
            if(!$post)
                $post = get_post($post_id);
        }

        echo "<script type='text/javascript' src='". FRM_URL ."/js/formidable.js'></script>";
        echo "<script type='text/javascript'>
//<![CDATA[
jQuery(document).ready(function($){
$('#frm_form_". $id ."_container .frm-show-form').submit(function(e){e.preventDefault();window.frmGetFormErrors(this,'". FRM_SCRIPT_URL ."');});
});
//]]>
</script>";
        echo FrmAppController::get_form_shortcode(compact('id', 'entry_id'));

        $frm_ajax_edit = false;
        //if(!isset($_POST) or !isset($_POST['action']))
        //    echo FrmProEntriesController::footer_js();
        
        die();
    }
    
    function update_field_ajax($entry_id, $field_id, $value){
        global $frmdb, $wpdb;
        
        $entry_id = (int)$entry_id;
        
        if(!$entry_id)
            return false;
           
        $where = '';
        if(is_numeric($field_id))
            $where .= "fi.id=$field_id";
        else
            $where .= "field_key='$field_id'";
            
        $field = FrmField::getAll($where, '', ' LIMIT 1');
    
        if(!$field or !FrmProEntry::user_can_edit($entry_id, $field->form_id))
            return false;
        
        $post_id = false;
        
        $field->field_options = maybe_unserialize($field->field_options);
        if(isset($field->field_options['post_field']) and !empty($field->field_options['post_field']))
            $post_id = $frmdb->get_var($frmdb->entries, array('id' => $entry_id), 'post_id');
            
        if(!$post_id){
            $updated = $wpdb->update( $frmdb->entry_metas, 
                array('meta_value' => $value), 
                array('item_id' => $entry_id, 'field_id' => $field_id) 
            );

            if(!$updated){
                $wpdb->query($wpdb->prepare("DELETE FROM $frmdb->entry_metas WHERE item_id = %d and field_id = %d", $entry_id, $field_id));
                $updated = FrmEntryMeta::add_entry_meta($entry_id, $field_id, '', $value);
            }
            wp_cache_delete( $entry_id, 'frm_entry');
        }else{
            switch($field->field_options['post_field']){
                case 'post_custom':
                    $updated = update_post_meta($post_id, $field->field_options['post_custom'], maybe_serialize($value));
                break;
                case 'post_category':
                    $taxonomy = (isset($field->field_options['taxonomy']) and !empty($field->field_options['taxonomy'])) ? $field->field_options['taxonomy'] : 'category';
                    $updated = wp_set_post_terms( $post_id, $value, $taxonomy );
                break;
                default:
                    $post = get_post($post_id, ARRAY_A);
                    $post[$field->field_options['post_field']] = maybe_serialize($value);
                    $updated = wp_insert_post( $post );
            }
        }
        return $updated;
    }
    
    function send_email($entry_id, $form_id, $type){
        if(current_user_can('frm_view_forms') or current_user_can('frm_edit_forms')){
            if($type=='autoresponder')
                $sent_to = FrmProNotification::autoresponder($entry_id, $form_id);
            else
                $sent_to = FrmProNotification::entry_created($entry_id, $form_id);
            
            if(is_array($sent_to))
                echo implode(',', $sent_to);
            else
                echo $sent_to;
        }else{
            _e('No one! You do not have permission', 'formidable');
        }
    }
    
}

?>