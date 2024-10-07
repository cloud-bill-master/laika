<?php
/**
 * APP: Laika
 * Author: Showket Ahmed
 * APP Link: https://cloudbillmaster.com
 * Email: riyadtayf@gmail.com
 * Version: 1.0.0
 * Provider: Cloud Bill Master Ltd.
 */

 namespace CBM\app;

// Forbidden Access
defined('ROOTPATH') || http_response_code(403).die('403 Forbidden Access!');

use CBM\src\model\Options;

// class Call
class Cons
{
    // Auto Load Constants
    public function __call($name, $arguments)
    {
        return Options::getOption($name);
    }

    // Auto Load Constants
    public static function __callStatic($name, $arguments)
    {
        return Options::getOption($name);
    }
}