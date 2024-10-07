<?php
/**
 * APP: Laika
 * Author: Showket Ahmed
 * APP Link: https://cloudbillmaster.com
 * Email: riyadtayf@gmail.com
 * Version: 1.0.0
 * Provider: Cloud Bill Master Ltd.
 */
namespace CBM\app\http;

// Direct Access Denied
defined('ROOTPATH') || http_response_code(403). die('403 Forbidden Access!');

class Request
{
    // Request Method
    public String $method;

    public function __construct()
    {
        $this->method = strtolower($_SERVER['REQUEST_METHOD']);
    }

    // Check if Requested by Post
    public function isPost():bool
    {
        return $this->method === 'post';
    }

    // Check if Requested by Get
    public function isGet():bool
    {
        return $this->method === 'get';
    }

    // Request Get Value
    public function get(string $key):string
    {
        if($this->isGet()){
            return ($this->data()[$key] ?? '');
        }
        return '';
    }

    // Request Get Value
    public function post(string $key):string
    {
        if($this->isPost()){
            return ($this->data()[$key] ?? '');
        }
        return '';
    }

    // Get Request Data
    public function data(array $request = []):array
    {
        $data = [];
        $request = $request ?: $_REQUEST;
        foreach($request as $key => $val){
            if(!is_array($val)){
                $data[$key] = htmlspecialchars($val);
            }else{
                $data[$key] = $this->data($val);
            }
        }
        return $data;
    }
}