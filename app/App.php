<?php

namespace CBM\app;

use CBM\app\http\Request;
use CBM\app\http\Header;
use CBM\app\http\Uri;

class App
{
    // Request
    public Request $request;
    // Uri
    public Uri $uri;
    // Header
    public Header $header;

    public function __construct()
    {
        $this->request    = new Request;
        $this->uri        = new Uri;
        $this->header     = new Header;
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