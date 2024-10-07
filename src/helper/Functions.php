<?php
/**
 * APP: Laika
 * Author: Showket Ahmed
 * APP Link: https://cloudbillmaster.com
 * Email: riyadtayf@gmail.com
 * Version: 1.0.0
 * Provider: Cloud Bill Master Ltd.
 */

namespace CBM\src\helper;

// Direct Access Denied
defined('ROOTPATH') || http_response_code(403). die('403 Forbidden Access!');

use CBM\app\App;

class Functions
{
    // Shutdown Register function
    public function explain(object $e, string $message = NULL){
        if($message){
            $this->show("Message: ".$message);
        }
        $this->show($e, true);
    }

    // Show A Variable Value/Values
    public function show($data, bool $die = false):void
    {
        echo "<pre style=\"background-color:#000;color:#fff;margin:0\">";
        print_r($data);
        echo "</pre>";
        $die ? die() : $die;
    }

    // Dump Data & Die
    public function dd($data, bool $die = false):void
    {
        echo '<pre style="background-color:#000;color:#fff;margin:0">';
        var_dump($data);
        echo '</pre>';
        $die ? die() : $die;
    }

    // App Url
    public function appUri():string
    {
        return App::load()->cons->webhost() ?: App::load()->request->$this->appUri();
    }

    //  Urls Array
    public function url(int|string $index):string
    {
        return App::load()->router->uri[(int) $index] ?? false;
    }

    // Check Values Are Same
    public function matchValue($value, $match, $strict = false):bool // Stricts validate same type
    {
        return $strict ? ($value === $match) : ($value == $match);
    }

    // Old Value
    public function oldValue(string $name):string
    {
        if((App::load()->request->post($name)) || App::load()->request->get($name)){
            return App::load()->request->data()[$name];
        }
        return "";
    }

    // Old Select
    public function oldSelect(?string $key = '', string $match = '')
    {
        if((App::load()->request->post($key) === $match) || App::load()->request->get($key) === $match){
            return "selected";
        }elseif($key === $match){
            return "selected";
        }
        return "";
    }

    // Old Checked
    public function oldChecked(bool|string $key = NULL, int|bool|string $match = NULL)
    {
        return ($key === $match) ? " checked" : "";
    }

    // Create Link
    public function location(string $location = ''):string
    {
        $location = trim(trim($location, '/'));
        $location = (!empty($location)) ? '/' . $location : '/';
        return $this->appUri().$location;
    }

    // Redirect public function
    public function redirect(string $location = '', int $code = 302)
    {
        App::load()->response->set($code);
        header('Location:'.$this->location($location));
        die();
    }

    // Create Link
    public function getUri(string $name = '', string $arg = NULL):string
    {
        $name = $name ?: 'sameUri';
        return $arg ? Uri::$name($arg) : Uri::$name();
    }

    // Get Visitor IP
    public function getClientIp():string
    {
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        $client  = $_SERVER['HTTP_CLIENT_IP'] ?? NULL;
        $forward = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? NULL;
        $remote  = $_SERVER['REMOTE_ADDR'];

        if(filter_var($client, FILTER_VALIDATE_IP))
        {
            $ip = $client;
        }
        elseif(filter_var($forward, FILTER_VALIDATE_IP))
        {
            $ip = $forward;
        }
        else
        {
            $ip = $remote;
        }
        return $ip;
    }

    // To JSON
    public function toJson(array $array, bool $object = false):string
    {
        return $object ? json_encode($array, JSON_FORCE_OBJECT | JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT) : json_encode($array, JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT);
    }

    // From JSON
    public function fromJson(string $string):string|array
    {
        return json_decode($string, true);
    }

    // To Object
    public function toObject(string|array $arr)
    {
        $arr = is_string($arr) ? fromJson($arr) : $arr;
        $obj = new stdClass;
        foreach($arr as $key => $value){
            if(is_array($value)){
                $value = toObject($value);
            }
            $obj->$key = $value;
        }
        return $obj;
    }

