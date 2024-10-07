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
namespace CBM\app;

// Forbidden Access
defined('ROOTPATH') || http_response_code(403).die('403 Forbidden Access!');

use PDO;
use PDOException;
use CBM\src\helper\Functions;

class Model
{
    private static $instance = null;
    private $pdo;
    private $offset = 0;
    private $table;
    private $select = '*';
    private $joins = [];
    private $where = [];
    private $filter = [];
    private $operator = '';
    private $order = '';
    private $limit = '';
    private $params = [];

    // Start Connection
    private function __construct() {
        $dsn = strtolower(DB_DRIVER).":host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_TABLE;
        try{
            $this->pdo = new PDO($dsn, DB_USER, DB_PASSWORD);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->pdo->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
        }catch(PDOException $e){
            exit('<body style="margin:0;"><div style="height:100vh;position:relative;"><h1 style="text-align:center;color:#ef3a3a; position:absolute;top:50%;left:50%;transform:translate(-50%, -50%);margin:0;">Database Connection Failed. </h1></div></body>');
        }
    }

    public static function conn():static
    {
        if(!self::$instance)
        {
            self::$instance = new static;
        }
        return self::$instance;
    }

    private function reset():void
    {
        $this->where = [];
        $this->filter = [];
        $this->joins = [];
        $this->params = [];
        $this->select = '*';
        $this->order = '';
        $this->limit = '';
        $this->operator = '';
        $this->table = '';
        $this->offset = 0;
    }

    // Make Table Name
    public function setTable(string $table):string
    {
        $table = strtolower($table);
        $pfx = strtolower(TABLE_PREFIX);
        if(strpos($table, $pfx) === false)
        {
            $table = $pfx.$table;
        }
        return $table;
    }

    // Set Table
    public function table(string $table):object
    {
        $table = $this->setTable($table);
        $this->table = $table;
        return $this;
    }

    // Set Slect Columns
    public function select(string $columns = '*'):object
    {
        $this->select = $columns;
        return $this;
    }

    // Set Join
    public function join(string $table, string $condition, string $type = 'LEFT'):object
    {
        $table = $this->setTable($table);
        $this->joins[] = "{$type} JOIN {$table} ON {$condition}";
        return $this;
    }

    // Set Where
    public function filter(string $column, string $operator, int|string $value):object
    {
        $this->filter[] = "{$column} {$operator} ?";
        $this->params[] = $value;
        return $this;
    }

    // Set Where
    public function where(array $where, string $compare = '=', string $operator = 'AND'):object // $operator = AND / OR / && / ||
    {
        $this->operator = $operator;
        foreach($where as $key=>$value){
            $this->where[] = "{$key} {$compare} ?";
            $this->params[] = $value;
        }
        return $this;
    }

    // Set Order
    public function order(string $column, string $direction = 'ASC'):object
    {
        $direction = ucwords($direction);
        $this->order = "ORDER BY {$column} {$direction}";
        return $this;
    }

    // Set Limit
    public function limit(Int|String|Null $limit = LIMIT):object
    {
        $pagenumber = (int) App::load()->request->get('page') + 1;
        // Get Limit
        $limit = (int) $limit;
        
        // Set Offset
        $this->offset = ($pagenumber > 0) ? (($pagenumber - 1) * $limit) : 0;

        // Set Query
        $this->limit = "LIMIT {$limit} OFFSET {$this->offset}";
        return $this;
    }

    // Execute Database
    public function get():array
    {
        $sql = "SELECT {$this->select} FROM {$this->table}";
        $result = [];

        if (!empty($this->joins)) {
            $sql .= ' ' . implode(' ', $this->joins);
        }

        if (!empty($this->where)) {
            $sql .= ' WHERE ' . implode(" {$this->operator} ", $this->where);
        }

        if (!empty($this->filter)) {
            $sql .= ' WHERE ' . implode(" AND ", $this->filter);
        }
        
        if (!empty($this->order)) {
            $sql .= ' ' . $this->order;
        }

        if (!empty($this->limit)) {
            $sql .= ' ' . $this->limit;
        }
        try{
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($this->params);
            $result = $stmt->fetchAll();
            // Reset Values
            $this->reset();
        }catch(PDOException $e){
            $func = new Functions();
            $func->explain($e, $sql);
        }

        return $result;
    }

