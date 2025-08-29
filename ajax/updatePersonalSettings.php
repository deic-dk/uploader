<?php

OCP\JSON::checkAppEnabled('importer');
OCP\User::checkLoggedIn();

$user_id = OCP\USER::getUser();

$errors = Array();

$upload_folder = $_POST['upload_folder'];
$upload_group = $_POST['upload_group'];

\OCP\Config::setUserValue($user_id, 'uploader', 'upload_folder', $upload_folder);
\OCP\Config::setUserValue($user_id, 'uploader', 'upload_group', $upload_group);

OC_Log::write('uploader',"Updated settings. ".serialize($errors), OC_Log::WARN);

OCP\JSON::encodedPrint($errors);
