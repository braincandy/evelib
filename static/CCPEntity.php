<?php

class CCPEntity {
  
  private $data = array();
  
  public function setData(array $data) {
    $this->data = $data;
  }
  
  public function setDatum($key, $value) {
    $this->data[$key] = $value;
  }
  
  public function getDatum($key) {
    return $this->data[$key];
  }
  
  public static function colorSecurity($security) {
    $color = '#F00';
    $security = round($security * 10.0) / 10.0;
    if ($security >= 1) { $color = '#2FEFEF'; }
    elseif ($security >= 0.9) { $color = '#48F0C0'; }
    elseif ($security >= 0.8) { $color = '#00EF47'; }
    elseif ($security >= 0.7) { $color = '#00FF00'; }
    elseif ($security >= 0.6) { $color = '#8FEF2F'; }
    elseif ($security >= 0.5) { $color = '#EFEF00'; }
    elseif ($security >= 0.4) { $color = '#D77700'; }
    elseif ($security >= 0.3) { $color = '#F06000'; }
    elseif ($security >= 0.2) { $color = '#F04800'; }
    elseif ($security >= 0.1) { $color = '#D73000'; }
    return '<span class="sec'.str_replace('.', '_', $security).'" 
      style="color: ' . $color . '">' . $security . '</span>';
  }
  
  public static function includeNameInQuery(SelectQuery &$query, $foreignKey) {
    $query->field('names.itemName', 'itemName');
    $query->join('invNames', new FieldCondition('names.itemID', $foreignKey), 'names');
  }
  
  public static function genericLoadByField(DataConnection $conn, $options) {
    $class = $options['class'];
    $q = SelectQuery::fastFieldQuery($options['table'], $options['field'], $options['value']);
    if (!empty($options['fk_name'])) {
      CCPEntity::includeNameInQuery($q, $options['fk_name']);
    }
    if (!empty($options['single'])) {
      return new $class($q->fetchRow($conn), $conn);
    }
    else {
      $results = array();
      $data = $q->fetchArray($conn);
      foreach ($data as $dataset) {
        if (!empty($options['index'])) {
          $results[$dataset[$options['index']]] = new $class($dataset, $conn);
        } else {
          $results[] = new $class($dataset, $conn);
        }
      }
      return $results;
    }
  }
  
}