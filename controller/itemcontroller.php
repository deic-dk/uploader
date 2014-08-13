<?php

namespace OCA\Uploader\Controller;

use OCA\AppFramework\Controller\Controller;
use OCA\AppFramework\Db\DoesNotExistException;
use OCA\AppFramework\Http\RedirectResponse;

use OCA\Uploader\Db\Item;


class ItemController extends Controller {

	private $itemMapper;

	/**
	 * @param Request $request: an instance of the request
	 * @param API $api: an api wrapper instance
	 * @param ItemMapper $itemMapper: an itemwrapper instance
	 */
	public function __construct($api, $request, $itemMapper){
		parent::__construct($api, $request);
		$this->itemMapper = $itemMapper;
	}


	/**
	 * ATTENTION!!!
	 * The following comments turn off security checks
	 * Please look up their meaning in the documentation!
	 *
	 * @CSRFExemption
	 * @IsAdminExemption
	 * @IsSubAdminExemption
	 *
	 * Redirects to the index page
	 */
	public function redirectToIndex(){
		$url = $this->api->linkToRoute('uploader_index');
		return new RedirectResponse($url);
	}


	/**
	 * ATTENTION!!!
	 * The following comments turn off security checks
	 * Please look up their meaning in the documentation!
	 *
	 * @CSRFExemption
	 * @IsAdminExemption
	 * @IsSubAdminExemption
	 *
	 * @brief renders the index page
	 * @return an instance of a Response implementation
	 */
	public function index(){
		// example database access
		// check if an entry with the current user is in the database, if not
		// create a new entry
		try {
			$item = $this->itemMapper->findByUserId($this->api->getUserId());
		} catch (DoesNotExistException $e) {
			$item = new Item();
			$item->setUser($this->api->getUserId());
			$item->setPath('/home/path');
			$item->setName('john');
			$this->itemMapper->insert($item);
		}

		$templateName = 'main';
		$params = array(
			'somesetting' => $this->api->getAppValue('somesetting'),
			'item' => $item,
			'test' => $this->params('test')
		);
          $this->api->addScript('html5fileupload');
          $this->api->addScript('html5fileupload_create');
          $this->api->addStyle('html5fileupload');
		return $this->render($templateName, $params);
	}



	/**
	 * @Ajax
	 *
	 * @brief sets a global system value
	 * @param array $urlParams: an array with the values, which were matched in 
	 *                          the routes file
	 */
	public function setSystemValue(){
		$value = $this->params('somesetting');
		$this->api->setAppValue('somesetting', $value);

		$params = array(
			'somesetting' => $value
		);

		return $this->renderJSON($params);
	}

	/**
	 * @Ajax
	 */
	public function getSystemValue(){
		$value = $this->api->getAppValue('somesetting');

		$params = array(
			'somesetting' => $value
		);

		return $this->renderJSON($params);
	}

}
