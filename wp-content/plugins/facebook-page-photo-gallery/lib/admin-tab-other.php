				<h2><?php _e('Other Settings <span style="color:red">(advanced)</span>', 'fppg'); ?></h2>

				<p><?php _e('These are additional settings for advanced users.', 'fppg'); ?></p>
				
				<table class="form-table" style="clear:none;">
					<tbody>
					
						<tr valign="top">
							<th scope="row"><?php _e('Callbacks', 'fppg'); ?></th>
							<td>
								<fieldset>
								
									<label for="callbackOnStart">
										<?php _e('Callback on Start event (default: empty)', 'fppg'); ?>
										<textarea rows="10" cols="50" class="large-text code" name="fppg_callbackOnStart" wrap="physical" id="callbackOnStart"><?php echo ($settings['fppg_callbackOnStart']); ?></textarea>
									</label><br /><br />
									
									<label for="callbackOnShow">
										<?php _e('Callback on Show event (default: empty)', 'fppg'); ?>
										<textarea rows="10" cols="50" class="large-text code" name="fppg_callbackOnShow" wrap="physical" id="callbackOnShow"><?php echo ($settings['fppg_callbackOnShow']); ?></textarea>
									</label><br /><br />
									
									<label for="callbackOnClose">
										<?php _e('Callback on Close event (default: empty)', 'fppg'); ?>
										<textarea rows="10" cols="50" class="large-text code" name="fppg_callbackOnClose" wrap="physical" id="callbackOnClose"><?php echo ($settings['fppg_callbackOnClose']); ?></textarea>
									</label><br />

									<small><strong><em><?php _e('Example:', 'fppg'); ?></em></strong></small><br />

									<small><em><code>function() { alert('Completed!'); }</code></em></small><br /><br />
									
									<small><em><?php _e('Leave the fields empty to disable.', 'fppg'); ?></em></small><br /><br />

								</fieldset>
							</td>
						</tr>
						
						<tr valign="top">
							<th scope="row"><?php _e('Frame Size', 'fppg'); ?></th>
							<td>
								<fieldset>

									<label for="frameWidth">
										<input type="text" name="fppg_frameWidth" id="frameWidth" value="<?php echo $settings['fppg_frameWidth']; ?>" size="4" maxlength="4" />
										<?php _e('Width in pixels of FancyBox when showing iframe content (default: 560)', 'fppg'); ?>
									</label><br /><br />

									<label for="frameHeight">
										<input type="text" name="fppg_frameHeight" id="frameHeight" value="<?php echo $settings['fppg_frameHeight']; ?>" size="4" maxlength="4" />
										<?php _e('Height in pixels of FancyBox when showing iframe content (default: 340)', 'fppg'); ?>
									</label><br /><br />

								</fieldset>
							</td>
						</tr>
						
						<tr valign="top">
							<th scope="row"><?php _e('Load JavaScript in Footer', 'fppg'); ?></th>
							<td>
								<fieldset>

									<label for="loadAtFooter">
										<input type="checkbox" name="fppg_loadAtFooter" id="loadAtFooter"<?php if ($settings['fppg_loadAtFooter']) echo ' checked="yes"';?> />
										<?php _e('Loads JavaScript at the end of the blog\'s HTML (experimental) (default: off)', 'fppg'); ?>
									</label><br />
									
									<small><em><?php _e('This option won\'t be recognized if you use <strong>Parallel Load</strong> plugin. In that case, you can do this from Parallel Load\'s options.', 'fppg'); ?></em></small><br /><br />

								</fieldset>
							</td>
						</tr>
						
					</tbody>
				</table>