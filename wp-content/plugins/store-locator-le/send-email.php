<?php
/*****************************************************************************
 * Send email message based on get variables.
 *****************************************************************************/

error_reporting(0);
include('load_wp_config.php');

$message_headers = 
    "From: \"{$_GET['email_name']}\" <{$_GET['email_from']}>\n" . 
    "Content-Type: text/plain; charset=\"" . get_option('blog_charset') . "\"\n";

wp_mail($_GET['email_to'],$_GET['email_subject'],$_GET['email_message'],$message_headers);
?>

<script>
self.close();
</script>

<?php
if(get_option(SLPLUS_PREFIX.'-debugging') == 'on') {
    $fh = fopen('emaillog.txt', 'a') or die("can't open file");
    fwrite($fh, date("Y-m-d H:m:s")." ".$_GET['email_subject']."\n");
    fclose($fh);
}
?>

