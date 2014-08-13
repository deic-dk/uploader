<?php

namespace OCA\Uploader\Db;

require_once(__DIR__ . "/../classloader.php");


class ItemMapperTest extends \OCA\AppFramework\Utility\MapperTestUtility {

    private $mapper;
    private $row;

    protected function setUp(){
        $this->api = $this->getMock('OCA\AppFramework\Core\Api', array('prepareQuery'), array('apptemplateadvanced'));
        $this->mapper = new ItemMapper($this->api);
        $this->row = array(
            'id' => 1,
            'user' => 'john',
            'path' => '/test',
            'name' => 'right'
        );

    }


    public function testFindByUserId(){
        $userId = 1;
        $expected = 'SELECT * FROM `*PREFIX*apptemplateadvanced_items` WHERE `user` = ?';

        $this->setMapperResult($expected, array($userId), array($this->row));

        $item = $this->mapper->findByUserId($userId);

        $this->assertEquals($this->row['id'], $item->getId());
        $this->assertEquals($this->row['path'], $item->getPath());
        $this->assertEquals($this->row['name'], $item->getName());
        $this->assertEquals($this->row['user'], $item->getUser());
    }


}
