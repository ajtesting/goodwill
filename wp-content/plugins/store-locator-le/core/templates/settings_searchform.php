<?php 
    global  $city_checked, $country_checked, $show_tag_checked, $show_any_checked,
        $sl_radius_label, $sl_website_label,$sl_instruction_message,$slpMapSettings,
        $radii, $the_distance_unit;
?>       
<div id='search_settings'>
    <div class='section_column'>              
            <h2><?php _e('Features', SLPLUS_PREFIX);?></h2>
            
            <div class='form_entry'>
                <label for='sl_use_city_search'>
                    <?php _e('Show City Pulldown', SLPLUS_PREFIX); ?>:
                </label>
                <input name='sl_use_city_search' 
                    value='1' 
                    type='checkbox' 
                    <?php echo $city_checked?> 
                    >
            </div>
        
        <div class='form_entry'>
            <label for='radii'><?php _e('Radii Options', SLPLUS_PREFIX);?>:</label>
            <input  name='radii' value='<?php echo $radii;?>' size='25'>
            <?php
            echo slp_createhelpdiv('radii',
                __("Separate each number with a comma ','. Put parenthesis '( )' around the default.</span>", SLPLUS_PREFIX)
                );
            ?>              
        </div>  
            
        <div class='form_entry'>
            <label for='sl_distance_unit'><?php _e('Distance Unit', SLPLUS_PREFIX);?>:</label>
            <select name='sl_distance_unit'>
            <?php
                $the_distance_unit[__("Kilometers", SLPLUS_PREFIX)]="km";
                $the_distance_unit[__("Miles", SLPLUS_PREFIX)]="miles";
                
                foreach ($the_distance_unit as $key=>$value) {
                    $selected=(get_option('sl_distance_unit')==$value)?" selected " : "";
                    print "<option value='$value' $selected>$key</option>\n";
                }
                ?>
            </select>
        </div>    
        
           
        <?php
        if (function_exists('execute_and_output_plustemplate')) {
            execute_and_output_plustemplate('mapsettings_searchfeatures.php');
        } else {
            print "<div class='form_entry' style='text-align:right;padding-top:136px;'>Want more?<br/> <a href='http://www.cybersprocket.com/'>Check out our other WordPress offerings.</a></div>";
        }                    
        ?>        
    </div>
    
    
    <div class='section_column'>                     
        <h2><?php _e("Labels", SLPLUS_PREFIX); ?></h2>
        
        <div class='form_entry'>
            <label for='search_label'><?php _e("Address Input", SLPLUS_PREFIX); ?>:</label>
            <input name='search_label' value='<?php echo get_option('sl_search_label'); ?>'>
            <?php
            echo slp_createhelpdiv('search_label',
                __("Label for search form address entry.", SLPLUS_PREFIX)
                );
            ?>             
        </div>
        
        <?php
        if (function_exists('execute_and_output_plustemplate')) {
            execute_and_output_plustemplate('mapsettings_labels.php');
        }                
        ?>                     

        <div class='form_entry'>
            <label for='sl_radius_label'><?php _e("Radius Dropdown", SLPLUS_PREFIX); ?>:</label>
            <input name='sl_radius_label' value='<?php echo $sl_radius_label; ?>'>
            <?php
            echo slp_createhelpdiv('sl_radius_label',
                __("Label for search form radius pulldown.", SLPLUS_PREFIX)
                );
            ?>              
        </div>                

        <div class='form_entry'>
            <label for='sl_website_label'><?php _e("Website URL", SLPLUS_PREFIX);?>:</label>
            <input name='sl_website_label' value='<?php echo $sl_website_label; ?>'>
            <?php
            echo slp_createhelpdiv('sl_website_label',
                __("Label for website URL in search results.", SLPLUS_PREFIX)
                );
            ?>              
        </div>            

        <div class='form_entry'>
            <label for='sl_instruction_message'><?php _e("Instruction Message", SLPLUS_PREFIX); ?>:</label>
            <input name='sl_instruction_message' value='<?php echo $sl_instruction_message; ?>' size='50'>
            <?php
            echo slp_createhelpdiv('sl_instruction_message',
                __("Instruction text when map is first displayed.", SLPLUS_PREFIX)
                );
            ?>            
        </div>                          
    </div>
</div>
