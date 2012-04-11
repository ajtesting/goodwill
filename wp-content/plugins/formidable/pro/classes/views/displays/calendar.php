<?php

for ($i=0; $i<($maxday+$startday); $i++){
    $end_tr = false;
    if(($i % 7) == 0 ) echo "<tr>\n";
    if(($i < $startday)) echo "<td></td>\n";
    else{ ?>
<td<?php echo (((($i % 7) == 0) or (($i % 7) == 6)) ? ' class="frmcal-week-end"':'') ?>><div class="frmcal_date"><?php echo ($day = $i - $startday + 1) ?></div> <div class="frmcal-content">
    <?php
        if(isset($daily_entries) and isset($daily_entres[$i]) and !empty($daily_entres[$i])){
            foreach($daily_entres[$i] as $entry){
                if(isset($used_entries) and isset($used_entries[$entry->id])){
                    echo '<div class="frm_cal_multi_'. $entry->id .'">'. $used_entries[$entry->id] .'</div>';
                }else{
                    echo $this_content = apply_filters('frm_display_entry_content', $new_content, $entry, $shortcodes, $display, $show);
                
                    if(isset($used_entries))
                        $used_entries[$entry->id] = $this_content;
                    unset($this_content);
                }
            }
        } 
    ?></div>
</td>
<?php
    }
    if(($i % 7) == 6 ){
        $end_tr = true;
        echo "</tr>\n";
    }
}

while($extrarows != 0) {
echo "<td></td>\n";
$extrarows--;
}

if(!$end_tr)
    echo '</tr>';

?>