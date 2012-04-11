				<h2><?php _e('Appearance Settings <span style="color:green">(basic)</span>', 'fppg'); ?></h2>

				<p><?php  _e('These setting control how Fancybox looks, they let you tweak color, borders and position of elements, like the image title and closing buttons.', 'fppg'); ?></p>

				<table class="form-table" style="clear:none;">
					<tbody>

						<tr valign="top">
							<th scope="row"><?php _e('Border Color', 'fppg'); ?></th>
							<td>
								<fieldset>
								
									<label for="border">
										<input type="checkbox" name="fppg_border" id="border"<?php if ($settings['fppg_border']) echo ' checked="yes"';?> />
										<?php _e('Show Border (default: off)', 'fppg'); ?>
									</label><br /><br />

									<label for="borderColor">
										<input type="text" name="fppg_borderColor" id="borderColor" value="<?php echo $settings['fppg_borderColor'] ?>" size="7" maxlength="7" />
										<?php _e('HTML color of the border (default: #BBBBBB)', 'fppg'); ?>
									</label><br /><br />

								</fieldset>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row"><?php _e('Close Button', 'fppg'); ?></th>
							<td>
								<fieldset>

									<label for="showCloseButton">
										<input type="checkbox" name="fppg_showCloseButton" id="showCloseButton"<?php if ($settings['fppg_showCloseButton']) echo ' checked="yes"';?> />
										<?php _e('Show Close button (default: on)', 'fppg'); ?>
									</label><br /><br />

									<?php _e('Close button position:', 'fppg'); ?><br />
									<input id="closePosLeft" type="radio" value="left" name="fppg_closeHorPos"<?php if ($settings['fppg_closeHorPos'] == 'left') echo ' checked="yes"';?> />
									<label for="closePosLeft" style="padding-right:15px">
										<?php _e('Left', 'fppg'); ?>
									</label>

									<input id="closePosRight" type="radio" value="right" name="fppg_closeHorPos"<?php if ($settings['fppg_closeHorPos'] == 'right') echo ' checked="yes"';?> />
									<label for="closePosRight">
										<?php _e('Right (default)', 'fppg'); ?>
									</label><br />

									<input id="closePosBottom" type="radio" value="bottom" name="fppg_closeVerPos"<?php if ($settings['fppg_closeVerPos'] == 'bottom') echo ' checked="yes"';?> />
									<label for="closePosBottom" style="padding-right:15px">
										<?php _e('Bottom', 'fppg'); ?>
									</label>

									<input id="closePosTop" type="radio" value="top" name="fppg_closeVerPos"<?php if ($settings['fppg_closeVerPos'] == 'top') echo ' checked="yes"';?> />
									<label for="closePosTop">
										<?php _e('Top (default)', 'fppg'); ?>
									</label><br /><br />

								</fieldset>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row"><?php _e('Padding', 'fppg'); ?></th>
							<td>
								<fieldset>

									<label for="paddingColor">
										<input type="text" name="fppg_paddingColor" id="paddingColor" value="<?php echo $settings['fppg_paddingColor'] ?>" size="7" maxlength="7" />
										<?php _e('HTML color of the padding (default: #FFFFFF)', 'fppg'); ?>
									</label><br />
									
									<small><em><?php _e('(This should be left on #FFFFFF (white) if you want to display anything other than images, like inline or framed content)', 'fppg'); ?></em></small><br /><br />

									<label for="padding">
										<input type="text" name="fppg_padding" id="padding" value="<?php echo $settings['fppg_padding']; ?>" size="7" maxlength="7" />
										<?php _e('Padding size in pixels (default: 10)', 'fppg'); ?>
									</label><br /><br />

								</fieldset>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row"><?php _e('Overlay Options', 'fppg'); ?></th>
							<td>
								<fieldset>

									<label for="overlayShow">
										<input type="checkbox" name="fppg_overlayShow" id="overlayShow"<?php if ($settings['fppg_overlayShow']) echo ' checked="yes"';?> />
										<?php _e('Add overlay (default: on)', 'fppg'); ?>
									</label><br /><br />

									<label for="overlayColor">
										<input type="text" name="fppg_overlayColor" id="overlayColor" value="<?php echo $settings['fppg_overlayColor']; ?>" size="7" maxlength="7" />
										<?php _e('HTML color of the overlay (default: #666666)', 'fppg'); ?>
									</label><br /><br />

									<label for="overlayOpacity">
										<select name="fppg_overlayOpacity" id="overlayOpacity">
											<?php
											foreach($overlayArray as $key=> $opacity) {
												if($settings['fppg_overlayOpacity'] != $opacity) $selected = '';
												else $selected = ' selected';
												echo "<option value='$opacity'$selected>$opacity</option>\n";
											}
											?>
										</select>
										<?php _e('Opacity of overlay. 0 is transparent, 1 is opaque (default: 0.3)', 'fppg'); ?>
									</label><br /><br />

								</fieldset>
							</td>
						</tr>
						
						<tr valign="top">
							<th scope="row"><?php _e('Show Title', 'fppg'); ?></th>
							<td>
								<fieldset>

									<label for="showTitle">
										<input type="checkbox" name="fppg_showTitle" id="showTitle"<?php if ($settings['fppg_showTitle']) echo ' checked="yes"';?> />
										<?php _e('Show the image title (default: on)', 'fppg'); ?>
									</label><br /><br />
									
								</fieldset>
							</td>
						</tr>
<tr valign="top">
							<th scope="row"><?php _e(' Title Position', 'fppg'); ?></th>
							<td>
								<fieldset>

									<label for="titlePosition">
										<select name="fppg_titlePosition" id="titlePosition">
											<?php
											foreach($titlepos as $key=> $pos) {
												if($settings['fppg_titlePosition'] != $pos) $selected = '';
												else $selected = ' selected';
												echo "<option value='$pos'$selected>$pos</option>\n";
											}
											?>
										</select> 
										<?php _e('Position of title (default: outside)', 'fppg'); ?>
									</label><br /><br />

								</fieldset>
							</td>
						</tr>
					</tbody>
				</table>