    // Check Data Exist
    public function check():bool
    {
        $sql = "SELECT {$this->select} FROM {$this->table}";
        $result = [];

        if (!empty($this->joins)) {
            $sql .= ' ' . implode(' ', $this->joins);
        }

        if (!empty($this->where)) {
            $sql .= ' WHERE ' . implode(" {$this->operator} ", $this->where);
        }

        if (!empty($this->filter)) {
            $sql .= ' WHERE ' . implode(" AND ", $this->filter);
        }
        
        if (!empty($this->order)) {
            $sql .= ' ' . $this->order;
        }

        if (!empty($this->limit)) {
            $sql .= ' ' . $this->limit;
        }
        try{
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($this->params);
            $result = $stmt->fetchAll();
            // Reset Values
            $this->reset();
        }catch(PDOException $e){
            $func = new Functions();
            $func->explain($e, $sql);
        }

        if($result){
            return true;
        }
        return false;
    }

    // Execute Database For Single Value
    public function single():array
    {
        $sql = "SELECT {$this->select} FROM {$this->table}";

        if (!empty($this->joins)) {
            $sql .= ' ' . implode(' ', $this->joins);
        }

        if (!empty($this->where)) {
            $sql .= ' WHERE ' . implode(" {$this->operator} ", $this->where);
        }

        if (!empty($this->filter)) {
            $sql .= ' WHERE ' . implode(" AND ", $this->filter);
        }

        if (!empty($this->order)) {
            $sql .= ' ' . $this->order;
        }

        if (!empty($this->limit)) {
            $sql .= ' ' . $this->limit;
        }

        try{
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($this->params);
            $result = $stmt->fetch();
            // Reset Values
            $this->reset();
        }catch(PDOException $e){
            $func = new Functions();
            $func->explain($e, $sql);
        }
        if($result){
            return $result;
        }
        return [];
    }

    // Insert Into Database
    public function insert(array $data):int
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";

        try{
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(array_values($data));
        }catch(PDOException $e){
            $func = new Functions();
            $func->explain($e, $sql);
        }
        // Reset Values
        $this->reset();
        return (int) $this->pdo->lastInsertId(); // Returns the ID of the last inserted row
    }

    // Replace Data
    public function replace($data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $sql = "REPLACE INTO {$this->table} ($columns) VALUES ($placeholders)";
        try{
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(array_values($data));
        }catch(PDOException $e){
            $func = new Functions();
            $func->explain($e, $sql);
        }
        // Reset Values
        $this->reset();
        return true; // Returns the ID of the last inserted row
    }

    // Update Data Into Table
    public function update(array $data):int
    {
        $result = 0;
        $set = [];
        foreach ($data as $column => $value) {
            $set[] = "$column = ?";
            $params[] = $value;
        }
        $toUpdate = array_merge($params, $this->params);

        $sql = "UPDATE {$this->table} SET " . implode(', ', $set);

        if (!empty($this->where)) {
            $sql .= ' WHERE ' . implode(" {$this->operator} ", $this->where);

            try{
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute($toUpdate);
                $result = $stmt->rowCount();
            }catch(PDOException $e){
                $func = new Functions();
                $func->explain($e, $sql);
            }
        }
        // Reset Values
        $this->reset();
        return (int) ($result ?? 0);
    }

    // Delete Column
    public function pop():bool
    {
        $res = '';
        $sql = "DELETE FROM {$this->table}";
        if (!empty($this->where)) {
            $sql .= ' WHERE ' . implode(" {$this->operator} ", $this->where);
            try{
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute($this->params);
            }catch(PDOException $e){
                $func = new Functions();
                $func->explain($e, $sql);
            }
            $res = $stmt->rowCount();
        }
        // Reset Values
        $this->reset();
        return $res ?: false;
    }

    // Count Data
    public function count():int
    {
        $sql = "SELECT COUNT('*') FROM {$this->table}";
        $result = 0;

        if (!empty($this->joins)) {
            $sql .= ' ' . implode(' ', $this->joins);
        }

        if (!empty($this->where)) {
            $sql .= ' WHERE ' . implode(" {$this->operator} ", $this->where);
        }

        if (!empty($this->filter)) {
            $sql .= ' WHERE ' . implode(" AND ", $this->filter);
        }

        if (!empty($this->order)) {
            $sql .= ' ' . $this->order;
        }

        if (!empty($this->limit)) {
            $sql .= ' ' . $this->limit;
        }
        try{
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($this->params);
            $result = (int) $stmt->fetchColumn();
            // Reset Values
            $this->reset();
        }catch(PDOException $e){
            $func = new Functions();
            $func->explain($e, $sql);
        }
        return $result;
    }

    // Generate UUID
    public static function uuid(string $table, string $column)
    {
        $time = substr(str_replace('.', '', microtime(true)), -6);
        $uid = 'uuid-'.bin2hex(random_bytes(3)).'-'.bin2hex(random_bytes(3)).'-'.bin2hex(random_bytes(3)).'-'.bin2hex(random_bytes(3)).'-'.$time;
        // Check Already Exist or Return
        if(self::conn()->table($table)->select()->where([$column => $uid])->single()){
            return self::uuid($table, $column);
        }
        return $uid;
    }
}