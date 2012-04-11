<?php
if(!$new_field)
    return;
    
if ($new_field->type == 'data'){

    if (isset($new_field->field_options['form_select']) && is_numeric($new_field->field_options['form_select']))
        $new_entries = $frm_entry_meta->getAll("it.field_id=".$new_field->field_options['form_select']);
        
    $new_field->options = array();
    if (isset($new_entries) && !empty($new_entries)){
        foreach ($new_entries as $ent)
            $new_field->options[$ent->item_id] = $ent->meta_value;
    }
}else if(isset($new_field->field_options['post_field']) and $new_field->field_options['post_field'] == 'post_status'){
    $new_field->options = FrmProFieldsHelper::get_status_options($new_field);
}else{
    $new_field->options = stripslashes_deep(maybe_unserialize($new_field->options));
}
    

if(isset($new_field->field_options['post_field']) and $new_field->field_options['post_field'] == 'post_category'){
    $new_field = (array)$new_field;
    $new_field['value'] = (isset($field) and isset($field['hide_opt'][$meta_name])) ? $field['hide_opt'][$meta_name] : '';
    $new_field['exclude_cat'] = (isset($new_field->field_options['exclude_cat'])) ? $new_field->field_options['exclude_cat'] : '';
    echo FrmFieldsHelper::dropdown_categories(array('name' => "field_options[hide_opt_{$current_field_id}][]", 'id' => "field_options[hide_opt_{$current_field_id}]", 'field' => $new_field) );
}else{ ?>
<select name="field_options[hide_opt_<?php echo $current_field_id ?>][]">
    <option value=""><?php echo ($new_field->type == 'data') ? 'Anything' : 'Select'; ?></option>
    <?php if($new_field->options){ ?>
    <?php foreach ($new_field->options as $opt_key => $opt){  
    $selected = (isset($field) && (($new_field->type == 'data' && $field['hide_opt'][$meta_name] == $opt_key) || $field['hide_opt'][$meta_name] == $opt)) ? ' selected="selected"' : ''; ?>
    <option value="<?php echo ($new_field->type == 'data') ? $opt_key : stripslashes(esc_html($opt)); ?>"<?php echo $selected; ?>><?php echo FrmAppHelper::truncate($opt, 25); ?></option>
    <?php } 
    } ?>
</select>
<?php 
} ?>