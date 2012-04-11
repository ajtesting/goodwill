<span id="frm_delete_field_<?php echo $field['id']; ?>-<?php echo $opt_key ?>_container" class="frm_single_option">
<a href="javascript:frm_delete_field_option(<?php echo $field['id']?>,<?php echo $opt_key ?>);" class="frm_single_visible_hover alignleft"><img src="<?php echo FRM_IMAGES_URL ?>/trash.png" alt="Delete" /></a>
<?php if ($field['type'] != 'select'){ ?>
<input type="<?php echo $field['type'] ?>" name="<?php echo $field_name ?><?php echo ($field['type'] == 'checkbox')?'[]':''; ?>" value="<?php echo str_replace('"', '&quot;', $opt) ?>"<?php echo isset($checked)? $checked : ''; ?>/> 
<?php } ?>
<label class="frm_ipe_field_option" id="field_<?php echo $field['id']?>-<?php echo $opt_key ?>"><?php echo $opt ?></label>
</span>
<div class="clear"></div>