<?php
//adding or removing tags for specified a locations
if ($_POST) {extract($_POST);}

//var_dump($sl_id); exit;
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
if ($act=="add_tag") {
	//adding tags
	$wpdb->query("UPDATE ".$wpdb->prefix."store_locator SET sl_tags=CONCAT(sl_tags, '".strtolower($sl_tags).", ') WHERE sl_id IN ($id_string)");
}
elseif ($act=="remove_tag") {
	//removing tags
	if (empty($sl_tags)) {
		//if no tag is specified, all tags will be removed from selected locations
		$wpdb->query("UPDATE ".$wpdb->prefix."store_locator SET sl_tags='' WHERE sl_id IN ($id_string)");
	}
	else {
		$wpdb->query("UPDATE ".$wpdb->prefix."store_locator SET sl_tags=REPLACE(sl_tags, '$sl_tags,', '') WHERE sl_id IN ($id_string)");
	}
}
?>