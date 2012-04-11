<?php 
    global $map_type_options, $sl_num_initial_displayed, $the_domain, $char_enc,
            $zoom, $zoom_adj, $height,$height_units,$width,$width_units,
            $icon_notification_msg,$checked3,$icon,$icon2,$icon_str,$icon2_str;    
?>
<div id='map_settings'>
    <div class='section_column'>   
        <div class='map_designer_settings'>
            <h2><?php _e('Features', SLPLUS_PREFIX); ?></h2>
            <div class='form_entry'>
                <label for='sl_map_type'><?php _e('Default Map Type', SLPLUS_PREFIX);?>:</label>
                <select name='sl_map_type'><?php echo $map_type_options;?></select>
            </div>            
            <div class='form_entry'>
                <label for='sl_map_overview_control'><?php _e('Show Map Inset Box', SLPLUS_PREFIX);?>:</label>    
                <input name='sl_map_overview_control' value='1' type='checkbox' <?php echo (get_option('sl_map_overview_control')==1)?'checked':'';?> >
            </div>
            
            <div class='form_entry'>
                <label for='sl_load_locations_default'><?php _e("Immediately Show Locations", SLPLUS_PREFIX);?>:</label>
                <input name='sl_load_locations_default' value='1' type='checkbox' <?php echo (get_option('sl_load_locations_default')==1)?'checked':'';?> >
            </div>
            
            <div class='form_entry'>
                <label for='sl_num_initial_displayed'><? _e("Default Locations Shown", SLPLUS_PREFIX); ?>:</label>
                <input name='sl_num_initial_displayed' value='<?php echo $sl_num_initial_displayed;?>' class='small'>
                <?php
                echo slp_createhelpdiv('sl_num_initial_displayed',
                    __('Recommended Max: 50', SLPLUS_PREFIX)
                    );
                ?>                 
            </div>

            <?php
            if (function_exists('execute_and_output_plustemplate')) {
                execute_and_output_plustemplate('mapsettings_mapfeatures.php');
            }    
            ?>
        </div>
    </div>        

    
    <div class='section_column'>       
        <div class='map_designer_settings'>
            <h2><?php _e('Dimensions', SLPLUS_PREFIX);?></h2>
            
            <div class='form_entry'>
                <label for='zoom_level'><?php _e("Zoom Level", SLPLUS_PREFIX);?>:</label>
                <?php echo $zoom; ?>
                <?php
                echo slp_createhelpdiv('zoom_level',
                    __('19=street level, 0=world view. This is the initial zoom level of the map '.
                       ' if you do not check off "Immediately show locations.".  It is also the ' .
                       ' zoom level that will be used if a single location is returned by the search.' . 
                       ' All searches will automatically zoom in to a level that shows all of the matches on the map.', 
                       SLPLUS_PREFIX)
                    );
                ?>                 
                
            </div>

            <div class='form_entry'>
                <label for='zoom_tweak'><?php _e("Zoom  Adjustment", SLPLUS_PREFIX);?>:</label>
                <?php echo $zoom_adj; ?>
                <?php
                echo slp_createhelpdiv('zoom_tweak',
                    __('For the "auto-zoom" when results are shown the map will zoom to show all the returned locations, '.
                        'this setting allows you to determine how tight to zoom in. The higher the number the further out the zoom gets.', 
                       SLPLUS_PREFIX)
                    );
                ?>                 
                
            </div>

            
            <div class='form_entry'>
                <label for='height'><?php _e("Map Height", SLPLUS_PREFIX);?>:</label>
                <input name='height' value='<?php echo $height;?>' class='small'>&nbsp;
                <?php print choose_units($height_units, "height_units"); ?>
            </div>
            
            <div class='form_entry'>
                <label for='height'><?php _e("Map Width", SLPLUS_PREFIX);?>:</label>
                <input name='width' value='<?php echo $width;?>'  class='small'>&nbsp;
                <?php print choose_units($width_units, "width_units"); ?>
            </div>
        </div>
    </div>
    
    <div class='section_column'>       
        <div class='map_designer_settings'>
            <h2><?php _e('Icons', SLPLUS_PREFIX);?></h2>    
            <?php echo $icon_notification_msg;?>
            
            <div class='form_entry'>
                <label for='sl_remove_credits'><?php _e('Remove Credits', SLPLUS_PREFIX);?></label>
                <input name='sl_remove_credits' value='1' type='checkbox' <?php echo $checked3;?> >
            </div>
    
            <div class='form_entry'>
                <label for='icon'><?php _e('Home Icon', SLPLUS_PREFIX);?></label>
                <input name='icon' size='45' value='<?php echo $icon;?>' onchange="document.getElementById('prev').src=this.value">
                    &nbsp;&nbsp;<img id='prev' src='<?php echo $icon;?>' align='top'><br/>
                <div style='margin-left: 150px;'><?php echo $icon_str;?></div>        
            </div>
    
            <div class='form_entry'>
                <label for='icon2'><?php _e('Destination Icon', SLPLUS_PREFIX);?></label>
                <input name='icon2' size='45' value='<?php echo $icon2;?>' onchange="document.getElementById('prev2').src=this.value">
                    &nbsp;&nbsp;<img id='prev2' src='<?php echo $icon2;?>'align='top'><br/>
                <div style='margin-left: 150px;'><?php echo $icon2_str;?></div>
            </div>
        </div>
    </div>
    

    <div class='section_column'>   
        <div class='map_interface_settings'> 
            <h2><?php _e('Country', SLPLUS_PREFIX);?></h2>
            <div class='form_entry'>
                <label for='google_map_domain'><?php _e("Select Your Location", SLPLUS_PREFIX);?></label>
                <select name='google_map_domain'>
                <?php
                    foreach ($the_domain as $key=>$value) {
                        $selected=(get_option('sl_google_map_domain')==$value)?" selected " : "";
                        print "<option value='$key:$value' $selected>$key ($value)</option>\n";
                    }
                ?>
                </select>
            </div>
            
            <div class='form_entry'>
                <label for='sl_map_character_encoding'><?php _e('Select Character Encoding', SLPLUS_PREFIX);?></label>
                <select name='sl_map_character_encoding'>
                <?php
                    foreach ($char_enc as $key=>$value) {
                        $selected=(get_option('sl_map_character_encoding')==$value)?" selected " : "";
                        print "<option value='$value' $selected>$key</option>\n";                        
                    }
                ?>
                </select>
            </div>
        </div>
    </div>    
</div>

