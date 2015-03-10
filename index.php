<?php

\OCP\User::checkLoggedIn();
\OCP\App::checkAppEnabled('uploader');

\OCP\Util::addScript('uploader', 'html5fileupload');
\OCP\Util::addScript('uploader', 'html5fileupload_create');
\OCP\Util::addStyle('uploader', 'html5fileupload');

$tpl = new OCP\Template("uploader", "main", "user");
$tpl->printPage();
