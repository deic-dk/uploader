<?php

namespace OCA\Uploader\Admin;

use OCA\AppFramework\App;

use OCA\Uploader\DependencyInjection\DIContainer;


// we need to fetch the output and return it for the admin page. Dont ask why
ob_start();

App::main('SettingsController', 'index', array(), new DIContainer());

$content = ob_get_contents();
ob_clean();

return $content; 
