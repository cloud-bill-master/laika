<?php
/**
 * APP: Laika
 * Author: Showket Ahmed
 * APP Link: https://cloudbillmaster.com
 * Email: riyadtayf@gmail.com
 * Version: 1.0.0
 * Provider: Cloud Bill Master Ltd.
 */

// Direct Access Denied
defined('ROOTPATH') || http_response_code(403). die('403 Forbidden Access!');

// Database Driver
define('DB_DRIVER', 'mysql');

// Database Host
define('DB_HOST', 'localhost');

// Database connection Port
define('DB_PORT', 3306);

// Database User
define('DB_USER', 'root');

// Database Password
define('DB_PASSWORD', '');

// Database Table
define('DB_TABLE', 'laika');

// Database Table Prefix
define('TABLE_PREFIX', 'lk_');

// Limit
define('LIMIT', 20);