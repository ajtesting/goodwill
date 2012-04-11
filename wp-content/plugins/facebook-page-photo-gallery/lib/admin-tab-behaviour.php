				<h2><?php _e('Behavior Settings <span style="color:orange">(medium)</span>', 'fppg'); ?></h2>

				<p><?php _e('The following settings should be left on default unless you know what you are doing.', 'fppg'); ?></p>

				<table class="form-table" style="clear:none;">
					<tbody>

						<tr valign="top">
							<th scope="row"><?php _e('Auto Resize to Fit', 'fppg'); ?></th>
							<td>
								<fieldset>

									<label for="imageScale">
										<input type="checkbox" name="fppg_imageScale" id="imageScale"<?php if ($settings['fppg_imageScale']) echo ' checked="yes"';?> />
										<?php _e('Scale images to fit in viewport (default: on)', 'fppg'); ?>
									</label><br /><br />

								</fieldset>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row"><?php _e('Center on Scroll', 'fppg'); ?></th>
							<td>
								<fieldset>

									<label for="centerOnScroll">
										<input type="checkbox" name="fppg_centerOnScroll" id="centerOnScroll"<?php if ($settings['fppg_centerOnScroll']) echo ' checked="yes"';?> />
										<?php _e('Keep image in the center of the browser window when scrolling (default: on)', 'fppg'); ?>
									</label><br /><br />

								</fieldset>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row"><?php _e('Close on Content Click', 'fppg'); ?></th>
							<td>
								<fieldset>

									<label for="hideOnContentClick">
										<input type="checkbox" name="fppg_hideOnContentClick" id="hideOnContentClick"<?php if ($settings['fppg_hideOnContentClick']) echo ' checked="yes"';?> />
										<?php _e('Close FancyBox by clicking on the image (default: off)', 'fppg'); ?>
									</label><br />

									<small><em><?php _e('(You may want to leave this off if you display iframed or inline content that containts clickable elements - for example: play buttons for movies, links to other pages)', 'fppg'); ?></em></small><br /><br />

								</fieldset>
							</td>
						</tr>
						
						<tr valign="top">
							<th scope="row"><?php _e('Close on Overlay Click', 'fppg'); ?></th>
							<td>
								<fieldset>

									<label for="hideOnOverlayClick">
										<input type="checkbox" name="fppg_hideOnOverlayClick" id="hideOnOverlayClick"<?php if ($settings['fppg_hideOnOverlayClick']) echo ' checked="yes"';?> />
										<?php _e('Close FancyBox by clicking on the overlay sorrounding it (default: on)', 'fppg'); ?>
									</label><br /><br />

								</fieldset>
							</td>
						</tr>
						
						<tr valign="top">
							<th scope="row"><?php _e('Close with &quot;Esc&quot;', 'fppg'); ?></th>
							<td>
								<fieldset>

									<label for="enableEscapeButton">
										<input type="checkbox" name="fppg_enableEscapeButton" id="enableEscapeButton"<?php if ($settings['fppg_enableEscapeButton']) echo ' checked="yes"';?> />
										<?php _e('Close FancyBox when &quot;Escape&quot; key is pressed (default: on)', 'fppg'); ?>
									</label><br /><br />

								</fieldset>
							</td>
						</tr>
                                                <tr valign="top">
							<th scope="row"><?php _e('Cyclic gallery;', 'fppg'); ?></th>
							<td>
								<fieldset>

									<label for="cyclic">
										<input type="checkbox" name="fppg_cyclic" id="cyclic"<?php if ($settings['fppg_cyclic']) echo ' checked="yes"';?> />
										<?php _e('Have the gallery going continuously cyclic (default: off)', 'fppg'); ?>
									</label><br /><br />

								</fieldset>
							</td>
						</tr>

					</tbody>
				</table>