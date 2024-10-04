<?php
/**
 * Framework: Laika
 * Author: Showket Ahmed
 * Website: https://cloudbillmaster.com
 * Version: 1.0.0
 */
namespace CBM\app\http;

defined('ROOTPATH') || http_response_code(401). die('Access Denied');

class Uri
{
    // Sub Directory
    public String $subdir;

    // App Uri
    public String $appuri;

    // SLUG
    public Array $slug;

    public function __construct()
    {
        $this->subdir = $this->subdir();
        $this->appuri = $this->appuri();
        $this->slug = $this->slug();
    }

    // App Sub Directory
    private function subdir():string
    {
        $dir = str_replace('\\', '/', ROOTPATH);
        $doc = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
        return trim(str_replace($doc, '', $dir), '/');
    }
    
    // App Uri
    private function appuri():string
    {
        $http = (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] != 'off')) ? 'https://' : 'http://';
        $host = $http . ($_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME']);
        return $this->subdir() ? $host . '/' . $this->subdir() : $host;
    }

    // App Slug
    private function slug():array
    {
        $request_uri = trim(str_replace('/index.php', '', $_SERVER['REQUEST_URI']),'/');
        if($this->subdir()){
            $request_uri = str_replace('/'.$this->subdir(), '', $_SERVER['REQUEST_URI']);
            $request_uri = trim(str_replace('/index.php', '', $request_uri), '/');
        }
        return $request_uri ? explode('/', $request_uri) : [0=>'index'];
    }
}