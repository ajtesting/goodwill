    
    <table class="form-table">
        <tr>
            <td width="200px"><label><?php _e('Create a Post', 'formidable') ?></label></td>
            <td><input type="checkbox" name="options[create_post]" value="1" <?php checked($values['create_post'], 1); ?> onclick="frm_show_div('frm_hide_post',this.checked,1,'.')"/> <?php _e('Create a WordPress post, page, or custom post type with this form', 'formidable') ?></td>
        </tr>
        
        <tr class="frm_hide_post" <?php echo $hide_post = ($values['create_post']) ? '' : 'style="display:none;"'; ?>>
            <td><label><?php _e('Post Type', 'formidable') ?></label>
                <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('To setup a new custom post type, install and setup a plugin like \'Custom Post Type UI\', then return to this page to select your new custom post type.', 'formidable') ?>" />
            </td>
            <td>
            <select name="options[post_type]">
                <?php foreach($post_types as $post_key => $post_type){
                    echo '<option value="'. $post_key .'" '. selected($values['post_type'], $post_key).'>'. $post_type->labels->singular_name .'</option>'."\n";
                    unset($post_type);
                    }

                unset($post_types); 
                
                ?>
            </select>
        </td></tr>
        <?php
        $values['post_category'] = $values['post_custom_fields'] = array();
        if(empty($values['post_category']) and !empty($values['fields'])){
            foreach($values['fields'] as $fo_key => $fo){
                if($fo['post_field'] == 'post_category'){
                    if(!isset($fo['taxonomy']) or $fo['taxonomy'] == '')
                        $fo['taxonomy'] = 'post_category';

                    $tax_count = FrmProFormsHelper::get_taxonomy_count($fo['taxonomy'], $values['post_category']);

                    $values['post_category'][$fo['taxonomy'] .$tax_count] = array('field_id' => $fo['id'], 'exclude_cat' => $fo['exclude_cat'], 'meta_name' => $fo['taxonomy']);
                    unset($tax_count);
                }else if($fo['post_field'] == 'post_custom' and !array_key_exists($fo['custom_field'], $values['post_custom_fields'])){
                    $values['post_custom_fields'][$fo['custom_field']] = array('field_id' => $fo['id'], 'meta_name' => $fo['custom_field']);
                }
            }
        }
        ?> 
        <tr class="frm_hide_post" <?php echo $hide_post ?>>
            <td><label><?php _e('Post Title', 'formidable') ?> *</label></td>
            <td><select name="options[post_title]">
                <option value="">- <?php echo _e('Select Field', 'formidable') ?> -</option>
                <?php $post_key = 'post_title'; include('_post_field_options.php'); ?>
                </select>    
            </td>
        </tr>
        
        <tr class="frm_hide_post" <?php echo $hide_post ?>>
            <td><label><?php _e('Post Content', 'formidable') ?> *</label></td>
            <td><select name="options[post_content]">
                <option value="">- <?php echo _e('Select Field', 'formidable') ?> -</option>
                <?php $post_key = 'post_content'; include('_post_field_options.php'); ?>
                </select>    
            </td>
        </tr>
        
        <tr class="frm_hide_post" <?php echo $hide_post ?>>
            <td><label><?php _e('Excerpt', 'formidable') ?></label></td>
            <td><select name="options[post_excerpt]">
                <option value="">- <?php echo _e('None', 'formidable') ?> -</option>
                <?php $post_key = 'post_excerpt'; include('_post_field_options.php'); ?>
                </select>    
            </td>
        </tr>
        
        <tr class="frm_hide_post" <?php echo $hide_post ?>>
            <td><label><?php _e('Post Password', 'formidable') ?></label></td>
            <td><select name="options[post_password]">
                <option value="">- <?php echo _e('None', 'formidable') ?> -</option>
                <?php $post_key = 'post_password'; include('_post_field_options.php'); ?>
                </select>    
            </td>
        </tr>
        
        <tr class="frm_hide_post" <?php echo $hide_post ?>>
            <td><label><?php _e('Slug', 'formidable') ?></label></td>
            <td><select name="options[post_name]">
                <option value="">- <?php echo _e('Automatically Generate from Post Title', 'formidable') ?> -</option>
                <?php $post_key = 'post_name'; include('_post_field_options.php'); ?>
                </select>    
            </td>
        </tr>
        
        <tr class="frm_hide_post" <?php echo $hide_post ?>>
            <td><label><?php _e('Post Date', 'formidable') ?></label></td>
            <td><select name="options[post_date]">
                <option value="">- <?php echo _e('Use the Date Published', 'formidable') ?> -</option>
                <?php $post_key = 'post_date'; $post_field = array('date');
                    include('_post_field_options.php'); ?>
                </select>    
            </td>
        </tr>
        
        <tr class="frm_hide_post" <?php echo $hide_post ?>>
            <td><label><?php _e('Post Status', 'formidable') ?></label></td>
            <td><select name="options[post_status]">
                <option value="">- <?php echo _e('Create Draft', 'formidable') ?> -</option>
                <option value="publish" <?php selected($values['post_status'], 'publish') ?>>- <?php echo _e('Automatically Publish', 'formidable') ?> -</option>
                <?php $post_key = 'post_status'; $post_field = array('select', 'radio', 'hidden');
                    include('_post_field_options.php'); ?>
                </select>    
            </td>
        </tr>
        
        <?php 
            unset($post_field);
            unset($post_key);
        ?>


        <tr class="frm_hide_post" <?php echo $hide_post ?>>
            <td valign="top"><label><?php _e('Taxonomies/Categories', 'formidable') ?></label></td>
            <td>
                <div id="frm_posttax_rows" class="tagchecklist" style="padding-bottom:8px;">
                <?php 
                $tax_key = 0;
                foreach($values['post_category'] as $field_vars){
                        include(FRMPRO_VIEWS_PATH.'/frmpro-forms/_post_taxonomy_row.php');
                        $tax_key++;
                }
                ?>
                </div>
                <p><a href="javascript:frm_add_posttax_row();" class="button">+ Add</a></p>
            </td>
        </tr>
                            
        <tr class="frm_hide_post" <?php echo $hide_post ?>>
            <td valign="top"><label><?php _e('Custom Fields', 'formidable') ?></label>
                <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('To set the featured image, use \'_thumbnail_id\' as the custom field name.', 'formidable') ?>" />
            </td>
            <td>
                <div id="frm_postmeta_rows" class="tagchecklist" style="padding-bottom:8px;">
                <?php 
                foreach($values['post_custom_fields'] as $custom_data)
                    include(FRMPRO_VIEWS_PATH.'/frmpro-forms/_custom_field_row.php');
                ?>
                </div>
                <p><a href="javascript:frm_add_postmeta_row();" class="button">+ Add</a></p>
            </td>
        </tr>

    </table>