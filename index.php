<?php

\OCP\User::checkLoggedIn();
\OCP\App::checkAppEnabled('uploader');
OCP\JSON::checkAppEnabled('files_sharding');

OCP\Util::addStyle('chooser', 'jqueryFileTree');
OCP\Util::addscript('chooser', 'jquery.easing.1.3');
OCP\Util::addscript('chooser', 'jqueryFileTree');
OCP\Util::addScript('uploader', 'browse');

\OCP\Util::addScript('uploader', 'html5fileupload');
\OCP\Util::addScript('uploader', 'html5fileupload_create');
\OCP\Util::addStyle('uploader', 'html5fileupload');

OCP\App::setActiveNavigationEntry( 'uploader' );

$user_id = OCP\USER::getUser();
$upload_folder = \OCP\Config::getUserValue($user_id, 'uploader', 'upload_folder');
$upload_group = \OCP\Config::getUserValue($user_id, 'uploader', 'upload_group');
$masterurl = \OCP\Config::getSystemValue('masterurl', '');
$notify_on_download = \OCP\Config::getUserValue($user_id, 'activity', 'notify_email_shared_file_download')=='1';

$tpl = new OCP\Template("uploader", "main", "user");
$tpl->assign('upload_folder', $upload_folder);
$tpl->assign('upload_group', $upload_group);
$tpl->assign('masterurl', $masterurl);
$tpl->assign('email_on_download', $notify_on_download);
if(OCP\App::isEnabled('user_group_admin')){
	$groups = OC_User_Group_Admin_Util::getUserGroups($user_id, false, false, true);
	$tpl->assign('member_groups', $groups);
}

$tpl->printPage();
