<?php
/******************************************************************************
 * File: generate-xml.php 
 * 
 * Create the XML document that the Google Maps interface needs to show points
 * on the map.  This is what we run when a user does a search.  The JavaScript
 * function eventually calls this on the backend via an AJAX style interface.
 *
 ******************************************************************************/


error_reporting(0);
header("Content-type: text/xml");
include("database-info.php");
$dbPrefix = $wpdb->prefix;

// Get parameters from URL
$center_lat = $_GET["lat"];
$center_lng = $_GET["lng"];
$radius = $_GET["radius"];

//Since miles is default, if kilometers is selected, divide by 1.609344 in order to convert the kilometer value selection back in miles when generating the XML
//
$multiplier=3959;
$multiplier=(get_option('sl_distance_unit')=="km")? ($multiplier*1.609344) : $multiplier;


//-----------------
// Set the active MySQL database
//
$connection=mysql_connect ($host, $username, $password);
if (!$connection) { die('Not connected : ' . mysql_error()); }
$db_selected = mysql_select_db($database, $connection);
mysql_query("SET NAMES utf8");
if (!$db_selected) {
  die ('Can\'t use db : ' . mysql_error());
}

//-----------------
// Show Tag Search Is Enabled
//
$tag_filter = ''; 
if (
	(get_option(SLPLUS_PREFIX.'_show_tag_search') ==1) &&
	isset($_GET['tags']) && ($_GET['tags'] != '')
   ){
    $posted_tag = preg_replace('/^\s+(.*?)/','$1',$_GET['tags']);
    $posted_tag = preg_replace('/(.*?)\s+$/','$1',$posted_tag);
	$tag_filter = " AND ( sl_tags LIKE '%%". $posted_tag ."%%') ";
}

// Select all the rows in the markers table
$query = sprintf(
	"SELECT *,".
	"( $multiplier * acos( cos( radians('%s') ) * cos( radians( sl_latitude ) ) * cos( radians( sl_longitude ) - radians('%s') ) + sin( radians('%s') ) * sin( radians( sl_latitude ) ) ) ) AS sl_distance ".
	"FROM ${dbPrefix}store_locator HAVING (sl_distance < '%s') ".
	$tag_filter .
	'ORDER BY sl_distance ASC',
	mysql_real_escape_string($center_lat),
	mysql_real_escape_string($center_lng),
	mysql_real_escape_string($center_lat),
	mysql_real_escape_string($radius)
	);
	
//Order by distance	
/*
$query = sprintf(
	"SELECT *,".
	"( $multiplier * acos( cos( radians('%s') ) * cos( radians( sl_latitude ) ) * cos( radians( sl_longitude ) - radians('%s') ) + sin( radians('%s') ) * sin( radians( sl_latitude ) ) ) ) AS sl_distance ".
	"FROM ${dbPrefix}store_locator HAVING (sl_distance < '%s') ".
	$tag_filter .
	'ORDER BY sl_distance ASC',
	mysql_real_escape_string($center_lat),
	mysql_real_escape_string($center_lng),
	mysql_real_escape_string($center_lat),
	mysql_real_escape_string($radius)
	);
*/


$result = mysql_query($query);
if (!$result) {
  die('Invalid query: ' . mysql_error());
}


// Reporting
// Insert the query into the query DB
// 
if (get_option(SLPLUS_PREFIX.'-reporting_enabled') === 'on') {
    $qry = sprintf(                                              
            "INSERT INTO ${dbPrefix}slp_rep_query ". 
                       "(slp_repq_query,slp_repq_tags,slp_repq_address,slp_repq_radius) ". 
                "values ('%s','%s','%s','%s')",
                mysql_real_escape_string($_SERVER['QUERY_STRING']),
                mysql_real_escape_string($_GET['tags']),
                mysql_real_escape_string($_GET['address']),
                mysql_real_escape_string($_GET['radius'])
            );
    $wpdb->query($qry);
    $slp_QueryID = mysql_insert_id();
}

// Show Tags
//
$slplus_show_tags = (get_option(SLPLUS_PREFIX.'_show_tags') ==1);

// Start XML file, echo parent node
echo "<markers>\n";
// Iterate through the rows, printing XML nodes for each
while ($row = @mysql_fetch_assoc($result)){
    
  // ADD TO XML DOCUMENT NODE
  echo '<marker ';
  echo 'name="' . esc_attr($row['sl_store']) . '" ';
  echo 'address="' . 
        esc_attr($row['sl_address']) . ', '. 
        esc_attr($row['sl_address2']) . ', '.
        esc_attr($row['sl_city']). ', ' .esc_attr($row['sl_state']).' ' .
        esc_attr($row['sl_zip']).'" ';
  echo 'lat="' . $row['sl_latitude'] . '" ';
  echo 'lng="' . $row['sl_longitude'] . '" ';
  echo 'distance="' . $row['sl_distance'] . '" ';
  echo 'description="' . esc_attr($row['sl_description']) . '" ';
  echo 'url="' . esc_attr($row['sl_url']) . '" ';
  echo 'email="' . esc_attr($row['sl_email']) . '" ';
  echo 'hours="' . esc_attr($row['sl_hours']) . '" ';
  echo 'phone="' . esc_attr($row['sl_phone']) . '" ';
  echo 'image="' . esc_attr($row['sl_image']) . '" ';
  if ($slplus_show_tags) {  
      echo 'tags="'  . esc_attr($row['sl_tags']) . '" ';
  }
  echo "/>\n";
  
    // Reporting
    // Insert the results into the reporting table
    //
    if (get_option(SLPLUS_PREFIX.'-reporting_enabled') === "on") {
        $wpdb->query(
            sprintf(
                "INSERT INTO ${dbPrefix}slp_rep_query_results 
                    (slp_repq_id,sl_id) values (%d,%d)",
                    $slp_QueryID,
                    $row['sl_id']  
                )
            );           
    }  
}

// End XML file
echo "</markers>\n";

    

