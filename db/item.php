<?php

namespace OCA\Uploader\Db;

use \OCA\AppFramework\Db\Entity;


class Item extends Entity {

	public $id;
	public $name;
	public $path;
	public $user;

}
