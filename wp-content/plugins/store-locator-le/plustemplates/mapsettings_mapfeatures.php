<?php
    //----------------------------------------------------------------------
    // Plus Pack Enabled
    //
    global $slplus_plugin;
    if ($slplus_plugin->license->packages['Plus Pack']->isenabled) {                
?>

<!-- No Initial Directory -->
<div class='form_entry'>
    <label  for='<?php echo SLPLUS_PREFIX; ?>_disable_initialdirectory'><?php _e('Disable Initial Directory',SLPLUS_PREFIX); ?>:</label>
    <input name='<?php echo SLPLUS_PREFIX; ?>_disable_initialdirectory' value='1' type='checkbox'
    <?php
               if (get_option(SLPLUS_PREFIX.'_disable_initialdirectory') ==1) {
                   echo ' checked';
               }
    ?>
    >
    <?php
    echo slp_createhelpdiv('disable_initialdirectory',
        __('Do not display the listings under the map when "immediately show locations" is checked.', SLPLUS_PREFIX)
        );
    ?>      
</div>

<!-- Starting Image -->
<div class='form_entry'>
    <label  for='sl_starting_image'><?php _e("Starting Image",SLPLUS_PREFIX); ?>:</label>
    <input name='sl_starting_image' value='<?php echo get_option('sl_starting_image'); ?>' size='25'>
    <?php
    echo slp_createhelpdiv('sl_starting_image',
        __('If set, this image will be displayed until a search is performed.', SLPLUS_PREFIX)
        );
    ?>      
    
</div>


<!-- Show Tags -->
<div class='form_entry'>
    <label for='<?php echo SLPLUS_PREFIX; ?>_show_tags'><?php _e("Show Tags In Output",SLPLUS_PREFIX); ?>:</label>
    <input name='<?php echo SLPLUS_PREFIX; ?>_show_tags' value='1' type='checkbox'
    <?php
        if (get_option(SLPLUS_PREFIX.'_show_tags') ==1) {
            echo ' checked';
        }
    ?>
    >
    <?php
    echo slp_createhelpdiv('show-tags',
        __('Show the tags in the location output table and bubble.', SLPLUS_PREFIX)
        );
    ?>      
</div>

<!-- Email Form -->
<div class='form_entry'>
    <label for='<?php echo SLPLUS_PREFIX; ?>_use_email_form'><?php _e("Use Email Form",SLPLUS_PREFIX); ?>:</label>
    <input name='<?php echo SLPLUS_PREFIX; ?>_email_form' value='1' type='checkbox'
    <?php
        if (get_option(SLPLUS_PREFIX.'_email_form') ==1) {
            echo ' checked';
        }
    ?>
    >
    <?php
    echo slp_createhelpdiv('use_email_form',
        __('Use email form instead of mailto: link when showing email addresses.', SLPLUS_PREFIX)
        );
    ?>      
</div>


<!-- Disable ScrollWheel -->
<div class='form_entry'>
    <label  for='<?php echo SLPLUS_PREFIX; ?>_disable_scrollwheel'><?php _e('Disable Scroll Wheel',SLPLUS_PREFIX); ?>:</label>
    <input name='<?php echo SLPLUS_PREFIX; ?>_disable_scrollwheel' value='1' type='checkbox'
    <?php
               if (get_option(SLPLUS_PREFIX.'_disable_scrollwheel') ==1) {
                   echo ' checked';
               }
    ?>
    >
    <?php
    echo slp_createhelpdiv('disable_scrollwheel',
        __('Disable the scrollwheel zoom on the maps interface.', SLPLUS_PREFIX)
        );
    ?>      
</div>

<?php 
    echo CreateCheckboxDiv(
        '_disable_largemapcontrol3d',
        __('Hide map 3d control',SLPLUS_PREFIX),
        __('Turn the large map 3D control off.', SLPLUS_PREFIX)
        );
    
    echo CreateCheckboxDiv(
        '_disable_scalecontrol',
        __('Hide map scale',SLPLUS_PREFIX),
        __('Turn the map scale off.', SLPLUS_PREFIX)
        );
    
    echo CreateCheckboxDiv(
        '_disable_maptypecontrol',
        __('Hide map type',SLPLUS_PREFIX),
        __('Turn the map type selector off.', SLPLUS_PREFIX)
        );


    // Plus Pack Version 2.4
    //
    if ($slplus_plugin->license->packages['Plus Pack']->active_version >= 2004000) {
?>        

        <!-- Map Starting Point -->
        <div class='form_entry'>
            <label  for='<?php echo SLPLUS_PREFIX; ?>_map_center'><?php _e("Center Map At",SLPLUS_PREFIX); ?>:</label>
            <textarea name='<?php echo SLPLUS_PREFIX; ?>_map_center'><?php echo get_option(SLPLUS_PREFIX.'_map_center'); ?></textarea>
            <?php
            echo slp_createhelpdiv(SLPLUS_PREFIX.'_starting_image',
                __('Enter an address to serve as the initial focus for the map.  (Default: center of the country).', SLPLUS_PREFIX)
                );
            ?>      
            
        </div>        
<?php        
    }   
}    
?>        
