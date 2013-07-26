<?php

class SelectQuery {
  
  private $fields = array();
  private $table = NULL;
  private $tableAlias = NULL;
  private $joins = array();
  private $conditions = array();
  
  public function __construct($table, $alias = NULL) {
    $this->table = $table;
    $this->tableAlias = $alias;
  }
  
  public function condition(Condition $condition) {
    $this->conditions[] = $condition;
    return $this;
  }
  
  public function join($table, Condition $joinCondition, $alias = NULL) {
    if (empty($alias)) {
      $alias = $table;
    }
    $this->joins[$alias] = array(
      'to' => $table,
      'condition' => $joinCondition
    );
    return $this;
  }
  
  public function field($expression, $alias = NULL) {
    if (empty($alias)) {
      $alias = $expression;
    }
    $this->fields[$alias] = $expression;
    return $this;
  }
  
  public function fields(array $fields, $forceAliases = FALSE) {
    foreach ($fields as $field_key => $name) {
      if (is_numeric($field_key) && (!$forceAliases)) {
        $this->field($name);
      } else {
        $this->field($name, $field_key);
      }
    }
  }
  
  public function execute(DataConnection $connection) {
    return $connection->query($this->buildSQL());
  }
  
  public function fetchValue(DataConnection $connection) {
    $res = $this->execute($connection);
    $results = mysql_fetch_array($res);
    if (empty($results)) { return NULL; }
    return reset($results);
  }
  
  public function fetchRow(DataConnection $connection) {
    $res = $this->execute($connection);
    return mysql_fetch_array($res);
  }
  
  public function fetchArray(DataConnection $connection) {
    $res = $this->execute($connection);
    $results = array();
    while (($line = mysql_fetch_array($res)) !== FALSE) {
      $results[] = $line;
    }
    return $results;
  }
  
  public function buildSQL() {
    $clauses = array();
    
    $fields = array();
    foreach ($this->fields as $alias => $value) {
      if ($alias == $value) {
        $fields[] = $value;
      } else {
        $fields[] = $value.' `'.$alias.'`';
      }
    }
    if (!empty($fields)) {
      $clauses[] = 'SELECT ' . implode(', ', $fields);
    } else {
      $clauses[] = 'SELECT *';
    }
    $clauses[] = 'FROM ' . $this->table . (!empty($this->tableAlias) ? ' `' . $this->tableAlias . '`' : '');
    
    $joins = array();
    foreach ($this->joins as $alias => $info) {
      $table = $info['to'];
      $statement = 'JOIN ';
      if ($alias == $table) {
        $statement .= $table;
      } else {
        $statement .= $table . ' `' . $alias . '`';
      }
      $statement .= ' ON ' . $info['condition']->buildCondition();
      $joins[] = $statement;
    }
    $clauses[] = implode(' ', $joins);
    
    $conditions = array();
    foreach ($this->conditions as $condition) {
      $conditions[] = $condition->buildCondition();
    }
    if (!empty($conditions)) {
      $clauses[] = 'WHERE ' . implode(' AND ', $conditions);
    }
    
    return implode(' ', $clauses);
  }
  
  public static function fastFieldQuery($table, $field, $id) {
    $query = new SelectQuery($table);
    $query->field($table . '.*');
    $query->condition(new FieldValueCondition($field, $id));
    return $query;
  }
      
      
  
}