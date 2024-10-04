<?php

define('ROOTPATH', __DIR__);

require_once(ROOTPATH.'/app/Init.php');

// Run Application
$app = new CBM\app\App;
$app->run();
