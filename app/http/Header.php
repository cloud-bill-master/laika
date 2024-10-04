<?php
/**
 * Framework: Laika
 * Author: Showket Ahmed
 * Website: https://cloudbillmaster.com
 * Version: 1.0.0
 */
namespace CBM\app\http;

defined('ROOTPATH') || http_response_code(401). die('Access Denied');

class Header
{
    // Response Code
    private Int $code = 200;

    // Set Response Code
    public function response(int|string $code = null):void
    {
        $this->code = $code ?: $this->code;
        http_response_code($this->code);
    }
}