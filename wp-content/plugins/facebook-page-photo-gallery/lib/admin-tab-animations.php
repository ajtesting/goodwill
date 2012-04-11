				<h2><?php _e('Animation Settings <span style="color:green">(basic)</span>', 'fppg'); ?></h2>

				<p><?php _e('These settings control the animations when opening and closing Fancybox, and the optional easing effects.', 'fppg'); ?></p>

			<table class="form-table" style="clear:none;">
					<tbody>

						<tr valign="top">
							<th scope="row"><?php _e('Zoom Options', 'fppg'); ?></th>
							<td>
								<fieldset>

									<label for="zoomOpacity">
										<input type="checkbox" name="fppg_zoomOpacity" id="zoomOpacity"<?php if ($settings['fppg_zoomOpacity']) echo ' checked="yes"';?> />
										<?php _e('Change content transparency during zoom animations (default: on)', 'fppg'); ?>
									</label><br /><br />

									<label for="zoomSpeedIn">
										<select name="fppg_zoomSpeedIn" id="zoomSpeedIn">
											<?php
											foreach($msArray as $key=> $ms) {
												if($settings['fppg_zoomSpeedIn'] != $ms) $selected = '';
												else $selected = ' selected';
												echo "<option value='$ms'$selected>$ms</option>\n";
											} ?>
										</select>
										<?php _e('Speed in miliseconds of the zooming-in animation (default: 500)', 'fppg'); ?>
									</label><br /><br />

									<label for="zoomSpeedOut">
										<select name="fppg_zoomSpeedOut" id="zoomSpeedOut">
											<?php
											foreach($msArray as $key=> $ms) {
												if($settings['fppg_zoomSpeedOut'] != $ms) $selected = '';
												else $selected = ' selected';
												echo "<option value='$ms'$selected>$ms</option>\n";
											} ?>
										</select>
										<?php _e('Speed in miliseconds of the zooming-out animation (default: 500)', 'fppg'); ?>
									</label><br /><br />
									
									<label for="zoomSpeedChange">
										<select name="fppg_zoomSpeedChange" id="zoomSpeedChange">
											<?php
											foreach($msArray as $key=> $ms) {
												if($settings['fppg_zoomSpeedChange'] != $ms) $selected = '';
												else $selected = ' selected';
												echo "<option value='$ms'$selected>$ms</option>\n";
											} ?>
										</select>
										<?php _e('Speed in miliseconds of the animation when navigating thorugh gallery items (default: 300)', 'fppg'); ?>
									</label><br /><br />

								</fieldset>
							</td>
						</tr>

						<tr valign="top">
							<th scope="row"><?php _e('Easing', 'fppg'); ?></th>
							<td>
								<fieldset>

									<label for="easing">
										<input type="checkbox" name="fppg_easing" id="easing"<?php if ($settings['fppg_easing']) echo ' checked="yes"';?> />
										<?php _e('Activate easing (default: off)', 'fppg'); ?>
									</label><br /><br />

									<label for="easingIn">
										<select name="fppg_easingIn" id="easingIn">
											<?php
											foreach($easingArray as $key=> $easingIn) {
												if($settings['fppg_easingIn'] != $easingIn) $selected = '';
												else $selected = ' selected';
												echo "<option value='$easingIn'$selected>$easingIn</option>\n";
											}
											?>
										</select>
										<?php _e('Easing method when opening FancyBox. (default: easeOutBack)', 'fppg'); ?>
									</label><br /><br />

									<label for="easingOut">
										<select name="fppg_easingOut" id="easingOut">
											<?php
											foreach($easingArray as $key=> $easingOut) {
												if($settings['fppg_easingOut'] != $easingOut) $selected = '';
												else $selected = ' selected';
												echo "<option value='$easingOut'$selected>$easingOut</option>\n";
											}
											?>
										</select>
										<?php _e('Easing method when closing FancyBox. (default: easeInBack)', 'fppg'); ?>
									</label><br /><br />

									<label for="easingChange">
										<select name="fppg_easingChange" id="easingChange">
											<?php
											foreach($easingArray as $key=> $easingChange) {
												if($settings['fppg_easingChange'] != $easingChange) $selected = '';
												else $selected = ' selected';
												echo "<option value='$easingChange'$selected>$easingChange</option>\n";
											}
											?>
										</select>
										<?php _e('Easing method when navigating through gallery items. (default: easeInOutQuart)', 'fppg'); ?>
									</label><br />

									<small><em><?php _e('(There are 30 different easing methods, the first ones are the most boring. You can test them <a href="http://commadot.com/jquery/easing.php" target="_blank">here</a> or <a href="http://hosted.zeh.com.br/mctween/animationtypes.html" target="_blank">here</a>)', 'fppg'); ?></em></small><br /><br />

								</fieldset>
							</td>
						</tr>

					</tbody>
				</table>