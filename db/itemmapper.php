<?php

namespace OCA\Uploader\Db;

use \OCA\AppFramework\Core\API;
use \OCA\AppFramework\Db\Mapper;


class ItemMapper extends Mapper {


	/**
	 * @param API $api: Instance of the API abstraction layer
	 */
	public function __construct($api){
		parent::__construct($api, 'apptemplateadvanced_items');
	}


	/**
	 * Finds an item by id
	 * @throws DoesNotExistException: if the item does not exist
	 * @throws MultipleObjectsReturnedException if more than one item exist
	 * @return the item
	 */
	public function find($id){
		$sql = 'SELECT * FROM `' . $this->getTableName() . '` WHERE `id` = ?';
		$params = array($id);
		
		$row = $this->findOneQuery($sql, $params);
		
		$item = new Item();
		$item->fromRow($row);
		return $item;
	}


	/**
	 * Finds an item by user id
	 * @param string $userId: the id of the user that we want to find
	 * @throws DoesNotExistException: if the item does not exist
	 * @throws MultipleObjectsReturnedException if more than one item exist
	 * @return the item
	 */
	public function findByUserId($userId){
		$sql = 'SELECT * FROM `' . $this->getTableName() . '` WHERE `user` = ?';
		$params = array($userId);

		$row = $this->findOneQuery($sql, $params);
		
		$item = new Item();
		$item->fromRow($row);
		return $item;
	}


	/**
	 * Finds all Items
	 * @return array containing all items
	 */
	public function findAll(){
		$sql = 'SELECT * FROM `' . $this->getTableName() . '`';

		$result = $this->execute($sql);
		
		$entityList = array();

		while($row = $result->fetchRow()){
			$item = new Item();
			$item->fromRow($row);
			array_push($entityList, $item);
		}

		return $entityList;
	}


}
