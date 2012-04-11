<div class="frmcal" id="frmcal-<?php echo $display->id ?>">
<div class="frmcal-header"><a href="<?php echo add_query_arg(array('frmcal-month' => $prev_month, 'frmcal-year' => $prev_year)) ?>#frmcal-<?php echo $display->id ?>" class="frmcal-prev" title="<?php echo $month_names[$prev_month] ?>">&#171; <?php echo $month_names[$prev_month] ?></a><a href="<?php echo add_query_arg(array('frmcal-month' => $next_month, 'frmcal-year' => $next_year)) ?>#frmcal-<?php echo $display->id ?>" class="frmcal-next" title="<?php echo $month_names[$next_month] ?>"><?php echo $month_names[$next_month] ?> &#187;</a><div class="frmcal-title"><span class="frmcal-month"><?php echo $month_names[$month] ?></span> <span class="frmcal-year"><?php echo $year ?></span></div>
</div>
<table class="frmcal-calendar">
    <thead>
        <tr><th class="frmcal-week-end"><span title="<?php echo $day_names[0] ?>"><?php echo $day_names[0] ?></span></th><th><span title="<?php echo $day_names[1] ?>"><?php echo $day_names[1] ?></span></th><th><span title="<?php echo $day_names[2] ?>"><?php echo $day_names[2] ?></span></th><th><span title="<?php echo $day_names[3] ?>"><?php echo $day_names[3] ?></span></th><th><span title="<?php echo $day_names[4] ?>"><?php echo $day_names[4] ?></span></th><th><span title="<?php echo $day_names[5] ?>"><?php echo $day_names[5] ?></span></th><th class="frmcal-week-end"><span title="<?php echo $day_names[6] ?>"><?php echo $day_names[6] ?></span></th></tr>
    </thead>
    <tbody>