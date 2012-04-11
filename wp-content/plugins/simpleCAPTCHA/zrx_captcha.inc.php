<?php
/*  Copyright 2008  Law Eng Soon (zorex), http://www.zorex.info
**
**  This program is free software; you can redistribute it and/or modify
**  it under the terms of the GNU General Public License as published by
**  the Free Software Foundation; either version 2 of the License, or
**  (at your option) any later version.
**
**  This program is distributed in the hope that it will be useful,
**  but WITHOUT ANY WARRANTY; without even the implied warranty of
**  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
**  GNU General Public License for more details.
**
**  You should have received a copy of the GNU General Public License
**  along with this program; if not, write to the Free Software
**  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

# ----------------------------------------------------------------
# Simple CAPTCHA class
# ----------------------------------------------------------------
class zrx_captcha {

	# ------
	# Version info
	# ------
	var $version = '1.5.1b';
	
	# ----------------------------------------------------------------
	# Constructor
	# ----------------------------------------------------------------
	function zrx_captcha() {
		add_action('comment_form', array("zrx_captcha", "draw_form"), 9999);
		add_action('comment_post', array("zrx_captcha", "comment_post"));
	}
	
	# ----------------------------------------------------------------
	# Draw the security form
	# ----------------------------------------------------------------
	function draw_form($id) {
	
		global $newCaptcha;
		global $user_ID;
		
		# If GD not enabled, disable Simple CAPTCHA
		if( !$newCaptcha->GDLoaded() ) {
			# ---- Start HTML code ----
			?>
			<div style="background-color:#FFBFC1; border:solid 1px #B30004; color: #B30004; padding: 3px;">
You need to enable GD extension in order to use Simple CAPTCHA.</div>
			<?php
			# ---- End HTML code ----
			return $id;
		}
		
		# If its registered user, no need CAPTCHA
		if( $user_ID ) {
		?>
        <div align="center" style="background-color:#E6F1FF; border:solid 1px #004BCA; color: #004BCA; padding: 3px;">
Registered user do not need to go through CAPTCHA test.</div>
        <?php
			return $id;
		}
		
		# ---- Start HTML code ----
		?>
<noscript><br /><br />
<div align="center" style="background-color:#FFBFC1; border:solid 1px #B30004; color: #B30004; padding: 3px;">
You need to enable javascript in order to use Simple CAPTCHA.</div></noscript>
<script type="text/javascript">
//<![CDATA[
var count = 0;
	// Reload the CAPTCHA
	function reloadCaptcha() {
		frm = document.getElementById("simple_captcha");
		opacity("simple_captcha", 100, 0, 300);
		count++;
		frm.src = "<?php bloginfo('url'); ?>/wp-content/plugins/simpleCAPTCHA/gdimg.php?re=" + count;
		opacity("simple_captcha", 0, 100, 300);
	}
	
	// Change opacity
	function opacity(id, opacStart, opacEnd, millisec) {
		//speed for each frame
		var speed = Math.round(millisec / 100);
		var timer = 0;
	
		//determine the direction for the blending, if start and end are the same nothing happens
		if(opacStart > opacEnd) {
			for(i = opacStart; i >= opacEnd; i--) {
				setTimeout("changeOpac(" + i + ",'" + id + "')",(timer * speed));
				timer++;
			}
		} else if(opacStart < opacEnd) {
			for(i = opacStart; i <= opacEnd; i++)
				{
				setTimeout("changeOpac(" + i + ",'" + id + "')",(timer * speed));
				timer++;
			}
		}
	}

	// Change the opacity for different browsers
	function changeOpac(opacity, id) {
		var object = document.getElementById(id).style;
		object.opacity = (opacity / 100);
		object.MozOpacity = (opacity / 100);
		object.KhtmlOpacity = (opacity / 100);
		object.filter = "alpha(opacity=" + opacity + ")";
	} 
	
//]]>
</script>
<div id="zrx_captcha">
<div align="left"><small>Security Code:</small></div>
<table cellpadding="0" cellspacing="0" style="padding-bottom:5px;">
	<tr>
    	<td align="left" valign="top">
        	<input type="text" name="publicKey" maxlength="6" tabindex="3" />
        </td>
        <td align="center" valign="top" width="130">
        <a href="http://blog.zorex.info/?page_id=2" target="_blank">
        <img id="simple_captcha" src="<?php bloginfo('url'); ?>/wp-content/plugins/simpleCAPTCHA/gdimg.php?re=0" title="Simple CAPTCHA v<?php echo $newCaptcha->version; ?> by zorex" alt="" />
        </a>
        </td>
        <td align="left" valign="top" width="32">
        <img src="<?php bloginfo('url'); ?>/wp-content/plugins/simpleCAPTCHA/captcha_reload.gif" onClick="setTimeout('reloadCaptcha()', 10)" 
        style="cursor:pointer" title="Request a new image" alt="" />
        </td>
	</tr>
</table>
<br />
</div>
<script type="text/javascript">
//<![CDATA[

for( i = 0; i < document.forms.length; i++ ) {
	if( typeof(document.forms[i].publicKey) != 'undefined' ) {
		commentForm = document.forms[i].comment.parentNode;
		break;
	}
}
var commentArea = commentForm.parentNode;
var captchafrm = document.getElementById("zrx_captcha");
commentArea.insertBefore(captchafrm, commentForm);
commentArea.publicKey.size = commentArea.author.size;
commentArea.publicKey.className = commentArea.author.className;
//]]>
</script><?php 
        # ---- End HTML code ----
        
        # Display the alert box if wrong security code is entered
        if( isset($_POST['zrx_err']) ) {
			$newCaptcha->errMsg();
		}
	}
	
	# ----------------------------------------------------------------
	# Show error message
	# ----------------------------------------------------------------
	function errMsg() {
		# ---- Start HTML code ----
		?>
<script type="text/javascript">
//<![CDATA[
	// Copy back the data into the form
	ff = document.getElementById("commentform");
	ff.author.value = "<?php echo htmlspecialchars($_POST['author1']); ?>";
	ff.email.value = "<?php echo htmlspecialchars($_POST['email1']); ?>";
	ff.url.value = "<?php echo htmlspecialchars($_POST['url1']); ?>";
	ff.comment.value = "<?php $trans = array("\r" => '\r', "\n" => '\n');
	echo strtr(htmlspecialchars($_POST['comment1']), $trans); ?>";
	alert("Invalid secutiry code! Please try again.");
//]]>
</script><?php
		# ---- End HTML code ----
	}
	
	# ----------------------------------------------------------------
	# Validate user post and security code as well
	# ----------------------------------------------------------------
	function comment_post($id) {
		global $newCaptcha;
		global $user_ID;
		
		# Is user, no need validate secret key, If GD not enabled, disable Simple CAPTCHA
		if( $user_ID || !$newCaptcha->GDLoaded() ) {
			return $id;
		}
		
		session_start();
		$publicKey = $_POST['publicKey'];
		$secretKey = $_SESSION['secret'];
		
		# Check if the public and private key match
		if( $newCaptcha->validateKey($publicKey, $secretKey) ) {
			return $id;
		}
		
		wp_set_comment_status($id, 'delete');
		
		?><html>
		    <head><title>Invalid Code</title></head>
			<body>
				<form name="data" action="<?php echo $_SERVER['HTTP_REFERER']; ?>#respond" method="post">
					<input type="hidden" name="zrx_err" value="1" />
					<input type="hidden" name="author1" value="<?php echo htmlspecialchars($_POST['author']); ?>" />
					<input type="hidden" name="email1" value="<?php echo htmlspecialchars($_POST['email']); ?>" />
					<input type="hidden" name="url1" value="<?php echo htmlspecialchars($_POST['url']); ?>" />
					<textarea style="display:none;" name="comment1"><?php echo htmlspecialchars($_POST['comment']); ?></textarea>
				</form>
				<script type="text/javascript">
				<!--
				document.forms[0].submit();
				//-->
				</script>					
			</body>
		</html>
		<?php
		exit();
	}
	
	# ----------------------------------------------------------------
	# Validate pub key and secret key
	# ----------------------------------------------------------------
	function validateKey($pub, $sec) {
		
		if( strtolower(md5(trim($pub))) == strtolower(trim($sec)) ) {
			return true;
		}
		return false;
	}
	
	# ----------------------------------------------------------------
	# Check whether GD extension is loaded
	# ----------------------------------------------------------------
	function GDLoaded() {
		if (!extension_loaded('gd')) {
		    return false;
		}
		return true;
	}
}
# eof
?>