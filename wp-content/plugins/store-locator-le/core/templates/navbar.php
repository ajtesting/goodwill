<?php
/****************************************************************************
 ** file: core/templates/navbar.php
 **
 ** The top Store Locator Settings navigation bar.
 ***************************************************************************/
 
 global $slplus_plugin;
?>

<ul>
<?php if (trim($slplus_plugin->driver_args['api_key'])!="") { ?>
    <li class='like-a-button'><a href="<?php echo SLPLUS_ADMINPAGE;?>view-locations.php">Locations: Manage</a></li>
    <li class='like-a-button'><a href="<?php echo SLPLUS_ADMINPAGE;?>add-locations.php">Locations: Add</a></li>
    <li class='like-a-button'><a href="<?php echo SLPLUS_ADMINPAGE;?>map-designer.php">Settings: Map</a></li>
<?php } else { 
    $slplus_plugin->notifications->add_notice(9,__('Enter your Google API key to enable location management.',SLPLUS_PREFIX));
}
?>
    <li class='like-a-button'><a href="<?php echo admin_url(); ?>options-general.php?page=csl-slplus-options">Settings: General</a></li>    
    <?php 
    //--------------------------------
    // Plus Version : Show Reports Tab
    //
    if ($slplus_plugin->license->packages['Plus Pack']->isenabled) {      
        print '<li class="like-a-button"><a href="'.SLPLUS_PLUSPAGE.'reporting.php">Reports</a></li>';
    }
    ?>    
</ul>


