<div class="wrap">
    <div id="icon-edit-pages" class="icon32"><br/></div>
    <h2><?php _e('Edit Entry', 'formidable') ?>
        <a href="?page=formidable-entries&amp;action=new" class="button add-new-h2"><?php _e('Add New', 'formidable'); ?></a>
    </h2>
        
    <div class="form-wrap">
        <div class="frm_forms<?php echo ($values['custom_style']) ? ' with_frm_style' : ''; ?>" id="frm_form_<?php echo $form->id ?>_container">
        <?php include(FRM_VIEWS_PATH.'/frm-entries/errors.php'); ?>

        <?php require(FRM_VIEWS_PATH.'/shared/nav.php'); ?>
        
        <form enctype="multipart/form-data" method="post"  id="form_<?php echo $form->form_key ?>">
        <div id="poststuff" class="metabox-holder has-right-sidebar">
        <div class="inner-sidebar">
            <div id="submitdiv" class="postbox ">
            <h3 class="hndle"><span><?php _e('Publish', 'formidable') ?></span></h3>
            <div class="inside">
                <div class="submitbox">
                <div id="minor-publishing" style="border:none;">
                <div class="misc-pub-section">
                    <?php if($record->post_id){ ?>
                    <a href="<?php echo get_permalink($record->post_id) ?>" class="button-secondary alignright" style="margin-left:10px"><?php _e('View Post', 'formidable') ?></a>
                    <?php } ?>
                    <a href="?page=formidable-entries&amp;action=show&amp;id=<?php echo $record->id; ?>" class="button-secondary alignright"><?php _e('View', 'formidable') ?></a>
                    <a href="?page=formidable-entries&amp;action=duplicate&amp;form=<?php echo $form->id ?>&amp;id=<?php echo $record->id; ?>" class="button-secondary alignright" style="margin-right:10px"><?php _e('Duplicate', 'formidable') ?></a>
                    <div class="clear"></div>

                    <p class="howto">
                    <?php FrmProEntriesHelper::resend_email_links($record->id, $form->id); ?>
                    </p>
                    
                </div>
                </div>
                
                <div id="major-publishing-actions">
            	    <div id="delete-action">
            	    <a class="submitdelete deletion" href="?page=formidable-entries&amp;action=destroy&amp;id=<?php echo $record->id; ?>&amp;form=<?php echo $form->id ?>" onclick="return confirm('<?php _e('Are you sure you want to delete this entry?', 'formidable') ?>);" title="<?php _e('Delete', 'formidable') ?>"><?php _e('Delete', 'formidable') ?></a>
            	    </div>
            	    <div id="publishing-action">
                    <input type="submit" value="<?php echo esc_attr($submit) ?>" class="button-primary" />
                    </div>
                    <div class="clear"></div>
                </div>
                </div>
            </div>
            </div>
        </div>
        
        <div id="post-body">
        <div id="post-body-content">
        <?php 
        $form_action = 'update'; 
        wp_nonce_field('update-options'); 
        if($form) FrmAppController::get_form_nav($form->id, true);
        require(FRM_VIEWS_PATH.'/frm-entries/form.php'); 
        ?>
        
        <p>
        <input class="button-primary" type="submit" value="<?php echo esc_attr($submit) ?>" /> 
        <?php _e('or', 'formidable') ?> 
        <a class="button-secondary cancel" href="?page=formidable-entries"><?php _e('Cancel', 'formidable') ?></a>
        </p>
        </div>
        </div>
        
        </form>
        </div>

        </div>
    </div>
    
</div>