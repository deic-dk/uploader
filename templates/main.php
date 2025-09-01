<div class="uploader_share">
	<span class="bold">Share uploaded files</span><span class="info" title="<?php p($l->t('Share the uploaded files via links.')); ?>">ⓘ</span><input type="checkbox" id="shareCheckbox">
		<input type="button" class="HTML5FileUploadShare_Button uploader_share_meta btn-primary btn-flat btn" value="Share" disabled="disabled"> <a title="<?php p($l->t('Show *files* shared by you by public links')); ?>" class="HTML5FileUploadShare" target=_blank href="<?php p(OC::$WEBROOT);?>/index.php/apps/files/?dir=%2F&view=sharingout&links_only=true&files_only=true"><?php p($l->t('Show already shared files')); ?></a>
	<div class="uploader_share_meta">
		<span>Recipients</span><span title="<?php p($l->t('Optional space-separated list of email addresses to send download links to'));?>" class="info">ⓘ</span>
		<input class="uploader_share_meta first" autocomplete="off" type="email" multiple automplete="nope" id="uploader_recipients"></input>
	</div>
	<div class="uploader_share_meta">
		<span>Expiration</span><span class="info" title="<?php p($l->t('Optional expiration date on share')); ?>">ⓘ</span>
		<input type="checkbox" id="expirationCheckbox">
		<input id="expirationDate" autocomplete="off" automplete="nope" type="sharee" palceholder="Expiration date">
	</div>
	<div class="uploader_share_meta">
		<span><?php p($l->t('Password protect')); ?></span><span class="info" title="<?php p($l->t('Require this password to access the files. You must inform recipients of the password by other means.')); ?>">ⓘ</span>
		<input type="checkbox" id="passwordCheckbox">
		<input id="linkPassText" type="password" autocomplete="off" automplete="nope" placeholder="Password">
	</div>
	<?php if(OCP\App::isEnabled('user_group_admin')){ ?>
	<div class="uploader_share_meta">
		<span><?php p($l->t('Require login')); ?></span><span class="info" title="<?php p($l->t('Require login on')." ".$_['masterurl']);?> to access these files">ⓘ</span>
		<input type="checkbox" id="loginCheckbox">
	</div>
	<?php } ?>
	<div class="uploader_share_meta">
		<span><?php p($l->t('Notify on download')); ?></span><span class="info" title="<?php p($l->t('Email me when my shared files are downloaded. This is a global setting.')); ?>">ⓘ</span>
		<input type="checkbox" id="notifyCheckbox"<?php if($_['email_on_download']){?> checked="checked"<?php }?>>
	</div>
</div>

<div id="uploaderapp" masterurl="<?php echo($_['masterurl']);?>">

<div id="choose_upload_folder">
	<select class="target_folder" id="uploader_group_folder">
			<option value="" selected="selected"><?php p($l->t("Home")); ?></option>
			<?php
			foreach($_['member_groups'] as $group){
				echo "<option value=\"".$group['gid']."\"".
						(!empty($_['upload_group'])&&$_['upload_group']==$group['gid']?" selected=\"selected\"":"").">".
						$group['gid']."</option>";
			}
			?>
	</select>
	<div id="upload_folder" dir="<?php echo($_['upload_folder']);?>"></div>
</div>

<div id="HTML5FileUpload_information">
<span class="app_information">This service allows ScienceData users, in a controlled and secure environment, to send large files to specific recipients - both  other users and externals. Simply drag and drop files on the box above, click "upload", fill in the recpients' email addresses and click "Share".</span>
<br /><br />
<span class="app_information">If you're sharing movies/MP4 files, you may want to first <a href="/sites/videotrim/" target="_blank">trim</a> them and/or <a href="<?php echo(OC::$WEBROOT);?>/index.php/apps/batch/">scale</a> them down in size.</span>
<br /><br />
<span class="app_information">The service is available to all ScienceData users - who are imposed no limits on file sizes beyond those set by their quota (which can be lifted for datasets of generic interest).</span>
</div>

</div>
