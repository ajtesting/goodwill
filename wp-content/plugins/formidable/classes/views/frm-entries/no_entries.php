<?php $colspan = (isset($form_cols)) ? count($form_cols)+1 : ''; ?>
    <h3>You don't have any entries in this form.<br/> How to publish:</h3>
    </td></tr>
    <tr class="alternate"><td colspan="<?php echo $colspan ?>">
        <h3>Option 1: Generate your shortcode</h3>
        <ol>
        <li>Go to your WordPress page or post.</li>
        <li class="alignleft" style="width:45%">Click on the form icon above the content box to open a popup with additional options.<br/>
        <img alt="" src="http://static.strategy11.com.s3.amazonaws.com/insert-shortcode-icon.png" align="none"></li>
        <li class="alignright" style="width:45%">Select your form from the dropdown and check the boxes to show the title and description if desired.<br/>
        <img alt="" src="http://static.strategy11.com.s3.amazonaws.com/insert-form-shortcode.png" align="none"></li>
        <li class="alignright" style="width:45%">Click the "Insert Form" button.</li>
        </ol>
        <div class="clear"></div>
    </td></tr>
    <tr><td colspan="<?php echo $colspan ?>">
        <h3>Option 2: Add a Widget</h3>
        <ol class="alignleft" style="margin-right:30px;">
            <li>Drag a "Formidable Form" widget into your sidebar.</li>
            <li>Select a form from the "Form" drop-down.</li>
            <li>Click the "Save" button</li>
        </ol>
        <img src="<?php echo FRM_URL ?>/screenshot-2.png" alt="Formidable Form Widget" title="formidable-widget" height="261" width="252" />
    </td></tr>
    <tr class="alternate"><td colspan="<?php echo $colspan ?>">    
        <h3>Option 3: Insert the shortcode or PHP</h3>
        <p>Insert the following shortcode in your page, post, or text widget. This will be replaced with your form:<br/>
            <input type="text" style="text-align:center;font-weight:bold;width:500px;" readonly="true" onclick="this.select();" onfocus='this.select();' value='[formidable id=<?php echo $form->id; ?>]' />
        </p>
        <p>Show the form with the title and description:<br/>
            <input type="text" style="text-align:center;font-weight:bold;width:500px;" readonly="true" onclick="this.select();" onfocus='this.select();' value='[formidable id=<?php echo $form->id; ?> title=true description=true]' />
        </p>
        
        <p>Insert into a theme template file:<br/>
            <input type="text" style="text-align:center;font-weight:bold;width:500px;" readonly="true" onclick="this.select();" onfocus="this.select();" value="echo FrmEntriesController::show_form(<?php echo $form->id; ?>, $key='', $title=true, $description=true);" />
        </p>
