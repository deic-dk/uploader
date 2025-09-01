<?php

require_once('apps/uploader/util.php');

$data = json_decode(file_get_contents('php://input'), true);

\OCP\Util::writeLog('uploader', "Received data: " . $data['recipient'] . " --> " . serialize($data), \OCP\Util::WARN);

$recipient = empty($data['recipient'])?'':$data['recipient'];
$expiration = empty($data['expiration'])?'':$data['expiration'];
$filenames = empty($data['filenames'])?'':$data['filenames'];
$fileids = empty($data['fileids'])?'':$data['fileids'];
$links = empty($data['links'])?'':$data['links'];
$require_login = !empty($data['require_login']) && $data['require_login']!='no' && $data['require_login']!='false';
$unrequire_login = !empty($data['require_login']) && ($data['require_login']==='no' || $data['require_login']==='false');
$notify_on_download = !empty($data['notify_on_download']) && $data['notify_on_download']!='no' && $data['notify_on_download']!='false';
$restricted_share_id = empty($data['restricted_share_id'])?'':$data['restricted_share_id'];

if($require_login===true){
	\OCP\App::checkAppEnabled('user_group_admin');
	$restrictedGroup = \OCP\Config::getSystemValue('restricted_public_share_group', 'restricted');
	// Share with the group 'restricted' - create the group if necessary
	$restrictedGroupInfo = \OC_User_Group_Admin_Util::getGroupInfo($restrictedGroup);
	if(empty($restrictedGroupInfo)){
		\OCP\Util::writeLog('uploader', 'Creating restricted group '.$restrictedGroup, \OC_Log::WARN);
		\OC_User_Group_Admin_Util::createGroup($restrictedGroup, OC_User_Group_Admin_Util::$HIDDEN_GROUP_OWNER, 'yes');
	}
	foreach($fileids as $fileid){
		\OCP\Util::writeLog('uploader', 'Sharing with restricted group '.$fileid, \OC_Log::WARN);
		if(\OCP\App::isEnabled('files_sharding')){
			\OCA\Files\Share_files_sharding\Api::shareItem('file', $fileid, \OCP\Share::SHARE_TYPE_GROUP,
					$restrictedGroup, \OCP\PERMISSION_READ);
		}
		elseif(empty($alreadySharedItem)){
			\OCP\Share::shareItem('file', $fileid, \OCP\Share::SHARE_TYPE_GROUP,
					$restrictedGroup, \OCP\PERMISSION_READ);
		}
	}
}
elseif($unrequire_login===true && !empty($restricted_share_id)){
	\OCP\App::checkAppEnabled('user_group_admin');
	$restrictedGroup = \OCP\Config::getSystemValue('restricted_public_share_group', 'restricted');
	// Unshare with the group 'restricted'
	$restrictedGroupInfo = \OC_User_Group_Admin_Util::getGroupInfo($restrictedGroup);
	foreach($fileids as $fileid){
		\OCP\Util::writeLog('uploader', 'Unsharing with restricted group '.$fileid, \OC_Log::WARN);
		if(\OCP\App::isEnabled('files_sharding')){
			return \OCA\Files\Share_files_sharding\Api::deleteShare(array('id'=>$restricted_share_id));
		}
		elseif(empty($alreadySharedItem)){
			\OCP\Share::deleteShare(array('id'=>$restricted_share_id));
		}
	}
}

$res = array();
if(!empty($data['notify_on_download'])){
	if($notify_on_download){
		\OCP\Config::setUserValue($user_id, 'activity', 'notify_email_shared_file_download', '1');
	}
	else{
		\OCP\Config::setUserValue($user_id, 'activity', 'notify_email_shared_file_download', '');
	}
}

if(!empty($recipient)){
	\OCP\Util::writeLog('uploader', 'Sending mail to '.$recipient, \OC_Log::WARN);
	$res['errors'] = \OCA\Uploader\Util::sendLinkShareMail($recipient, $filenames, $links, $expiration);
}
$res['fileids'] = $fileids;
$res['message'] = 'File'.(count($filenames)>1?'s':'').' shared';
OCP\JSON::encodedPrint($res);

?>
