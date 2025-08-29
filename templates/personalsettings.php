<fieldset id="uploaderPersonalSettings" class="section">
	<h2><?php p($l->t("Upload files"));?></h2>
	<?php p($l->t("Default upload folder"));?>:
	<input type="text" id="uploader_upload_folder" name="HTML5FileUpload_destdir"
		value="<?php echo(isset($_['upload_folder'])?$_['upload_folder']:''); ?>" placeholder="/"/>
	<label id="uploader_choose_upload_folder" class="uploader_choose_download_folder target_folder btn btn-flat"><?php p($l->t("Browse"));?></label>
		<select class="target_folder" id="uploader_group_folder">
		<option value=""<?php if(empty($_['upload_group'])){echo(' selected="selected"');}?>><?php p($l->t("Home")); ?></option>
		<?php
		foreach($_['member_groups'] as $group){
			echo "<option value=\"".$group['gid']."\"".
			(!empty($_['upload_group'])&&$_['upload_group']==$group['gid']?" selected=\"selected\"":"").">".
			$group['gid']."</option>";
		}
		?>
	</select>
	<div id="download_folder" style="visibility:hidden;display:none;"></div>
	<div class="uploader_folder_dialog" display="none">
		<div class="loadFolderTree"></div>
		<div class="file" style="visibility: hidden; display:inline;"></div>
	</div>
	<br />
	<label id="uploader_settings_submit" class="button"><?php p($l->t('Save'));?>
	</label>&nbsp;<label id="uploader_msg"></label>
</fieldset>

