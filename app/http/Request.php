<?php
/**
 * Framework: Laika
 * Author: Showket Ahmed
 * Website: https://cloudbillmaster.com
 * Version: 1.0.0
 */
namespace CBM\app\http;

defined('ROOTPATH') || http_response_code(401). die('Access Denied');

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