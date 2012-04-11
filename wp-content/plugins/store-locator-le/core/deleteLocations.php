<?php
if ($_POST) {extract($_POST);}
if (is_array($sl_id)==1) {
	$id_string="";
	foreach ($sl_id as $value) {
		$id_string.="$value,";
	}
	$id_string=substr($id_string, 0, strlen($id_string)-1);
}
else {
	$id_string=$sl_id;
}
$wpdb->query("DELETE FROM ".$wpdb->prefix."store_locator WHERE sl_id IN ($id_string)");
