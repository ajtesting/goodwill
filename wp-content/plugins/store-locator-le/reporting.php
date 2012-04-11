<?php
/****************************************************************************
 ** file: reporting.php
 **
 ** The reporting system
 ***************************************************************************/
 
global $slplus_plugin, $wpdb;

//===========================================================================
// Supporting Functions
//===========================================================================


/**************************************
 ** function: DetailDataSection()
 **
 ** Create a standard details section with a data table based on a MySQL Query
 **
 **/
function DetailDataSection($theQuery, $SectionHeader, $columnHeaders, $columnDataLines, $Qryname) {
    global $wpdb;
    $thisDataset = $wpdb->get_results($theQuery);
    $thisQryname = strtolower(preg_replace('/\s/','_',$Qryname));
    $thisQryvalue= htmlspecialchars($theQuery,ENT_QUOTES,'UTF-8');
    
    $thisSectionDesc = 
        '<div id="rb_details" class="reportblock">' .
            '<div class="rb_column">'.
                '<h2>' . $SectionHeader . '</h2>' .
                '<input type="hidden" name="'.$thisQryname.'" value="'.$thisQryvalue.'">' .
                '<table id="'.$Qryname.'_table" cellpadding="0" cellspacing="0">' .
                    '<thead>' .
                        '<tr>';
                        
    foreach ($columnHeaders as $columnHeader) {                        
       $thisSectionDesc .= "<th>$columnHeader</th>";
    }                            
                            
    $thisSectionDesc .=  '</tr>' .
                    '</thead>' .
                    '<tbody>'
                    ;

    $slpReportRowClass = 'rowon';                    
    foreach ($thisDataset as $thisDatapoint) {
        $slpReportRowClass = ($slpReportRowClass === 'rowoff') ? 'rowon' : 'rowoff';
        $thisSectionDesc .= '<tr>';
        foreach ($columnDataLines as $columnDataLine) {            
            $columnName = $columnDataLine['columnName'];
            $columnClass= $columnDataLine['columnClass'];
            $thisSectionDesc .= sprintf(
                '<td class="%s %s">%s</td>',
                $columnClass,
                $slpReportRowClass,
                $thisDatapoint->$columnName
                );           
        }
        $thisSectionDesc .= '</tr>';
    }
    
    $thisSectionDesc .=
                    '</tbody>' .
                '</table>'.
            '</div>' .
        '</div>'
        ;   
        
    return $thisSectionDesc;        
}


//===========================================================================
// Main Processing
//===========================================================================

// Data Settings
//
$slpQueryTable     = $wpdb->prefix . 'slp_rep_query';
$slpResultsTable   = $wpdb->prefix . 'slp_rep_query_results';
$slpLocationsTable = $wpdb->prefix . 'store_locator';
 
// Instantiate the form rendering object
//
$slpReportSettings = new wpCSL_settings__slplus(
    array(
            'no_license'        => true,
            'prefix'            => $slplus_plugin->prefix,
            'url'               => $slplus_plugin->url,
            'name'              => $slplus_plugin->name . ' - Reporting',
            'plugin_url'        => $slplus_plugin->plugin_url,
            'render_csl_blocks' => false,
            'form_action'       => admin_url().'admin.php?page='.SLPLUS_PLUGINDIR.'reporting.php',
            'save_text'         => 'Run Report'
        )
 ); 

//-------------------------
// Navbar Section
//-------------------------    
$slpReportSettings->add_section(
    array(
        'name' => 'Navigation',
        'div_id' => 'slplus_navbar',
        'description' => get_string_from_phpexec(SLPLUS_COREDIR.'/templates/navbar.php'),
        'is_topmenu' => true,
        'auto' => false
    )
);
 
