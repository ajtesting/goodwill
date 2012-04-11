<?php
    echo CreateCheckboxDiv(
        '_hide_radius_selections',
        __('Hide radius selection',SLPLUS_PREFIX),
        __('Hides the radius selection from the user, the default radius will be used.', SLPLUS_PREFIX)
        );
    
    echo CreateCheckboxDiv(
        '_hide_address_entry',
        __('Hide address entry box',SLPLUS_PREFIX),
        __('Hides the address entry box from the user.', SLPLUS_PREFIX)
        );
    
    echo CreateCheckboxDiv(
        '_disable_search',
        __('Disable search',SLPLUS_PREFIX),
        __('This makes the search form non-interactive.  Typically used with the immediately show locations feature with a smaller listing set.', SLPLUS_PREFIX)
        );            

    //----------------------------------------------------------------------
    // Plus Pack Enabled
    //
    global $slplus_plugin;
    if ($slplus_plugin->license->packages['Plus Pack']->isenabled) {                
?>

<div class='form_entry'>
    <label for='slplus_show_state_pd'>
        <?php _e('Show State Pulldown', SLPLUS_PREFIX); ?>:
    </label>
    <input name='slplus_show_state_pd' 
        value='1' 
        type='checkbox' 
        <?php
        if (get_option('slplus_show_state_pd') ==1) {
            echo ' checked';
        }                
        ?> 
        >
</div>


<div class='form_entry'>
    <label for='sl_use_country_search'>
        <?php _e('Show Country Pulldown', SLPLUS_PREFIX); ?>:
    </label>
    <input name='sl_use_country_search' 
        value='1' 
        type='checkbox' 
        <?php
        if (get_option('sl_use_country_search') ==1) {
            echo ' checked';
        }                
        ?> 
        >
</div>

<?php
    echo CreateCheckboxDiv(
        '_show_tag_search',
        __('Tag Input',SLPLUS_PREFIX),
        __('Show the tag entry box on the search form.', SLPLUS_PREFIX)
        );        
?>

<div class='form_entry'>
    <label for='<?php echo SLPLUS_PREFIX; ?>_tag_search_selections'>
        <?php _e('Preselected Tag Searches', SLPLUS_PREFIX); ?>:
    </label>
    <input  name='<?php echo SLPLUS_PREFIX; ?>_tag_search_selections' 
        value='<?php print get_option(SLPLUS_PREFIX.'_tag_search_selections'); ?>' 
        >
    <?php
    echo slp_createhelpdiv('tag_search_selections',
        __("Enter a comma (,) separated list of tags to show in the search pulldown, mark the default selection with parenthesis '( )'. This is a default setting that can be overriden on each page within the shortcode.",SLPLUS_PREFIX)
        );
    ?>      
</div>        


<?php
    echo CreateCheckboxDiv(
        '_show_tag_any',
        __('Add "any" to tags pulldown',SLPLUS_PREFIX),
        __('Add an "any" selection on the tag pulldown list thus allowing the user to show all locations in the area, not just those matching a selected tag.', SLPLUS_PREFIX)
        );
    }    
?>

