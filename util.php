<?php

namespace OCA\Uploader;

class Util {
	
	const TYPE_SHARED_FILE_DOWNLOAD = 'shared_file_download';
		
	public static function sendLinkShareMail($recipient, $filenames, $links, $expiration) {
		$l = \OC_L10N::get('uploader');
		$user = \OCP\USER::getUser();
		$userEmail = \OCP\Config::getUserValue($user, 'settings', 'email');
		$systemEmail = \OC_Config::getValue('mail_smtpname', 'no-reply');
		$userRealName = \OCP\User::getDisplayName($user);
		if(count($filenames)==1){
			$subject = (string)$l->t('%s has shared a file with you', array($userRealName));
		}
		else{
			$subject = (string)$l->t('%s has shared %d files with you', array($userRealName, count($filenames)));
		}
		list($htmlMail, $alttextMail) = self::createMailBody($filenames, $links, $expiration, $userRealName);
		$rs = explode(' ', $recipient);
		$failed = array();
		foreach($rs as $r){
			try {
				\OCP\Util::writeLog('uploader', 'Sending mail '.$systemEmail.'-->'.$r.'-->'.$subject.'-->'.$htmlMail, \OC_Log::WARN);
				\OC_MAIL::send($r, $r, $subject, $htmlMail, $systemEmail, $userRealName, 1, $alttextMail, '', '', '', $userEmail);
			}
			catch (\Exception $e) {
				\OCP\Util::writeLog('uploader', "Can't send mail with public link to $r: " . $e->getMessage(), \OCP\Util::ERROR);
				$failed[] = $r;
			}
		}
		return $failed;
	}
	
	private static function createMailBody($filenames, $links, $expiration, $senderDisplayName) {
		$l = \OC_L10N::get('uploader');
		
		$formatedDate = $expiration ? $l->l('date', $expiration) : null;
		
		$html = new \OC_Template("uploader", "mail", "");
		$html->assign ('links', $links);
		$html->assign ('user_displayname', $senderDisplayName);
		$html->assign ('filenames', $filenames);
		$html->assign('expiration',  $formatedDate);
		$htmlMail = $html->fetchPage();
		
		$alttext = new \OC_Template("uploader", "altmail", "");
		$alttext->assign ('links', $links);
		$alttext->assign ('user_displayname', $senderDisplayName);
		$alttext->assign ('filenames', $filenames);
		$alttext->assign('expiration', $formatedDate);
		$alttextMail = $alttext->fetchPage();
		
		return array($htmlMail, $alttextMail);
	}
	
	function checkRestrictedShare($fileID) {
		if(!\OCP\App::isEnabled('files_sharding') || \OCA\FilesSharding\Lib::isMaster()){
			$result = self::dbCheckRestrictedShare($fileID);
		}
		else{
			$resultArr = \OCA\FilesSharding\Lib::ws('check_restricted_share', array('fileid'=>$fileID), false, true, null, 'uploader');
			$result = $resultArr['restricted'];
		}
		return !empty($result);
	}
	
	function dbCheckRestrictedShare($fileID) {
		if(empty($fileID)){
			return false;
		}
		$restrictedGroup = \OCP\Config::getSystemValue('restricted_public_share_group', 'restricted');
		$sql = 'SELECT `file_source`, `item_type`, `permissions`, `stime` FROM `*PREFIX*share` WHERE `item_source` = ? AND share_type = ? AND share_with = ?';
		$args = array($fileID, \OCP\Share::SHARE_TYPE_GROUP, $restrictedGroup);
		$query = \OCP\DB::prepare($sql);
		$result = $query->execute($args);
		if(\OCP\DB::isError($result)){
			\OCP\Util::writeLog('files_sharing', \OC_DB::getErrorMessage($result), \OCP\Util::ERROR);
			return false;
		}
		\OCP\Util::writeLog('files_sharing', 'Checking if restricted: '.$sql.':'.$fileID.':'.\OCP\Share::SHARE_TYPE_GROUP.':'.$restrictedGroup, \OCP\Util::WARN);
		if($share = $result->fetchRow()){
			\OCP\Util::writeLog('files_sharing', 'Restricted...', \OCP\Util::WARN);
			return $share;
		}
		return false;
	}
	
	function activityNotify($path, $owner, $user){
		$filename = basename($path);
		$dirname = dirname($path);
		$ip = $_SERVER['REMOTE_ADDR'];
		$l = \OC_L10N::get('uploader');
		\OCA\UserNotification\Data::send('uploader', $l->t('Your shared file '.$filename.' has been downloaded'), array($filename), 'file_downloaded', array($path, $owner, $ip, $user), $dirname, 'OC::$WEBROOT./index.php/apps/files/?dir='.$dirname, $owner, \OCA\Uploader\Util::TYPE_SHARED_FILE_DOWNLOAD,
				\OCA\UserNotification\Data::PRIORITY_MEDIUM, $owner);
	}

}

