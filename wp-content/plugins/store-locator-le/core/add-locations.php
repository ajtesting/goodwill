<?php
/****************************************************************************
 ** file: add-locations.php
 **
 ** handles the add locations form
 ***************************************************************************/

 
function add_this_addy($fields,$values,$theaddress) {
	global $wpdb;
	$fields=substr($fields, 0, strlen($fields)-1);
	$values=substr($values, 0, strlen($values)-1);	
	$wpdb->query("INSERT into ". $wpdb->prefix . "store_locator ($fields) VALUES ($values)");
	do_geocoding($theaddress);
	
}




/****************************************************************************
 ***************************************************************************/

global $wpdb, $sl_upload_path, $sl_path;

print "<div class='wrap'>
            <div id='icon-add-locations' class='icon32'><br/></div>
            <h2>".
            __('Store Locator Plus - Add Locations', SLPLUS_PREFIX).
            "</h2>";
            
initialize_variables();

//-------------------------
// Navbar Section
//-------------------------    
print '<div id="slplus_navbar">';
print get_string_from_phpexec(SLPLUS_COREDIR.'/templates/navbar.php');
print '</div>';


//Inserting addresses by manual input
//
$notpca = isset($_GET['mode']) ? ($_GET['mode']!="pca") : true;
if ( isset($_POST['sl_store']) && $_POST['sl_store'] && $notpca ) {
    $fieldList = '';
    $valueList = '';
	foreach ($_POST as $key=>$value) {
		if (ereg("sl_", $key)) {
			$fieldList.="$key,";
			$value=comma($value);
			$valueList.="\"".stripslashes($value)."\",";
		}
	}

	$this_addy = $_POST['sl_address'].', '.
		      $_POST['sl_city'].', '.$_POST['sl_state'].' '.
		      $_POST['sl_zip'];
	add_this_addy($fieldList,$valueList,$this_addy);
	print "<div class='updated fade'>".
            $_POST['sl_store'] ." " .
            __("Added Succesfully",SLPLUS_PREFIX) . '.</div>';
            
/** Bulk Upload
 **/
} elseif ( isset($_FILES['csvfile']['name']) && 
	   ($_FILES['csvfile']['name']!='')  &&
	    ($_FILES['csvfile']['size'] > 0)
	) {	

    if  (function_exists('custom_upload_mimes')) {
        add_filter('upload_mimes', 'custom_upload_mimes');
    }	

	// Get the type of the uploaded file. This is returned as "type/extension"
	$arr_file_type = wp_check_filetype(basename($_FILES['csvfile']['name']));
	if ($arr_file_type['type'] == 'text/csv') {

                // Save the file to disk
                //
                $updir = wp_upload_dir();
                $updir = $updir['basedir'].'/slplus_csv';
                if(!is_dir($updir)) {
                    mkdir($updir,0755);
                }
                if (move_uploaded_file($_FILES['csvfile']['tmp_name'],
                        $updir.'/'.$_FILES['csvfile']['name'])) {
                        $reccount = 0;
                             
                        $adle_setting = ini_get('auto_detect_line_endings');
                        ini_set('auto_detect_line_endings', true);                         
                        if (($handle = fopen($updir.'/'.$_FILES['csvfile']['name'], "r")) !== FALSE) {
                            $fldNames = array('sl_store','sl_address','sl_address2','sl_city','sl_state',
                                            'sl_zip','sl_country','sl_tags','sl_description','sl_url',
                                            'sl_hours','sl_phone','sl_email');
                            $maxcols = count($fldNames);
                            while (($data = fgetcsv($handle)) !== FALSE) {
                                $num = count($data);
                                if ($num <= $maxcols) {
                                    $fieldList = '';
                                    $valueList = '';
                                    $this_addy = '';
                                    for ($fldno=0; $fldno < $num; $fldno++) {
                                        $fieldList.=$fldNames[$fldno].',';
                                        $valueList.="\"".stripslashes(comma($data[$fldno]))."\",";
                                        if (($fldno>=1) && ($fldno<=6)) {
                                            $this_addy .= $data[$fldno] . ', ';
                                        }
                                    }
                                    $this_addy = substr($this_addy, 0, strlen($this_addy)-2);
                                    add_this_addy($fieldList,$valueList,$this_addy);
                                    sleep(0.5);
                                    $reccount++;
                                } else {
                                     print "<div class='updated fade'>".
                                        __('The CSV file has too many fields.',
                                            SLPLUS_PREFIX
                                            );
                                     print ' ';
                                     printf(__('Got %d expected less than %d.', SLPLUS_PREFIX),
                                        $num,$maxcols);
                                     print '</div>';                                    
                                }                                    
                            }
                            fclose($handle);
                        }
                        ini_set('auto_detect_line_endings', $adle_setting);                         
                        

                        if ($reccount > 0) {
                            print "<div class='updated fade'>".
                                    sprintf("%d",$reccount) ." " .
                                    __("locations added succesfully.",SLPLUS_PREFIX) . '</div>';
                        }                                

                // Could not save
                } else {
                        print "<div class='updated fade'>".
                        __("File could not be saved, check the plugin directory permissions:",SLPLUS_PREFIX) .
                            "<br/>" . $updir.

			    '.</div>';	
		}
					    
        // Not CSV Format Warning		    
	} else {
		print "<div class='updated fade'>".
		    __("Uploaded file needs to be in CSV format.",SLPLUS_PREFIX) . 
		    " Type was " . $arr_file_type['type'] . 
		    '.</div>';	
	}
}

	
$base=get_option('siteurl');

// Show the manual location entry form
execute_and_output_template('add_locations.php');
