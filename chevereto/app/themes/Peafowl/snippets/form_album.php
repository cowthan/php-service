<?php if(!defined('access') or !access) die('This file cannot be directly accessed.'); ?>
<?php $album = function_exists('get_album_safe_html') ? get_album_safe_html() : NULL; ?>
<div class="c7 input-label">
	<?php
		$label = 'form-album-name';
	?>
    <label for="<?php echo $label; ?>"><?php _se('Album name'); ?></label>
    <input type="text" id="<?php echo $label; ?>" name="<?php echo $label; ?>" class="text-input" value="<?php echo $album["name"]; ?>" placeholder="<?php if(!G\get_global('album')) { _se('Untitled album'); } ?>" required maxlength="<?php echo CHV\getSetting('album_name_max_length'); ?>">
	<?php
		if(!function_exists('get_album_safe_html')) {
	?>
	<span class="btn-alt c7"><?php _se('or'); ?> <a data-switch="move-existing-album"><?php _se('move to existing album'); ?></a></span>
	<?php
		}
	?>
</div>
<div class="input-label">
	<label for="form-album-description"><?php _se('Album description'); ?> <span class="optional"><?php _se('optional'); ?></span></label>
	<textarea id="form-album-description" name="form-album-description" class="text-input no-resize" placeholder="<?php _se('Brief description of this album'); ?>"><?php echo function_exists('get_album_safe_html') ? get_album_safe_html()['description'] : NULL; ?></textarea>
</div>
<?php if(CHV\getSetting('website_privacy_mode') == 'public' or (CHV\getSetting('website_privacy_mode') == 'private' and CHV\getSetting('website_content_privacy_mode') == 'default')) { ?>
<div class="input-label overflow-auto">
    <div class="c7 grid-columns">
        <label for="form-privacy"><?php _se('Album Privacy'); ?></label>
        <select name="form-privacy" id="form-privacy" class="text-input" data-combo="form-privacy-combo" rel="template-tooltip" data-tiptip="right" data-title="<?php _se('Who can view this content'); ?>">
            <option value="public"<?php if($album['privacy'] == 'public') echo ' selected'; ?>><?php _se('Public'); ?></option>
            <option value="private"<?php if($album['privacy'] == 'private') echo ' selected'; ?>><?php _se('Private (just me)'); ?></option>
			<option value="private_but_link"<?php if($album['privacy'] == 'private_but_link') echo ' selected'; ?>><?php _se('Private (anyone with the link)'); ?></option>
        </select>
    </div>
</div>
<?php } ?>