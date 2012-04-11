<table width=100% class='' cellpadding='3px'>
    <tr>
        <td valign=bottom width='33%' style='padding-left:0px'>
<?php
$pos=0;
if ($start<0 || $start=="" || !isset($start) || empty($start)) {$start=0;}
if ($num_per_page<0 || $num_per_page=="") {$num_per_page=10;}
$prev=$start-$num_per_page;
$next=$start+$num_per_page;
if (ereg("&start=$start",$_SERVER['QUERY_STRING'])) {
	$prev_page=str_replace("&start=$start","&start=$prev",$_SERVER['REQUEST_URI']);
	$next_page=str_replace("&start=$start","&start=$next",$_SERVER['REQUEST_URI']);
} else {
	$prev_page=$_SERVER['REQUEST_URI']."&start=$prev";
	$next_page=$_SERVER['REQUEST_URI']."&start=$next";
}
if ($numMembers2>$num_per_page) {

if ((($start/$num_per_page)+1)-5<1) {
	$beginning_link=1;
}
else {
	$beginning_link=(($start/$num_per_page)+1)-5;
}
if ((($start/$num_per_page)+1)+5>(($numMembers2/$num_per_page)+1)) {
	$end_link=(($numMembers2/$num_per_page)+1);
}
else {
	$end_link=(($start/$num_per_page)+1)+5;
}
$pos=($beginning_link-1)*$num_per_page;
	for ($k=$beginning_link; $k<$end_link; $k++) {
		if (ereg("&start=$start",$_SERVER['QUERY_STRING'])) {
			$curr_page=str_replace("&start=$start","&start=$pos",$_SERVER['QUERY_STRING']);
		}
		else {
			$curr_page=$_SERVER['QUERY_STRING']."&start=$pos";
		}
		if (($start-($k-1)*$num_per_page)<0 || ($start-($k-1)*$num_per_page)>=$num_per_page) {
			print "<a class='' href=\"{$_SERVER['PHP_SELF']}?$curr_page\" rel='nofollow'>";
		}
		print $k;
		if (($start-($k-1)*$num_per_page)<0 || ($start-($k-1)*$num_per_page)>=$num_per_page) {
			print "</a>";
		}
		$pos=$pos+$num_per_page;
		print "&nbsp;&nbsp;";
	}
}

$qry = isset($_GET['q'])?$_GET['q']:'';
$cleared=ereg_replace("q=$qry", "", $_SERVER['REQUEST_URI']);

$extra_text=(trim($qry)!='')    ? 
    __("for your search of", SLPLUS_PREFIX).
        " <strong>\"$qry\"</strong>&nbsp;|&nbsp;<a href='$cleared'>".
        __("Clear&nbsp;Results", SLPLUS_PREFIX)."</a>" : 
    "" ;
?>
</td>
<td align='center' valign='bottom' width='33%'><div class='' style='padding:5px; font-weight:normal'>
<?php 

	$end_num=($numMembers2<($start+$num_per_page))? $numMembers2 : ($start+$num_per_page) ;
	print "<nobr>".__("Results", SLPLUS_PREFIX)." <strong>".($start+1)." - ".$end_num."</strong>"; 
	if (!ereg("doSearch", (isset($_GET['u'])?$_GET['u']:''))) {
		print " ($numMembers2 ".__("total", SLPLUS_PREFIX).")".$extra_text; 
	}
	print "</nobr>";

?>
</div>
</td>
<td align=right valign=bottom width='33%' style='padding-right:0px'>
<table><tr><td width=75><nobr>
<?php 
if (($start-$num_per_page)>=0) {
  print "<a class='' href='$prev_page' rel='nofollow'>";
  print __("Previous", SLPLUS_PREFIX)."&nbsp;$num_per_page";
  print "</a>";
}
if (($start-$num_per_page)>=0 && ($start+$num_per_page)<$numMembers2) { ?>
&nbsp;&nbsp;|&nbsp;
<?php } ?>
</td>
<td width='85px' valign=bottom>
<?php 
if (($start+$num_per_page)<$numMembers2) { 
 print "<a class='' href='$next_page' rel='nofollow'>";
 print __("Next", SLPLUS_PREFIX)."&nbsp;$num_per_page";
 print "</a><br>";
} 
?>
</nobr>
</td></tr></table>
</td>
</tr>
</table>
<!--div style='margin:0px auto; position:relative; left:50px'><center><?php// if ($current_dir!="articles" && $current_dir!="groups") {include("$root/google/google_ads_728_90_2.php");} ?></center></div><br-->