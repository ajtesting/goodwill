<?php

class FrmProStatisticsController{
    function FrmProStatisticsController(){
        add_action('admin_menu', array( &$this, 'menu' ), 24);
        add_filter('frm_nav_array', array( &$this, 'frm_nav'), 3);
        add_shortcode('frm-graph', array(&$this, 'graph_shortcode'));
        add_shortcode('frm-stats', array(&$this, 'stats_shortcode'));
    }
    
    function menu(){
        global $frm_settings;
        add_submenu_page('formidable', 'Formidable | '. __('Reports', 'formidable'), __('Reports', 'formidable'), 'frm_view_reports', 'formidable-reports', array(&$this, 'show'));
        
        add_action('admin_head-'. sanitize_title($frm_settings->menu) .'_page_formidable-reports', array(&$this, 'head'));
    }
    
    function head(){
        $js_file  = array(FRMPRO_URL.'/js/swfobject.js', FRMPRO_URL.'/js/json2.js');
        require(FRM_VIEWS_PATH . '/shared/head.php');
        require_once(FRMPRO_PATH . '/js/ofc-library/open-flash-chart-object.php');
        require_once(FRMPRO_PATH . '/js/ofc-library/open-flash-chart.php');
    }
    
    function frm_nav($nav){
        if(current_user_can('frm_view_reports'))
            $nav['formidable-reports'] = __('Reports', 'formidable');
        return $nav;
    }
    
    function show(){
        global $frmdb, $frm_form, $frm_field, $frm_entry_meta, $frm_entry, $wpdb;
        if  (!isset($_GET['form'])){
            require_once(FRMPRO_VIEWS_PATH.'/frmpro-statistics/show.php');
            return;
        }
        
        $form = $frm_form->getOne($_GET['form']);
        //$form_options = maybe_unserialize($form->options);
        $fields = $frm_field->getAll("fi.type not in ('divider','captcha','break','rte','textarea','file','data','grid','html') and fi.form_id=".$form->id, 'field_order ASC');
        
        $js = '';
        $data = array();
        $odd = true;
        $colors = array('#EF8C08', '#21759B', '#1C9E05');
        foreach ($fields as $field){  
            $data[$field->id] = $this->get_graph($field, array('colors' => $colors, 'bg_color' => '#FFFFFF', 'odd' => $odd));
 
            $js .= 'swfobject.embedSWF("'.FRMPRO_URL.'/js/open-flash-chart.swf", "chart_'.$field->id.'",
              "650", "400", "9.0.0", "expressInstall.swf", {"get-data":"get_data_'.$field->id.'"} );';
              
            $odd = $odd ? false : true;
        }
        
        //Chart for Entries Submitted
        $values = array();
        $labels = array();
        $start_timestamp = mktime(0, 0, 0, date("m")-1, date("d"), date("Y"));
        $end_timestamp = time();
        $query = "SELECT DATE(en.created_at) as endate,COUNT(*) as encount FROM $frmdb->entries en WHERE en.created_at BETWEEN '".date("Y-n-j", $start_timestamp)." 00:00:00' AND '".date("Y-n-j", $end_timestamp)." 23:59:59' AND en.form_id=$form->id GROUP BY DATE(en.created_at)";

        $entries_array = $wpdb->get_results($query);

        $temp_array = $counts_array = $dates_array = array();

        // Refactor Array for use later on
        foreach($entries_array as $e)
          $temp_array[$e->endate] = $e->encount;

        // Get the dates array
        for($e = $start_timestamp; $e <= $end_timestamp; $e += 60*60*24)
          $dates_array[] = date("Y-m-d", $e);

        // Make sure counts array is in order and includes zero click days
        foreach($dates_array as $date_str){
          if(isset($temp_array[$date_str]))
              $counts_array[$date_str] = $temp_array[$date_str];
          else
              $counts_array[$date_str] = 0;
        }
        
        $date_format = get_option('date_format');
        foreach ($counts_array as $date => $count){
            $labels[] = date_i18n($date_format, strtotime($date));
            $values[] = (int)$count;
        }

        $title = new title( __('Daily Entries', 'formidable') );
        
        $line_1_default_dot = new dot();
        $line_1_default_dot->colour($colors[0]);
        $line_1_default_dot->tooltip('#x_label#<br>#val# Entries');
        