    // Convert To Float Number
    public function toFloat(int|string|float|null $number, int|string $decimal = 2)
    {
        $new_number = is_numeric($number) ? number_format($number, (int) $decimal,'.','') : number_format(0, (int) $decimal,'.','');
        return $new_number;
    }

    // Convert to Price
    public function toPrice(string|int|float $price = NULL, int|string $decimal = 2):string
    {
        $decimal = (int) $decimal;
        $price = toFloat($price, $decimal);
        return App::load()->cons->currencypfx() . $price;
    }

    // Show Date in a Format
    public function showDate(?string $date)
    {
        $date = (string) $date;
        if($date){
            return Dates::to_date($date);
        }
        return Lang::$noDate;
    }

    // Local Date
    public function localDateTime(?string $datetime)
    {
        if($datetime){
            $str = strtotime($datetime);
            return date('Y-m-d\TH:i:s', $str);
        }
        return '0000-00-00T00:00:00';
    }

    // Check Staff Has Access
    public function access(string $access):bool
    {
        $accessList = json_decode(App::load()->session->get('admin_access_list', ADMIN));
        return $accessList->$access ?? false;
    }

    // Staff Has Permission "Comma Separated Value"
    public function hasPermission(string $access, string $location = '')
    {
        $permissions = explode(',', $access);
        foreach($permissions as $permission)
        {
            if(!access(trim($permission)))
            {
                if(str_contains($permission, 'view')){
                    setMessage(LANG::$noViewPermission, false);
                }elseif(str_contains($permission, 'add')){
                    setMessage(LANG::$noAddPermission, false);
                }elseif(str_contains($permission, 'edit')){
                    setMessage(LANG::$noEditPermission, false);
                }elseif(str_contains($permission, 'remove')){
                    setMessage(LANG::$noRemovePermission, false);
                }else{
                    setMessage(LANG::$noPermission, false);
                }
                redirect($location);
            }
        }
    }

    // Get $_POST Value
    public function post(string $key):bool|string|array
    {
        return App::load()->request->post($key);
    }

    // Get $_GET Value
    public function get(string $key):bool|string
    {
        return App::load()->request->get($key);
    }

    // Get Request Data
    public function requestData():array
    {
        return App::load()->request->data();
    }

    // Valid Username Check
    public function isValidUsername(?string $username):bool
    {
        $folderMatch = trim(App::load()->request->subDirectory(), '/');
        // Check Minimum 6 Character
        if((strlen($username)<6) || preg_match('/[^a-zA-Z]/i', $username))
        {
            setMessage(Lang::$invalidUsername, false);
            return false;
        }
        if(str_contains($username, $folderMatch)){
            setMessage(Lang::$unsupportedUsername, false);
            return false;
        }
        return true;
    }

    // Check Input Password & Confirm Passwod
    public function checkInputPasswords(?string $password, ?string $confirmPassword):bool
    {
        if(strlen($password) < 6){
            setMessage(Lang::$minimum6CharPassword, false);
            return false;
        }
        // check for lowercase letter
        if(!preg_match("/[a-z]/", $password)){
            setMessage(Lang::$minimum1LowerCase, false);
            return false;
        }
        // check for uppercase letter
        if(!preg_match("/[A-Z]/", $password)){
            setMessage(Lang::$minimum1UpperCase, false);
            return false;
        }
        // check for number
        if(!preg_match("/\d/", $password)){
            setMessage(Lang::$minimum1Number, false);
            return false;
        }
        // check for special character
        if(!preg_match("/[!@#$%^&*()\-_=+{};:,<.>]/", $password)){
            setMessage(Lang::$minimum1SpecialChar, false);
            return false;
        }
        // Check Password & Confirm Password is Same
        if($password != $confirmPassword){
            setMessage(Lang::$passwordNotSame, false);
            return false;
        }
        return true;
    }

