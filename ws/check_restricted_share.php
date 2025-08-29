<?php

OCP\JSON::checkAppEnabled('files_sharding');
OCP\JSON::checkAppEnabled('uploader');
require_once('apps/uploader/util.php');

//OCP\JSON::checkLoggedIn();

$fileid = isset($_GET['fileid'])?$_GET['fileid']:null;

if(empty($fileid)){
	$res = false;
}
else{
	$res = OCA\Uploader\Util::checkRestrictedShare($fileid);
}

$ret = Array('fileid' => $fileid, 'restricted' => $res);

OCP\JSON::encodedPrint($ret);
