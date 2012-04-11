<?php 
    global $value;
    global $text_domain;
?>
<table>
    <tr>
        <td><div class="add_location_form">
            <label  for='store-<?php echo $value['sl_id']?>'><?php _e('Name of Location', $text_domain);?></label>
            <input name='store-<?php echo $value['sl_id']?>' value='<?php echo $value['sl_store']?>'><br/>

            <label  for='address-<?php echo $value['sl_id']?>'><?php _e('Street - Line 1', $text_domain);?></label>
            <input name='address-<?php echo $value['sl_id']?>' value='<?php echo $value['sl_address']?>'><br/>

		    <label  for='address2-<?php echo $value['sl_id']?>'><?php _e('Street - Line 2', $text_domain);?></label>
            <input name='address2-<?php echo $value['sl_id']?>' value='<?php echo $value['sl_address2']?>'><br/>

		    <label  for='city-<?php echo $value['sl_id']?>'><?php _e('City, State, ZIP', $text_domain);?></label>
            <input name='city-<?php echo $value['sl_id']?>'    value='<?php echo $value['sl_city']?>'     style='width: 21.4em; margin-right: 1em;'>
            <input name='state-<?php echo $value['sl_id']?>'   value='<?php echo $value['sl_state']?>'    style='width: 7em; margin-right: 1em;'>
            <input name='zip-<?php echo $value['sl_id']?>'     value='<?php echo $value['sl_zip']?>'      style='width: 7em;'><br/>

		    <label  for='country-<?php echo $value['sl_id']?>'><?php _e('Country', $text_domain);?></label>
            <input name='country-<?php echo $value['sl_id']?>' value='<?php echo $value['sl_country']?>'  style='width: 40em;'><br/>
            </div>
        </td>
    </tr>
</table>
