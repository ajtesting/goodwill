<?php $current_page = (isset($_GET['page'])) ? $_GET['page'] : 'None'; ?>
<ul class="frm_form_nav">
	 <li class="last"> <a<?php if($current_page == 'formidable-reports') echo ' class="current_page"'; ?> href="<?php echo admin_url('admin.php?page=formidable') ?>-reports&amp;action=show&amp;form=<?php echo $id ?>&amp;show_nav=1"><?php _e('Reports', 'formidable') ?></a></li>
	<li> <a<?php if($current_page == 'formidable-entries') echo ' class="current_page"'; ?> href="<?php echo admin_url('admin.php?page=formidable') ?>-entries&amp;action=list&amp;form=<?php echo $id ?>&amp;show_nav=1"><?php _e('Entries', 'formidable') ?></a></li>
    <li><a<?php if(($current_page == 'formidable' or $current_page == 'formidable-new') and isset($_GET['action']) and $_GET['action'] == 'settings') echo ' class="current_page"'; ?> href="<?php echo admin_url('admin.php?page=formidable') ?>&amp;action=settings&amp;id=<?php echo $id ?>"><?php _e('Settings', 'formidable') ?></a> </li>
<li class="first"><a<?php if(($current_page == 'formidable' or $current_page == 'formidable-new') and isset($_GET['action']) and (in_array($_GET['action'], array('edit', 'new', 'duplicate')))) echo ' class="current_page"'; ?> href="<?php echo admin_url('admin.php?page=formidable') ?>&amp;action=edit&amp;id=<?php echo $id ?>"><?php _e('Build', 'formidable') ?></a> </li>
</ul>
<div class="clear"></div>