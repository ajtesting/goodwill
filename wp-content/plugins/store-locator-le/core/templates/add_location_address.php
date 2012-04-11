<?php 
global $text_domain, $sl_upload_path, $slplus_plugin;
?>
<table cellpadding='10px' cellspacing='0' style='width:100%' class='manual_add_table'>
    <tr>
        <td style='padding-top:0px;' valign='top'>
            <form name='manualAddForm' method=post enctype="multipart/form-data">
                <table cellpadding='0' class='widefat'>
                <thead><tr><th><?php _e("Enter An Address", $text_domain);?></th></tr></thead>
                <tr><td>
                <table>
                    <tr>
                        <td><div class="add_location_form">
                            <label  for='sl_store'><?php _e('Name of Location', $text_domain);?></label>
                            <input name='sl_store'><br/>
                
                            <label  for='sl_address'><?php _e('Street - Line 1', $text_domain);?></label>
                            <input name='sl_address'><br/>
                
                            <label  for='sl_address2'><?php _e('Street - Line 2', $text_domain);?></label>
                            <input name='sl_address2'><br/>
                
                            <label  for='sl_city'><?php _e('City, State, ZIP', $text_domain);?></label>
                            <input name='sl_city'   style='width: 21.4em; margin-right: 1em;'>
                            <input name='sl_state'  style='width: 7em; margin-right: 1em;'>
                            <input name='sl_zip'    style='width: 7em;'>
                            <br/>
                
                            <label  for='sl_country'><?php _e('Country', $text_domain);?></label>
                            <input name='sl_country'><br/>
                            <br/>
                            <input type='submit' value='<?php _e("Add Location", $text_domain);?>' class='button-primary'>
                            </div>
                            
                        </td>
                    </tr>
                </table>
        </td></tr>
        <thead><tr><th><?php _e("Additional Information", $text_domain);?></th></tr></thead>
        <tr><td><div class="add_location_form">
		    <label for='sl_description'><?php _e("Description", $text_domain);?></label>
            <textarea name='sl_description' rows='5'></textarea><br/>
            
            <label for='sl_tags'><?php _e("Tags", $text_domain);?></label>
            <input name='sl_tags'><br/>
            <span style="padding-left: 130px;"><em>separate with commas</em></span><br/>
            
            <label for='sl_url'><?php _e("URL", $text_domain);?></label>
            <input name='sl_url'><br/>
            
            <label for='sl_email'><?php _e("email", $text_domain);?></label>
            <input name='sl_email'><br/>
            
            <label for='sl_hours'><?php _e("Hours", $text_domain);?></label>
            <input name='sl_hours'><br/>
            
            <label for='sl_phone'><?php _e("Phone", $text_domain);?></label>
            <input name='sl_phone'><br/>
            
            <label for='sl_image'><?php _e("Image URL (shown with location)", $text_domain);?></label>
            <input name='sl_image'><br/>
            <br/>
            
            <input type='submit' value='<?php _e("Add Location", $text_domain);?>' class='button-primary'>
            </div>
	</div></td>
		</tr>

        <?php
        if (
            $slplus_plugin->license->packages['Plus Pack']->isenabled &&
            function_exists('execute_and_output_plustemplate')
            ) {
            execute_and_output_plustemplate('addlocations_bulkupload.php');
        }
        ?>  		
	</table>
	</form>
	</td>
    </tr>
</table>
</div>
