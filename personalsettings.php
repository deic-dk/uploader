<?php

OCP\JSON::checkAppEnabled('uploader');
OCP\User::checkLoggedIn();

OCP\Util::addscript('uploader', 'personalsettings');
OCP\Util::addscript('uploader', 'browse');

OCP\Util::addStyle('chooser', 'jqueryFileTree');
OCP\Util::addscript('chooser', 'jquery.easing.1.3');
OCP\Util::addscript('chooser', 'jqueryFileTree');
OCP\Util::addStyle('uploader', 'personalsettings');

$user_id = OCP\USER::getUser();
$upload_folder = \OCP\Config::getUserValue($user, 'uploader', 'upload_folder');
$upload_group = \OCP\Config::getUserValue($user, 'uploader', 'upload_group');

$tmpl = new OCP\Template('uploader', 'personalsettings');
$tmpl->assign('upload_folder', $upload_folder);
$tmpl->assign('upload_group', $upload_group);

if(OCP\App::isEnabled('user_group_admin')){
	$groups = OC_User_Group_Admin_Util::getUserGroups($user_id, false, false, true);
	$tmpl->assign('member_groups', $groups);
}

return $tmpl->fetchPage();
