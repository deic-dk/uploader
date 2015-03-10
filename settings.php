<?php

\OCP\User::checkLoggedIn();
\OCP\User::checkAdminUser();
\OCP\App::checkAppEnabled('uploader');

$tpl = new OCP\Template("uploader", "settins", "admin");
$tpl->printPage();


