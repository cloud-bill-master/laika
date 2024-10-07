<?php
/**
 * APP: Laika
 * Author: Showket Ahmed
 * APP Link: https://cloudbillmaster.com
 * Email: riyadtayf@gmail.com
 * Version: 1.0.0
 * Provider: Cloud Bill Master Ltd.
 */

// Namespace
namespace app\core;

// Forbidden Access
defined('ROOTPATH') || http_response_code(403).die('403 Forbidden Access!');

// Forbidden Access
defined('ROOTPATH') || http_response_code(401).die('403 Forbidden Access!');

use app\core\http\Request;
use app\helper\function\Functions;

class Router
{
    // Request Uri
    public array $uri;

    // Request
    private Request $request;

    // Page Controller
    private String $controller;

    // Method
    private String $method;

    // Construct
    public function __construct()
    {
        $this->request = new Request();
        $path = $this->request->requestPath();
        // echo $path;
        // $this->controller = 'Index';
        $this->method = 'index';
        $this->uri = ($path) ? explode('/', strtolower(trim($path, '/'))) : [];
    }

    // Resolve Request And Actions
    public function resolve()
    {
        // Check Authentication
        // Functions::checkAuth();

        $this->controller = (!$this->uri) ? 'Home' : ucfirst($this->uri[0]);

        // Set Controller File
        $this->controller = "app\\controller\\{$this->controller}";

        // $this->method = url(1) ? strtolower(url(1)) : 'index';

        if(!class_exists($this->controller) || !method_exists(new $this->controller, $this->method))
        {
            $this->controller = "app\\controller\\_404";
        }
        // Call Class & Method
        call_user_func([new $this->controller, $this->method]);
    }
}