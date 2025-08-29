<?php

namespace OCA\Uploader;

\OCP\App::registerPersonal('uploader', 'personalsettings');

\OCP\App::addNavigationEntry( array(
	
	// the string under which your app will be referenced
	// in owncloud, for instance: \OC_App::getAppPath('APP_ID')
	'id' => 'uploader',

	// sorting weight for the navigation. The higher the number, the higher
	// will it be listed in the navigation
	'order' => 29,
	
	// the route that will be shown on startup
	'href' => \OCP\Util::linkToRoute('uploader_index'),
	
	// the icon that will be shown in the navigation
//	'icon' => \OCP\Util::imagePath('uploader', 'uploader.svg' ),
	
	// the title of your application. This will be used in the
	// navigation or on the settings page of your app
	'name' => \OC_L10N::get('uploader')->t('Upload')
	
));

\OC::$CLASSPATH['Uploader_Activity'] ='apps/uploader/activity.php';
\OC::$server->getActivityManager()->registerExtension(function() {
	return new \Uploader_Activity(
			\OC::$server->query('L10NFactory'),
			\OC::$server->getURLGenerator(),
			\OC::$server->getActivityManager(),
			\OC::$server->getConfig()
			);
});
	
