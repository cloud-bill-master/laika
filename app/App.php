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

// Direct Access Denied
defined('ROOTPATH') || http_response_code(403). die('403 Forbidden Access!');

// use CBM\app\http\Request;
// use CBM\app\http\Header;
// use CBM\app\http\Uri;

class App
{
    // Request
    public http\Request $request;

    // Response
    public http\Response $response;

    // Uri
    public http\Uri $uri;

    // Header
    public http\Header $header;

    // Constants
    public Cons $cons;

    public function __construct()
    {
        $this->request  = new http\Request;
        $this->uri      = new http\Uri;
        $this->header   = new http\Header;
        $this->cons     = new Cons;
    }

    public static function load()
    {
        return new Static;
    }

    // Run Application
    public function run()
    {
        echo 'App Running';
    }
}