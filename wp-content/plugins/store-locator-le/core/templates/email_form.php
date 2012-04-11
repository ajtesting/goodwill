<form onsubmit='send_email();' id='emailForm' action=''>
    <div id='email_form_content'>
        <div class='form_entry'>
            <label for='email_from'>
                <?php _e('From', SLPLUS_PREFIX); ?>:
            </label>
            <input name='email_from'  value='' />
        </div>        
        
        <div class='form_entry'>
            <label for='email_to'>
                <?php _e('To', SLPLUS_PREFIX); ?>:
            </label>
            <input name='email_to'  value='' />
        </div>        
        
        <div class='form_entry'>
            <label for='email_subject'>
                <?php _e('Subject', SLPLUS_PREFIX); ?>:
            </label>
            <input name='email_subject'  value='' />
        </div>        
        
        <div class='form_entry'>
            <label for='email_message'>
                <?php _e('Message', SLPLUS_PREFIX); ?>:
            </label>
            <input name='email_message'  value='' />
        </div>                
    </div>
</form>

