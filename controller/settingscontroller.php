<?php

namespace OCA\Uploader\Controller;

use OCA\AppFramework\Controller\Controller;


class SettingsController extends Controller {
	

	/**
	 * @param Request $request: an instance of the request
	 * @param API $api: an api wrapper instance
	 */
	public function __construct($api, $request){
		parent::__construct($api, $request);
	}


	/**
	 * ATTENTION!!!
	 * The following comment turns off security checks
	 * Please look up their meaning in the documentation!
	 *
	 * @CSRFExemption 
	 *
	 * @brief renders the settings page
	 * @param array $urlParams: an array with the values, which were matched in 
	 *                          the routes file
	 */
	public function index(){
		$templateName = 'settings';
		$params = array(
			'url' => $this->api->getAppValue('somesetting')
		);

		return $this->render($templateName, $params, 'blank');
	}

}
