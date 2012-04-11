<div class="postbox ">
<h3 class="hndle"><span><?php _e('Image Size', 'formidable') ?></span></h3>
<div class="inside">
	<p class="howto"><?php _e('Change the displayed size of your uploaded image', 'formidable') ?></p>
	<p><?php _e('Add', 'formidable') ?> <code>size=(thumbnail, medium, large, or full)</code><br/>
    <?php _e('Example', 'formidable') ?>: <code>[125 size="medium"]</code></p>
</div>
</div>

<div class="postbox ">
<h3 class="hndle"><span><?php _e('Data From Entries', 'formidable') ?></span></h3>
<div class="inside">
	<p class="howto"><?php _e('Specify the data shown for a "Data From Entries" field', 'formidable') ?> <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('Linked Entry id: id, Entry key: key, Linked entry created at: created_at, a field from the entry: use the id or key from the field in you other form.', 'formidable') ?>" /></p>
	<p><?php _e('Add', 'formidable') ?> <code>show=(id, key, created-at, *<?php _e('Field ID', 'formidable') ?>*, *<?php _e('Field Key', 'formidable') ?>*)</code><br/>
    <?php _e('Example', 'formidable') ?>: <code>[125 show="other-key"]</code></p>
</div>
</div>


<div class="postbox ">
<h3 class="hndle"><span><?php _e('User Information', 'formidable') ?></span></h3>
<div class="inside">
	<p class="howto"><?php _e('Use a field other than user Display Name if there is a User ID field in your form', 'formidable') ?></p>
	<p><?php _e('Add', 'formidable') ?> <code>show=(first_name, last_name, display_name, user_login, user_email)</code><br/>
    <?php _e('Example', 'formidable') ?>: <code>[125 show="first_name"]</code><br/>
    <?php _e('Leave blank instead of defaulting to User Login', 'formidable') ?>: <code>blank=1</code></p>
</div>
</div>


<div class="postbox ">
<h3 class="hndle"><span><?php _e('Other Options', 'formidable') ?></span></h3>
<div class="inside">
    <table width="100%">
    <tr><th width="110px">Code</th><th>Use</th></tr>
    <tr><td><code>sep</code></td><td>[125 sep=", "]</td></tr>
    <tr><td><code>equals</code></td><td>[if 125 equals="hello"]</td></tr> 
    <tr><td><code>not_equal</code></td><td>[if 125 not_equal="hello"]</td></tr> 
    <tr><td><code>sanitize</code> <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('Replaces spaces with dashes and lowercases all. Use if adding an HTML class or ID', 'formidable') ?>" /></td><td>[125 sanitize=1]</td></tr> 
    <tr><td><code>sanitize_url</code> <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('Replaces all HTML entities with a URL safe string.', 'formidable') ?>" /></td><td>[125 sanitize_url=1]</td></tr>
    <tr><td><code>truncate</code> <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('Truncate text with a link to view more. If using Both (dynamic), the link goes to the detail page. Otherwise, it will show in-place.', 'formidable') ?>" /></td><td>[125 truncate=100]</td></tr>
    <tr><td><code>more_text</code> <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('Specify the more link text.', 'formidable') ?>" /></td><td>[125 truncate=100 more_text="More"]</td></tr>
    <tr><td><code>clickable</code> <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('Automatically turn URLs and emails into links.', 'formidable') ?>" /></td><td>[125 clickable=1]</td></tr>
    </table>
</div>
</div>
