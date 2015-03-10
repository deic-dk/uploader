<?php

namespace OCA\Uploader;

$this->create('uploader_index', '/')->action(
		function($params){
			require __DIR__.'/../index.php';
		}
);
