<table class="form_results<?php echo ($style)? ' with_frm_style': ''; ?>" id="form_results<?php echo $form->id ?>" cellspacing="0">
    <thead>
    <tr>      
    <?php foreach ($form_cols as $col){ ?>
        <th><?php echo stripslashes($col->name); ?></th>
    <?php } ?>
    </tr>
    </thead>
    <tbody>
<?php if(empty($entries)){ ?>
    <tr><td colspan="<?php echo count($form_cols) ?>"><?php echo $no_entries ?></td></tr>
<?php
}else{
    $class = 'odd';
    foreach($entries as $entry){  ?>
        <tr class="frm_<?php echo $class ?>">
        <?php foreach ($form_cols as $col){ ?>
            <td valign="top">
                <?php echo FrmProEntryMetaHelper::display_value((isset($entry->metas[$col->id]) ? $entry->metas[$col->id] : false), $col, array('type' => $col->type, 'post_id' => $entry->post_id, 'entry_id' => $entry->id)); 
                ?>
            </td>
        <?php } ?>
        </tr>
<?php
    $class = ($class == 'even') ? 'odd' : 'even';
    }
}
?>
    </tbody>
    <tfoot>
    <tr>
        <?php foreach ($form_cols as $col){ ?>
            <th><?php echo stripslashes($col->name); ?></th>
        <?php } ?>
    </tr>
    </tfoot>
</table>