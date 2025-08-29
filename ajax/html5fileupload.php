<?php

$group = empty($_POST['group'])?'':$_POST['group'];
$user = \OCP\USER::getUser();

$view = new \OC\Files\View('/');
//  Add  custom  path  to  $pathToFiles
$pathToFiles = $view->getLocalFolder('/'.OCP\User::getUser().(empty($group)?'/files/':'/user_group_admin/'.$group.'/').(isset($_POST['destination'])?$_POST['destination'].'/':''));
//  Den  Slash  am  Ende  nicht  vergessen!
$pathToFilesTmp = $view->getLocalFolder('/'.OCP\User::getUser().'/cache/uploader/');
//  Den  Slash  am  Ende  nicht  vergessen!

//$maxSize = 4194304;//  4  MB  Maximum,  sonst  kann  es  zum  Browser-crash  führen
$allowedFileTypes = array(); //  Erlaubte  MIME-Types.

switch ($_POST['Action']) {
	case  'getServerConfig':
		$iniSize = ini_get('upload_max_filesize');
		$entitys = array(0  =>  'B',  1  =>  'K',  2  =>  'M',  3  =>  'G');
		$entity = substr($iniSize,  strlen($iniSize)  -  1,  1);
		$size = substr($iniSize,  0,  strlen($iniSize)  -  1);
		$i = 0;

		while($entitys[$i] !=  $entity){
			$size *= 1024;
			$i++;
		}
		//$size = (($size  >  $maxSize)?$maxSize:$size);
		$ServerConfig = new  SimpleXMLElement('<ServerConfig/>');
		$ServerConfig->addChild('MaxFileSize',  $size);
		$iniTime = ini_get('max_input_time');
		$ServerConfig->addChild('MaxInputTime',  $iniTime);
		$el = $ServerConfig->addChild('MIME');
		foreach($allowedFileTypes  as  $key  =>  $val){
			$el->addChild('MIME-Type',  $val);
		}
		header('Content-Type:  text/xml;  charset=UTF-8');
		echo($ServerConfig->asXML());
		break;
	case 'upload':
		if(!file_exists($pathToFilesTmp)){
			mkdir($pathToFilesTmp);
		}
		if(!file_exists($pathToFiles)){
			mkdir($pathToFiles);
		}
		move_uploaded_file($_FILES['fileToUpload']['tmp_name'],  $pathToFilesTmp.$_POST['fileName'].'_'.$_POST['fileIndex']);
		if(isset($_POST['fileDone'])){
			$target = $pathToFiles.$_POST['fileName'];
			$dst = fopen($target,  'wb');
			$i = 0;
			while(file_exists($pathToFilesTmp.$_POST['fileName'].'_'.$i)){
				$src = fopen($pathToFilesTmp.$_POST['fileName'].'_'.$i,  'rb');
				stream_copy_to_stream($src,  $dst);
				fclose($src);
				unlink($pathToFilesTmp.$_POST['fileName'].'_'.$i);
				$i++;
			}
			
			//  Scan  the  uploaded  file,  get  ID  to  return  for  sharing
			
			if(!empty($group)){
				$group_dir = "/" . $user . "/user_group_admin/".$group;
				$view = new \OC\Files\View($group_dir);
			}
			else{
				$view = \OC\Files\Filesystem::getView();
			}
			list($storage,  $internalPath)  =  $view->resolvePath('/'.$_POST['destination'].'/'.$_POST['fileName']);
			if($storage){
				OC_Log::write('uploader','Scanning:  '.$storage->getCache()->getNumericStorageId().'-->'.$_POST['destination'].'/'.$_POST['fileName'],  OC_Log::WARN);
				$scanner  =  $storage->getScanner($internalPath);
				$fileData  =  $scanner->scanFile($internalPath);
				$res = array();
				$res['fileid'] = $fileData['fileid'];
				$res['etag'] = $fileData['etag'];
				$res['mimetype'] = $fileData['mimetype'];
			}
			\OCP\JSON::encodedPrint($res);
		}
		break;
	case 'delParts':
		$i = 0;
		while(file_exists($pathToFiles.$_POST['fileName'].'_'.$i)){
			unlink($pathToFiles.$_POST['fileName'].'_'.$i);
			$i++;
			}
		echo  $i.'  Teil-Datei(en)  gelöscht!';
		break;

	default:
		echo  '0';
	}
?>
