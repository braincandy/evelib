<?php

class FieldCondition extends Condition {
  
  private $field = '';
  private $field2 = '';
  private $op = '';
  
  public function __construct($field, $field2, $operator = '=') {
    $this->field = $field;
    $this->field2 = $field2;
    $this->op = $operator;
  }
  
  public function buildCondition() {
    return $this->field . $this->op . $this->field2;
  }
  
}
