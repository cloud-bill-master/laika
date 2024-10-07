<?php
/**
 * APP: Laika
 * Author: Showket Ahmed
 * APP Link: https://cloudbillmaster.com
 * Email: riyadtayf@gmail.com
 * Version: 1.0.0
 * Provider: Cloud Bill Master Ltd.
 */
namespace CBM\src\model;

// Forbidden Access
defined('ROOTPATH') || http_response_code(401).die('403 Forbidden Access!');

use CBM\app\App;
use CBM\app\Model;

class Options
{
    // DB ID
    private static $id = 'option_id';

    // Table Name Var
    private static $table = 'options';

	// Get All Data
	public static function getAll(array $where = []):array
	{
		return Model::conn()->table(self::$table)->select()->get();
	}

    // Get Limit Data
	public static function getLimit(array $where = []):array
	{
		return Model::conn()->table(self::$table)->select()->limit()->get();
	}

    // Get Single
    public static function getOption(string $name):string
    {
        $val = Model::conn()->table(self::$table)->select('value')->where(['name'=>strtolower($name)])->single();
        return $val["value"] ?? "";
    }
}