        $line_1 = new line();
        $line_1->set_default_dot_style($line_1_default_dot);
        $line_1->set_values( $values );
        $line_1->set_colour( $colors[1] );
        
        $chart = new open_flash_chart();
        $chart->set_title( $title );
        $chart->set_bg_colour( '#FFFFFF' );
        
        $x = new x_axis();
        $x_labels = new x_axis_labels();
        $x_labels->rotate(340);
        $x_labels->set_labels( $labels );
        $x_labels->visible_steps(2);
        $x->set_labels($x_labels);
        
        $chart->set_x_axis( $x );
        
        $y = new y_axis();
        if (!empty($values)){
            $max = max($values)+1;
            $step = ceil($max/10);
            $y->set_range(0, $max, $step);
        }
        $chart->add_element( $line_1 );
        $chart->set_y_axis( $y );
        
        $data['time'] = $chart->toPrettyString();
        $js .= 'swfobject.embedSWF("'.FRMPRO_URL.'/js/open-flash-chart.swf","chart_time","900","300","9.0.0","expressInstall.swf", {"get-data":"get_data_time"} );';
        
        require(FRMPRO_VIEWS_PATH.'/frmpro-statistics/head.php');
        require(FRMPRO_VIEWS_PATH.'/frmpro-statistics/show.php');
    }
    
    function get_graph($field, $args){
        $defaults = array(
            'ids' => false, 
            'colors' => array('#EF8C08', '#21759B', '#1C9E05'), 'grid_color' => '#f7e8bf', 'bg_color' => '#FFFFFF', 
            'odd' => false, 'truncate' => 40, 'truncate_label' => 15, 'response_count' => 10, 
            'user_id' => false, 'type' => 'default', 'x_axis' => false, 'data_type' => 'count',
            'limit' => '', 'x_start' => '', 'x_end' => '', 'show_key' => false, 'min' => '', 'max' => '',
            'include_zero' => false, 'width' => 400
        );
        extract(wp_parse_args($args, $defaults));
        
        global $frm_entry_meta, $frmdb, $wpdb;           
        $values = $labels = $f_values = $f_labels = array();
        $title = new title( preg_replace("/&#?[a-z0-9]{2,8};/i", "", FrmAppHelper::truncate($field->name, $truncate, 0)) );
        
        $show_key = (int)$show_key;
        if($show_key and $show_key < 5)
            $show_key = 10;
            
        $fields = $f_inputs = array();
        $fields[$field->id] = $field;

        if($ids){ 
            $ids = explode(',', $ids);
        
            foreach($ids as $id_key => $f){
                $ids[$id_key] = $f = trim($f);
                if(!$f or empty($f)){
                    unset($ids[$id_key]);
                    continue;
                }

                if($add_field = FrmField::getOne($f)){
                    $fields[$add_field->id] = $add_field;
                    $ids[$id_key] = $add_field->id;
                }
                unset($f);
                unset($id_key);
            }
        }else{
            $ids = array();
        }
        
        if($x_axis){
            $x_field = FrmField::getOne($x_axis);
               
            $query = $x_query = "SELECT meta_value, item_id FROM $frmdb->entry_metas em";
            if(!$x_field)
                $x_query = "SELECT id, {$x_axis} FROM $frmdb->entries e";
            
            if($user_id){
                $query .= " LEFT JOIN $frmdb->entries e ON (e.id=em.item_id)";
                if($x_field)
                    $x_query .= " LEFT JOIN $frmdb->entries e ON (e.id=em.item_id)";
            }
              
            if($x_field){
                $x_query .= " WHERE em.field_id='{$x_field->id}'";
                if(!empty($x_start)){
                    if($x_field->type == 'date')
                        $x_start = date('Y-m-d', strtotime($x_start));
                    
                    $x_query .= " and meta_value >= '$x_start'";
                }
                
                if(!empty($x_end)){
                    if($x_field->type == 'date')
                        $x_end = date('Y-m-d', strtotime($x_end));
                        
                    $x_query .= " and meta_value <= '$x_end'";
                }
            }else{
                $x_query .= " WHERE form_id=". $field->form_id;
                if(!empty($x_start)){
                    if(in_array($x_axis, array('created_at', 'updated_at')))
                        $x_start = date('Y-m-d', strtotime($x_start));
                    $x_query .= " and e.{$x_axis} >= '$x_start'";
                }

                if(!empty($x_end)){
                    if(in_array($x_axis, array('created_at', 'updated_at')))
                        $x_end = date('Y-m-d', strtotime($x_end)) .' 23:59:59';
                    $x_query .= " and e.{$x_axis} <= '$x_end'";
                }
            }
            
            $q = array();
            foreach($fields as $f_id => $f){
                if($f_id != $field->id)
                    $q[$f_id] = $query ." WHERE em.field_id='{$f_id}'". ( ($user_id) ? " AND user_id='$user_id'" : '');
            }
                
            $query .= " WHERE em.field_id='{$field->id}'";
            
            if($user_id){
                $query .= " AND user_id='$user_id'";
                $x_query .= " AND user_id='$user_id'";
            }

            $inputs = $wpdb->get_results($query, ARRAY_A);
            $x_inputs = $wpdb->get_results($x_query, ARRAY_A);
            
            if(!$x_inputs)
                $x_inputs = array('id' => '0');
 
            unset($query);
            unset($x_query);
            
            foreach($q as $f_id => $query){
                $f_inputs[$f_id] = $wpdb->get_results($query, ARRAY_A);
                unset($query);
            }
            
            unset($q);
        }else{
            if($user_id)
                $inputs = $wpdb->get_col("SELECT meta_value FROM $frmdb->entry_metas em LEFT JOIN $frmdb->entries e ON (e.id=em.item_id) WHERE em.field_id='{$field->id}' AND user_id='$user_id'");
            else
                $inputs = $frm_entry_meta->get_entry_metas_for_field($field->id);
                
            foreach($fields as $f_id => $f){
                if($f_id != $field->id)
                    $f_inputs[$f_id] = $wpdb->get_col("SELECT meta_value FROM $frmdb->entry_metas em LEFT JOIN $frmdb->entries e ON (e.id=em.item_id) WHERE em.field_id='{$f_id}'". ( ($user_id) ? " AND user_id='$user_id'" : ''));
                unset($f_id);
                unset($f);
            }
        }

        $inputs = array_map('maybe_unserialize', $inputs);
        $inputs = stripslashes_deep($inputs);
        
        foreach($f_inputs as $f_id => $f){
            $f = array_map('maybe_unserialize', $f);
            $f_inputs[$f_id] = stripslashes_deep($f);
            unset($f_id);
            unset($f);
        }
        
        $field_options = maybe_unserialize($field->options);
        $field->field_options = maybe_unserialize($field->field_options);
        
        global $frm_posts;
        if($frm_posts and isset($frm_posts[$field->form_id])){
            $form_posts = $frm_posts[$field->form_id];
        }else{
            $form_posts = $frmdb->get_records($frmdb->entries, array('form_id' => $field->form_id, 'post_id >' => 1), '', '', 'id,post_id');
            
            if(!$frm_posts)
                $frm_posts = array();
            $frm_posts[$field->form_id] = $form_posts;
        }
       
        if(!empty($form_posts)){
            if(isset($field->field_options['post_field']) and $field->field_options['post_field'] != ''){
                if($field->field_options['post_field'] == 'post_category'){
                    $field_options = FrmProFieldsHelper::get_category_options($field);
                }else if($field->field_options['post_field'] == 'post_custom' and $field->field_options['custom_field'] != ''){
                    //check custom fields
                    foreach($form_posts as $form_post){
                        $meta_value = get_post_meta($form_post->post_id, $field->field_options['custom_field'], true);
                        if($meta_value){
                            if($x_axis)
                                $inputs[] = array('meta_value' => $meta_value, 'item_id' => $form_post->id);
                            else
                                $inputs[] = $meta_value;
                        }
                    }
                }else{ //if field is post field
                    if($field->field_options['post_field'] == 'post_status')
                        $field_options = FrmProFieldsHelper::get_status_options($field);
                    
                    foreach($form_posts as $form_post){
                        $post_value = $wpdb->get_var("SELECT ". $field->field_options['post_field'] ." FROM $wpdb->posts WHERE ID=".$form_post->post_id);
                        if($post_value){
                            if($x_axis)
                                $inputs[] = array('meta_value' => $post_value, 'item_id' => $form_post->id);
                            else
                                $inputs[] = $post_value;
                        }
                    }
                }
            }
        }

        $chart = new open_flash_chart();
        $chart->set_title( $title );
        $bar = new bar_glass();
        $x = new x_axis();
        $y = new y_axis();
        $x_labels = new x_axis_labels();
        $pie = false;

        if(isset($x_inputs) and $x_inputs){
            $x_temp = array();
            foreach($x_inputs as $x_input){
                if($x_field)
                    $x_temp[$x_input['item_id']] = $x_input['meta_value'];
                else
                    $x_temp[$x_input['id']] = $x_input[$x_axis];
            }
            $x_inputs = $x_temp;
            
            unset($x_temp);
            unset($x_input);
        }
        
        if($x_axis and $inputs){
            $y_temp = array();
            foreach($inputs as $input)
                $y_temp[$input['item_id']] = $input['meta_value'];
            
            foreach($ids as $f_id){
                if(!isset($f_values[$f_id]))
                    $f_values[$f_id] = array();
                $f_values[$f_id][key($y_temp)] = 0;
                unset($f_id);
            }
            
            $inputs = $y_temp;
            
            unset($y_temp);
            unset($input);
        }

        foreach($f_inputs as $f_id => $f){
            $temp = array();
            foreach($f as $input){
                if(is_array($input)){
                    $temp[$input['item_id']] = $input['meta_value'];
                    
                    foreach($ids as $d){
                        if(!isset($f_values[$d][$input['item_id']]))
                            $f_values[$d][$input['item_id']] = 0;
                        
                        unset($d);
                    }
                }else{
                    $temp[] = $input;
                }
                
                unset($input);
            }

            $f_inputs[$f_id] = $temp;
            
            unset($temp);
            unset($input);
            unset($f);
        }
        

        if (in_array($field->type, array('select', 'checkbox', 'radio', '10radio', 'scale')) and (!isset($x_inputs) or !$x_inputs)){ 
            if($limit == '') $limit = 10;
            $field_opt_count = count($field_options);

            if($field_options){
            foreach ($field_options as $opt_key => $opt){
                $count = 0;
                
                if(empty($opt))
                    continue;
                    
                $opt = stripslashes_deep($opt);
                $field_val = apply_filters('frm_field_value_saved', $opt, $opt_key, $field->field_options);
                
                foreach ($inputs as $in){
                    if (FrmAppHelper::check_selected($in, $field_val)){
                        if($data_type == 'total')
                            $count += $field_val;
                        else
                            $count++;
                    }
                }
                
                $new_val = FrmAppHelper::truncate($opt, $truncate_label, 2);
                
                if($count > 0 or $field_opt_count < $limit or (!$count and $include_zero)){
                    $labels[$new_val] = $new_val;
                    $values[$new_val] = $count;
                }
                unset($count);
                
                foreach($f_inputs as $f_id => $f){
                    
                    foreach($f as $in){
                        if(!isset($f_values[$f_id]))
                            $f_values[$f_id] = array();    

                        if(!isset($f_values[$f_id][$new_val]))
                            $f_values[$f_id][$new_val] = 0;
                            
                        if (FrmAppHelper::check_selected($in, $field_val)){
                            if($data_type == 'total')
                                $f_values[$f_id][$new_val] += $field_val;
                            else
                                $f_values[$f_id][$new_val]++;
                        }
                        
                        unset($in);
                    }
                        
                    unset($f_id);
                    unset($f);
                }
                
            }
            }
            
            if (!in_array($field->type, array('checkbox', '10radio', 'scale'))) //and count($field_options) == 2
                $pie = true;
            
            $x_labels->rotate(340);
            //$x_labels->set_colour( '#A2ACBA' );
            //$x->set_colour( '#A2ACBA' );
        }else if ($field->type == 'user_id'){
            $form = $frmdb->get_one_record($frmdb->forms, array('id' => $field->form_id));
            $form_options = maybe_unserialize($form->options);
            $id_count = array_count_values($inputs);
            if ($form->editable and (isset($form_options['single_entry']) and isset($form_options['single_entry_type']) and $form_options['single_entry_type'] == 'user')){
                //if only one response per user, do a pie chart of users who have submitted the form
                $users_of_blog = (function_exists('get_users')) ? get_users() : get_users_of_blog();
                $total_users = count( $users_of_blog );
                unset($users_of_blog);
                $id_count = count($id_count);
                $not_completed = (int)$total_users - (int)$id_count;
                $labels = array(__('Completed', 'formidable'), __('Not Completed', 'formidable'));
                $values = array($id_count, $not_completed);
                $pie = true;
            }else{
                //arsort($id_count);
                foreach ($id_count as $val => $count){
                    $user_info = get_userdata($val);
                    $labels[] = ($user_info) ? $user_info->display_name : __('Deleted User', 'formidable');
                    $values[] = $count;
                }
                if (count($labels) < 10)
                    $pie = true;
            }
            
            if(!$pie)
                $x_labels->rotate(340);
        }else{
            if(isset($x_inputs) and $x_inputs){
                $calc_array = array();
                
                foreach ($inputs as $entry_id => $in){
                    $entry_id = (int)$entry_id;
                    if(!isset($values[$entry_id]))
                        $values[$entry_id] = 0;
                        
                    $labels[$entry_id] = (isset($x_inputs[$entry_id])) ? $x_inputs[$entry_id] : ''; 
                    
                    if(!isset($calc_array[$entry_id]))
                        $calc_array[$entry_id] = array('count' => 0);
                        
                    if($data_type == 'total' or $data_type == 'average'){    
                        $values[$entry_id] += (int)$in;
                        $calc_array[$entry_id]['total'] = $values[$entry_id];
                        $calc_array[$entry_id]['count']++;
                    }else{
                        $values[$entry_id]++;
                    }
                    
                    unset($entry_id);
                    unset($in);
                }
                
                if($data_type == 'average'){
                    foreach($calc_array as $entry_id => $calc){
                        $values[$entry_id] = ($calc['total'] / $calc['count']);
                        unset($entry_id);
                        unset($calc);
                    }
                }
                
                $calc_array = array();
                foreach($f_inputs as $f_id => $f){
                    if(!isset($calc_array[$f_id]))
                        $calc_array[$f_id] = array();
                        
                    foreach($f as $entry_id => $in){
                        $entry_id = (int)$entry_id;
                        if(!isset($labels[$entry_id])){
                            $labels[$entry_id] = (isset($x_inputs[$entry_id])) ? $x_inputs[$entry_id] : '';
                            $values[$entry_id] = 0;
                        }
                        
                        if(!isset($calc_array[$f_id][$entry_id]))
                            $calc_array[$f_id][$entry_id] = array('count' => 0);
                            
                        if(!isset($f_values[$f_id][$entry_id]))
                            $f_values[$f_id][$entry_id] = 0;
                        
                        if($data_type == 'total' or $data_type == 'average'){    
                            $f_values[$f_id][$entry_id] += (int)$in;
                            $calc_array[$f_id][$entry_id]['total'] = $f_values[$f_id][$entry_id];
                            $calc_array[$f_id][$entry_id]['count']++;
                        }else{
                            $f_values[$f_id][$entry_id]++;
                        }
                        
                        unset($entry_id);
                        unset($in);
                    }
                    
                    unset($f_id);
                    unset($f);
                }
                
                if($data_type == 'average'){
                    foreach($calc_array as $f_id => $calc){
                        foreach($calc as $entry_id => $c){
                            $f_values[$f_id][$entry_id] = ($c['total'] / $c['count']);
                            unset($entry_id);
                            unset($c);
                        }
                        unset($calc);
                        unset($f_id);
                    }
                }
                unset($calc_array);
                
            }else{
                $id_count = array_count_values(array_map('strtolower', $inputs));
                arsort($id_count);
                
                $i = 0;
                foreach ($id_count as $val => $count){
                    if ($i < $response_count){
                        if ($field->type == 'user_id'){
                            $user_info = get_userdata($val);
                            $new_val = $user_info->display_name;
                        }else{
                            $new_val = ucwords($val);
                        }
                        $labels[$new_val] = $new_val;
                        $values[$new_val] = $count;
                        
                    }
                    $i++;
                }
                
                foreach($f_inputs as $f_id => $f){
                    $id_count = array_count_values(array_map('strtolower', $f));
                    arsort($id_count);

                    $i = 0;
                    foreach ($id_count as $val => $count){
                        if ($i < $response_count){
                            if ($field->type == 'user_id'){
                                $user_info = get_userdata($val);
                                $new_val = $user_info->display_name;
                            }else{
                                $new_val = ucwords($val);
                            }
                            $position = array_search($new_val, $labels);
                            if(!$position){
                                end($labels);
                                $position = key($labels);
                                $labels[$new_val] = $new_val;
                                $values[$new_val] = 0;
                                
                            }
                            $f_values[$f_id][$new_val] = $count;
                        }
                        $i++;

                    }

                    unset($f_id);
                    unset($f);
                }
                
            }
            
            $x_labels->rotate(340);
        }
         
         if(isset($x_inputs) and $x_inputs){       
            $used_vals = $calc_array = array();
            foreach($labels as $l_key => $label){
                if(empty($label) and (!empty($x_start) or !empty($x_end))){
                    unset($values[$l_key]);
                    unset($labels[$l_key]);
                    continue;
                }
                
                if(in_array($x_axis, array('created_at', 'updated_at')))
                    $labels[$l_key] = $label = date('Y-m-d', strtotime($label)); 
                 
                if(isset($used_vals[$label])){
                    $values[$l_key] += $values[$used_vals[$label]];
                    unset($values[$used_vals[$label]]);
                    
                    foreach($ids as $f_id){
                        if(!isset($f_values[$f_id][$l_key]))
                            $f_values[$f_id][$l_key] = 0;
                        if(!isset($f_values[$f_id][$used_vals[$label]]))
                            $f_values[$f_id][$used_vals[$label]] = 0;
                            
                        $f_values[$f_id][$l_key] += $f_values[$f_id][$used_vals[$label]];
                        unset($f_values[$f_id][$used_vals[$label]]);
                        unset($f_id);
                    }
                    
                    unset($labels[$used_vals[$label]]);
                }
                $used_vals[$label] = $l_key;
                
                if($data_type == 'average'){
                    if(!isset($calc_array[$label]))
                        $calc_array[$label] = 0;
                    $calc_array[$label]++;
                }
                
                unset($label);
                unset($l_key);
            }
            
            if(!empty($calc_array)){
                foreach($calc_array as $label => $calc){
                    if(isset($used_vals[$label])){
                        $values[$used_vals[$label]] = ($values[$used_vals[$label]] / $calc);
                        
                        foreach($ids as $f_id){
                            $f_values[$f_id][$used_vals[$label]] = ($f_values[$f_id][$used_vals[$label]] / $calc);
                            unset($f_id);
                        }
                    }
                    
                    unset($label);
                    unset($calc);
                }
            }
            unset($used_vals);
        }
        
        $combine_dates = false;
        if((isset($x_field) and $x_field and $x_field->type == 'date') or in_array($x_axis, array('created_at', 'updated_at')))
            $combine_dates = apply_filters('frm_combine_dates', true, $x_field);
            
        if($combine_dates){
            if($include_zero){
                $start_timestamp = (empty($x_start)) ? time() : strtotime($x_start);
                $end_timestamp = (empty($x_end)) ? time() : strtotime($x_end);
                $dates_array = array();
                
                // Get the dates array
                for($e = $start_timestamp; $e <= $end_timestamp; $e += 60*60*24)
                    $dates_array[] = date('Y-m-d', $e);
                
                unset($e);

                // Add the zero count days
                foreach($dates_array as $date_str){
                    if(!in_array($date_str, $labels)){
                        $labels[$date_str] = $date_str;
                        $values[$date_str] = 0;
                        foreach($ids as $f_id){
                            if(!isset($f_values[$f_id][$date_str]))
                                $f_values[$f_id][$date_str] = 0;
                        }
                    }
                }
                
                unset($dates_array);
                unset($start_timestamp);
                unset($end_timestamp);
            }
            
            asort($labels);
            
            global $frmpro_settings;
            foreach($labels as $l_key => $l){
                if((isset($x_field) and $x_field and $x_field->type == 'date') or in_array($x_axis, array('created_at', 'updated_at'))){
                    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $l)){ 
                        global $frmpro_settings;
                        $labels[$l_key] = FrmProAppHelper::convert_date($l, 'Y-m-d', $frmpro_settings->date_format);
                    }
                }
                unset($l_key);
                unset($l);
            }
            
            $values = FrmProAppHelper::sort_by_array($values, array_keys($labels));
            foreach($ids as $f_id){
                $f_values[$f_id] = FrmProAppHelper::sort_by_array($f_values[$f_id], array_keys($labels));
                $f_values[$f_id] = FrmProAppHelper::reset_keys($f_values[$f_id]);
                ksort($f_values[$f_id]);
                unset($f_id);
            }
        }else{
            if(isset($x_inputs) and $x_inputs){
                foreach($labels as $l_key => $l){
                    foreach($ids as $f_id){
                        //do a last check to make sure all bars/lines have a value for each label
                        if(!isset($f_values[$f_id][$l_key]))
                            $f_values[$f_id][$l_key] = 0;
                        unset($fid);
                    }
                    unset($l_key);
                    unset($l);
                }
            }
            
            foreach($ids as $f_id){
                $f_values[$f_id] = FrmProAppHelper::reset_keys($f_values[$f_id]);
                ksort($f_values[$f_id]);
                unset($f_id);
            }
            
            ksort($labels);
            ksort($values);
        }
        
        $labels = FrmProAppHelper::reset_keys($labels);
        $values = FrmProAppHelper::reset_keys($values);
        
        $pie = ($type == 'default') ? $pie : (($type == 'pie') ? true : false);
        if ($pie){
            $bar = new pie();
            $bar->set_alpha(0.6);
            $bar->set_start_angle( 35 );
            $bar->add_animation( new pie_fade() );
            $bar->set_tooltip( '#val# of #total#<br>#percent# of 100%' );
            $bar->set_colours( $colors );
            $pie_values = array();
            //$total_count = count($inputs);
            foreach ($values as $val_key => $val){
                if($val)
                    $pie_values[] = new pie_value($val, "$labels[$val_key] ($val)");
            }
            
            $bar->set_values( $pie_values );
        }else{
            $color = $odd ? current($colors) : next($colors);
            if(!$color)
                $color = reset($colors);

            if($type == 'line')
                $bar = new line();
            else if($type == 'hbar')
                $bar = new hbar($color);
            else if($type == 'area') 
                $bar = new area();
            else if($type == 'bar_flat')
                $bar = new bar($color);
            else
                $bar = new bar_glass($color);
            
            $bar->set_colour( $color);    
            $bar->set_values( $values );
            if($show_key)
                $bar->set_key( stripslashes($field->name), $show_key );
                
            $x_labels->set_labels( $labels );
            $x->set_labels( $x_labels );
            $x->set_grid_colour( $grid_color );
            $y->set_grid_colour( $grid_color );

            if($combine_dates and !strpos($width, '%') and ((count($labels) * 30) > (int)$width))
                $x_labels->visible_steps(ceil((count($labels) * 30) / (int)$width));
            
            $set_max = $max;
            if (!empty($values) and empty($max)){
                $max = abs(max($values)*1.2);
                
                if ($max < 3) $max = 3;
            }
            
            foreach($ids as $f_id){
                $new_max = abs(max($f_values[$f_id])*1.2);
                if($set_max != $max and $new_max > $max)
                    $max = $new_max;
                unset($f_id);
                unset($new_max);
            }
            
            $bars = array();
            foreach($f_values as $f_id => $f_vals){
                if($type == 'line')
                    $bars[$f_id] = new line();
                else if($type == 'hbar')
                    $bars[$f_id] = new hbar($color);
                else if($type == 'area') 
                    $bars[$f_id] = new area();
                else if($type == 'bar_flat')
                    $bars[$f_id] = new bar();
                else
                    $bars[$f_id] = new bar_glass();
                
                $color = next($colors);
                if(!$color)
                    $color = reset($colors);
                    
                $bars[$f_id]->set_colour( $color );    
                $bars[$f_id]->set_values( $f_vals );
                if($show_key)
                    $bars[$f_id]->set_key( stripslashes($fields[$f_id]->name), $show_key );
                
                
                unset($f_id);
            }
            
            if(isset($max) and !empty($max)){
                $step = ceil($max/10);
                if(empty($min))
                    $min = 0;
                $y->set_range($min, $max, $step);
            }
        }

        $chart->add_element( $bar );
        if(isset($bars) and !empty($bars)){
            foreach($bars as $f_bar)
                $chart->add_element( $f_bar );
        }
        
        $chart->set_bg_colour( $bg_color );
        
        if(!$pie){
            $chart->set_y_axis( $y );
            $chart->set_x_axis( $x );  
        }    
        
        return $chart->toPrettyString();
    }
    
    function graph_shortcode($atts){
        extract(shortcode_atts(array(
            'id' => false, 'id2' => false, 'id3' => false, 'id4' => false, 'ids' => false,
            'include_js' => true, 'colors' => '#EF8C08,#21759B,#1C9E05', 'grid_color' => '#f7e8bf', 
            'bg_color' => '#FFFFFF', 'height' => 400, 'width' => 400, 'truncate' => 40, 
            'truncate_label' => 7, 'response_count' => 10, 'user_id' => false, 
            'type' => 'default', 'x_axis' => false, 'data_type' => 'count', 'limit' => '',
            'x_start' => '', 'x_end' => '', 'show_key' => false, 'min' => '', 'max' => '',
            'include_zero' => false
        ), $atts));
        
        if (!$id) return;
        global $frm_field;
        
        if(!$ids and ($id2 or $id3 or $id4)){
            $ids = array($id2, $id3, $id4);
            $ids = implode(',', $ids);
        }
            
            
        $x_axis = (!$x_axis or $x_axis == 'false') ? false : $x_axis;
        
        if($user_id == 'current'){
            global $user_ID;
            $user_id = $user_ID;
        }
        
        $html = $js = $js_content = $js_content2 = '';
        $fields = $frm_field->getAll("fi.id in ($id)");
        $colors = explode(',', $colors);
        
        require_once(FRMPRO_PATH . '/js/ofc-library/open-flash-chart-object.php');
        require_once(FRMPRO_PATH . '/js/ofc-library/open-flash-chart.php');
        
        $js_content .= '<script type="text/javascript">';
        if($include_js){
            $js_content .= 'if(typeof(swfobject) == "undefined")
                jQuery("head").append(\'<script type="text/javascript" src="'.FRMPRO_URL.'/js/swfobject.js"><\/script>\');
                jQuery("head").append(\'<script type="text/javascript" src="'.FRMPRO_URL.'/js/json2.js"><\/script>\');'."\n";
        }
        
        global $frm_gr_count;
        if(!$frm_gr_count)
            $frm_gr_count = 0;
        foreach ($fields as $field){
            $frm_gr_count++;
            $this_id = $field->id .'_'. $frm_gr_count;
            $html .= '<div id="chart_'. $this_id .'"></div>';
            $js .= 'swfobject.embedSWF("'.FRMPRO_URL.'/js/open-flash-chart.swf","chart_'. $this_id .'","'.$width.'","'.$height.'","9.0.0","expressInstall.swf",{"get-data":"get_data_'. $this_id .'"},{"wmode" : "transparent"});';
            $js_content2 .= 'function get_data_'. $this_id .'(){return JSON.stringify(data_'. $this_id .');}';
            $js_content2 .= 'var data_'. $this_id .'='. $this->get_graph($field, compact('ids', 'colors', 'grid_color', 'bg_color', 'truncate', 'truncate_label', 'response_count', 'user_id', 'type', 'x_axis', 'data_type', 'limit', 'x_start', 'x_end', 'show_key', 'min', 'max', 'include_zero', 'width')) .';';
        }
            
        $js_content .= $js . $js_content2;
        $js_content .= '</script>';
        
        return $js_content . $html;
    }
    
    function stats_shortcode($atts){
        $defaults = array(
            'id' => false, 'type' => 'total', 'user_id' => false, 
            'value' => false, 'round' => 100, 'limit' => ''
        );

        extract(shortcode_atts($defaults, $atts));
        if (!$id) return;
        if($user_id == 'current'){
            global $user_ID;
            $user_id = $user_ID;
        }
        
        foreach($defaults as $unset => $val)
            unset($atts[$unset]);
            
        return FrmProFieldsHelper::get_field_stats($id, $type, $user_id, $value, $round, $limit, $atts);
    }
    
}

?>