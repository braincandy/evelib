<?php

class FieldValueCondition extends Condition {
  
  private $field = '';
  private $value = '';
  private $op = '';
  
  public function __construct($field, $value, $operator = '=') {
    $this->field = $field;
    $this->value = $value;
    $this->op = $operator;
  }
  
  public function buildCondition() {
    if (is_array($this->value)) {
      if ($this->op == '=') {
        $this->op = 'IN';
      }
      if ($this->op == '!=') {
        $this->op = 'NOT IN';
      }
      $values = array();
      foreach ($this->value as $val) {
        $values[] = "'" . mysql_real_escape_string($val) . "'";
      }
      return $this->field . ' '. $this->op .' ('.implode(', ', $values).')';
    }
    return $this->field . ' ' . $this->op . " '" . mysql_real_escape_string($this->value) . "'";
  }
  
}
