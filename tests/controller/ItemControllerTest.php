<?php

namespace OCA\Uploader\Controller;

use OCA\AppFramework\Http\Request;
use OCA\AppFramework\Db\DoesNotExistException;
use OCA\AppFramework\Utility\ControllerTestUtility;

use OCA\Uploader\Db\Item;


require_once(__DIR__ . "/../classloader.php");


class ItemControllerTest extends ControllerTestUtility {


	public function testRedirectToIndexAnnotations(){
		$api = $this->getAPIMock();
		$controller = new ItemController($api, new Request(), null);
		$methodName = 'redirectToIndex';
		$annotations = array('CSRFExemption', 'IsAdminExemption', 'IsSubAdminExemption');

		$this->assertAnnotations($controller, $methodName, $annotations);
	}


	public function testIndexAnnotations(){
		$api = $this->getAPIMock();
		$controller = new ItemController($api, new Request(), null);
		$methodName = 'index';
		$annotations = array('CSRFExemption', 'IsAdminExemption', 'IsSubAdminExemption');

		$this->assertAnnotations($controller, $methodName, $annotations);
	}


	public function testIndexGetSystemValue(){
		$api = $this->getAPIMock();
		$api->expects($this->any())
					->method('getAppValue')
					->with($this->equalTo('somesetting'))
					->will($this->returnValue('systemvalue'));

		$itemMapperMock = $this->getMock('ItemMapper', array('findByUserId'));

		$controller = new ItemController($api, new Request(), $itemMapperMock);

		$response = $controller->index();
		$params = $response->getParams();
		$this->assertEquals('systemvalue', $params['somesetting']);
	}


	public function testIndexItemExists(){
		$api = $this->getAPIMock();
		$api->expects($this->any())
					->method('getUserId')
					->will($this->returnValue('richard'));

		$item = new Item();
		$item->setUser('user');
		$item->setPath('/path');
		$item->setId(3);
		$item->setName('name');

		$itemMapperMock = $this->getMock('ItemMapper', array('findByUserId'));
		$itemMapperMock->expects($this->any())
					->method('findByUserId')
					->will($this->returnValue($item));
		
		$controller = new ItemController($api, new Request(), $itemMapperMock);

		$response = $controller->index();
		$params = $response->getParams();
		$this->assertEquals($item, $params['item']);
	}


	public function testIndexItemDoesNotExist(){
		$api = $this->getAPIMock();
		$api->expects($this->any())
					->method('getUserId')
					->will($this->returnValue('richard'));

		$itemMapperMock = $this->getMock('ItemMapper', array('findByUserId', 'insert'));
		$itemMapperMock->expects($this->any())
					->method('findByUserId')
					->will($this->throwException(new DoesNotExistException('')));

		$controller = new ItemController($api, new Request(), $itemMapperMock);

		$response = $controller->index();
		$params = $response->getParams();

		$this->assertEquals('richard', $params['item']->getUser());
		$this->assertEquals('/home/path', $params['item']->getPath());
		$this->assertEquals('john', $params['item']->getName());
	}


	public function testSetSystemValueAnnotations(){
		$api = $this->getAPIMock();
		$controller = new ItemController($api, new Request(), null);	
		$methodName = 'setSystemValue';
		$annotations = array('Ajax');

		$this->assertAnnotations($controller, $methodName, $annotations);
	}


	public function testSetSystemValue(){
		$post = array('somesetting' => 'this is a test');
		$request = new Request(array(), $post);

		// create an api mock object
		$api = $this->getAPIMock();

		// expects to be called once with the method
		// setSystemValue('somesetting', 'this is a test')
		$api->expects($this->once())
					->method('setAppValue')
					->with(	$this->equalTo('somesetting'),
							$this->equalTo('this is a test'));

		// we want to return the appname uploader when this method
		// is being called
		$api->expects($this->any())
					->method('getAppName')
					->will($this->returnValue('uploader'));

		$controller = new ItemController($api, $request, null);
		$response = $controller->setSystemValue(null);

		// check if the correct parameters of the json response are set
		$this->assertEquals($post, $response->getParams());
	}


}