//------------------------------------
// Create The Report Parameters Panel
//  
$slpReportSettings->add_section(
    array(
            'name'          => __('Report Parameters',SLPLUS_PREFIX),
            'description'   => __('Use these settings to select which data to
                report on.',SLPLUS_PREFIX),
            'auto'          => true
        )
 );

// Start of date range to report on
// default: 30 days ago
//
$slpReportStartDate = isset($_POST[SLPLUS_PREFIX.'-start_date']) ?
    $_POST[SLPLUS_PREFIX.'-start_date'] :
    date('Y-m-d',time() - (30 * 24 * 60 * 60));
$slpReportSettings->add_item(
    'Report Parameters', 
    __('Start Date: ',SLPLUS_PREFIX),   
    'start_date',    
    'text',
    null,
    null,
    null,
    $slpReportStartDate
); 

// Start of date range to report on
// default: today
//
$slpReportEndDate = (isset($_POST[SLPLUS_PREFIX.'-end_date'])) ?
    $_POST[SLPLUS_PREFIX.'-end_date'] :
    date('Y-m-d',time()) . ' 23:59:59';
    if (!preg_match('/\d\d:\d\d$/',$slpReportEndDate)) {
        $slpReportEndDate .= ' 23:59:59';
    }
    
$slpReportSettings->add_item(
    'Report Parameters', 
    __('End Date: ',SLPLUS_PREFIX),   
    'end_date',    
    'text',
    null,
    null,
    null,
    $slpReportEndDate
);     

// How many detail records to report back
// default: 10
//
$slpReportLimit = isset($_POST[SLPLUS_PREFIX.'-report_limit']) ?
    $_POST[SLPLUS_PREFIX.'-report_limit'] :
    '10';
$slpReportSettings->add_item(
    'Report Parameters', 
    __('How many detail records? ',SLPLUS_PREFIX),   
    'report_limit',    
    'text',
    false,
    __('Determines how many detail records are reported. ' .
       'More records take longer to report. '.
       '(Default: 10, recommended maximum: 500)',
       SLPLUS_PREFIX
       ),
    null,
    $slpReportLimit
);

$slpReportSettings->add_item(
    'Report Parameters', 
    '',   
    'runreport',    
    'submit_button',
    null,
    null,
    null,
    __('Run Report',SLPLUS_PREFIX)
);     


//------------------------------------
// The Summary Graph Panel
//  
$slpReportSettings->add_section(
    array(
            'name'          => __('Store Locator Plus Usage',SLPLUS_PREFIX),
            'description'   => '<div id="chart_div"></div>',
            'auto'          => true
        )
 );

// Total results each day
// select 
//      count(*) as TheCount, 
//      sum((select count(*) from wp_slp_rep_query_results RES 
//                  where slp_repq_id = QRY2.slp_repq_id)) as TheResults,
//      DATE(slp_repq_time) as TheDate 
// from wp_slp_rep_query QRY2 group by TheDate;
//
$slpReportQuery = sprintf(
    "select count(*) as QueryCount," . 
        "sum((select count(*) from %s ". 
                    "where slp_repq_id = qry2.slp_repq_id)) as ResultCount," .
        "DATE(slp_repq_time) as TheDate " .        
        "FROM %s qry2 " .
        "WHERE slp_repq_time > '%s' AND " .
        "      slp_repq_time <= '%s' " .       
        "GROUP BY TheDate",
    $slpResultsTable,
    $slpQueryTable,
    $slpReportStartDate,
    $slpReportEndDate
    );
$slpReportDataset = $wpdb->get_results($slpReportQuery);

$slpGoogleChartRows = 0;
$slpGoogleChartData = '';
$slpRepTotalQueries = 0;
$slpRepTotalResults = 0;
foreach ($slpReportDataset as $slpReportDatapoint) {
    $slpGoogleChartData .= sprintf(
        "data.setValue(%d, 0, '%s');".
        "data.setValue(%d, 1, %d);".
        "data.setValue(%d, 2, %d);",
        $slpGoogleChartRows,
        $slpReportDatapoint->TheDate,
        $slpGoogleChartRows,
        $slpReportDatapoint->QueryCount,
        $slpGoogleChartRows,        
        $slpReportDatapoint->ResultCount
        );
    $slpGoogleChartRows++;        
    $slpRepTotalQueries += $slpReportDatapoint->QueryCount;
    $slpRepTotalResults += $slpReportDatapoint->ResultCount;
}    

$slpGoogleChartType = ($slpGoogleChartRows < 2)  ?
    'ColumnChart' :
    'AreaChart';


//------------------------------------
// The Summary Data Panel
//
// Get the total searches in this time span
//
// select count(*) from wp_slp_rep_query 
//      where slp_repq_time > '2011-05-17' and 
//            slp_repq_time <= '2011-06-16 23:59:59';
//
$slpReportQuery = sprintf(
    "SELECT count(*) FROM %s " .
        "WHERE slp_repq_time > '%s' AND " .
        "      slp_repq_time <= '%s' ",
    $slpQueryTable,
    $slpReportStartDate,
    $slpReportEndDate
    );
$slpReportDatapoint = $wpdb->get_var($slpReportQuery);

$slpSectionDesc = sprintf(
    '<div class="reportline total">' .
        __('Total searches: <strong>%s</strong>', SLPLUS_PREFIX). "<br/>" . 
        __('Total results: <strong>%s</strong>', SLPLUS_PREFIX). 
    '</div>',
     $slpRepTotalQueries,
     $slpRepTotalResults
    );    

$slpReportSettings->add_section(
    array(
            'name'          => __('Summary',SLPLUS_PREFIX),
            'description'   => $slpSectionDesc,
            'auto'          => true
        )
 );


//------------------------------------
// The Details Data Panel
//
$slpSectionDescription = '';

//....
//
// What are the top addresses searched?
//
// SELECT slp_repq_address,count(*) as QueryCount 
//      FROM wp_slp_rep_query 
//      WHERE slp_repq_time > '%s' AND slp_repq_time <= '%s'
//      GROUP BY slp_repq_address 
//      ORDER BY QueryCount DESC;
//
//
$slpReportQuery = sprintf(
    "SELECT slp_repq_address, count(*)  as QueryCount FROM %s " .
        "WHERE slp_repq_time > '%s' AND " .
        "      slp_repq_time <= '%s' " .
        "GROUP BY slp_repq_address ".
        "ORDER BY QueryCount DESC " .
        "LIMIT %s"
        ,
    $slpQueryTable,
    $slpReportStartDate,
    $slpReportEndDate,
    $slpReportLimit
    );
$slpSectionHeader = sprintf(__('Top %s Addresses Searched', SLPLUS_PREFIX),$slpReportLimit);
$slpColumnHeaders = array(
    __('Address',SLPLUS_PREFIX),
    __('Total',SLPLUS_PREFIX)
    );
$slpDataLines = array(
        array('columnName' => 'slp_repq_address', 'columnClass'=> ''            ),
        array('columnName' => 'QueryCount',       'columnClass'=> 'alignright'  ),
    );
$slpSectionDescription .= DetailDataSection(
                $slpReportQuery, $slpSectionHeader, 
                $slpColumnHeaders, $slpDataLines, 
                __('topsearches',SLPLUS_PREFIX)
                );

//....
//
// What are the top results returned?
//
// SELECT sl_store,sl_city,sl_state, sl_zip, sl_tags, count(*) as ResultCount 
//      FROM wp_slp_rep_query_results res 
//          LEFT JOIN wp_store_locator sl 
//              ON (res.sl_id = sl.sl_id)  
//      WHERE slp_repq_time > '%s' AND slp_repq_time <= '%s'
//      GROUP BY sl_store,sl_city,sl_state,sl_zip,sl_tags
//      ORDER BY ResultCount DESC
//      LIMIT %s
//
$slpReportQuery = sprintf(
    "SELECT sl_store,sl_city,sl_state, sl_zip, sl_tags, count(*) as ResultCount " . 
        "FROM %s res ".
            "LEFT JOIN %s sl ". 
                "ON (res.sl_id = sl.sl_id) ".  
            "LEFT JOIN %s qry ". 
                "ON (res.slp_repq_id = qry.slp_repq_id) ".  
            "WHERE slp_repq_time > '%s' AND slp_repq_time <= '%s' ".
        "GROUP BY sl_store,sl_city,sl_state,sl_zip,sl_tags ".
        "ORDER BY ResultCount DESC ".
        "LIMIT %s"
        ,
    $slpResultsTable,
    $slpLocationsTable,
    $slpQueryTable,
    $slpReportStartDate,
    $slpReportEndDate,
    $slpReportLimit
    );
$slpSectionHeader = sprintf(__('Top %s Results Returned', SLPLUS_PREFIX),$slpReportLimit);
$slpColumnHeaders = array(
    __('Store',SLPLUS_PREFIX),
    __('City',SLPLUS_PREFIX),
    __('State',SLPLUS_PREFIX),
    __('Zip',SLPLUS_PREFIX),
    __('Tags',SLPLUS_PREFIX),
    __('Total',SLPLUS_PREFIX)
    );
$slpDataLines = array(
        array('columnName' => 'sl_store',   'columnClass'=> ''            ),
        array('columnName' => 'sl_city',    'columnClass'=> ''            ),
        array('columnName' => 'sl_state',   'columnClass'=> ''            ),
        array('columnName' => 'sl_zip',     'columnClass'=> ''            ),
        array('columnName' => 'sl_tags',    'columnClass'=> ''            ),
        array('columnName' => 'ResultCount','columnClass'=> 'alignright'  ),
    );
$slpSectionDescription .= DetailDataSection(
                $slpReportQuery, $slpSectionHeader, 
                $slpColumnHeaders, $slpDataLines,
                __('topresults',SLPLUS_PREFIX)
                );

$slpSectionDescription .= '
    <div id="rb_details" class="reportblock">
        <div class="rb_column">
          <h2>' . __('Export To CSV',SLPLUS_PREFIX) . '</h2>
          <div class="form_entry">
              <label for="export_all">'.__('Export all records',SLPLUS_PREFIX).'</label>
              <input id="export_all" type="checkbox"  name="export_all" value="1">
          </div>
          <div class="form_entry">
              <input id="export_searches" class="button-secondary button-export" type="button" value="'.__('Top Searches',SLPLUS_PREFIX).'"><br/>
              <input id="export_results"  class="button-secondary button-export" type="button" value="'.__('Top Results',SLPLUS_PREFIX).'">
          </div>
        </div>
    </div>
    ';

$slpReportSettings->add_section(
    array(
            'name'          => __('Details',SLPLUS_PREFIX),
            'description'   => $slpSectionDescription,
            'auto'          => true
        )
 );



//----------------------------
// If we have data to report on
//
if ($slpRepTotalQueries > 0) {
    ?>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
  google.load("visualization", "1", {packages:["corechart"]});
  google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Date');
        data.addColumn('number', 'Queries');
        data.addColumn('number', 'Results');
        data.addRows(<?php echo $slpGoogleChartRows; ?>);
        <?php echo $slpGoogleChartData; ?>
        var chart = new google.visualization.<?php echo $slpGoogleChartType; ?>(document.getElementById('chart_div'));
        chart.draw(data, {width: 800, height: 400, pointSize: 4});
      }
    </script>
<?php

// No Data Yet - Tell Them
//
} else {
    ?>
<script type="text/javascript">
    jQuery(document).ready(       
            function($) {
                  $("#chart_div").html("<p>No data recorded yet.  Chart will be available after a Store Locator Plus search has been performed.</p>");
            }
        );
</script>    
<?    
}


//------------------------------------
// Render It 
//
if ($slplus_plugin->license->packages['Plus Pack']->isenabled) {      
    $slpReportSettings->render_settings_page();
} else {
    $slplus_plugin->notifications->add_notice(9,__('This is a Plus Pack feature.',SLPLUS_PREFIX));
    $slplus_plugin->notifications->display();    
}    

