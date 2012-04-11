/*****************************************************************************
 * File: store-locator-emailform.js
 * 
 * Create the lightbox email form.
 *
 *****************************************************************************/

 function slp_show_email_form(to) {
    emailWin=window.open("about:blank","",
        "height=220,width=310,scrollbars=no,top=50,left=50,status=0,toolbar=0,location=0,menubar=0,directories=0,resizable=0");
    with (emailWin.document) {
        writeln("<html><head><title>Send Email To " + to + "</title></head>");
                
        writeln("<body scroll='no' onload='self.focus()' onblur='close()'>");
        
        writeln("<style>");
        writeln(".form_entry{ width: 300px; clear: both;} ");
        writeln(".form_submit{ width: 300px; text-align: center; padding: 12px;} ");
        writeln(".to{ float: left; font-size: 12px; color: #444444; } ");
        writeln("LABEL{ float: left; width: 75px;  text-align:right; ");
        writeln(      " font-size: 11px; color: #888888; margin: 3px 3px 0px 0px;} ");
        writeln("INPUT type=['text']{ float: left; width: 225px; text-align:left; } ");
        writeln("INPUT type=['submit']{ padding-left: 120px; } ");
        writeln("TEXTAREA { width: 185px; clear: both; padding-left: 120px; } ");
        writeln("</style>");
        
        writeln("<form id='emailForm' method='GET'");
        writeln(    " action='"+add_base+"/send-email.php'>");
        
        writeln("    <div id='email_form_content'>");

        writeln("        <div class='form_entry'>");
        writeln("            <label for='email_to'>To:</label>");
        writeln("            <input type='hidden' name='email_to' value='"+to+"'/>");
        writeln("            <div class='to'>"+to+"</div>");
        writeln("        </div>");           
                
        
        writeln("        <div class='form_entry'>");
        writeln("            <label for='email_name'>Your Name:</label>");
        writeln("            <input name='email_name' value='' />");
        writeln("        </div>");
        
        writeln("        <div class='form_entry'>");
        writeln("            <label for='email_from'>Your Email:</label>");
        writeln("            <input name='email_from' value='' />");
        writeln("        </div>");             
                
        writeln("        <div class='form_entry'>");
        writeln("            <label for='email_subject'>Subject:</label>");
        writeln("            <input name='email_subject'  value='' />");
        writeln("        </div>");        
                
        writeln("        <div class='form_entry'>");
        writeln("            <label for='email_message'>Message:</label>");
        writeln("            <textarea name='email_message'></textarea>");
        writeln("        </div>");                
        writeln("    </div>");    

        writeln("    <div class='form_submit'>");
        writeln("        <input type='submit' value='Send Message'>");
        writeln("    </div>");
        writeln("</form>");
        writeln("</body></html>");
        close();
    }     
 }
 
