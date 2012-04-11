				<h2><?php _e('Thumbnail Appearance', 'fppg'); ?></h2>

				<p><?php _e('These settings determine the appearance of the Thumbnails from Facebook .', 'fppg'); ?></p>

			<table class="form-table" style="clear:none;">
					<tbody>

						<tr valign="top">
							<th scope="row"><?php _e('Thumbnail Style', 'fppg'); ?></th>
							<td>
								<fieldset>

									<label for="thumbnailWidth">
										<input type="text" name="fppg_thumbnailWidth" id="thumbnailWidth" value="<?php if ($settings['fppg_thumbnailWidth']!="") echo $settings['fppg_thumbnailWidth'];?>" />
										<?php _e('Thumbnail Width (default: 161px)', 'fppg'); ?>
									</label><br /><br />

									<label for="thumbnailHeight">
										<input type="text" name="fppg_thumbnailHeight" id="thumbnailHeight" value="<?php if ($settings['fppg_thumbnailHeight']!="") echo $settings['fppg_thumbnailHeight'];?>" />
										<?php _e('Thumbnail Height (default: 120px)', 'fppg'); ?>
									</label><br /><br />

									<label for="thumbnailBorder">
										<input type="text" name="fppg_thumbnailBorder" id="thumbnailBorder" value="<?php if ($settings['fppg_thumbnailBorder']!="") echo $settings['fppg_thumbnailBorder'];?>" />
										<?php _e('Thumbnail Border (default: 1px solid #ccc)', 'fppg'); ?>
									</label><br /><br />


									<label for="thumbnailBgColor">
										<input type="text" name="fppg_thumbnailBgColor" id="thumbnailBgColor" value="<?php if ($settings['fppg_thumbnailBgColor']!="") echo $settings['fppg_thumbnailBgColor'];?>" />
										<?php _e('Thumbnail Background Color (default: 160px)', 'fppg'); ?>
									</label><br /><br />

									<label for="thumbnailPadding">
										<input type="text" name="fppg_thumbnailPadding" id="thumbnailWidth" value="<?php if ($settings['fppg_thumbnailPadding']!="") echo $settings['fppg_thumbnailPadding'];?>" />
										<?php _e('Thumbnail Padding (default: 160px)', 'fppg'); ?>
									</label><br /><br />

									<label for="thumbnailShaddow">
										<input type="text" name="fppg_thumbnailShaddow" id="thumbnailShaddow" value="<?php if ($settings['fppg_thumbnailShaddow']!="") echo $settings['fppg_thumbnailShaddow'];?>" />
										<?php _e('Thumbnail Shaddow (default: 160px)', 'fppg'); ?>
									</label><br /><br />


                                                                </fieldset>
			</tr>

					</tbody>
				</table>