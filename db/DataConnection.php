<?php

class DataConnection {

  private $host = '';
  private $user = '';
  private $password = '';
  private $database = '';
  private $conn = NULL;
  
  private static $connections = array();
  private static $registry = array();
  
  public static function registerDatabase($id, $info) {
    self::$registry[$id] = $info;
  }
  
  public function query($sql) {
    $results = mysql_query($sql) or die(mysql_error());
    return $results;
  }
  
  public static function getConnection($id) {
    if (!isset(self::$connections[$id])) {
      if (isset(self::$registry[$id])) {
        self::$connections[$id] = new DataConnection(
            self::$registry[$id]['user'],
            self::$registry[$id]['password'],
            self::$registry[$id]['host'],
            self::$registry[$id]['database']
        );
      } else {
        die("No registry entry for " . $id);
      }
    }
    return self::$connections[$id];
  }
  
  public function __construct($u, $p, $h, $db) {
    $this->user = $u;
    $this->password = $p;
    $this->host = $h;
    $this->database = $db;
    $this->init();
  }
  
  public function init() {
    $this->conn = mysql_connect($this->host, $this->user, $this->password)
      or die(mysql_error());
    mysql_select_db($this->database) or die(mysql_error());
  }
  
  

}