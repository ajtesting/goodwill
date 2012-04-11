<div class="wrap">
    <div id="icon-edit-pages" class="icon32"><br/></div>
    <h2><?php printf(__('Add New %1$s Entry', 'formidable'), stripslashes($form->name)); ?></h2>
    
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

                    <div id="major-publishing-actions">
                	    <div id="delete-action">
                	    <a class="submitdelete deletion" onclick="history.back(-1)" title="<?php _e('Cancel', 'formidable') ?>"><?php _e('Cancel', 'formidable') ?></a>
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
            <?php $form_action = 'create'; ?>
            <?php wp_nonce_field('update-options'); ?>
            <?php if($form) FrmAppController::get_form_nav($form->id, true); ?>
            <?php require(FRM_VIEWS_PATH.'/frm-entries/form.php'); ?>
            
            <p>
                <input class="button-primary" type="submit" value="<?php echo esc_attr($submit) ?>" /> 
                <?php _e('or', 'formidable') ?>
                <a class="button-secondary cancel" href="?page=formidable-entries"><?php _e('Cancel', 'formidable') ?></a>
            </p>
            </div>
            </div>
            </div>
        </form>
        </div>
    </div>

</div>