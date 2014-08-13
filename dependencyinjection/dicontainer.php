<?php

namespace OCA\Uploader\DependencyInjection;

use OCA\AppFramework\DependencyInjection\DIContainer as BaseContainer;

use OCA\Uploader\Controller\ItemController;
use OCA\Uploader\Controller\SettingsController;
use OCA\Uploader\Db\ItemMapper;


class DIContainer extends BaseContainer {


	/**
	 * Define your dependencies in here
	 */
	public function __construct(){
		// tell parent container about the app name
		parent::__construct('uploader');


		/**
		 * Delete the following twig config to use ownClouds default templates
		 */
		// use this to specify the template directory
		$this['TwigTemplateDirectory'] = __DIR__ . '/../templates';

		// if you want to cache the template directory in yourapp/cache
		// uncomment this line. Remember to give your webserver access rights
		// to the cache folder 
		// $this['TwigTemplateCacheDirectory'] = __DIR__ . '/../cache';		

		/** 
		 * CONTROLLERS
		 */
		$this['ItemController'] = $this->share(function($c){
			return new ItemController($c['API'], $c['Request'], $c['ItemMapper']);
		});

		$this['SettingsController'] = $this->share(function($c){
			return new SettingsController($c['API'], $c['Request']);
		});


		/**
		 * MAPPERS
		 */
		$this['ItemMapper'] = $this->share(function($c){
			return new ItemMapper($c['API']);
		});


	}
}

