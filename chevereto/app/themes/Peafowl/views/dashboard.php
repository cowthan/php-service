<?php if(!defined('access') or !access) die('This file cannot be directly accessed.'); ?>
<?php G\Render\include_theme_header(); ?>

<div class="content-width">
	
	<div class="form-content">
		
		<div class="header header-tabs">
			<h1><?php _se('Dashboard'); ?></h1>
			<?php G\Render\include_theme_file("snippets/tabs"); ?>
		</div>
		
		<?php
			switch(get_dashboard()) {
				case 'stats':
		?>
		<div class="dashboard-group">
			<div class="overflow-auto text-align-center margin-top-20">
				<a href="<?php echo G\get_base_url('dashboard/images'); ?>" class="stats-block c6 fluid-column display-inline-block"<?php if(get_counts()['image']['total'] > 999999) { echo ' rel="tooltip" data-tipTip="top" title="'.number_format(get_counts()['image']['total']).'"'; } ?>>
					<span class="stats-big-number">
						<strong class="number"><?php echo get_counts()['image']['total'] > 999999 ? get_nice_counts()['image']['total'] : number_format(get_counts()['image']['total']); ?></strong>
						<span class="label"><?php _ne('Image', 'Images', get_counts()['image']['total']); ?></span>
					</span>
				</a>
				<a href="<?php echo G\get_base_url('dashboard/albums'); ?>" class="stats-block c6 fluid-column display-inline-block"<?php if(get_counts()['album']['total'] > 999999) { echo ' rel="tooltip" data-tipTip="top" title="'.number_format(get_counts()['album']['total']).'"'; } ?>>
					<span class="stats-big-number">
						<strong class="number"><?php echo get_counts()['album']['total'] > 999999 ? get_nice_counts()['album']['total'] : number_format(get_counts()['album']['total']); ?></strong>
						<span class="label"><?php _ne('Album', 'Albums', get_counts()['album']['total']); ?></span>
					</span>
				</a>
				<a href="<?php echo G\get_base_url('dashboard/users'); ?>" class="stats-block c6 fluid-column display-inline-block"<?php if(get_counts()['user']['total'] > 999999) { echo ' rel="tooltip" data-tipTip="top" title="'.number_format(get_counts()['album']['total']).'"'; } ?>>
					<span class="stats-big-number">
						<strong class="number"><?php echo get_counts()['user']['total'] > 999999 ? get_nice_counts()['user']['total'] : number_format(get_counts()['user']['total']); ?></strong>
						<span class="label"><?php _ne('User', 'Users', get_counts()['user']['total']); ?></span>
					</span>
				</a>
				<div class="stats-block c6 fluid-column display-inline-block">
					<div class="stats-big-number">
						<strong class="number"><?php echo get_nice_counts()['disk']['used']; ?> <span><?php echo get_nice_counts()['disk']['unit']; ?></span></strong>
						<span class="label"><?php _se('Disk used'); ?></span>
					</div>
				</div>
			</div>
			
			<ul class="tabbed-content-list table-li margin-top-20">
				<?php
					foreach(get_system_values() as $v) {
				?>
				<li><span class="c6 display-table-cell padding-right-10"><?php echo $v['label']; ?></span> <span class="display-table-cell"><?php echo $v['content']; ?></span></li>
				<?php
					}
				?>
			</ul>
			
		</div>
		
		<?php 
				break;
				
				case 'images':
				case 'albums':
				case 'users':
					global $tabs;
					$tabs = get_sub_tabs();
		?>
		<div class="header header-tabs margin-bottom-10 follow-scroll">
			<?php G\Render\include_theme_file("snippets/tabs"); ?>
			<?php
				global $user_items_editor;
				$user_items_editor = false;
				G\Render\include_theme_file("snippets/user_items_editor");
			?>
			<div class="header-content-right phone-float-none">
				<?php G\Render\include_theme_file("snippets/listing_tools_editor"); ?>
			</div>
			
			<?php if(get_dashboard() == 'users') { ?>
			<div class="header-content-right phone-float-none">
				<div class="list-selection">
					<a class="header-link" data-modal="form" data-target="modal-add-user"><?php _se('Add user'); ?></a>
				</div>
			</div>
			<div data-modal="modal-add-user" class="hidden" data-submit-fn="CHV.fn.user.add.submit" data-ajax-deferred="CHV.fn.user.add.complete">
				<span class="modal-box-title"><?php _se('Add user'); ?></span>
				<div class="modal-form">
					<div class="input-label c7">
						<label for="form-role"><?php _se('Role'); ?></label>
						<select name="form-role" id="form-role" class="text-input">
							<option value="admin"><?php _se('Administrator'); ?></option>
							<option value="user" selected><?php _se('User'); ?></option>
						</select>
					</div>
					<div class="input-label c11">
						<label for="username"><?php _se('Username'); ?></label>
						<input type="text" name="form-username" id="form-username" class="text-input" maxlength="<?php echo CHV\Settings::get('username_max_length'); ?>" rel="tooltip" data-tipTip="right" pattern="<?php echo CHV\Settings::get('username_pattern'); ?>" rel="tooltip" data-title='<?php echo strtr('%i to %f characters<br>Letters, numbers and "_"', ['%i' => CHV\Settings::get('username_min_length'), '%f' => CHV\Settings::get('username_max_length')]); ?>' maxlength="<?php echo CHV\Settings::get('username_max_length'); ?>" placeholder="<?php _se('Username'); ?>" required>
						<span class="input-warning red-warning"></span>
					</div>
					<div class="input-label c11">
						<label for="form-name"><?php _se('Email'); ?></label>
						<input type="email" name="form-email" id="form-email" class="text-input" placeholder="<?php _se('Email address'); ?>" required>
						<span class="input-warning red-warning"></span>
					</div>
					<div class="input-label c11">
						<label for="form-name"><?php _se('Password'); ?></label>
						<input type="password" name="form-password" id="form-password" class="text-input" title="<?php _se('%d characters min', CHV\Settings::get('user_password_min_length')); ?>" pattern="<?php echo CHV\Settings::get('user_password_pattern'); ?>" rel="tooltip" data-tipTip="right" placeholder="<?php _se('Password'); ?>" required>
						<span class="input-warning red-warning"></span>
					</div>
				</div>
			</div>
			<?php } ?>
		</div>
		
		<div id="content-listing-tabs" class="tabbed-listing">
			<div id="tabbed-content-group">
				<?php
					G\Render\include_theme_file("snippets/listing");
				?>
			</div>
		</div>
		<?php
			break;	
			case 'settings':
		?>
		<form id="dashboard-settings" method="post" data-type="<?php echo get_dashboard(); ?>" data-action="validate" enctype="multipart/form-data">
			
			<?php echo G\Render\get_input_auth_token(); ?>
			
			<div class="header default-margin-bottom">
				<h1>
					<span class="icon icon-cog phablet-hide tablet-hide laptop-hide desktop-hide"></span>
					<span class="phone-hide"><?php echo get_dashboard_menu()[get_dashboard()]['label']; ?></span>
				</h1>
				<div data-content="pop-selection" class="pop-btn header-link float-left margin-left-10" data-action="settings-switch">
					<span class="pop-btn-text margin-left-5"><?php echo get_settings()['label']; ?><span class="arrow-down"></span></span>
					<div class="pop-box pbcols3 anchor-left arrow-box arrow-box-top">
						<div class="pop-box-inner pop-box-menu pop-box-menucols">
							<ul>
								<?php
									foreach(get_settings_menu() as $item) {
								?>
								<li<?php if($item["current"]) echo ' class="current"'; ?>><a href="<?php echo $item["url"]; ?>"><?php echo $item["label"]; ?></a></li>
								<?php
									}
								?>
							</ul>
						</div>
					</div>
				</div>
				<?php if(get_settings()['key'] == 'categories') { ?>
				<div class="header-content-right phone-float-none">
					<div class="list-selection">
						<a class="header-link" data-modal="form" data-target="modal-add-category"><?php _se('Add category'); ?></a>
					</div>
				</div>
				<div data-modal="modal-add-category" class="hidden" data-submit-fn="CHV.fn.category.add.submit" data-before-fn="CHV.fn.category.add.before" data-ajax-deferred="CHV.fn.category.add.complete">
					<span class="modal-box-title"><?php _se('Add category'); ?></span>
					<div class="modal-form">
						<?php G\Render\include_theme_file('snippets/form_category_edit'); ?>
					</div>
				</div>
				<?php } ?>
				<?php if(get_settings()['key'] == 'ip-bans') { ?>
				<div class="header-content-right phone-float-none">
					<div class="list-selection">
						<a class="header-link" data-modal="form" data-target="modal-add-ip_ban"><?php _se('Add IP ban'); ?></a>
					</div>
				</div>
				<div data-modal="modal-add-ip_ban" class="hidden" data-submit-fn="CHV.fn.ip_ban.add.submit" data-before-fn="CHV.fn.ip_ban.add.before" data-ajax-deferred="CHV.fn.ip_ban.add.complete">
					<span class="modal-box-title"><?php _se('Add IP ban'); ?></span>
					<div class="modal-form">
						<?php G\Render\include_theme_file('snippets/form_ip_ban_edit'); ?>
					</div>
				</div>
				<?php } ?>
				<?php if(get_settings()['key'] == 'external-storage') { ?>
				<div class="header-content-right phone-float-none">
					<div class="list-selection">
						<a class="header-link" data-modal="form" data-target="modal-add-storage"><?php _se('Add storage'); ?></a>
					</div>
				</div>
				<div data-modal="modal-add-storage" class="hidden" data-submit-fn="CHV.fn.storage.add.submit" data-before-fn="CHV.fn.storage.add.before" data-ajax-deferred="CHV.fn.storage.add.complete">
					<span class="modal-box-title"><?php _se('Add storage'); ?></span>
					<div class="modal-form">
						<?php G\Render\include_theme_file('snippets/form_storage_edit'); ?>
					</div>
				</div>
				<?php } ?>
			</div>
			
			<?php
				if(get_dashboard() == 'settings') {
			?>
			
			<?php if(get_settings()['key'] == 'website') { ?>
			<div class="c9 phablet-c1">
				<div class="input-label">
					<label for="website_name"><?php _se('Website name'); ?></label>
					<input type="text" name="website_name" id="website_name" class="text-input" value="<?php echo CHV\Settings::get('website_name', true); ?>" required>
					<div class="input-warning red-warning"><?php echo get_input_errors()['website_doctitle']; ?></div>
				</div>
				<div class="input-label">
					<label for="website_doctitle"><?php _se('Website doctitle'); ?></label>
					<input type="text" name="website_doctitle" id="website_doctitle" class="text-input" value="<?php echo CHV\Settings::get('website_doctitle', true); ?>">
				</div>
				<div class="input-label">
					<label for="website_description"><?php _se('Website description'); ?></label>
					<input type="text" name="website_description" id="website_description" class="text-input" value="<?php echo CHV\Settings::get('website_description', true); ?>">
					<div class="input-warning red-warning"><?php echo get_input_errors()['website_description']; ?></div>
				</div>
				<div class="input-label">
					<label for="website_keywords"><?php _se('Website keywords'); ?></label>
					<input type="text" name="website_keywords" id="website_keywords" class="text-input" value="<?php echo CHV\Settings::get('website_keywords', true); ?>">
					<div class="input-warning red-warning"><?php echo get_input_errors()['website_keywords']; ?></div>
				</div>
			</div>
			
			<hr class="line-separator"></hr>
			
			<?php 
				$zones = timezone_identifiers_list();
				foreach ($zones as $zone) {
					$zone = explode('/', $zone);
					if(in_array($zone[0], array("Africa", "America", "Antarctica", "Arctic", "Asia", "Atlantic", "Australia", "Europe", "Indian", "Pacific"))) {      
						if (isset($zone[1]) != '') {
							$regions[$zone[0]][$zone[0]. '/' . $zone[1]] = str_replace('_', ' ', $zone[1]);
						} 
					}
				}
			?>
			
			<div class="input-label">
				<label for="timezone-region"><?php _se('Default time zone'); ?></label>
				<div class="overflow-auto">
					<div class="c5 phablet-c1 phone-c1 grid-columns phone-margin-bottom-10 phablet-margin-bottom-10 margin-right-10">
						<select id="timezone-region" class="text-input" data-combo="timezone-combo">
							<option><?php _se('Select region'); ?></option>
							<?php
								$user_region = preg_replace("/(.*)\/.*/", "$1", CHV\Settings::get('default_timezone'));
								foreach($regions as $key => $region) {
									$selected = $user_region == $key ? " selected" : "";
									echo '<option value="'.$key.'"'.$selected.'>'.$key.'</option>';
								}
							?>
						</select>
					</div>
					<div id="timezone-combo" class="c5 phablet-c1 grid-columns">
						<?php
							foreach($regions as $key => $region) {
								$show_hide = $user_region == $key ? "" : " soft-hidden";
						?>
						<select id="timezone-combo-<?php echo $key; ?>" class="text-input switch-combo<?php echo $show_hide; ?>" data-combo-value="<?php echo $key; ?>">
							<?php
								foreach($region as $k => $l) {
									$selected = CHV\Settings::get('default_timezone') == $k ? " selected" : "";
									echo '<option value="'.$k.'"'.$selected.'>'.$l.'</option>'."\n";
								}
							?>
						</select>
						<?php
							}
						?>
					</div>
				</div>
				<input type="hidden" id="default_timezone" name="default_timezone" data-content="timezone" data-highlight="#timezone-region" value="<?php echo CHV\Settings::get('default_timezone', true); ?>" required>
			</div>
			
			<hr class="line-separator"></hr>
			
			<div class="input-label">
				<label for="website_search"><?php _se('Search'); ?></label>
				<div class="c5 phablet-c1"><select type="text" name="website_search" id="website_search" class="text-input">
					<?php
						echo CHV\Render\get_select_options_html([1 => _s('Enabled'), 0 => _s('Disabled')], CHV\Settings::get('website_search'));
					?>
				</select></div>
				<div class="input-below"><?php _se('Enable this if you want to allow the search feature.'); ?></div>
			</div>
			
			<div class="input-label">
				<label for="website_explore_page"><?php _se('Explore'); ?></label>
				<div class="c5 phablet-c1"><select type="text" name="website_explore_page" id="website_explore_page" class="text-input">
					<?php
						echo CHV\Render\get_select_options_html([1 => _s('Enabled'), 0 => _s('Disabled')], CHV\Settings::get('website_explore_page'));
					?>
				</select></div>
				<div class="input-below"><?php _se('Enable this if you want to allow the explore page.'); ?></div>
			</div>
			
			<div class="input-label">
				<label for="website_random"><?php _se('Random'); ?></label>
				<div class="c5 phablet-c1"><select type="text" name="website_random" id="website_random" class="text-input">
					<?php
						echo CHV\Render\get_select_options_html([1 => _s('Enabled'), 0 => _s('Disabled')], CHV\Settings::get('website_random'));
					?>
				</select></div>
				<div class="input-below"><?php _se('Enable this if you want to allow the random feature.'); ?></div>
			</div>
			
			<hr class="line-separator"></hr>
			
            <div class="input-label">
				<label for="website_mode"><?php _se('Website mode'); ?></label>
				<div class="c5 phablet-c1"><select type="text" name="website_mode" id="website_mode" class="text-input" data-combo="website-mode-combo">
					<?php
						echo CHV\Render\get_select_options_html(['community' => _s('Community'), 'personal' => _s('Personal')], CHV\Settings::get('website_mode'));
					?>
				</select></div>
				<div class="input-below input-warning red-warning"><?php echo get_input_errors()['website_mode']; ?></div>
				<div class="input-below"><?php _se('You can switch the website mode anytime.'); ?></div>
			</div>
            
			<div id="website-mode-combo">
				
				<div data-combo-value="personal" class="switch-combo phablet-c1<?php if((get_safe_post() ? get_safe_post()['website_mode'] : CHV\Settings::get('website_mode')) != 'personal') echo ' soft-hidden'; ?>">
					
					<hr class="line-separator"></hr>
					
					<div class="input-label">
						<label for="website_mode_personal_uid"><?php _se('Personal mode target user'); ?></label>
						<div class="c3"><input type="number" min="1" name="website_mode_personal_uid" id="website_mode_personal_uid" class="text-input" value="<?php echo CHV\Settings::get('website_mode_personal_uid'); ?>" placeholder="<?php _se('User ID'); ?>" rel="tooltip" title="<?php _se('Your user id is: %s', CHV\Login::getUser()['id']); ?>" data-tipTip="right" data-required></div>
						<div class="input-below input-warning red-warning"><?php echo get_input_errors()['website_mode_personal_uid']; ?></div>
						<div class="input-below"><?php _se('Numeric ID of the target user for personal mode.'); ?></div>
					</div>
					<div class="input-label">
						<label for="website_mode_personal_routing"><?php _se('Personal mode routing'); ?></label>
						<div class="c5"><input type="text" name="website_mode_personal_routing" id="website_mode_personal_routing" class="text-input" value="<?php echo CHV\Settings::get('website_mode_personal_routing'); ?>" placeholder="/" ></div>
						<div class="input-below input-warning red-warning"><?php echo get_input_errors()['website_mode_personal_routing']; ?></div>
						<div class="input-below"><?php _se('Custom route to map /username to /something. Use "/" to map to homepage.'); ?></div>
					</div>
					
					<hr class="line-separator"></hr>
					
				</div>
				
			</div>
			
			<div class="input-label">
				<label for="website_privacy_mode"><?php _se('Website privacy mode'); ?></label>
				<div class="c5 phablet-c1"><select type="text" name="website_privacy_mode" id="website_privacy_mode" class="text-input" data-combo="website-privacy-mode-combo">
					<?php
						echo CHV\Render\get_select_options_html(['public' => _s('Public'), 'private' => _s('Private')], CHV\Settings::get('website_privacy_mode'));
					?>
				</select></div>
				<div class="input-below input-warning red-warning"><?php echo get_input_errors()['website_privacy_mode']; ?></div>
				<div class="input-below"><?php _se('Private mode will make the website only available for registered users.'); ?></div>
			</div>
			
			<div id="website-privacy-mode-combo">
				<div data-combo-value="private" class="switch-combo phablet-c1<?php if((get_safe_post() ? get_safe_post()['website_privacy_mode'] : CHV\Settings::get('website_privacy_mode')) != 'private') echo ' soft-hidden'; ?>">
					<div class="input-label">
						<label for="website_content_privacy_mode"><?php _se('Content privacy mode'); ?></label>
						<div class="c5 phablet-c1"><select type="text" name="website_content_privacy_mode" id="website_content_privacy_mode" class="text-input">
						<?php
							echo CHV\Render\get_select_options_html([
								'default'			=> _s('Default'),
								'private'			=> _s('Force private (self)'),
								'private_but_link'	=> _s('Force private (anyone with the link)'),
							], CHV\Settings::get('website_content_privacy_mode'));
						?>
						</select></div>
						<div class="input-below input-warning red-warning"><?php echo get_input_errors()['website_content_privacy_mode']; ?></div>
						<div class="input-below"><?php _se('Forced privacy modes will override user selected privacy.'); ?></div>
					</div>
				</div>
			</div>
			
			<?php } ?>
			
			<?php if(get_settings()['key'] == 'image-upload') { ?>
			<div class="input-label">
				<label for="enable_uploads"><?php _se('Enable uploads'); ?></label>
				<div class="c5 phablet-c1"><select type="text" name="enable_uploads" id="enable_uploads" class="text-input">
					<?php
						echo CHV\Render\get_select_options_html([1 => _s('Enabled'), 0 => _s('Disabled')], CHV\Settings::get('enable_uploads'));
					?>
				</select></div>
				<div class="input-below"><?php _se("Enable this if you want to allow image uploads. This setting doesn't affect administrators."); ?></div>
			</div>
			<div class="input-label">
				<label for="guest_uploads"><?php _se('Guest uploads'); ?></label>
				<div class="c5 phablet-c1"><select type="text" name="guest_uploads" id="guest_uploads" class="text-input"<?php if(CHV\getSetting('website_mode') == 'personal') echo ' disabled'; ?>>
					<?php
						echo CHV\Render\get_select_options_html([1 => _s('Enabled'), 0 => _s('Disabled')], CHV\Settings::get('guest_uploads'));
					?>
				</select></div>
				<div class="input-below"><?php _se('Enable this if you want to allow non registered users to upload.'); ?></div>
				<?php if(CHV\getSetting('website_mode') == 'personal') { ?><div class="input-below"><span class="icon icon-info color-red"></span> <?php _se('This setting is disabled when personal mode is active.'); ?></div><?php } ?>
			</div>
			
			<hr class="line-separator"></hr>
			
			<div class="input-label">
				<label for="upload_max_filesize_mb"><?php _se('Max. filesize'); ?> (MB)</label>
				<div class="c2"><input type="number" min="0" pattern="\d+" name="upload_max_filesize_mb" id="upload_max_filesize_mb" class="text-input" value="<?php echo get_safe_post() ? get_safe_post()['upload_max_filesize_mb'] : CHV\Settings::get('upload_max_filesize_mb'); ?>" placeholder="Size mb." required></div>
				<div class="input-below input-warning red-warning"><?php echo get_input_errors()['upload_max_filesize_mb']; ?></div>
				<div class="input-below"><?php _se('Max. allowed filesize. (Max allowed by server is %s)', G\format_bytes(G\get_ini_bytes(ini_get('upload_max_filesize'))), 'strtr'); ?></div>
			</div>
			<div class="input-label">
				<label for="upload_image_path"><?php _se('Image path'); ?></label>
				<div class="c9 phablet-c1"><input type="text" name="upload_image_path" id="upload_image_path" class="text-input" value="<?php echo get_safe_post() ? get_safe_post()['upload_image_path'] : CHV\Settings::get('upload_image_path'); ?>" placeholder="<?php _se('Relative to Chevereto root'); ?>" required></div>
				<span class="input-warning red-warning"><?php echo get_input_errors()['upload_image_path']; ?></span>
				<div class="input-below"><?php _se('Where to store the images? Relative to Chevereto root.'); ?></div>
			</div>
			<div class="input-label">
				<label for="upload_storage_mode"><?php _se('Storage mode'); ?></label>
				<div class="c5 phablet-c1"><select type="text" name="upload_storage_mode" id="upload_storage_mode" class="text-input">
					<?php
						echo CHV\Render\get_select_options_html(['datefolder' => _s('Datefolders'), 'direct' => _s('Direct')], CHV\Settings::get('upload_storage_mode'));
					?>
				</select></div>
				<div class="input-below"><?php _se('Datefolders creates %s structure', date('/Y/m/d/')); ?></div>
			</div>
			<div class="input-label">
				<label for="upload_filenaming"><?php _se('File naming method'); ?></label>
				<div class="c5 phablet-c1"><select type="text" name="upload_filenaming" id="upload_filenaming" class="text-input">
					<?php
						echo CHV\Render\get_select_options_html(['original' => _s('Original'), 'random' => _s('Random'), 'mixed' => _s('Mixed')], CHV\Settings::get('upload_filenaming'));
					?>
				</select></div>
				<div class="input-below"><?php _se('"Original" will try to keep the image source name while "Random" will generate a random name. "Mixed" is a combination of both methods.'); ?></div>
			</div>
			
			<hr class="line-separator"></hr>
			
			<div class="input-label">
				<label for="upload_thumb_width" class="display-block-forced"><?php _se('Thumb size'); ?></label>
				<div class="c5 overflow-auto clear-both">
					<div class="c2 float-left">
						<input type="number" min="16" pattern="\d+" name="upload_thumb_width" id="upload_thumb_width" class="text-input" value="<?php echo get_safe_post() ? get_safe_post()['upload_thumb_width'] : CHV\Settings::get('upload_thumb_width'); ?>" placeholder="<?php echo  CHV\Settings::getDefault('upload_thumb_width'); ?>" rel="tooltip" data-tiptip="top" title="<?php _se('Width'); ?>" required>
					</div>
					<div class="c2 float-left margin-left-10">
						<input type="number" min="16" pattern="\d+" name="upload_thumb_height" id="upload_thumb_height" class="text-input" value="<?php echo get_safe_post() ? get_safe_post()['upload_thumb_height'] : CHV\Settings::get('upload_thumb_height'); ?>" placeholder="<?php echo  CHV\Settings::getDefault('upload_thumb_height'); ?>" rel="tooltip" data-tiptip="top" title="<?php _se('Height'); ?>" required>
					</div>
				</div>
				<div class="input-below input-warning red-warning"><?php echo get_input_errors()['upload_thumb_width']; ?></div>
				<div class="input-below input-warning red-warning"><?php echo get_input_errors()['upload_thumb_height']; ?></div>
				<div class="input-below"><?php _se('Thumbnails will be fixed to this size.'); ?></div>
			</div>
			<div class="input-label">
				<label for="upload_medium_width"><?php _se('Medium size'); ?></label>
				<div class="c2">
					<input type="number" min="16" pattern="\d+" name="upload_medium_width" id="upload_medium_width" class="text-input" value="<?php echo get_safe_post() ? get_safe_post()['upload_medium_width'] : CHV\Settings::get('upload_medium_width'); ?>" placeholder="<?php echo CHV\Settings::getDefault('upload_medium_width'); ?>" rel="tooltip" data-tiptip="top" title="<?php _se('Width'); ?>" required>
				</div>
				<div class="input-below input-warning red-warning"><?php echo get_input_errors()['upload_medium_width']; ?></div>
				<div class="input-below"><?php _se('Height will be automatic calculated.'); ?></div>
			</div>
			
			<hr class="line-separator"></hr>
			
			<div class="input-label">
				<label for="watermark_enable"><?php _se('Watermarks'); ?></label>
				<div class="c5 phablet-c1"><select type="text" name="watermark_enable" id="watermark_enable" class="text-input" data-combo="watermark-combo">
					<?php
						echo CHV\Render\get_select_options_html([1 => _s('Enabled'), 0 => _s('Disabled')], get_safe_post() ? get_safe_post()['watermark_enable'] : CHV\Settings::get('watermark_enable'));
					?>
				</select></div>
				<div class="input-below input-warning red-warning clear-both"><?php echo get_input_errors()['watermark_enable']; ?></div>
				<div class="input-below"><?php _se('Enable this to put a logo or anything you want in image uploads.'); ?></div>
			</div>
			<div id="watermark-combo">
				<div data-combo-value="1" class="switch-combo phablet-c1<?php if((get_safe_post() ? get_safe_post()['watermark_enable'] : CHV\Settings::get('watermark_enable')) != 1) echo ' soft-hidden'; ?>">
					<?php
						if(!is_writable(CHV_PATH_CONTENT_IMAGES_SYSTEM)) {
					?>
					<p class="highlight"><?php _se("Warning: Can't write in %s", CHV_PATH_CONTENT_IMAGES_SYSTEM); ?></p>
					<?php
						}
					?>
					
					<div class="input-label">
						<label for="watermark_checkboxes"><?php _se('Watermark user toggles'); ?></label>
						<?php echo CHV\Render\get_checkbox_html([
							'name'		=> 'watermark_enable_guest',
							'label'		=> _s('Enable watermark on guest uploads'),
							'checked'	=> ((bool)(get_safe_post() ? get_safe_post()['watermark_enable_guest'] : CHV\Settings::get('watermark_enable_guest')))
						]); ?>
						<?php echo CHV\Render\get_checkbox_html([
							'name'		=> 'watermark_enable_user',
							'label'		=> _s('Enable watermark on user uploads'),
							'checked'	=> ((bool)(get_safe_post() ? get_safe_post()['watermark_enable_user'] : CHV\Settings::get('watermark_enable_user')))
						]); ?>
						<?php echo CHV\Render\get_checkbox_html([
							'name'		=> 'watermark_enable_admin',
							'label'		=> _s('Enable watermark on admin uploads'),
							'checked'	=> ((bool)(get_safe_post() ? get_safe_post()['watermark_enable_admin'] : CHV\Settings::get('watermark_enable_admin')))
						]); ?>
					</div>
					
					<div class="input-label">
						<label for="watermark_checkboxes"><?php _se('Watermark file toggles'); ?></label>
						<?php echo CHV\Render\get_checkbox_html([
							'name'		=> 'watermark_enable_file_gif',
							'label'		=> _s('Enable watermark on GIF image uploads'),
							'checked'	=> ((bool)(get_safe_post() ? get_safe_post()['watermark_enable_file_gif'] : CHV\Settings::get('watermark_enable_file_gif')))
						]); ?>
					</div>
					
					<div class="input-label">
						<label for="watermark_target_min_width" class="display-block-forced"><?php _se('Minimum image size needed to apply watermark'); ?></label>
						<div class="c5 overflow-auto clear-both">
							<div class="c2 float-left">
								<input type="number" min="0" pattern="\d+" name="watermark_target_min_width" id="watermark_target_min_width" class="text-input" value="<?php echo get_safe_post() ? get_safe_post()['watermark_target_min_width'] : CHV\Settings::get('watermark_target_min_width'); ?>" placeholder="<?php echo  CHV\Settings::getDefault('watermark_target_min_width'); ?>" rel="tooltip" data-tiptip="top" title="<?php _se('Width'); ?>" required>
							</div>
							<div class="c2 float-left margin-left-10">
								<input type="number" min="0" pattern="\d+" name="watermark_target_min_height" id="watermark_target_min_height" class="text-input" value="<?php echo get_safe_post() ? get_safe_post()['watermark_target_min_height'] : CHV\Settings::get('watermark_target_min_height'); ?>" placeholder="<?php echo  CHV\Settings::getDefault('watermark_target_min_height'); ?>" rel="tooltip" data-tiptip="top" title="<?php _se('Height'); ?>" required>
							</div>
						</div>
						<div class="input-below input-warning red-warning"><?php echo get_input_errors()['watermark_target_min_width']; ?></div>
						<div class="input-below input-warning red-warning"><?php echo get_input_errors()['watermark_target_min_height']; ?></div>
						<div class="input-below"><?php _se("Images smaller than this won't be watermarked. Use zero (0) to don't set a minimum image size limit."); ?></div>
					</div>
					
					<div class="input-label">
						<label for="watermark_image"><?php _se('Watermark image'); ?></label>
						<div class="transparent-canvas dark margin-bottom-10" style="max-width: 200px;"><img class="display-block" width="100%" src="<?php echo CHV\get_system_image_url(CHV\Settings::get('watermark_image')) . '?' . G\random_string(8); ?>"></div>
						<div class="c5 phablet-c1">
							<input id="watermark_image" name="watermark_image" type="file" accept="image/png">
						</div>
						<div class="input-below input-warning red-warning"><?php echo get_input_errors()['watermark_image']; ?></div>
						<div class="input-below"><?php _se('You will get best results with plain logos with drop shadow. You can use a large image if the file size is not that big (recommended max. is 16KB). Must be a PNG.'); ?></div>
					</div>
					<div class="input-label">
						<label for="watermark_position"><?php _se('Watermark position'); ?></label>
						<div class="c5 phablet-c1"><select type="text" name="watermark_position" id="watermark_position" class="text-input">
							<?php
								echo CHV\Render\get_select_options_html([
									'left top'		=> _s('left top'),
									'left center'	=> _s('left center'),
									'left bottom'	=> _s('left bottom'),
									'center top'	=> _s('center top'),
									'center center' => _s('center center'),
									'center bottom' => _s('center bottom'),
									'right top'		=> _s('right top'),
									'right center'	=> _s('right center'),
									'right bottom'	=> _s('right bottom')
								], get_safe_post() ? get_safe_post()['watermark_position'] : CHV\Settings::get('watermark_position'));
							?>
						</select></div>
						<div class="input-below input-warning red-warning clear-both"><?php echo get_input_errors()['watermark_position']; ?></div>
						<div class="input-below"><?php _se('Relative position of the watermark image. First horizontal align then vertical align.'); ?></div>
					</div>
					<div class="input-label">
						<label for="watermark_percentage"><?php _se('Watermark percentage'); ?></label>
						<div class="c2">
							<input type="number" min="1" max="100" pattern="\d+" name="watermark_percentage" id="watermark_percentage" class="text-input" value="<?php echo get_safe_post() ? get_safe_post()['watermark_percentage'] : CHV\Settings::get('watermark_percentage'); ?>" placeholder="<?php echo CHV\Settings::getDefault('watermark_percentage'); ?>" required>
						</div>
						<div class="input-below input-warning red-warning"><?php echo get_input_errors()['watermark_percentage']; ?></div>
						<div class="input-below"><?php _se('Watermark percentual size relative to the target image area. Values 1 to 100.'); ?></div>
					</div>
					<div class="input-label">
						<label for="watermark_margin"><?php _se('Watermark margin'); ?></label>
						<div class="c2">
							<input type="number" min="0" pattern="\d+" name="watermark_margin" id="watermark_margin" class="text-input" value="<?php echo get_safe_post() ? get_safe_post()['watermark_margin'] : CHV\Settings::get('watermark_margin'); ?>" placeholder="<?php echo CHV\Settings::getDefault('watermark_margin'); ?>" required>
						</div>
						<div class="input-below input-warning red-warning"><?php echo get_input_errors()['watermark_margin']; ?></div>
						<div class="input-below"><?php _se('Margin from the border of the image to the watermark image.'); ?></div>
					</div>
					<div class="input-label">
						<label for="watermark_opacity"><?php _se('Watermark opacity'); ?></label>
						<div class="c2">
							<input type="number" min="1" max="100" pattern="\d+" name="watermark_opacity" id="watermark_opacity" class="text-input" value="<?php echo get_safe_post() ? get_safe_post()['watermark_opacity'] : CHV\Settings::get('watermark_opacity'); ?>" placeholder="<?php echo CHV\Settings::getDefault('watermark_opacity'); ?>" required>
						</div>
						<div class="input-below input-warning red-warning"><?php echo get_input_errors()['watermark_opacity']; ?></div>
						<div class="input-below"><?php _se('Opacity of the watermark in the final watermarked image. Values 0 to 100.'); ?></div>
					</div>
				</div>
			</div>
			
			<?php } ?>
			
			<?php if(get_settings()['key'] == 'categories') { ?>
			<script>
				$(document).ready(function() {
					CHV.obj.categories = <?php echo json_encode(get_categories()); ?>;
				});
			</script>
			<ul data-content="dashboard-categories-list" class="tabbed-content-list table-li-hover table-li margin-top-20 margin-bottom-20">
				<li class="table-li-header phone-hide">
					<span class="c5 display-table-cell padding-right-10"><?php _se('Name'); ?></span>
					<span class="c4 display-table-cell padding-right-10 phone-hide phablet-hide"><?php _se("URL key"); ?></span>
					<span class="c13 display-table-cell phone-hide"><?php _se("Description"); ?></span>
				</li>
				<?php
					$li_template = '<li data-content="category" data-category-id="%ID%">
					<span class="c5 display-table-cell padding-right-10"><a data-modal="edit" data-target="form-modal" data-category-id="%ID%" data-content="category-name">%NAME%</a></span>
					<span class="c4 display-table-cell padding-right-10 phone-hide phablet-hide" data-content="category-url_key">%URL_KEY%</span>
					<span class="c13 display-table-cell padding-right-10 phone-display-block" data-content="category-description">%DESCRIPTION%</span>
					<span class="c2 display-table-cell"><a class="delete-link" data-category-id="%ID%" data-args="%ID%" data-confirm="'. _s("Do you really want to delete the %s category? This can't be undone.").'" data-submit-fn="CHV.fn.category.delete.submit" data-before-fn="CHV.fn.category.delete.before" data-ajax-deferred="CHV.fn.category.delete.complete">'. _s('Delete').'</a></span>
				</li>';
					if(get_categories()) {
						foreach(get_categories() as $category) {
							$replaces = [];
							foreach($category as $k => $v) {
								$replaces['%' . strtoupper($k) . '%'] = $v;
							}
							echo strtr($li_template, $replaces);
						}
					}
				?>
			</ul>
			<div class="hidden" data-content="category-dashboard-template">
				<?php echo $li_template; ?>
			</div>
			<p><?php _se("Note: Deleting a category doesn't delete the images that belongs to that category."); ?></p>
			<div data-modal="form-modal" class="hidden" data-submit-fn="CHV.fn.category.edit.submit" data-before-fn="CHV.fn.category.edit.before" data-ajax-deferred="CHV.fn.category.edit.complete" data-ajax-url="<?php echo G\get_base_url("json"); ?>">
				<span class="modal-box-title"><?php _se('Edit category'); ?></span>
				<div class="modal-form">
					<input type="hidden" name="form-category-id">
					<?php G\Render\include_theme_file('snippets/form_category_edit'); ?>
				</div>
			</div>
			<?php } ?>
			<?php
				if(get_settings()['key'] == 'ip-bans') {
					try {
						$ip_bans = CHV\Ip_ban::getAll();
					} catch(Exception $e) {
						G\exception_to_error($e);
					}
			?>
			<script>
				$(document).ready(function() {
					CHV.obj.ip_bans = <?php echo json_encode($ip_bans); ?>;
				});
			</script>
			<ul data-content="dashboard-ip_bans-list" class="tabbed-content-list table-li table-li-hover margin-top-20 margin-bottom-20">
				<li class="table-li-header phone-hide">
					<span class="c6 display-table-cell padding-right-10">IP</span>
					<span class="c5 display-table-cell padding-right-10 phone-hide phablet-hide"><?php _se('Expires'); ?></span>
					<span class="c13 display-table-cell phone-hide"><?php _se('Message'); ?></span>
				</li>
				<?php
					$li_template = '<li data-content="ip_ban" data-ip_ban-id="%ID%" class="word-break-break-all">
					<span class="c6 display-table-cell padding-right-10"><a data-modal="edit" data-target="form-modal" data-ip_ban-id="%ID%" data-content="ip_ban-ip">%IP%</a></span>
					<span class="c5 display-table-cell padding-right-10 phone-hide phablet-hide" data-content="ip_ban-expires">%EXPIRES%</span>
					<span class="c14 display-table-cell padding-right-10 phone-display-block" data-content="ip_ban-message">%MESSAGE%</span>
					<span class="c2 display-table-cell"><a class="delete-link" data-ip_ban-id="%ID%" data-args="%ID%" data-confirm="'. _s("Do you really want to remove the ban to the IP %s? This can't be undone.").'" data-submit-fn="CHV.fn.ip_ban.delete.submit" data-before-fn="CHV.fn.ip_ban.delete.before" data-ajax-deferred="CHV.fn.ip_ban.delete.complete">'. _s('Delete').'</a></span>
				</li>';
					foreach($ip_bans as $ip_ban) {
						$replaces = [];
						foreach($ip_ban as $k => $v) {
							$replaces['%' . strtoupper($k) . '%'] = $v;
						}
						echo strtr($li_template, $replaces);
					}
				?>
			</ul>
			<div class="hidden" data-content="ip_ban-dashboard-template">
				<?php echo $li_template; ?>
			</div>
			<p><?php _se("Banned IP address will be forbidden to use the entire website."); ?></p>
			<div data-modal="form-modal" class="hidden" data-submit-fn="CHV.fn.ip_ban.edit.submit" data-before-fn="CHV.fn.ip_ban.edit.before" data-ajax-deferred="CHV.fn.ip_ban.edit.complete" data-ajax-url="<?php echo G\get_base_url("json"); ?>">
				<span class="modal-box-title"><?php _se('Edit IP ban'); ?></span>
				<div class="modal-form">
					<input type="hidden" name="form-ip_ban-id">
					<?php G\Render\include_theme_file('snippets/form_ip_ban_edit'); ?>
				</div>
			</div>
			<?php } ?>
			
			<?php if(get_settings()['key'] == 'users') { ?>
			<div class="input-label">
				<label for="enable_signups"><?php _se('Enable signups'); ?></label>
				<div class="c5 phablet-c1"><select type="text" name="enable_signups" id="enable_signups" class="text-input"<?php if(CHV\getSetting('website_mode') == 'personal') echo ' disabled'; ?>>
					<?php
						echo CHV\Render\get_select_options_html([1 => _s('Enabled'), 0 => _s('Disabled')], CHV\Settings::get('enable_signups'));
					?>
				</select></div>
				<div class="input-below"><?php _se('Enable this if you want to allow users to signup.'); ?></div>
				<?php if(CHV\getSetting('website_mode') == 'personal') { ?><div class="input-below"><span class="icon icon-info color-red"></span> <?php _se('This setting is disabled when personal mode is active.'); ?></div><?php } ?>
			</div>
			
			<hr class="line-separator"></hr>
			
			<div class="input-label">
				<label for="user_routing"><?php _se('Username routing'); ?></label>
				<div class="c5 phablet-c1"><select type="text" name="user_routing" id="user_routing" class="text-input"<?php if(CHV\getSetting('website_mode') == 'personal') echo ' disabled'; ?>>
					<?php
						echo CHV\Render\get_select_options_html([1 => _s('Enabled'), 0 => _s('Disabled')], CHV\Settings::get('user_routing'));
					?>
				</select></div>
				<div class="input-below"><?php _se('Enable this if you want to use %s/username URLs instead of %s/user/username.', ['%s' => rtrim(G\get_base_url(), '/')]); ?></div>
				<?php if(CHV\getSetting('website_mode') == 'personal') { ?><div class="input-below"><span class="icon icon-info color-red"></span> <?php _se('This setting is disabled when personal mode is active.'); ?></div><?php } ?>
			</div>
			<div class="input-label">
				<label for="logged_user_logo_link"><?php _se('Logged user logo link'); ?></label>
				<div class="c5 phablet-c1"><select type="text" name="logged_user_logo_link" id="logged_user_logo_link" class="text-input">
					<?php
						echo CHV\Render\get_select_options_html(['homepage' => _s('Homepage'), 'user_profile' => _s('User profile')], CHV\Settings::get('logged_user_logo_link'));
					?>
				</select></div>
				<div class="input-below"><?php _se('Configure the link used in the logo when user is logged in.'); ?></div>
			</div>
			
			<hr class="line-separator"></hr>
			
			<div class="input-label">
				<label for="require_user_email_confirmation"><?php _se('Require email confirmation'); ?></label>
				<div class="c5 phablet-c1"><select type="text" name="require_user_email_confirmation" id="require_user_email_confirmation" class="text-input">
					<?php
						echo CHV\Render\get_select_options_html([1 => _s('Enabled'), 0 => _s('Disabled')], CHV\Settings::get('require_user_email_confirmation'));
					?>
				</select></div>
				<div class="input-below"><?php _se('Enable this if users must validate their email address on sign up.'); ?></div>
			</div>
			<div class="input-label">
				<label for="require_user_email_social_signup"><?php _se('Require email for social signup'); ?></label>
				<div class="c5 phablet-c1"><select type="text" name="require_user_email_social_signup" id="require_user_email_social_signup" class="text-input">
					<?php
						echo CHV\Render\get_select_options_html([1 => _s('Enabled'), 0 => _s('Disabled')], CHV\Settings::get('require_user_email_social_signup'));
					?>
				</select></div>
				<div class="input-below"><?php _se('Enable this if users using social networks to register must provide an email address.'); ?></div>
			</div>
			
			<hr class="line-separator"></hr>
			
			<div class="input-label">
				<label for="user_image_avatar_max_filesize_mb"><?php _se('User avatar max. filesize'); ?> (MB)</label>
				<div class="c2"><input type="number" min="0" pattern="\d+" name="user_image_avatar_max_filesize_mb" id="user_image_avatar_max_filesize_mb" class="text-input" value="<?php echo get_safe_post() ? get_safe_post()['user_image_avatar_max_filesize_mb'] : CHV\Settings::get('user_image_avatar_max_filesize_mb'); ?>" placeholder="Size mb." required></div>
				<div class="input-below input-warning red-warning"><?php echo get_input_errors()['user_image_avatar_max_filesize_mb']; ?></div>
				<div class="input-below"><?php _se('Max. allowed filesize for user avatar image. (Max allowed by server is %s)', G\format_bytes(G\get_ini_bytes(ini_get('upload_max_filesize'))), 'strtr'); ?></div>
			</div>
			<div class="input-label">
				<label for="user_image_background_max_filesize_mb"><?php _se('User background max. filesize'); ?> (MB)</label>
				<div class="c2"><input type="number" min="0" pattern="\d+" name="user_image_background_max_filesize_mb" id="user_image_background_max_filesize_mb" class="text-input" value="<?php echo get_safe_post() ? get_safe_post()['user_image_background_max_filesize_mb'] : CHV\Settings::get('user_image_background_max_filesize_mb'); ?>" placeholder="Size mb." required></div>
				<div class="input-below input-warning red-warning"><?php echo get_input_errors()['user_image_background_max_filesize_mb']; ?></div>
				<div class="input-below"><?php _se('Max. allowed filesize for user background image. (Max allowed by server is %s)', G\format_bytes(G\get_ini_bytes(ini_get('upload_max_filesize'))), 'strtr'); ?></div>
			</div>
			
			<?php } ?>
			
			<?php if(get_settings()['key'] == 'flood-protection') { ?>
			<p><?php _se("Block image uploads by IP if the system notice a flood  behavior based on the number of uploads per time period. This setting doesn't affect administrators."); ?></p>
			<div class="input-label">
				<label for="flood_uploads_protection"><?php _se('Flood protection'); ?></label>
				<div class="c5 phablet-c1"><select type="text" name="flood_uploads_protection" id="flood_uploads_protection" class="text-input" data-combo="flood-protection-combo">
					<?php
						echo CHV\Render\get_select_options_html([1 => _s('Enabled'), 0 => _s('Disabled')], get_safe_post() ? get_safe_post()['flood_uploads_protection'] : CHV\Settings::get('flood_uploads_protection'));
					?>
				</select></div>
			</div>
			<div id="flood-protection-combo">
				<div data-combo-value="1" class="switch-combo <?php if(!(get_safe_post() ? get_safe_post()['flood_uploads_protection'] : CHV\Settings::get('flood_uploads_protection'))) echo ' soft-hidden'; ?>">
					<div class="input-label">
						<label for="flood_uploads_notify"><?php _se('Notify to email'); ?></label>
						<div class="c5 phablet-c1"><select type="text" name="flood_uploads_notify" id="flood_uploads_notify" class="text-input">
							<?php
								echo CHV\Render\get_select_options_html([1 => _s('Enabled'), 0 => _s('Disabled')], get_safe_post() ? get_safe_post()['flood_uploads_notify'] : CHV\Settings::get('flood_uploads_notify'));
							?>
						</select></div>
						<div class="input-below"><?php _se('If enabled the system will send an email on flood incidents.'); ?></div>
					</div>
					<div class="input-label">
						<label for="flood_uploads_minute"><?php _se('Minute limit'); ?></label>
						<div class="c3"><input type="number" min="0" name="flood_uploads_minute" id="flood_uploads_minute" class="text-input" value="<?php echo get_safe_post() ? get_safe_post()['flood_uploads_minute'] : CHV\Settings::get('flood_uploads_minute', true); ?>" placeholder="<?php echo CHV\Settings::getDefault('flood_uploads_minute'); ?>"></div>
						<div class="input-warning red-warning"><?php echo get_input_errors()['flood_uploads_minute']; ?></div>
					</div>
					<div class="input-label">
						<label for="flood_uploads_hour"><?php _se('Hourly limit'); ?></label>
						<div class="c3"><input type="number" min="0" name="flood_uploads_hour" id="flood_uploads_hour" class="text-input" value="<?php echo get_safe_post() ? get_safe_post()['flood_uploads_hour'] : CHV\Settings::get('flood_uploads_hour', true); ?>" placeholder="<?php echo CHV\Settings::getDefault('flood_uploads_hour'); ?>"></div>
						<div class="input-warning red-warning"><?php echo get_input_errors()['flood_uploads_hour']; ?></div>
					</div>
					<div class="input-label">
						<label for="flood_uploads_day"><?php _se('Daily limit'); ?></label>
						<div class="c3"><input type="number" min="0" name="flood_uploads_day" id="flood_uploads_day" class="text-input" value="<?php echo get_safe_post() ? get_safe_post()['flood_uploads_day'] : CHV\Settings::get('flood_uploads_day', true); ?>" placeholder="<?php echo CHV\Settings::getDefault('flood_uploads_day'); ?>"></div>
						<div class="input-warning red-warning"><?php echo get_input_errors()['flood_uploads_day']; ?></div>
					</div>
					<div class="input-label">
						<label for="flood_uploads_week"><?php _se('Weekly limit'); ?></label>
						<div class="c3"><input type="number" min="0" name="flood_uploads_week" id="flood_uploads_week" class="text-input" value="<?php echo get_safe_post() ? get_safe_post()['flood_uploads_week'] : CHV\Settings::get('flood_uploads_week', true); ?>" placeholder="<?php echo CHV\Settings::getDefault('flood_uploads_week'); ?>"></div>
						<div class="input-warning red-warning"><?php echo get_input_errors()['flood_uploads_week']; ?></div>
					</div>
					<div class="input-label">
						<label for="flood_uploads_month"><?php _se('Monthly limit'); ?></label>
						<div class="c3"><input type="number" min="0" name="flood_uploads_month" id="flood_uploads_month" class="text-input" value="<?php echo get_safe_post() ? get_safe_post()['flood_uploads_month'] : CHV\Settings::get('flood_uploads_month', true); ?>" placeholder="<?php echo CHV\Settings::getDefault('flood_uploads_month'); ?>"></div>
						<div class="input-warning red-warning"><?php echo get_input_errors()['flood_uploads_month']; ?></div>
					</div>
				</div>
			</div>
			<?php } ?>
			
			<?php if(get_settings()['key'] == 'content') { ?>
			<div class="input-label">
				<label for="show_nsfw_in_listings"><?php _se('Show not safe content in listings'); ?></label>
				<div class="c5 phablet-c1"><select type="text" name="show_nsfw_in_listings" id="show_nsfw_in_listings" class="text-input">
					<?php
						echo CHV\Render\get_select_options_html([1 => _s('Enabled'), 0 => _s('Disabled')], CHV\Settings::get('show_nsfw_in_listings'));
					?>
				</select></div>
				<div class="input-below"><?php _se("Enable this if you want to show not safe content in listings. This setting doesn't affect administrators and can be overridden by user own settings."); ?></div>
			</div>
			<div class="input-label">
				<label for="theme_nsfw_blur"><?php _se('Blur NSFW content in listings'); ?></label>
				<div class="c5 phablet-c1"><select type="text" name="theme_nsfw_blur" id="theme_nsfw_blur" class="text-input">
					<?php
						echo CHV\Render\get_select_options_html([1 => _s('Enabled'), 0 => _s('Disabled')], CHV\Settings::get('theme_nsfw_blur'));
					?>
				</select></div>
				<div class="input-below"><?php _se("Enable this if you want to apply a blur effect on the NSFW images in listings."); ?></div>
			</div>
			<div class="input-label">
				<label for="show_banners_in_nsfw"><?php _se('Show banners in not safe content'); ?></label>
				<div class="c5 phablet-c1"><select type="text" name="show_banners_in_nsfw" id="show_banners_in_nsfw" class="text-input">
					<?php
						echo CHV\Render\get_select_options_html([1 => _s('Enabled'), 0 => _s('Disabled')], CHV\Settings::get('show_banners_in_nsfw'));
					?>
				</select></div>
				<div class="input-below"><?php _se("Enable this if you want to show banners in not safe content pages."); ?></div>
			</div>
			
			<div class="input-label">
				<label for="show_nsfw_in_random_mode"><?php _se('Show not safe content in random mode'); ?></label>
				<div class="c5 phablet-c1"><select type="text" name="show_nsfw_in_random_mode" id="show_nsfw_in_random_mode" class="text-input">
					<?php
						echo CHV\Render\get_select_options_html([1 => _s('Enabled'), 0 => _s('Disabled')], get_safe_post() ? get_safe_post()['show_nsfw_in_random_mode'] : CHV\Settings::get('show_nsfw_in_random_mode'));
					?>
				</select></div>
			</div>
			<?php } ?>
			
			<?php if(get_settings()['key'] == 'listings') { ?>
			<div class="input-label">
				<label for="listing_items_per_page"><?php _se('List items per page'); ?></label>
				<div class="c2"><input type="number" min="1" name="listing_items_per_page" id="listing_items_per_page" class="text-input" value="<?php echo CHV\Settings::get('listing_items_per_page', true); ?>" placeholder="<?php echo CHV\Settings::getDefault('listing_items_per_page', true); ?>" required></div>
				<div class="input-below input-warning red-warning"><?php echo get_input_errors()['listing_items_per_page']; ?></div>
				<div class="input-below"><?php _se('How many items should be displayed per page listing.'); ?></div>
			</div>
			
			<div class="input-label">
				<label for="listing_pagination_mode"><?php _se('List pagination mode'); ?></label>
				<div class="c5 phablet-c1"><select type="text" name="listing_pagination_mode" id="listing_pagination_mode" class="text-input">
					<?php
						echo CHV\Render\get_select_options_html(['endless' => _s('Endless scrolling'), 'classic' => _s('Classic pagination')], CHV\Settings::get('listing_pagination_mode'));
					?>
				</select></div>
				<div class="input-below input-warning red-warning"><?php echo get_input_errors()['listing_pagination_mode']; ?></div>
				<div class="input-below"><?php _se('What pagination method should be used.'); ?></div>
			</div>
			
			<div class="input-label">
				<label for="theme_image_listing_sizing"><?php _se('Image listing size'); ?></label>
				<div class="c5 phablet-c1"><select type="text" name="theme_image_listing_sizing" id="theme_image_listing_sizing" class="text-input">
					<?php
						echo CHV\Render\get_select_options_html(['fluid' => _s('Fluid'), 'fixed' => _s('Fixed')], get_safe_post() ? get_safe_post()['theme_image_listing_sizing'] : CHV\Settings::get('theme_image_listing_sizing'), CHV\Settings::get('theme_image_listing_sizing'));
					?>
				</select></div>
				<div class="input-below input-warning red-warning clear-both"><?php echo get_input_errors()['theme_image_listing_sizing']; ?></div>
				<div class="input-below"><?php _se('Both methods use a fixed width but fluid method uses automatic heights.'); ?></div>
			</div>
			
			<hr class="line-separator"></hr>
			
			<div class="input-label">
				<label><?php _se('Listing columns number'); ?></label>
				<div class="input-below"><?php _se('Here you can set how many columns are used based on each target device.'); ?></div>
				<div class="overflow-auto margin-bottom-10 margin-top-10">
					<label for="listing_columns_phone" class="c2 float-left input-line-height"><?php _se('Phone'); ?></label>
					<input type="number" name="listing_columns_phone" id="listing_columns_phone" class="text-input c2" value="<?php echo CHV\Settings::get('listing_columns_phone', true); ?>" placeholder="<?php echo CHV\Settings::getDefault('listing_columns_phone', true); ?>" pattern="\d*" min="1" max="7" required>
				</div>
				<div class="overflow-auto margin-bottom-10">
					<label for="listing_columns_phablet" class="c2 float-left input-line-height"><?php _se('Phablet'); ?></label>
					<input type="number" name="listing_columns_phablet" id="listing_columns_phablet" class="text-input c2" value="<?php echo CHV\Settings::get('listing_columns_phablet', true); ?>" placeholder="<?php echo CHV\Settings::getDefault('listing_columns_phablet', true); ?>" pattern="\d*" min="1" max="8" required>
				</div>
				<div class="overflow-auto margin-bottom-10">
					<label for="listing_columns_tablet" class="c2 float-left input-line-height"><?php _se('Tablet'); ?></label>
					<input type="number" name="listing_columns_tablet" id="listing_columns_tablet" class="text-input c2" value="<?php echo CHV\Settings::get('listing_columns_tablet', true); ?>" placeholder="<?php echo CHV\Settings::getDefault('listing_columns_tablet', true); ?>" pattern="\d*" min="1" max="8" required>
				</div>
				<div class="overflow-auto margin-bottom-10">
					<label for="listing_columns_laptop" class="c2 float-left input-line-height"><?php _se('Laptop'); ?></label>
					<input type="number" name="listing_columns_laptop" id="listing_columns_laptop" class="text-input c2" value="<?php echo CHV\Settings::get('listing_columns_laptop', true); ?>" placeholder="<?php echo CHV\Settings::getDefault('listing_columns_laptop', true); ?>" pattern="\d*" min="1" max="8" required>
				</div>
				<div class="overflow-auto margin-bottom-10">
					<label for="listing_columns_desktop" class="c2 float-left input-line-height"><?php _se('Desktop'); ?></label>
					<input type="number" name="listing_columns_desktop" id="listing_columns_desktop" class="text-input c2" value="<?php echo CHV\Settings::get('listing_columns_desktop', true); ?>" placeholder="<?php echo CHV\Settings::getDefault('listing_columns_desktop', true); ?>" pattern="\d*" min="1" max="8" required>
				</div>
				<div class="input-below input-warning red-warning"><?php echo get_input_errors()['listing_columns']; ?></div>
			</div>
			<?php } ?>
			
			<?php if(get_settings()['key'] == 'theme') { ?>
			<p><?php echo _se('Put your themes in the %s folder', G_APP_PATH_THEMES); ?></p>
			<div class="input-label c5 phablet-c1">
				<label for="theme"><?php _se("Theme"); ?></label>
				<?php
					$themes = [];
					foreach(scandir(G_APP_PATH_THEMES) as $v) {
						if(is_dir(G_APP_PATH_THEMES . DIRECTORY_SEPARATOR . $v) and !in_array($v, ['.', '..'])) {
							$themes[$v] = $v;
						}
					}
				?>
				<select type="text" name="theme" id="theme" class="text-input">
					<?php
						echo CHV\Render\get_select_options_html($themes, CHV\Settings::get('theme'));
					?>
				</select>
			</div>
			
			<div class="input-label">
				<label for="theme_tone"><?php _se('Tone'); ?></label>
				<div class="c5 phablet-c1"><select type="text" name="theme_tone" id="theme_tone" class="text-input">
					<?php
						echo CHV\Render\get_select_options_html(['light' => _s('Light'), 'dark' => _s('Dark')], get_safe_post() ? get_safe_post()['theme_tone'] : CHV\Settings::get('theme_tone'), CHV\Settings::get('theme_tone'));
					?>
				</select></div>
				<div class="input-below input-warning red-warning clear-both"><?php echo get_input_errors()['theme_tone']; ?></div>
			</div>
			
			<div class="input-label">
				<label for="theme_main_color"><?php _se('Main color'); ?></label>
				<div class="c4"><input type="text" name="theme_main_color" id="theme_main_color" class="text-input" value="<?php echo CHV\Settings::get('theme_main_color', true); ?>" placeholder="#00A7DA" pattern="#?([\da-fA-F]{2})([\da-fA-F]{2})([\da-fA-F]{2})" title="<?php _se('Hexadecimal color value'); ?>" rel="toolTip" data-tipTip="right"></div>
				<div class="input-below input-warning red-warning"><?php echo get_input_errors()['theme_main_color']; ?></div>
				<div class="input-below"><?php _se('Use this to set the main theme color. Value must be in <a href="%s" target="_blank">hex format</a>.', 'http://www.color-hex.com/'); ?></div>
			</div>
			
			<div class="input-label">
				<label for="theme_top_bar_color"><?php _se('Top bar color'); ?></label>
				<div class="c5 phablet-c1"><select type="text" name="theme_top_bar_color" id="theme_top_bar_color" class="text-input">
					<?php
						echo CHV\Render\get_select_options_html(['black' => _s('Black'), 'white' => _s('White')], get_safe_post() ? get_safe_post()['theme_top_bar_color'] : CHV\Settings::get('theme_top_bar_color'), CHV\Settings::get('theme_top_bar_color'));
					?>
				</select></div>
				<div class="input-below input-warning red-warning clear-both"><?php echo get_input_errors()['theme_top_bar_color']; ?></div>
				<div class="input-below"><?php _se('If you set this to "white" the top bar and all the black tones will be changed to white tones.'); ?></div>
			</div>
			
			<div class="input-label">
				<label for="theme_top_bar_button_color"><?php _se('Top bar button color'); ?></label>
				<div class="c5 phablet-c1"><select type="text" name="theme_top_bar_button_color" id="theme_top_bar_button_color" class="text-input">
					<?php
						echo CHV\Render\get_select_options_html([
							'blue'	=> _s('Blue'),
							'green'	=> _s('Green'),
							'orange'=> _s('Orange'),
							'red'	=> _s('Red'),
							'grey'	=> _s('Grey'),
							'black'	=> _s('Black'),
							'white'	=> _s('White'),
							'default'	=> _s('Default'),
						], get_safe_post() ? get_safe_post()['theme_top_bar_button_color'] : CHV\Settings::get('theme_top_bar_button_color'), CHV\Settings::get('theme_top_bar_button_color'));
					?>
				</select></div>
				<div class="input-below input-warning red-warning clear-both"><?php echo get_input_errors()['theme_top_bar_button_color']; ?></div>
				<div class="input-below"><?php _se('Color for the top bar buttons like the "Create account" button.'); ?></div>
			</div>
			
			<hr class="line-separator"></hr>
			
			<?php
				if(!is_writable(CHV_PATH_CONTENT_IMAGES_SYSTEM)) {
			?>
			<p class="highlight"><?php _se("Warning: Can't write in %s", CHV_PATH_CONTENT_IMAGES_SYSTEM); ?></p>
			<?php
				}
			?>
			
			<div class="input-label">
				<label for="logo_vector_enable"><?php _se('Enable vector logo'); ?></label>
				<div class="c5 phablet-c1"><select type="text" name="logo_vector_enable" id="logo_vector_enable" class="text-input" data-combo="logo-vector-combo">
					<?php
						echo CHV\Render\get_select_options_html([1 => _s('Enabled'), 0 => _s('Disabled')], get_safe_post() ? get_safe_post()['logo_vector_enable'] : CHV\Settings::get('logo_vector_enable'));
					?>
				</select></div>
				<div class="input-below input-warning red-warning clear-both"><?php echo get_input_errors()['logo_vector_enable']; ?></div>
				<div class="input-below"><?php _se('Enable vector logo for high quality logo in devices with high pixel density.'); ?></div>
			</div>
			<div id="logo-vector-combo">
				<div data-combo-value="1" class="switch-combo c9 phablet-c1<?php if((get_safe_post() ? get_safe_post()['logo_vector_enable'] : CHV\Settings::get('logo_vector_enable')) != 1) echo ' soft-hidden'; ?>">
					<div class="input-label">
						<label for="logo_vector"><?php _se('Vector logo image'); ?></label>
						<div class="transparent-canvas dark margin-bottom-10" style="max-width: 200px;"><img class="display-block" width="100%" src="<?php echo CHV\get_system_image_url(CHV\Settings::get('logo_vector')) . '?' . G\random_string(8); ?>"></div>
						<div class="c5 phablet-c1">
							<input id="logo_vector" name="logo_vector" type="file" accept="image/svg">
						</div>
						<div class="input-below input-warning red-warning"><?php echo get_input_errors()['logo_vector']; ?></div>
						<div class="input-below"><?php _se('Vector version or your website logo in SVG format.'); ?></div>
					</div>
				</div>
			</div>
			
			<div class="input-label">
				<label for="logo_image"><?php _se('Raster logo image'); ?></label>
				<div class="transparent-canvas dark margin-bottom-10" style="max-width: 200px;"><img class="display-block" width="100%" src="<?php echo CHV\get_system_image_url(CHV\Settings::get('logo_image')) . '?' . G\random_string(8); ?>"></div>
				<div class="c5 phablet-c1">
					<input id="logo_image" name="logo_image" type="file" accept="image/*">
				</div>
				<div class="input-below input-warning red-warning"><?php echo get_input_errors()['logo_image']; ?></div>
				<div class="input-below"><?php _se('Bitmap version or your website logo. PNG format is recommended.'); ?></div>
			</div>
			
			<div class="input-label">
				<label for="theme_logo_height"><?php _se('Logo height'); ?></label>
				<div class="c4"><input type="text" name="theme_logo_height" id="theme_logo_height" class="text-input" value="<?php echo CHV\Settings::get('theme_logo_height', true); ?>" placeholder="<?php _se('No value'); ?>"></div>
				<div class="input-below input-warning red-warning"><?php echo get_input_errors()['theme_logo_height']; ?></div>
				<div class="input-below"><?php _se('Use this to set the logo height if needed.'); ?></div>
			</div>
			
			<hr class="line-separator"></hr>
			
			<div class="input-label">
				<label for="favicon_image"><?php _se('Favicon image'); ?></label>
				<div class="transparent-canvas dark margin-bottom-10" style="max-width: 100px;"><img class="display-block" width="100%" src="<?php echo CHV\get_system_image_url(CHV\Settings::get('favicon_image')) . '?' . G\random_string(8);  ?>"></div>
				<div class="c5 phablet-c1">
					<input id="favicon_image" name="favicon_image" type="file" accept="image/*">
				</div>
				<div class="input-below input-warning red-warning"><?php echo get_input_errors()['favicon_image']; ?></div>
				<div class="input-below"><?php _se('Favicon image. Image must have same width and height.'); ?></div>
			</div>
			
			<hr class="line-separator"></hr>
			
			<div class="input-label">
				<label for="theme_download_button"><?php _se('Enable download button'); ?></label>
				<div class="c5 phablet-c1"><select type="text" name="theme_download_button" id="theme_download_button" class="text-input">
					<?php
						echo CHV\Render\get_select_options_html([1 => _s('Enabled'), 0 => _s('Disabled')], CHV\Settings::get('theme_download_button'));
					?>
				</select></div>
				<div class="input-below"><?php _se('Enable this if you want to show the image download button.'); ?></div>
			</div>
			
			<div class="input-label">
				<label for="theme_image_right_click"><?php _se('Enable right click on image'); ?></label>
				<div class="c5 phablet-c1"><select type="text" name="theme_image_right_click" id="theme_image_right_click" class="text-input">
					<?php
						echo CHV\Render\get_select_options_html([1 => _s('Enabled'), 0 => _s('Disabled')], CHV\Settings::get('theme_image_right_click'));
					?>
				</select></div>
				<div class="input-below"><?php _se('Enable this if you want to allow right click on image viewer page.'); ?></div>
			</div>
			
			<hr class="line-separator"></hr>
			
			<div class="input-label">
				<label for="theme_show_exif_data"><?php _se('Enable show Exif data'); ?></label>
				<div class="c5 phablet-c1"><select type="text" name="theme_show_exif_data" id="theme_show_exif_data" class="text-input">
					<?php
						echo CHV\Render\get_select_options_html([1 => _s('Enabled'), 0 => _s('Disabled')], CHV\Settings::get('theme_show_exif_data'));
					?>
				</select></div>
				<div class="input-below"><?php _se('Enable this if you want to show image Exif data.'); ?></div>
			</div>
			
			<hr class="line-separator"></hr>
			
			<div class="input-label">
				<label for="theme_show_social_share"><?php _se('Enable social share'); ?></label>
				<div class="c5 phablet-c1"><select type="text" name="theme_show_social_share" id="theme_show_social_share" class="text-input">
					<?php
						echo CHV\Render\get_select_options_html([1 => _s('Enabled'), 0 => _s('Disabled')], CHV\Settings::get('theme_show_social_share'));
					?>
				</select></div>
				<div class="input-below"><?php _se('Enable this if you want to show social network buttons to share content.'); ?></div>
			</div>
			
			<div class="input-label">
				<label for="theme_show_embed_content"><?php _se('Enable embed codes (content)'); ?></label>
				<div class="c5 phablet-c1"><select type="text" name="theme_show_embed_content" id="theme_show_embed_content" class="text-input">
					<?php
						echo CHV\Render\get_select_options_html([1 => _s('Enabled'), 0 => _s('Disabled')], CHV\Settings::get('theme_show_embed_content'));
					?>
				</select></div>
				<div class="input-below"><?php _se('Enable this if you want to show embed codes for the content.'); ?></div>
			</div>
			
			<div class="input-label">
				<label for="theme_show_embed_uploader"><?php _se('Enable embed codes (uploader)'); ?></label>
				<div class="c5 phablet-c1"><select type="text" name="theme_show_embed_uploader" id="theme_show_embed_uploader" class="text-input">
					<?php
						echo CHV\Render\get_select_options_html([1 => _s('Enabled'), 0 => _s('Disabled')], CHV\Settings::get('theme_show_embed_uploader'));
					?>
				</select></div>
				<div class="input-below"><?php _se('Enable this if you want to show embed codes when upload gets completed.'); ?></div>
			</div>
			
			<hr class="line-separator"></hr>
			
			<div class="input-label">
				<label for="theme_nsfw_upload_checkbox"><?php _se('Not safe content checkbox in uploader'); ?></label>
				<div class="c5 phablet-c1"><select type="text" name="theme_nsfw_upload_checkbox" id="theme_nsfw_upload_checkbox" class="text-input">
					<?php
						echo CHV\Render\get_select_options_html([1 => _s('Enabled'), 0 => _s('Disabled')], CHV\Settings::get('theme_nsfw_upload_checkbox'));
					?>
				</select></div>
				<div class="input-below"><?php _se('Enable this if you want to show a checkbox to indicate not safe content upload.'); ?></div>
			</div>
				
			<hr class="line-separator"></hr>
			
			<div class="input-label">
				<label for="theme_custom_css_code"><?php _se('Custom CSS code'); ?></label>
				<div class="c12 phablet-c1"><textarea type="text" name="theme_custom_css_code" id="theme_custom_css_code" class="text-input r4" placeholder="<?php _se('Put your custom CSS code here. It will be placed as <style> just before the closing </head> tag.'); ?>"><?php echo CHV\Settings::get('theme_custom_css_code', true); ?></textarea></div>
			</div>
			
			<div class="input-label">
				<label for="theme_custom_js_code"><?php _se('Custom JS code'); ?></label>
				<div class="c12 phablet-c1"><textarea type="text" name="theme_custom_js_code" id="theme_custom_js_code" class="text-input r4" placeholder="<?php _se('Put your custom JS code here. It will be placed as <script> just before the closing </head> tag.'); ?>"><?php echo CHV\Settings::get('theme_custom_js_code', true); ?></textarea></div>
			</div>
			
			<?php } ?>
			
			<?php if(get_settings()['key'] == 'homepage') { ?>
			<div class="input-label">
				<label for="homepage_style"><?php _se('Style'); ?></label>
				<div class="c5 phablet-c1"><select type="text" name="homepage_style" id="homepage_style" class="text-input" data-combo="home-style-combo">
					<?php
						echo CHV\Render\get_select_options_html([
							'landing'		=> _s('Landing page'),
							'split'			=> _s('Split landing + images'),
							'route_explore' => _s('Route explore')
						], CHV\Settings::get('homepage_style'));
					?>
				</select></div>
				<div class="input-below input-warning red-warning"><?php echo get_input_errors()['homepage_style']; ?></div>
				<div class="input-below"><?php _se('Select the homepage style. To customize it further edit app/themes/%s/views/index.php', CHV\Settings::get('theme')); ?></div>
			</div>
			<div id="home-style-combo">
				<div data-combo-value="landing split" class="switch-combo<?php if(!in_array((get_safe_post() ? get_safe_post()['homepage_style'] : CHV\Settings::get('homepage_style')), ['split', 'landing'])) echo ' soft-hidden'; ?>">
					<div class="input-label">
						<label for="homepage_cover_image"><?php _se('Cover image'); ?></label>
						<div class="transparent-canvas dark margin-bottom-10" style="max-width: 200px;"><img class="display-block" width="100%" src="<?php echo CHV\get_system_image_url(CHV\Settings::get('homepage_cover_image')); ?>"></div>
						<div class="c5 phablet-c1">
							<input id="homepage_cover_image" name="homepage_cover_image" type="file" accept="image/*">
						</div>
						<div class="input-below input-warning red-warning"><?php echo get_input_errors()['homepage_cover_image']; ?></div>
					</div>
					<?php if(CHV\Settings::get('logo_vector_enable')) { ?>
					
					<hr class="line-separator"></hr>
					
					<div class="input-label">
						<label for="logo_vector_homepage"><?php _se('Vector logo image'); ?> <span class="optional"><?php _se('optional'); ?></span></label>
						<div class="transparent-canvas dark margin-bottom-10" style="max-width: 200px;"><img class="display-block" width="100%" src="<?php echo CHV\get_system_image_url(CHV\Settings::get('logo_vector_homepage')) . '?' . G\random_string(8); ?>"></div>
						<div class="c5 phablet-c1">
							<input id="logo_vector_homepage" name="logo_vector_homepage" type="file" accept="image/svg">
						</div>
						<div class="input-below input-warning red-warning"><?php echo get_input_errors()['logo_vector_homepage']; ?></div>
						<div class="input-below"><?php _se('Vector version or your website logo in SVG format (only for homepage).'); ?></div>
					</div>
					<?php } // landing logo vector ?>
					<div class="input-label">
						<label for="logo_image_homepage"><?php _se('Raster logo image'); ?> <span class="optional"><?php _se('optional'); ?></span></label>
						<div class="transparent-canvas dark margin-bottom-10" style="max-width: 200px;"><img class="display-block" width="100%" src="<?php echo CHV\get_system_image_url(CHV\Settings::get('logo_image_homepage')) . '?' . G\random_string(8); ?>"></div>
						<div class="c5 phablet-c1">
							<input id="logo_image_homepage" name="logo_image_homepage" type="file" accept="image/*">
						</div>
						<div class="input-below input-warning red-warning"><?php echo get_input_errors()['logo_image_homepage']; ?></div>
						<div class="input-below"><?php _se('Bitmap version or your website logo (only for homepage). PNG format is recommended.'); ?></div>
					</div>
					
					<hr class="line-separator"></hr>
					
					<div class="input-label">
						<label for="homepage_title_html"><?php _se('Title'); ?></label>
						<div class="c12 phablet-c1"><textarea type="text" name="homepage_title_html" id="homepage_title_html" class="text-input r2 resize-vertical" placeholder="<?php echo G\safe_html(_s('This will be added inside the homepage %s tag. Leave it blank to use the default contents.', '<h1>')); ?>"><?php echo CHV\Settings::get('homepage_title_html'); ?></textarea></div>
					</div>
					
					<div class="input-label">
						<label for="homepage_paragraph_html"><?php _se('Paragraph'); ?></label>
						<div class="c12 phablet-c1"><textarea type="text" name="homepage_paragraph_html" id="homepage_paragraph_html" class="text-input r2 resize-vertical" placeholder="<?php echo G\safe_html(_s('This will be added inside the homepage %s tag. Leave it blank to use the default contents.', '<p>')); ?>"><?php echo CHV\Settings::get('homepage_paragraph_html'); ?></textarea></div>
					</div>
					
					<hr class="line-separator"></hr>
					
					<div class="input-label">
						<label for="homepage_cta_color"><?php _se('Call to action button color'); ?></label>
						<div class="c5 phablet-c1"><select type="text" name="homepage_cta_color" id="homepage_cta_color" class="text-input">
							<?php
								echo CHV\Render\get_select_options_html([
									'blue'	=> _s('Blue'),
									'green'	=> _s('Green'),
									'orange'=> _s('Orange'),
									'red'	=> _s('Red'),
									'grey'	=> _s('Grey'),
									'black'	=> _s('Black'),
									'white'	=> _s('White'),
									'default'	=> _s('Default'),
								], get_safe_post() ? get_safe_post()['homepage_cta_color'] : CHV\Settings::get('homepage_cta_color'), CHV\Settings::get('homepage_cta_color'));
							?>
						</select></div>
						<div class="input-below input-warning red-warning clear-both"><?php echo get_input_errors()['homepage_cta_color']; ?></div>
						<div class="input-below"><?php _se('Color of the homepage call to action button.'); ?></div>
					</div>
					
					<div class="input-label">
						<label for="homepage_cta_outline"><?php _se('Call to action outline style button'); ?></label>
						<div class="c5 phablet-c1"><select type="text" name="homepage_cta_outline" id="homepage_cta_outline" class="text-input">
							<?php
								echo CHV\Render\get_select_options_html([1 => _s('Enabled'), 0 => _s('Disabled')], CHV\Settings::get('homepage_cta_outline'));
							?>
						</select></div>
						<div class="input-below input-warning red-warning clear-both"><?php echo get_input_errors()['homepage_cta_outline']; ?></div>
						<div class="input-below"><?php _se('Enable this to use outline style for the homepage call to action button.'); ?></div>
					</div>
					
					<div class="input-label">
						<label for="homepage_cta_fn"><?php _se('Call to action functionality'); ?></label>
						<div class="c5 phablet-c1"><select type="text" name="homepage_cta_fn" id="homepage_cta_fn" class="text-input" data-combo="cta-fn-combo">
							<?php
								echo CHV\Render\get_select_options_html([
									'cta-upload'=> _s('Trigger uploader'),
									'cta-link'	=> _s('Open URL')
								], CHV\Settings::get('homepage_cta_fn'));
							?>
						</select></div>
						<div class="input-warning red-warning"><?php echo get_input_errors()['homepage_cta_fn']; ?></div>
					</div>
					<div id="cta-fn-combo">
						<div data-combo-value="cta-link" class="switch-combo<?php if((get_safe_post() ? get_safe_post()['homepage_cta_fn'] : CHV\Settings::get('homepage_cta_fn')) !== 'cta-link') echo ' soft-hidden'; ?>">
							<div class="input-label">
								<label for="homepage_cta_fn_extra"><?php _se('Call to action URL'); ?></label>
								<div class="c9 phablet-c1"><input type="text" name="homepage_cta_fn_extra" id="homepage_cta_fn_extra" class="text-input" value="<?php echo CHV\Settings::get('homepage_cta_fn_extra', true); ?>" placeholder="<?php _se('Enter an absolute or relative URL'); ?>" <?php echo ((get_safe_post() ? get_safe_post()['homepage_cta_fn'] : CHV\Settings::get('homepage_cta_fn')) !== 'cta-link') ? 'data-required' : 'required'; ?>></div>
								<div class="input-below input-warning red-warning"><?php echo get_input_errors()['homepage_cta_fn_extra']; ?></div>
								<div class="input-below"><?php _se('A relative URL like %r will be mapped to %l', ['%r' => 'page/welcome', '%l' => G\get_base_url('page/welcome')]); ?></div>
							</div>
						</div>
					</div>

					<div class="input-label">
						<label for="homepage_cta_html"><?php _se('Call to action HTML'); ?></label>
						<div class="c12 phablet-c1"><textarea type="text" name="homepage_cta_html" id="homepage_cta_html" class="text-input r2 resize-vertical" placeholder="<?php echo G\safe_html(_s('This will be added inside the call to action <a> tag. Leave it blank to use the default contents.')); ?>"><?php echo CHV\Settings::get('homepage_cta_html'); ?></textarea></div>
					</div>
					
				</div>
				<div data-combo-value="split" class="switch-combo<?php if((get_safe_post() ? get_safe_post()['homepage_style'] : CHV\Settings::get('homepage_style')) !== 'split') echo ' soft-hidden'; ?>">
					<div class="input-label">
						<label for="homepage_uids"><?php _se('User IDs'); ?></label>
						<div class="c4"><input type="text" name="homepage_uids" id="homepage_uids" class="text-input" value="<?php echo CHV\Settings::get('homepage_uids', true); ?>" placeholder="<?php _se('No value'); ?>" rel="tooltip" title="<?php _se('Your user id is: %s', CHV\Login::getUser()['id']); ?>" data-tipTip="right"></div>
						<div class="input-below input-warning red-warning"><?php echo get_input_errors()['homepage_uids']; ?></div>
						<div class="input-below"><?php _se('Comma-separated list of target user IDs to show images on homepage. Leave it blank or zero to display all recent images.'); ?></div>
					</div>
				</div>
			</div>
			
			<?php } ?>
			
			
			<?php if(get_settings()['key'] == 'banners') { ?>
			<p><?php _se('Here you can set the codes for the predefined ad spaces.'); ?></p>
			<h3 class="margin-top-20"><?php _se('Homepage'); ?></h3>
			<div class="input-label">
				<label for="banner_home_before_cover"><?php _se('Before cover (homepage)'); ?></label>
				<div class="c12 phablet-c1"><textarea type="text" name="banner_home_before_cover" class="text-input r3"><?php echo CHV\get_banner_code('banner_home_before_cover'); ?></textarea></div>
			</div>
			<div class="input-label">
				<label for="banner_home_after_cover"><?php _se('After cover (homepage)'); ?></label>
				<div class="c12 phablet-c1"><textarea type="text" name="banner_home_after_cover" class="text-input r3"><?php echo CHV\get_banner_code('banner_home_after_cover'); ?></textarea></div>
			</div>
			<div class="input-label">
				<label for="banner_home_after_listing"><?php _se('After listing (homepage)'); ?></label>
				<div class="c12 phablet-c1"><textarea type="text" name="banner_home_after_listing" class="text-input r3"><?php echo CHV\get_banner_code('banner_home_after_listing'); ?></textarea></div>
			</div>
			<hr class="line-separator"></hr>
			<h3 class="margin-top-20"><?php _se('Listings'); ?></h3>
			<div class="input-label">
				<label for="banner_listing_before_pagination"><?php _se('Before pagination'); ?></label>
				<div class="c12 phablet-c1"><textarea type="text" name="banner_listing_before_pagination" class="text-input r3"><?php echo CHV\get_banner_code('banner_listing_before_pagination'); ?></textarea></div>
			</div>
			<div class="input-label">
				<label for="banner_listing_after_pagination"><?php _se('After pagination'); ?></label>
				<div class="c12 phablet-c1"><textarea type="text" name="banner_listing_after_pagination" class="text-input r3"><?php echo CHV\get_banner_code('banner_listing_after_pagination'); ?></textarea></div>
			</div>
			
			<hr class="line-separator"></hr>
			
			<h3 class="margin-top-20"><?php _se('Content (image and album)'); ?></h3>
			<div class="input-label">
				<label for="banner_content_tab-about_column"><?php _se('Tab about column'); ?></label>
				<div class="c12 phablet-c1"><textarea type="text" name="banner_content_tab-about_column" class="text-input r3"><?php echo CHV\get_banner_code('banner_content_tab-about_column'); ?></textarea></div>
			</div>
			<div class="input-label">
				<label for="banner_content_before_comments"><?php _se('Before comments'); ?></label>
				<div class="c12 phablet-c1"><textarea type="text" name="banner_content_before_comments" class="text-input r3"><?php echo CHV\get_banner_code('banner_content_before_comments'); ?></textarea></div>
			</div>
			
			<hr class="line-separator"></hr>
			
			<h3 class="margin-top-20"><?php _se('Image page'); ?></h3>
			<div class="input-label">
				<label for="banner_image_image-viewer_top"><?php _se('Inside viewer top (image page)'); ?></label>
				<div class="c12 phablet-c1"><textarea type="text" name="banner_image_image-viewer_top" class="text-input r3"><?php echo CHV\get_banner_code('banner_image_image-viewer_top'); ?></textarea></div>
				<div class="input-below"><?php _se('Expected banner size 728x90'); ?></div>
			</div>
			<div class="input-label">
				<label for="banner_image_image-viewer_foot"><?php _se('Inside viewer foot (image page)'); ?></label>
				<div class="c12 phablet-c1"><textarea type="text" name="banner_image_image-viewer_foot" class="text-input r3"><?php echo CHV\get_banner_code('banner_image_image-viewer_foot'); ?></textarea></div>
				<div class="input-below"><?php _se('Expected banner size 728x90'); ?></div>
			</div>
			<div class="input-label">
				<label for="banner_image_after_image-viewer"><?php _se('After image viewer (image page)'); ?></label>
				<div class="c12 phablet-c1"><textarea type="text" name="banner_image_after_image-viewer" class="text-input r3"><?php echo CHV\get_banner_code('banner_image_after_image-viewer'); ?></textarea></div>
			</div>
			<div class="input-label">
				<label for="banner_image_before_header"><?php _se('Before header (image page)'); ?></label>
				<div class="c12 phablet-c1"><textarea type="text" name="banner_image_before_header" class="text-input r3"><?php echo CHV\get_banner_code('banner_image_before_header'); ?></textarea></div>
			</div>
			<div class="input-label">
				<label for="banner_image_after_header"><?php _se('After header (image page)'); ?></label>
				<div class="c12 phablet-c1"><textarea type="text" name="banner_image_after_header" class="text-input r3"><?php echo CHV\get_banner_code('banner_image_after_header'); ?></textarea></div>
			</div>
			<div class="input-label">
				<label for="banner_image_footer"><?php _se('Footer (image page)'); ?></label>
				<div class="c12 phablet-c1"><textarea type="text" name="banner_image_footer" class="text-input r3"><?php echo CHV\get_banner_code('banner_image_footer'); ?></textarea></div>
			</div>
			
			<hr class="line-separator"></hr>
			
			<h3 class="margin-top-20"><?php _se('Album page'); ?></h3>
			<div class="input-label">
				<label for="banner_album_before_header"><?php _se('Before header (album page)'); ?></label>
				<div class="c12 phablet-c1"><textarea type="text" name="banner_album_before_header" class="text-input r3"><?php echo CHV\get_banner_code('banner_album_before_header'); ?></textarea></div>
			</div>
			<div class="input-label">
				<label for="banner_album_after_header"><?php _se('After header (album page)'); ?></label>
				<div class="c12 phablet-c1"><textarea type="text" name="banner_album_after_header" class="text-input r3"><?php echo CHV\get_banner_code('banner_album_after_header'); ?></textarea></div>
			</div>
			
			<hr class="line-separator"></hr>
			
			<h3 class="margin-top-20"><?php _se('User profile page'); ?></h3>
			<div class="input-label">
				<label for="banner_user_after_top"><?php _se('After top (user profile)'); ?></label>
				<div class="c12 phablet-c1"><textarea type="text" name="banner_user_after_top" class="text-input r3"><?php echo CHV\get_banner_code('banner_user_after_top'); ?></textarea></div>
			</div>
			<div class="input-label">
				<label for="banner_user_before_listing"><?php _se('Before listing (user profile)'); ?></label>
				<div class="c12 phablet-c1"><textarea type="text" name="banner_user_before_listing" class="text-input r3"><?php echo CHV\get_banner_code('banner_user_before_listing'); ?></textarea></div>
			</div>
			
			<hr class="line-separator"></hr>
			
			<h3 class="margin-top-20"><?php _se('Explore page'); ?></h3>
			<div class="input-label">
				<label for="banner_explore_after_top"><?php _se('After top (explore page)'); ?></label>
				<div class="c12 phablet-c1"><textarea type="text" name="banner_explore_after_top" class="text-input r3"><?php echo CHV\get_banner_code('banner_explore_after_top'); ?></textarea></div>
			</div>
			
			<?php } ?>
			
			<?php if(get_settings()['key'] == 'system') { ?>
			<div class="input-label">
				<label for="crypt_salt"><?php _se('Crypt salt'); ?></label>
				<div class="c5 phablet-c1"><input type="text" name="crypt_salt" id="crypt_salt" class="text-input" value="<?php echo CHV\Settings::get('crypt_salt'); ?>" disabled></div>
				<div class="input-below"><?php _se('This is the salt used to convert numeric ID to alphanumeric. It was generated on install.'); ?></div>
			</div>
			<div class="input-label">
				<label for="minify_enable"><?php _se('Minify code'); ?></label>
				<div class="c5 phablet-c1"><select type="text" name="minify_enable" id="minify_enable" class="text-input">
					<?php
						echo CHV\Render\get_select_options_html([1 => _s('Enabled'), 0 => _s('Disabled')], CHV\Settings::get('minify_enable'));
					?>
				</select></div>
				<div class="input-below"><?php _se('Enable this if you want to auto minify CSS and JS code.'); ?></div>
			</div>
			<div class="input-label">
				<label for="website_search"><?php _se('Maintenance'); ?></label>
				<div class="c5 phablet-c1"><select type="text" name="maintenance" id="maintenance" class="text-input">
					<?php
						echo CHV\Render\get_select_options_html([0 => _s('Disabled'), 1 => _s('Enabled')], CHV\Settings::get('maintenance'));
					?>
				</select></div>
				<div class="input-below"><?php _se("When enabled the website will show a maintenance message. This setting doesn't affect administrators."); ?></div>
			</div>
			<hr class="line-separator"></hr>
			<div class="input-label">
				<label for="error_reporting"><?php _se('PHP error reporting'); ?></label>
				<div class="c5 phablet-c1"><select type="text" name="error_reporting" id="error_reporting" class="text-input">
					<?php
						echo CHV\Render\get_select_options_html([1 => _s('Enabled'), 0 => _s('Disabled')], CHV\Settings::get('error_reporting'));
					?>
				</select></div>
				<div class="input-below"><?php _se('Enable this if you want to print errors generated by PHP <a %s>error_reporting()</a>. This should be disabled in production.', 'href="http://php.net/manual/en/function.error-reporting.php" target="_blank"'); ?></div>
			</div>
			<div class="input-label">
				<label for="debug_level"><?php _se('Debug level'); ?></label>
				<div class="c5 phablet-c1"><select type="text" name="debug_level" id="debug_level" class="text-input" disabled>
					<?php
						echo CHV\Render\get_select_options_html([0 => _s('None'), 1 => _s('Error log'), 2 => _s('Print errors without error log'), 3 => _s('Print and log errors')], G\get_app_setting('debug_level'));
					?>
				</select></div>
				<div class="input-below"><?php _se('To configure the debug level check the <a %s>debug documentation</a>. Default level is "Error log" (1).', 'href="https://goo.gl/UQtZEf" target="_blank"'); ?></div>
			</div>
			
			<?php } ?>
			
			<?php if(get_settings()['key'] == 'languages') { ?>
			<div class="input-label">
				<label for="default_language"><?php _se('Default language'); ?></label>
				<div class="c5 phablet-c1"><select type="text" name="default_language" id="default_language" class="text-input">
					<?php
						foreach(CHV\get_available_languages() as $k => $v) {
							$selected_lang = $k == CHV\Settings::get('default_language') ? " selected" : "";
							echo '<option value="'.$k.'"'.$selected_lang.'>'.$v["name"].'</option>'."\n";
						}
					?>
				</select></div>
				<div class="input-below"><?php _se('Default base language to use.'); ?></div>
			</div>
			
			<div class="input-label">
				<label for="auto_language"><?php _se('Auto language'); ?></label>
				<div class="c5 phablet-c1"><select type="text" name="auto_language" id="auto_language" class="text-input">
					<?php
						echo CHV\Render\get_select_options_html([1 => _s('Enabled'), 0 => _s('Disabled')], CHV\Settings::get('auto_language'));
					?>
				</select></div>
				<div class="input-below"><?php _se("Enable this if you want to automatically detect and set the right language for each user."); ?></div>
			</div>
			
			<div class="input-label">
				<label for="language_chooser_enable"><?php _se('Language chooser'); ?></label>
				<div class="c5 phablet-c1"><select type="text" name="language_chooser_enable" id="language_chooser_enable" class="text-input" data-combo="language-enable-combo">
					<?php
						echo CHV\Render\get_select_options_html([1 => _s('Enabled'), 0 => _s('Disabled')], CHV\Settings::get('language_chooser_enable'));
					?>
				</select></div>
				<div class="input-below"><?php _se("Enable this if you want to allow language selection."); ?></div>
			</div>
			
			<?php if(count(CHV\get_available_languages()) > 0) { ?>
			<div id="language-enable-combo">
				<div data-combo-value="1" class="switch-combo<?php if((get_safe_post() ? get_safe_post()['language_chooser_enable'] == 0 : !CHV\Settings::get('language_chooser_enable'))) echo ' soft-hidden'; ?>">
					<div class="checkbox-label">
						<h4 class="input-label-label"><?php _se('Enabled languages'); ?></h4>
						<ul class="c20 phablet-c1">
							<?php
								foreach(CHV\get_available_languages() as $k => $v) {
									$lang_flag = array_key_exists($k, CHV\get_enabled_languages()) ? ' checked' : NULL;
									echo '<li class="c5 display-inline-block"><label class="display-block" for="languages_enable['.$k.']"> <input type="checkbox" name="languages_enable[]" id="languages_enable['.$k.']" value="'.$k.'"'.$lang_flag.'>'.$v['name'].'</label></li>';
								}
							?>
						</ul>
						<p class="margin-top-20"><?php _se("Unchecked languages won't be used in your website."); ?></p>
					</div>
				</div>
			</div>
			<?php } ?>
			
			<?php } ?>

			<?php
				if(get_settings()['key'] == 'external-storage') {
					$getStorages = CHV\Storage::getAll();
					$storages = [];
					if($getStorages) {
						foreach($getStorages as $k => $v) {
							$storages[$v['id']] = $v;
							
						}
					}
					$checkbox_icons = [
						0 => 'icon-checkbox-unchecked',
						1 => 'icon-checkbox-checked'
					];
					$storage_messages = [
						'is_https' => _s('Toggle this to enable or disable HTTPS'),
						'is_active'=> _s('Toggle this to enable or disable this storage')
					];
					$icon_template = '<span rel="toolTip" data-tipTip="right" title="%TITLE%" class="cursor-pointer icon %ICON%" data-checked-icon="'.$checkbox_icons[1].'" data-unchecked-icon="'.$checkbox_icons[0].'" data-action="toggle-storage-%PROP%" data-checkbox></span>';
			?>
			<script>
				$(document).ready(function() {
					CHV.obj.storages = <?php echo json_encode($storages); ?>;
					CHV.obj.storageTemplate = <?php echo json_encode(['messages' => $storage_messages, 'icon' => $icon_template, 'checkboxes' => $checkbox_icons]); ?>;
				});
			</script>
			<ul data-content="dashboard-storages-list" class="tabbed-content-list table-li margin-top-20 margin-bottom-20">
				<li class="table-li-header phone-hide">
					<span class="c2 display-table-cell padding-right-10">ID</span>
					<span class="c4 display-table-cell padding-right-10"><?php _se('Name'); ?></span>
					<span class="c4 display-table-cell padding-right-10">API</span>
					<!--<span class="c10 display-table-cell phone-hide">URL</span>-->
					<span class="c7 display-table-cell padding-right-10"><?php _se('Quota'); ?></span>
					<span class="c2 display-table-cell padding-right-10">HTTPS</span>
					<span class="c2 display-table-cell padding-right-10"><?php _se('Active'); ?></span>
					<span class="c4 display-table-cell padding-right-10"></span>
				</li>
				<?php
					$li_template = '<li data-content="storage" data-storage-id="%ID%">
					<span class="c2 display-table-cell padding-right-10" data-content="storage-id">%ID%</span>
					<span class="c4 display-table-cell padding-right-10"><a data-modal="edit" data-target="form-modal" data-storage-id="%ID%" data-content="storage-name">%NAME%</a></span>
					<span class="c4 display-table-cell padding-right-10" data-content="storage-api_name">%API_NAME%</span>
					<!--<span class="c10 display-table-cell padding-right-10" data-content="storage-url">%URL%</span>-->
					<span class="c7 display-table-cell padding-right-10" data-content="storage-usage_label">%USAGE_LABEL%</span>
					<span class="c2 display-table-cell padding-right-10" data-content="storage-https">%IS_HTTPS%</span>
					<span class="c2 display-table-cell padding-right-10" data-content="storage-active">%IS_ACTIVE%</span>
					<span class="c4 display-table-cell padding-right-10"><a href="'.G\get_base_url('search/images/?q=storage:%ID%').'" target="_blank">'._s('search content').'</a></span>
				</li>';
					
					if($storages) {
						foreach($storages as $storage) {
							$replaces = [];
							foreach($storage as $k => $v) {
								if(in_array($k, ['is_https', 'is_active'])) {
									$v = strtr($icon_template, ['%TITLE%' => $storage_messages[$k], '%ICON%' => $checkbox_icons[(int)$v], '%PROP%' => str_replace('is_', '', $k)]);
								}
								$replaces['%' . strtoupper($k) . '%'] = $v;
							}
							echo strtr($li_template, $replaces);
						}
					}
				?>
			</ul>
			<div class="hidden" data-content="storage-dashboard-template">
				<?php echo $li_template; ?>
			</div>
			<p class="font-weight-bold">
				<span class="c6 display-table-cell"><?php _se('Storage method'); ?></span>
				<span class="c3 display-table-cell"><?php _se('Disk used'); ?></span>
			</p>
			<?php foreach(get_storage_usage() as $k => $v) { ?>
			<p>
				<span class="c6 display-table-cell"><?php echo $v['label']; ?></span>
				<span class="c3 display-table-cell"><?php echo $v['formatted_size']; ?></span>
				<?php if($k == 'all') continue; ?><span class="c6 display-table-cell"><?php echo $v['link']; ?></span>
			</p>
			<?php } ?>
			<hr class="line-separator"></hr>
			<p><?php _se("Local storage is used by default or when no external storage is active."); echo ' '; _se('If you need help check the <a %s>storage documentation</a>.', 'href="https://goo.gl/jH5Dqx" target="_blank"'); ?></p>
			<div data-modal="form-modal" class="hidden" data-submit-fn="CHV.fn.storage.edit.submit" data-before-fn="CHV.fn.storage.edit.before" data-ajax-deferred="CHV.fn.storage.edit.complete" data-ajax-url="<?php echo G\get_base_url("json"); ?>">
				<span class="modal-box-title"><?php _se('Edit storage'); ?></span>
				<div class="modal-form">
					<input type="hidden" name="form-storage-id">
					<?php G\Render\include_theme_file('snippets/form_storage_edit'); ?>
				</div>
			</div>
			<?php } ?>
			
			<?php if(get_settings()['key'] == 'email') { ?>
			<div class="input-label">
				<label for="email_from_name"><?php _se('From name'); ?></label>
				<div class="c9 phablet-c1"><input type="text" name="email_from_name" id="email_from_name" class="text-input" value="<?php echo CHV\Settings::get('email_from_name', true); ?>" required></div>
				<div class="input-warning red-warning"><?php echo get_input_errors()['email_from_name']; ?></div>
				<div class="input-below"><?php _se('Sender name for emails sent to users.'); ?></div>
			</div>
			<div class="input-label">
				<label for="email_from_email"><?php _se('From email address'); ?></label>
				<div class="c9 phablet-c1"><input type="email" name="email_from_email" id="email_from_email" class="text-input" value="<?php echo CHV\Settings::get('email_from_email', true); ?>" required></div>
				<div class="input-warning red-warning"><?php echo get_input_errors()['email_from_email']; ?></div>
				<div class="input-below"><?php _se('Sender email for emails sent to users.'); ?></div>
			</div>
			<div class="input-label">
				<label for="email_incoming_email"><?php _se('Incoming email address'); ?></label>
				<div class="c9 phablet-c1"><input type="email" name="email_incoming_email" id="email_incoming_email" class="text-input" value="<?php echo CHV\Settings::get('email_incoming_email', true); ?>" required></div>
				<div class="input-warning red-warning"><?php echo get_input_errors()['email_incoming_email']; ?></div>
				<div class="input-below"><?php _se('Recipient for contact form and system alerts.'); ?></div>
			</div>
			<div class="input-label">
				<label for="email_mode"><?php _se('Email mode'); ?></label>
				<div class="c5 phablet-c1"><select type="text" name="email_mode" id="email_mode" class="text-input" data-combo="mail-combo">
					<?php
						echo CHV\Render\get_select_options_html(['smtp' => 'SMTP', 'phpmail' => 'PHP mail() func.'], get_safe_post() ? get_safe_post()['email_mode'] : CHV\Settings::get('email_mode'));
					?>
				</select></div>
				<div class="input-below input-warning red-warning clear-both"><?php echo get_input_errors()['email_mode']; ?></div>
				<div class="input-below"><?php _se('How to send emails? SMTP recommended.'); ?></div>
			</div>
			<div id="mail-combo">
				<div data-combo-value="smtp" class="switch-combo c9 phablet-c1<?php if((get_safe_post() ? get_safe_post()['email_mode'] : CHV\Settings::get('email_mode')) !== 'smtp') echo ' soft-hidden'; ?>">
					<div class="input-label">
						<label for="email_smtp_server"><?php _se('SMTP server and port'); ?></label>
						<div class="overflow-auto">
							<div class="c7 float-left">
								<input type="text" name="email_smtp_server" id="email_smtp_server" class="text-input" value="<?php echo get_safe_post() ? get_safe_post()['email_smtp_server'] : CHV\Settings::get('email_smtp_server'); ?>" placeholder="<?php _se('SMTP server'); ?>">
							</div>
							<div class="c2 float-left margin-left-10">
								<select type="text" name="email_smtp_server_port" id="email_smtp_server_port" class="text-input">
									<?php
										echo CHV\Render\get_select_options_html([25 => 25, 80 => 80, 465 => 465, 587 => 587], get_safe_post() ? get_safe_post()['email_smtp_server_port'] : CHV\Settings::get('email_smtp_server_port'));
									?>
								</select>
							</div>
						</div>
						<div class="input-below input-warning red-warning clear-both"><?php echo get_input_errors()['email_smtp_server']; ?></div>
					</div>
					<div class="input-label">
						<label for="email_smtp_server_username"><?php _se('SMTP username'); ?></label>
						<input type="text" name="email_smtp_server_username" id="email_smtp_server_username" class="text-input" value="<?php echo get_safe_post() ? get_safe_post()['email_smtp_server_username'] : CHV\Settings::get('email_smtp_server_username'); ?>">
						<div class="input-below input-warning red-warning"><?php echo get_input_errors()['email_smtp_server_username']; ?></div>
					</div>
					<div class="input-label">
						<label for="email_smtp_server_password"><?php _se('SMTP password'); ?></label>
						<input type="password" name="email_smtp_server_password" id="email_smtp_server_password" class="text-input" value="<?php echo get_safe_post() ? get_safe_post()['email_smtp_server_password'] : CHV\Settings::get('email_smtp_server_password'); ?>">
						<div class="input-below input-warning red-warning"><?php echo get_input_errors()['email_smtp_server_password']; ?></div>
					</div>
					<div class="input-label c5">
						<label for="email_smtp_server_security"><?php _se('SMTP security'); ?></label>
						<select type="text" name="email_smtp_server_security" id="email_smtp_server_security" class="text-input">
							<?php
								echo CHV\Render\get_select_options_html(['tls' => 'TLS', 'ssl' => 'SSL', 'unsecured' => _s('Unsecured')],  get_safe_post() ? get_safe_post()['email_smtp_server_security'] : CHV\Settings::get('email_smtp_server_security'));
							?>
						</select>
						<div class="input-below input-warning red-warning clear-both"><?php echo get_input_errors()['email_smtp_server_security']; ?></div>
					</div>
				</div>
			</div>
			<?php } ?>
			
			<?php if(get_settings()['key'] == 'social-networks') { ?>
			<div class="input-label">
				<label for="facebook">Facebook</label>
				<div class="c5 phablet-c1"><select type="text" name="facebook" id="facebook" class="text-input" data-combo="facebook-combo">
					<?php
						echo CHV\Render\get_select_options_html([1 => _s('Enabled'), 0 => _s('Disabled')], get_safe_post() ? get_safe_post()['facebook'] : CHV\Settings::get('facebook'));
					?>
				</select></div>
				<div class="input-below"><?php _se('You need a <a href="https://developers.facebook.com/" target="_blank">Facebook app</a> for this.'); ?></div>
			</div>
			<div id="facebook-combo">
				<div data-combo-value="1" class="switch-combo c9 phablet-c1<?php if(!(get_safe_post() ? get_safe_post()['facebook'] : CHV\Settings::get('facebook'))) echo ' soft-hidden'; ?>">
					<div class="input-label">
						<label for="facebook_app_id"><?php _se('Facebook app id'); ?></label>
						<input type="text" name="facebook_app_id" id="facebook_app_id" class="text-input" value="<?php echo get_safe_post() ? get_safe_post()['facebook_app_id'] : CHV\Settings::get('facebook_app_id', true); ?>">
						<div class="input-warning red-warning"><?php echo get_input_errors()['facebook_app_id']; ?></div>
					</div>
					<div class="input-label">
						<label for="facebook_app_secret"><?php _se('Facebook app secret'); ?></label>
						<input type="text" name="facebook_app_secret" id="facebook_app_secret" class="text-input" value="<?php echo get_safe_post() ? get_safe_post()['facebook_app_secret'] : CHV\Settings::get('facebook_app_secret', true); ?>">
						<div class="input-warning red-warning"><?php echo get_input_errors()['facebook_app_secret']; ?></div>
					</div>
				</div>
			</div>
            
			<hr class="line-separator"></hr>
            
			<div class="input-label">
				<label for="twitter">Twitter</label>
				<div class="c5 phablet-c1"><select type="text" name="twitter" id="twitter" class="text-input" data-combo="twitter-combo">
					<?php
						echo CHV\Render\get_select_options_html([1 => _s('Enabled'), 0 => _s('Disabled')], get_safe_post() ? get_safe_post()['twitter'] : CHV\Settings::get('twitter'));
					?>
				</select></div>
				<div class="input-below"><?php _se('You need a <a href="https://apps.twitter.com" target="_blank">Twitter app</a> for this.'); ?></div>
			</div>
			<div id="twitter-combo">
				<div data-combo-value="1" class="switch-combo c9 phablet-c1<?php if(!(get_safe_post() ? get_safe_post()['twitter'] : CHV\Settings::get('twitter'))) echo ' soft-hidden'; ?>">
					<div class="input-label">
						<label for="twitter_api_key"><?php _se('Twitter API key'); ?></label>
						<input type="text" name="twitter_api_key" id="twitter_api_key" class="text-input" value="<?php echo get_safe_post() ? get_safe_post()['twitter_api_key'] : CHV\Settings::get('twitter_api_key', true); ?>">
						<div class="input-warning red-warning"><?php echo get_input_errors()['twitter_api_key']; ?></div>
					</div>
					<div class="input-label">
						<label for="twitter_api_secret"><?php _se('Twitter API secret'); ?></label>
						<input type="text" name="twitter_api_secret" id="twitter_api_secret" class="text-input" value="<?php echo get_safe_post() ? get_safe_post()['twitter_api_secret'] : CHV\Settings::get('twitter_api_secret', true); ?>">
						<div class="input-warning red-warning"><?php echo get_input_errors()['twitter_api_secret']; ?></div>
					</div>
				</div>
			</div>
			<div class="input-label">
				<label for="twitter_account"><?php _se('Twitter account'); ?></label>
				<div class="c5 phablet-c1">
					<input type="text" name="twitter_account" id="twitter_account" class="text-input" value="<?php echo get_safe_post() ? get_safe_post()['twitter_account'] : CHV\Settings::get('twitter_account', true); ?>">
				</div>
				<div class="input-warning red-warning"><?php echo get_input_errors()['twitter_account']; ?></div>
			</div>			
            
			<hr class="line-separator"></hr>
            
			<div class="input-label">
				<label for="google">Google</label>
				<div class="c5 phablet-c1"><select type="text" name="google" id="google" class="text-input" data-combo="google-combo">
					<?php
						echo CHV\Render\get_select_options_html([1 => _s('Enabled'), 0 => _s('Disabled')], get_safe_post() ? get_safe_post()['google'] : CHV\Settings::get('google'));
					?>
				</select></div>
				<div class="input-below"><?php _se('You need a <a href="https://cloud.google.com/console" target="_blank">Google app</a> for this.'); ?></div>
			</div>
			<div id="google-combo">
				<div data-combo-value="1" class="switch-combo c9 phablet-c1<?php if(!(get_safe_post() ? get_safe_post()['google'] : CHV\Settings::get('google'))) echo ' soft-hidden'; ?>">
					<div class="input-label">
						<label for="google_client_id"><?php _se('Google client id'); ?></label>
						<input type="text" name="google_client_id" id="google_client_id" class="text-input" value="<?php echo get_safe_post() ? get_safe_post()['google_client_id'] : CHV\Settings::get('google_client_id', true); ?>">
						<div class="input-warning red-warning"><?php echo get_input_errors()['google_client_id']; ?></div>
					</div>
					<div class="input-label">
						<label for="google_client_secret"><?php _se('Google client secret'); ?></label>
						<input type="text" name="google_client_secret" id="google_client_secret" class="text-input" value="<?php echo get_safe_post() ? get_safe_post()['google_client_secret'] : CHV\Settings::get('google_client_secret', true); ?>">
						<div class="input-warning red-warning"><?php echo get_input_errors()['google_client_secret']; ?></div>
					</div>
				</div>
			</div>
			
			<hr class="line-separator"></hr>
			
			<div class="input-label">
				<label for="vk">VK</label>
				<div class="c5 phablet-c1"><select type="text" name="vk" id="vk" class="text-input" data-combo="vk-combo">
					<?php
						echo CHV\Render\get_select_options_html([1 => _s('Enabled'), 0 => _s('Disabled')], get_safe_post() ? get_safe_post()['vk'] : CHV\Settings::get('vk'));
					?>
				</select></div>
				<div class="input-below"><?php _se('You need a <a href="http://vk.com/dev" target="_blank">VK app</a> for this.'); ?></div>
			</div>
			<div id="vk-combo">
				<div data-combo-value="1" class="switch-combo c9 phablet-c1<?php if(!(get_safe_post() ? get_safe_post()['vk'] : CHV\Settings::get('vk'))) echo ' soft-hidden'; ?>">
					<div class="input-label">
						<label for="vk_client_id"><?php _se('VK client id'); ?></label>
						<input type="text" name="vk_client_id" id="vk_client_id" class="text-input" value="<?php echo get_safe_post() ? get_safe_post()['vk_client_id'] : CHV\Settings::get('vk_client_id', true); ?>">
						<div class="input-warning red-warning"><?php echo get_input_errors()['vk_client_id']; ?></div>
					</div>
					<div class="input-label">
						<label for="vk_client_secret"><?php _se('VK client secret'); ?></label>
						<input type="text" name="vk_client_secret" id="vk_client_secret" class="text-input" value="<?php echo get_safe_post() ? get_safe_post()['vk_client_secret'] : CHV\Settings::get('vk_client_secret', true); ?>">
						<div class="input-warning red-warning"><?php echo get_input_errors()['vk_client_secret']; ?></div>
					</div>
				</div>
			</div>
			<?php } ?>
			
			<?php if(get_settings()['key'] == 'external-services') { ?>
			<div class="input-label">
				<label for="recaptcha">CDN</label>
				<div class="c5 phablet-c1"><select type="text" name="cdn" id="cdn" class="text-input" data-combo="cdn-combo">
					<?php
						echo CHV\Render\get_select_options_html([1 => _s('Enabled'), 0 => _s('Disabled')], get_safe_post() ? get_safe_post()['cdn'] : CHV\Settings::get('cdn'));
					?>
				</select></div>
				<div class="input-below"><?php _se("CDN allows you to offload static content to several edge servers making your website faster. If you don't have a CDN provider you should try %s.", '<a href="https://chevereto.com/go/maxcdn" target="_blank">MaxCDN</a>'); ?></div>
			</div>
			<div id="cdn-combo" class="c9 phablet-c1">
				<div data-combo-value="1" class="switch-combo<?php if(!(get_safe_post() ? get_safe_post()['cdn'] : CHV\Settings::get('cdn'))) echo ' soft-hidden'; ?>">
					<div class="input-label">
						<label for="cdn_url">CDN URL</label>
						<input type="text" name="cdn_url" id="cdn_url" class="text-input" value="<?php echo get_safe_post() ? get_safe_post()['cdn_url'] : CHV\Settings::get('cdn_url', true); ?>" placeholder="http://something.netdna-cdn.com/">
						<div class="input-warning red-warning"><?php echo get_input_errors()['cdn_url']; ?></div>
					</div>
				</div>
			</div>
			<hr class="line-separator"></hr>
			<div class="input-label">
				<label for="recaptcha">reCAPTCHA</label>
				<div class="c5 phablet-c1"><select type="text" name="recaptcha" id="recaptcha" class="text-input" data-combo="recaptcha-combo">
					<?php
						echo CHV\Render\get_select_options_html([1 => _s('Enabled'), 0 => _s('Disabled')], get_safe_post() ? get_safe_post()['recaptcha'] : CHV\Settings::get('recaptcha'));
					?>
				</select></div>
				<div class="input-below"><?php _se('You need a <a href="%s" target="_blank">reCAPTCHA key</a> for this.', 'https://www.google.com/recaptcha/intro/index.html'); ?></div>
			</div>
			<div id="recaptcha-combo">
				<div data-combo-value="1" class="switch-combo<?php if(!(get_safe_post() ? get_safe_post()['recaptcha'] : CHV\Settings::get('recaptcha'))) echo ' soft-hidden'; ?>">
					<div class="c9 phablet-c1">
						<div class="input-label">
							<label for="recaptcha_public_key"><?php _se('reCAPTCHA public key'); ?></label>
							<input type="text" name="recaptcha_public_key" id="recaptcha_public_key" class="text-input" value="<?php echo get_safe_post() ? get_safe_post()['recaptcha_public_key'] : CHV\Settings::get('recaptcha_public_key', true); ?>">
							<div class="input-warning red-warning"><?php echo get_input_errors()['recaptcha_public_key']; ?></div>
						</div>
						<div class="input-label">
							<label for="recaptcha_private_key"><?php _se('reCAPTCHA private key'); ?></label>
							<input type="text" name="recaptcha_private_key" id="recaptcha_private_key" class="text-input" value="<?php echo get_safe_post() ? get_safe_post()['recaptcha_private_key'] : CHV\Settings::get('recaptcha_private_key', true); ?>">
							<div class="input-warning red-warning"><?php echo get_input_errors()['recaptcha_private_key']; ?></div></div>
						</div>
					</div>
					<div class="input-label">
						<div class="c9 phablet-c1">
							<label for="recaptcha_threshold"><?php _se('reCAPTCHA threshold'); ?></label>
							<div class="c2">
								<input type="number" min="0" name="recaptcha_threshold" id="recaptcha_threshold" class="text-input" value="<?php echo get_safe_post() ? get_safe_post()['recaptcha_threshold'] : CHV\Settings::get('recaptcha_threshold'); ?>">
							</div>
						</div>
						<div class="input-below"><?php _se('How many failed attempts are needed to ask for reCAPTCHA? Use zero (0) to always show reCAPTCHA.'); ?></div>
					</div>
				</div>
			</div>
			<hr class="line-separator"></hr>
			<div class="input-label">
				<label for="comment_code"><?php _se('Comment code'); ?></label>
				<div class="c12 phablet-c1"><textarea type="text" name="comment_code" id="comment_code" class="text-input r4" value="" placeholder="<?php _se('Disqus, Facebook or anything you want. It will be used in image view.'); ?>"><?php echo CHV\Settings::get('comment_code', true); ?></textarea></div>
			</div>
			<hr class="line-separator"></hr>
			<div class="input-label">
				<label for="analytics_code"><?php _se('Analytics code'); ?></label>
				<div class="c12 phablet-c1"><textarea type="text" name="analytics_code" id="analytics_code" class="text-input r4" value="" placeholder="<?php _se('Google Analytics or anything you want. It will be added to the theme footer.'); ?>"><?php echo CHV\Settings::get('analytics_code', true); ?></textarea></div>
			</div>
			<hr class="line-separator"></hr>
			<div class="input-label">
				<label for="cloudflare">Cloudflare</label>
				<div class="c5 phablet-c1"><select type="text" name="cloudflare" id="cloudflare" class="text-input" disabled>
					<?php
						echo CHV\Render\get_select_options_html([1 => _s('Enabled'), 0 => _s('Disabled')], CHV\Settings::get('cloudflare'));
					?>
				</select></div>
				<div class="input-below"><?php _se('Cloudflare is auto detected using %s.', 'HTTP_CF_CONNECTING_IP'); ?></div>
			</div>
			<?php } ?>
			
			<?php if(get_settings()['key'] == 'api') { ?>
				<p><?php _se('For documentation about the API check the <a %s>API documentation</a>', 'href="http://bit.ly/1EFSP0H" target="_blank"'); ?></p>
				<div class="input-label">
					<label for="api_v1_key"><?php _se('API v1 key'); ?></label>
					<div class="c9 phablet-c1"><input type="text" name="api_v1_key" id="api_v1_key" class="text-input" value="<?php echo CHV\Settings::get('api_v1_key', true); ?>"></div>
					<div class="input-warning red-warning"><?php echo get_input_errors()['api_v1_key']; ?></div>
					<div class="input-below"><?php _se('Use this key when using the <a %s>API v1</a>.', 'href="http://bit.ly/1F8s9sX" target="_blank"'); ?></div>
				</div>
			<?php } ?>
			
			<?php if(get_settings()['key'] == 'additional-settings') { ?>
			<div class="input-label">
				<label for="enable_cookie_law"><?php _se('Cookie law compliance'); ?></label>
				<div class="c5 phablet-c1"><select type="text" name="enable_cookie_law" id="enable_cookie_law" class="text-input">
					<?php
						echo CHV\Render\get_select_options_html([1 => _s('Enabled'), 0 => _s('Disabled')], CHV\Settings::get('enable_cookie_law'));
					?>
				</select></div>
				<div class="input-below"><?php _se('Enable this to display a message that complies with the EU Cookie law requirements. Note: You only need this if your website is hosted in the EU and if you add tracking cookies.'); ?></div>
			</div>
			<?php } ?>
			
			<?php
					if(is_show_submit()) {
			?>
			<hr class="line-separator"></hr>
			
			<div class="btn-container">
				<button class="btn btn-input default" type="submit"><?php _se('Save changes'); ?></button> <span class="btn-alt"><?php _se('or'); ?> <a href="<?php echo get_settings()['url']; ?>"><?php _se('cancel'); ?></a></span>
			</div>
			<?php
					}
			?>
			
			<?php
				}
			?>
			
		</form>
		<?php
				break;
			}
		?>
		
	</div>
    
</div>

<?php G\Render\include_theme_footer(); ?>

<?php if(get_post() and (is_changed() or is_error())) { ?>
<script>PF.fn.growl.expirable("<?php echo is_changed() ? (get_changed_message() ? get_changed_message() : _s('Changes have been saved.')) : _s('Check the errors to proceed.'); ?>");</script>
<?php } ?>