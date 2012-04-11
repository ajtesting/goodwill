<form action="<?php echo $action_link ?>" id="frm_search_form" method="get" >
    <?php if(preg_match("/[?]/", $action_link)){ ?>
    <input type="hidden" name="p" value="<?php echo $post_id ?>" />
    <?php } ?>
    <input type="search" name="frm_search" id="frm_search" />
    <div class="submit"><input type="submit" value="<?php echo $label ?>"/></div>
</form>