<?php

\OCP\User::checkLoggedIn();
\OCP\App::checkAppEnabled('uploader');

OCP\Util::addStyle('chooser', 'jqueryFileTree');
OCP\Util::addscript('chooser', 'jquery.easing.1.3');
OCP\Util::addscript('chooser', 'jqueryFileTree');
OCP\Util::addScript('uploader', 'browse');

\OCP\Util::addScript('uploader', 'html5fileupload');
\OCP\Util::addScript('uploader', 'html5fileupload_create');
\OCP\Util::addStyle('uploader', 'html5fileupload');

OCP\App::setActiveNavigationEntry( 'uploader' );

$user_id = OCP\USER::getUser();
$tpl = new OCP\Template("uploader", "main", "user");
if(OCP\App::isEnabled('user_group_admin')){
	$groups = OC_User_Group_Admin_Util::getUserGroups($user_id, false, false, true);
	$tpl->assign('member_groups', $groups);
}

$tpl->printPage();
