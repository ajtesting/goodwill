<?php
/*
+--------------------------------------------------------------------------
|
| Name		: CAPTCHA imager
| Author	: Law Eng Soon
| Email		: mailme@zorex.info
| Version	: v2.1
| Revision	: 5
| Date		: Sat, 02 Feb 2008 05:17 PM +0800
| Started	: Thu, 30 Nov 2006 05:41 PM +0800
|
+--------------------------------------------------------------------------
|
|	- CAPTCHA image generator 
|
+--------------------------------------------------------------------------
*/


header("Expires: Mon, 23 Jul 1993 05:00:00 GMT");// always modified
header("Last-Modified: Mon, 23 Jul 1993 05:00:00 GMT");// HTTP/1.1
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);// HTTP/1.0
header("Pragma: no-cache");
header("Content-type: image/png");

//we generate random number for use
$str = rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);

//save to session name secret
session_start();
session_unregister("secret");
$_SESSION['secret'] = md5($str);

$switch = rand(1, 4);

if($switch==1) { // Blue color
	$r = rand(57, 62);
	$g = rand(122, 128);
	$b = rand(217, 223);
}
if($switch==2) { // Blue color
	$r = rand(207, 213);
	$g = rand(97, 103);
	$b = rand(97, 103);
}
if($switch==3) { // Green color
	$r = rand(27, 33);
	$g = rand(192, 198);
	$b = rand(47, 53);
}
if($switch==4) { // Yellow color
	$r = rand(187, 193);
	$g = rand(187, 193);
	$b = rand(17, 23);
}

//get the base image
$img = @imagecreatefrompng('gd' . $switch . '.png');

# New canvas for the captcha
$canvas = imagecreatetruecolor( 96, 24);
imagecopyresampled($canvas, $img, 0, 0, 0, 0, 96, 24, 63, 18);

//allocate color for the text
$col = imagecolorallocate($img, $r, $g, $b);
//write the string on the img
imagestring($img, 14, 5, 1, $str, $col);

//resize the img to a bigger one
$dst = imagecreatetruecolor( 96, 24);
imagecopyresampled($dst, $img, 0, 0, 0, 0, 96, 24, 63, 18);

# Testing adding sinwave to image
$offset = rand(0, 30);
$graph = true;
for($i = 1; $i <= 96; $i++) {
	
	if( $offset > 0 && !$graph ) {
		$offset--;
	}
	else {
		$graph = true;
	}
	
	if( $offset < 30 && $graph ) {
		$offset++;
	}
	else {
		$graph = false;
	}
	
	$sin = sin($offset * 6) * 2;
	
	imagecopy($canvas, $dst, $i, 4 + $sin, $i, 4, 3, 16);
}

//draw border for the img
$col2 = imagecolorallocate($canvas, $r, $g, $b);
imagerectangle($canvas, 0, 0, 95, 23, $col2);

imagepng($canvas);

imagedestroy($canvas);

?>