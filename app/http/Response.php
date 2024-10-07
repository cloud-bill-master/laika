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

class Response
{
    // Response Code
    private Int|String $code;

    // Output Message
    public String $message;

    public function __construct()
    {
        $this->code = 200;
        $this->message = "Data Found!";
    }

    // Set Response Code
    public function set(int|string $code = null):void
    {
        $this->code = (int) ($code ?: $this->code);
        http_response_code($this->code);
    }

    // JSON Output
    public function stream(Array $data = [], ?String $message = NULL, Int $code = NULL)
    {
        // Set Response Code
        $this->code = $code ?: $this->code;
        http_response_code($this->code);

        // Set Message
        $output['message'] = $message ?: $this->message;

        // Set Response Status
        $output['status'] = ($this->code == 200) ? 'success' : 'failed';
        $output['response'] = $this->code;

        // Set Response Data
        $output['data'] = $data;
        // Return Response
        return json_encode($output, JSON_FORCE_OBJECT|JSON_PRETTY_PRINT);
    }
}