<?php
/****************************************************************************
 ** file: core/templates/managelocations_actionbar.php
 **
 ** The action bar for the manage locations page.
 ***************************************************************************/
 
 global $slplus_plugin, $hidden;

 if (get_option('sl_location_table_view') == 'Expanded') {
     $altViewText = __('Switch to normal view?',SLPLUS_PREFIX);
     $viewText = __('Normal View',SLPLUS_PREFIX);
 } else {
     $altViewText = __('Switch to expanded view?',SLPLUS_PREFIX);
     $viewText = __('Expanded View',SLPLUS_PREFIX);
 }
?>
<script type="text/javascript">
function doAction(theAction,thePrompt) {
    if((thePrompt == '') || confirm(thePrompt)){
        LF=document.forms['locationForm'];
        LF.act.value=theAction;
        LF.submit();
    }else{
        return false;
    }
}
</script>
<form name='locationForm' method='post'>
<div id="action_buttons">
    <div id="action_bar_header"><h3><?php print __('Location Actions',SLPLUS_PREFIX); ?></h3></div>
    <div id="other_actions"  class='orangebox'>
        <p class="centerbutton"><a class='like-a-button' href="#" onclick="doAction('delete','<?php echo __('Delete selected?',SLPLUS_PREFIX);?>')" name="delete_selected"><?php echo __("Delete Selected", SLPLUS_PREFIX); ?></a></p>
            
            <?php 
            //----------
            // Plus Pack
            //
            if ($slplus_plugin->license->packages['Plus Pack']->isenabled) {
                ?>
                 <p class="centerbutton"><a class='like-a-button' href="#" onclick="doAction('recode','<?php echo __('Recode selected?',SLPLUS_PREFIX);?>')" name="delete_selected"><?php echo __("Recode Selected", SLPLUS_PREFIX); ?></a></p>
            <?php                 
            }
            ?>    
    </div>
    <?php 
    //----------
    // Plus Pack
    //
    if ($slplus_plugin->license->packages['Plus Pack']->isenabled) {
    ?>
        <div id="tag_block" class='orangebox'>
            <div id="tag_actions">
                <ul>
                    <li class='like-a-button'><a href="#" name="tag_selected"    onclick="doAction('add_tag','<?php echo __('Tag selected?',SLPLUS_PREFIX);?>');"   ><?php echo __('Tag Selected', SLPLUS_PREFIX);?></a></li>
                    <li class='like-a-button'><a href="#" name="untag_selected"  onclick="doAction('remove_tag','<?php echo __('Remove tag from selected?',SLPLUS_PREFIX);?>');"><?php echo __('Untag Selected', SLPLUS_PREFIX);?></a></li>
                </ul>
            </div>
            <div id="tagentry">
                <label for="sl_tags"><?php echo __('Tags', SLPLUS_PREFIX); ?></label><input name='sl_tags'>
            </div>        
        </div>
    <?php        
    }
    ?>
    <div id="search_block" class='searchlocations orangebox'>
            <p class="centerbutton"><input class='like-a-button' type='submit' value='<?php print __("Search Locations", SLPLUS_PREFIX); ?>'></p>
            <input id='search-q' value='<?php print (isset($_REQUEST['q'])?$_REQUEST['q']:''); ?>' name='q'>
            <?php print $hidden; ?>
    </div>  
    <div id="list_options" class='orangebox'>
        <p class="centerbutton"><a class='like-a-button' href='#' onclick="doAction('changeview','<?php echo $altViewText; ?>');"><?php echo $viewText; ?></a></p>
        <?php print __('Show ', SLPLUS_PREFIX); ?>
        <select name='sl_admin_locations_per_page'
           onchange="doAction('locationsPerPage','');">                
<?php           
    $pagelen = get_option('sl_admin_locations_per_page');
    $opt_arr=array(10,25,50,100,200,300,400,500,1000,2000,4000,5000,10000);
    foreach ($opt_arr as $value) {
        $selected=($pagelen==$value)? " selected " : "";
        print "<option value='$value' $selected>$value</option>";
    }
?>    
        </select>
        <?php print __(' locations', SLPLUS_PREFIX); ?>. 
    </div>    


<?php 
//--------------------------------
// Plus Version : Location Filters
//
if ($slplus_plugin->license->packages['Plus Pack']->isenabled) {      
?>
<div id="filter_buttons">
    <div id="filter_bar_header"><h3><?php print __('Location Filters',SLPLUS_PREFIX); ?></h3></div>
    <div id="filterbox_1"  class='orangebox'>
        <p class="centerbutton"><a class='like-a-button' href="#" onclick="doAction('show_uncoded','')" name="show_uncoded"><?php echo __("Uncoded Only", SLPLUS_PREFIX); ?></a></p>         
        <p class="centerbutton"><a class='like-a-button' href="#" onclick="doAction('show_all','')" name="show_all"><?php echo __("Show All", SLPLUS_PREFIX); ?></a></p>         
    </div>
</div>
<?php
}
?>

</div>

