<?php

namespace CBM\app\http;

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