<?php

namespace OCA\Uploader;

use \OCA\AppFramework\App;

use \OCA\uploader\DependencyInjection\DIContainer;


/*************************
 * Define your routes here
 ************************/

/**
 * Normal Routes
*/
$this->create('uploader_index', '/')->action(
	function($params){
		App::main('ItemController', 'index', $params, new DIContainer());
	}
);