    // Valid Email Check
    public function isValidEmail(?string $email):bool
    {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            setMessage(sprintf(Lang::$invalidEmail, $email), false);
            return false;
        }
        return true;
    }

    // Check Superadmin
    public function isSuperadmin():bool
    {
        if(App::load()->session->get('role_id', ADMIN) == 1)
        {
            return true;
        }
        return false;
    }

    // Has Status Change Permission
    public function hasStatusChangePermission(string $access = 'editOrderStatus'):bool
    {
        if((isSuperadmin()) || (isPartialadmin() || access($access))){
            return true;
        }
        return false;
    }

    // Check Partial Admin
    public function isPartialadmin():bool
    {
        if(App::load()->session->get('role_id', ADMIN) == 2)
        {
            return true;
        }
        return false;
    }

    // Check Token for Post Method
    public function checkToken(string $location = ''):void
    {
        if(App::load()->request->data()['token'])
        {
            if(!hash_equals(App::load()->session->get('token'), App::load()->request->post('token')))
            {
                setMessage(Lang::$invalidCsrf, false);
                App::load()->session->pop('token');
                redirect($location);
            }else{
                App::load()->session->pop('token');
            }
        }else{
            setMessage(Lang::$csrfNotSet, false);
            App::load()->session->pop('token');
            redirect($location ?: Uri::sameUri());
        }
    }

    // Send Email
    public function sendEmail(string $email, string $message):bool
    {
        return true;
    }

    /////////////////////////
    /// Template public function ///
    /////////////////////////

    // App Logo
    public function appLogo():string
    {
        $logo = App::load()->cons->logo();
        return $this->appUri() . '/assets/img/' . ($logo ?: 'logo.png');
    }

    // App Logo
    public function appIcon():string
    {
        $icon = App::load()->cons->favicon();
        return $this->appUri() . '/assets/img/' . ($icon ?: 'favicon.png');
    }

    // Uploads Image
    public function getImage(string $filename):string
    {
        return $this->appUri()."/uploads/images/".$filename;
    }

    // Uploads Image
    public function getBarcodeImage(string $filename):string
    {
        return $this->appUri()."/uploads/barcodes/".$filename;
    }

    // Set Message
    public function setMessage(string $message, bool $type = true):void
    {
        if($type)
        {
            App::load()->session->set("message", "<div class=\"app-text-success app-my-1\">".$message."</div>");
        }else{
            App::load()->session->set("message", "<div class=\"app-text-danger app-my-1\">".$message."</div>");
        }
    }

    // Show Message
    public function message():string
    {
        $message = App::load()->session->get('message');
        App::load()->session->pop('message');
        return $message;
    }

    // Set Title
    public function appHeader():string
    {
        return '';
    }

    // Get Head public function
    public function appFooter():string
    {
        $html = "<!-- App Footer Start -->\n";
        $html .= "<script src=\"".$this->appUri()."/assets/js/app.js\"></script>\n\t";
        return $html;
    }

    // Set Meta
    public function setMeta(string $name, string $content):void
    {
        if(isset($GLOBALS['meta'])){
            $GLOBALS['meta'][] = "<meta name=\"{$name}\" content=\"{$content}\">";
        }
    }

    // Load Theme Style
    public function loadThemeStyle(string $name):void
    {
        echo functions::loadThemeStyle($name);
    }

    // Load Theme Script
    public function loadThemeScript(string $name):void
    {
        echo functions::loadThemeScript($name);
    }

    // Load Navbar
    public function loadNavbar()
    {
        $class = new \app\core\hooks\Adminnav();
        return $class->nav();
    }

    // Selected Background
    public function selectedBg(string $key, int|string $value = NULL, string $class = 'app-bg-default text-light')
    {
        if((App::load()->request->get($key) == $value) || (App::load()->request->post($key) == $value)){
            return $class;
        }
        return '';
    }
}