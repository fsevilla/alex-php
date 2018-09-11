<?php

namespace Core\Database;

class DatabaseModel {

  private static $instance = NULL;

  private $config = NULL;

  private $driver = NULL;

  private $db;

  private $qb = NULL;

  private $connection;

  public function __construct()
  {
    // Load Driver based on App's configuration
    $this->loadConfig();
    $this->loadDriver();
    $this->initDB();
    // $this->setQueryBuilder();
  }

  public static function getInstance()
  {
    if(!self::$instance)
    {
      self::$instance = new DatabaseModel();
    }

    return self::$instance;
  }

  private function loadConfig()
  {
      $this->config = require_once __DIR__.'/../../config/database.php';
  }

  private function loadDriver()
  {
    require_once(__DIR__.'/../../db/drivers/'.$this->config['driver'].'.driver.php');

    $this->driver = new DatabaseDriver();
  }

  private function initDB()
  {
    $conf = $this->config;
    $host = $conf['host'].":".$conf['port'];
    $this->connection = $this->driver->connect($host, $conf['username'], $conf['password']);

    if($this->connection)
    {
      $this->db = $this->driver->select_database($this->connection, $conf['database']);
    } else {
      if($conf['debug']) {
        echo 'Database Connection Error (' . mysqli_connect_errno() . '): ' . mysqli_connect_error();
      }
    }
  }

  private function setQueryBuilder()
  {
    // $this->qb = new QueryBuilder();
  }

  public function debug($on = true)
  {
    $this->isDebugModeOn = $on;
  }

  public function last_id() {
    return $this->driver->insert_id($this->connection);
  }

  public function query($q)
  {
    $begin = microtime();
    $result = $this->driver->query($this->connection, $q);
    $rows = $this->driver->affected_rows($this->connection);
    if($this->isDebugModeOn === true || $this->config['debug'] === true) 
    {
      $end = microtime();
      echo "<hr/>Database query: ".$q." (".round($end-$begin,4)." seconds) ".$rows." row(s) affected<hr/>";             
      if($this->driver->error($this->connection))
        echo "<b>Database Error:</b> ".$this->driver->error($this->connection)."<hr/>";
    }
    return $result;
  }

  public function getRows($q)
  {
    $recordset = $this->query($q);

    $count = $this->driver->num_rows($recordset);

    $rows = [];

    if($count)
    {
      while($r = $this->driver->fetch_array($recordset))
      {
        $row = (object)[];
        foreach ($r as $key => $value) {
          if(!is_numeric($key))
          {
            $row->$key = $value;
          }
        }

        $rows[] = $row;
      }
    }

    return $rows;
  }

  public function insert($data, $table)
  {
    $values = [];
    $fields = [];
    foreach ($data as $field => $value) {
      $fields[] = $field;
      $values[] = "'".$value."'";
    }

    $valuesStr = implode(', ', $values);
    $fieldsStr = implode(', ', $fields);

    $q = "INSERT INTO $table ($fieldsStr) VALUES ($valuesStr)";

    if($this->query($q)) {
      return $this->last_id();
    } else {
      return false;
    }
  }

  public function update($id, $data, $table, $id_field = 'id')
  {
    $values = [];
    
    foreach ($data as $field => $value) {
        $values[] = $field." = '".$value."'";
    }

    $valuesStr = implode(', ', $values);

    $q = "UPDATE $table SET $valuesStr WHERE $id_field = '$id'";

    return $this->query($q);
  }


  // TODO: queries must be in driver as these may be different between Database handlers
  function begin(){
    $this->query("START TRANSACTION");
  }

  function commit(){
    $this->query("COMMIT");
  }

  function rollback(){
    $this->query("ROLLBACK");
  }

}