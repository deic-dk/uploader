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

$tpl = new OCP\Template("uploader", "main", "user");
$tpl->printPage();
