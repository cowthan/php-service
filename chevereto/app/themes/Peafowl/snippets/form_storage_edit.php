<?php if(!defined('access') or !access) die('This file cannot be directly accessed.'); ?>
<div class="input-label c7">
	<label for="form-storage-name"><?php _se('Name'); ?></label>
	<input type="text" id="form-storage-name" name="form-storage-name" class="text-input" placeholder="<?php _se('Storage name') ?>" required maxlength="32">
</div>
<div class="input-label c5">
	<label for="form-storage-api_id"><?php _se('API'); ?></label>
	<select name="form-storage-api_id" id="form-storage-api_id" class="text-input" data-combo="storage-combo">
		<?php foreach(CHV\Storage::getApis() as $k => $v) { ?>
		<option value="<?php echo $k; ?>" data-url="<?php echo $v['url']; ?>"><?php echo $v['name']; ?></option>
		<?php } ?>
	</select>
	<script>
		$(document).ready(function() {
			// Update URL when storage API changes
			$(document).on("change", PF.obj.modal.selectors.root + " select[name=form-storage-api_id]", function() {
				var form_storage_url;
				var $modal = $(PF.obj.modal.selectors.root);
				var bucket = $.trim($("[name=form-storage-bucket]:visible", $modal).prop("value"));
				if($("option:selected", this).prop("value") != "1") {
					if($(this).data("value") && $(this).data("value") != "1") return;
					if($("option:selected", this).prop("value") == "2") {
						form_storage_url = $("option:selected", this).data("url");
					} else {
						form_storage_url = null;
					}
				} else {
					form_storage_url = $("select[name=form-storage-region] option:selected", PF.obj.modal.selectors.root).data("url");
				}
				
				if(bucket) {
					form_storage_url += bucket + "/";
				}
				
				$("input[name=form-storage-url]", PF.obj.modal.selectors.root).prop("value", form_storage_url);
				
				$(this).data("value", $("option:selected", this).prop("value"));
			});
			// Update URL when storage region changes
			$(document).on("change", PF.obj.modal.selectors.root + " select[name=form-storage-region]", function() {
				$("input[name=form-storage-url]", PF.obj.modal.selectors.root).prop("value", $("option:selected", this).data("url"));
			});
		});
	</script>
