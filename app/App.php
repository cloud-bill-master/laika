<?php

namespace CBM\app;

use CBM\app\http\Request;
use CBM\app\http\Uri;

class App
{
    // Request
    public Request $request;
    public Uri $uri;

    public function __construct()
    {
        $this->request    = new Request;
        $this->uri        = new Uri;
    }

    public static function load()
    {
        return new Static;
    }

    // Run Application
    public function run()
    {
        return 'App Running';
    }
}