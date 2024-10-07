<?php
/**
 * APP: Laika
 * Author: Showket Ahmed
 * APP Link: https://cloudbillmaster.com
 * Email: riyadtayf@gmail.com
 * Version: 1.0.0
 * Provider: Cloud Bill Master Ltd.
 */

// Direct Access Denied
defined('ROOTPATH') || http_response_code(403). die('403 Forbidden Access!');

// Load Vendor Autoload
require_once(ROOTPATH.'/vendor/autoload.php');

// Load Config Folder
$conf = glob(ROOTPATH.'/config/*.php');
foreach($conf as $file){
    require_once($file);
}