</div>
<div id="storage-combo">
	<div data-combo-value="1" class="input-label c7 switch-combo">
		<label for="form-storage-region"><?php _se('Region'); ?></label>
		<select name="form-storage-region" id="form-storage-region" class=" text-input">
			<?php foreach(CHV\Storage::getAPIRegions('s3') as $k => $v) { ?>
			<option value="<?php echo $k; ?>" data-url="<?php echo $v['url']; ?>"><?php echo $v['name']; ?></option>
			<?php } ?>
		</select>
	</div>
	<div data-combo-value="1 2" class="switch-combo">
		<div class="input-label c7">
			<label for="form-storage-bucket">Bucket</label>
			<input type="text" id="form-storage-bucket" name="form-storage-bucket" class="text-input" placeholder="<?php _se('Storage bucket') ?>" required>
		</div>
	</div>
	<div data-combo-value="1" class="switch-combo">
		<div class="input-label c7">
			<label for="form-storage-key"><?php _se('Key'); ?></label>
			<input type="text" id="form-storage-key" name="form-storage-key" class="text-input" placeholder="<?php _se('Storage key') ?>" required>
		</div>
		<div class="input-label c7">
			<label for="form-storage-secret"><?php _se('Secret'); ?></label>
			<input type="text" id="form-storage-secret" name="form-storage-secret" class="text-input" placeholder="<?php _se('Storage secret') ?>" required>
		</div>
	</div>
	<div data-combo-value="2" class="switch-combo soft-hidden">
		<div class="input-label">
			<div class="c7"><label for="form-storage-key"><?php _se('Client email'); ?></label>
			<input type="text" id="form-storage-key" name="form-storage-key" class="text-input" placeholder="<?php _se('Google Cloud client email') ?>" required></div>
			<div class="input-below"><?php _se('You will need a <a %s>service account</a> for this.', 'href="https://cloud.google.com/storage/docs/authentication#service_accounts" target="_blank"'); ?></div>
		</div>
		<div class="input-label c15">
			<label for="form-storage-secret"><?php _se('Private key'); ?></label>
			<textarea id="form-storage-secret" name="form-storage-secret" class="text-input" placeholder="<?php _se('Google Cloud JSON key') ?>" required></textarea>
		</div>
	</div>
	<div data-combo-value="7" class="switch-combo soft-hidden">
		<div class="input-label c7">
			<label for="form-storage-server"><?php _se('Identity URL'); ?></label>
			<input type="text" id="form-storage-server" name="form-storage-server" class="text-input" placeholder="<?php _se('Identity API endpoint') ?>" required rel="template-tooltip" data-tiptip="right" data-title="<?php _se('API endpoint for OpenStack identity'); ?>">
		</div>
		<div class="input-label c7">
			<label for="form-storage-key"><?php _se('Username'); ?></label>
			<input type="text" id="form-storage-key" name="form-storage-key" class="text-input" placeholder="<?php _se('Username') ?>" required>
		</div>
		<div class="input-label c7">
			<label for="form-storage-secret"><?php _se('Password'); ?></label>
			<input type="text" id="form-storage-secret" name="form-storage-secret" class="text-input" placeholder="<?php _se('Password') ?>" required>
		</div>
		<div class="input-label c7">
			<label for="form-storage-region"><?php _se('Region'); ?></label>
			<input type="text" id="form-storage-region" name="form-storage-region" class="text-input" placeholder="<?php _se('Storage region') ?>" required>
		</div>
		<div class="input-label c7">
			<label for="form-storage-bucket"><?php _se('Container'); ?></label>
			<input type="text" id="form-storage-bucket" name="form-storage-bucket" class="text-input" placeholder="<?php _se('Storage container') ?>" required>
		</div>
		<div class="input-label c7">
			<label for="form-storage-account_id"><?php _se('Tenant id'); ?> <span class="optional"><?php _se('optional'); ?></span></label>
			<input type="text" id="form-storage-account_id" name="form-storage-account_id" class="text-input" placeholder="<?php _se('Tenant id (account id)') ?>">
		</div>
		<div class="input-label c7">
			<label for="form-storage-account_name"><?php _se('Tenant name'); ?> <span class="optional"><?php _se('optional'); ?></span></label>
			<input type="text" id="form-storage-account_name" name="form-storage-account_name" class="text-input" placeholder="<?php _se('Tenant name (account name)') ?>">
		</div>
	</div>
	<div data-combo-value="5 6" class="switch-combo soft-hidden">
		<div class="input-label c7">
			<label for="form-storage-server"><?php _se('Server'); ?></label>
			<input type="text" id="form-storage-server" name="form-storage-server" class="text-input" placeholder="<?php _se('Server') ?>" required rel="template-tooltip" data-tiptip="right" data-title="<?php _se('Hostname or IP of the storage server'); ?>">
		</div>
		<div class="input-label c7">
			<label for="form-storage-bucket"><?php _se('Path'); ?></label>
			<input type="text" id="form-storage-bucket" name="form-storage-bucket" class="text-input" placeholder="<?php _se('Server path') ?>" required rel="template-tooltip" data-tiptip="right" data-title="<?php _se('Server path where the files will be stored'); ?>">
		</div>
		<div class="input-label c7">
			<label for="form-storage-key"><?php _se('Username'); ?></label>
			<input type="text" id="form-storage-key" name="form-storage-key" class="text-input" placeholder="<?php _se('Server username') ?>" required>
		</div>
		<div class="input-label c7">
			<label for="form-storage-secret"><?php _se('Password'); ?></label>
			<input type="text" id="form-storage-secret" name="form-storage-secret" class="text-input" placeholder="<?php _se('Server password') ?>" required>
		</div>
	</div>
	<div class="input-label">
		<div class="c7">
			<label for="form-storage-capacity"><?php _se('Storage capacity'); ?></label>
			<input type="text" id="form-storage-capacity" name="form-storage-capacity" class="text-input" placeholder="<?php _se('Example: 20 GB, 1 TB, etc.') ?>">
		</div>
		<div class="input-below"><?php _se('This storage will be disabled when it reach this capacity. Leave it blank or zero for no limit.'); ?></div>
	</div>
	<div class="input-label">
		<label for="form-storage-url">URL</label>
		<input type="text" id="form-storage-url" name="form-storage-url" class="text-input" placeholder="<?php _se('Storage URL') ?>" value="<?php echo CHV\Storage::getAPIRegions('s3')['us-east-1']['url'] ?>" required>
		<div class="input-below"><?php _se('The system will map the images of this storage to this URL.'); ?></div>
	</div>
</div>