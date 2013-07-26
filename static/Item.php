<?php

class Item extends CCPEntity {
  
  function __construct($data) {
    $this->setData($data);
  }
  
  public function id() {
    return $this->getDatum('typeID');
  }
  
  public function name() {
    return $this->getDatum('typeName');
  }
  
  public function description() {
    return $this->getDatum('description');
  }
  
  public function volume() {
    return $this->getDatum('volume');
  }
  
  public function basePrice() {
    return $this->getDatum('basePrice');
  }
  
  public function isContainer() {
    return !empty($this->capacity());
  }
  
  public function capacity() {
    return $this->getDatum('capacity');
  }
  
  public function batch() {
    return $this->getDatum('portionSize');
  }
  
  public static function loadByMarketGroup(DataConnection $c, $mgroupID) {
    return self::loadByField($c, 'marketGroupID', $mgroupID);
  }
  
  public static function loadByGroup(DataConnection $c, $groupID) {
    return self::loadByField($c, 'groupID', $groupID);
  }
  
  public static function loadByID(DataConnection $c, $id) {
    return self::loadByField($c, 'typeID', $id, TRUE);
  }
  
  public static function loadByField(DataConnection $c, $field, $value, $single = FALSE) {
    return CCPEntity::genericLoadByField($c, array(
      'field' => $field,
      'value' => $value,
      'single' => $single,
      'class' => __CLASS__,
      'table' => 'invTypes',
      'index' => 'typeID',
    ));
  }
  
}