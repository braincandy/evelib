<?php

class Constellation extends CCPEntity implements
  Nameable, Positionable, Identifiable {
  
  public function __construct(array $data) {
    $this->setData($data);
  }
  
  public function id() {
    return $this->getDatum('constellationID');
  }
  
  public function name() {
    return $this->getDatum('constellationName');
  }
  
  public function position() {
    return array(
      'x' => $this->getDatum('x'),
      'y' => $this->getDatum('y'),
      'z' => $this->getDatum('z'),
    );
  }
  
  public function getRegion(DataConnection $c) {
    return Region::loadByID($c, $this->getDatum('regionID'));
  }
  
  public function getSystems(DataConnection $c) {
    return SolarSystem::loadByConstellation($c, $this->id());
  }
  
  public function getStations(DataConnection $c) {
    return Station::loadByConstellation($c, $this->id());
  }
  
  public static function loadByRegion(DataConnection $c, $regionID) {
    return self::loadByField($conn, 'regionID', $regionID);
  }
  
  public static function loadByID(DataConnection $conn, $id) {
    return self::loadByField($conn, 'constellationID', $id, TRUE);
  }
  
  public static function loadByField(DataConnection $conn, $field, $value, $single = FALSE) {
    return CCPEntity::genericLoadByField($conn, array(
      'field' => $field,
      'value' => $value,
      'table' => 'mapConstellations',
      'single' => $single,
      'index' => 'constellationID',
      'class' => __CLASS__,
    ));
  }
  
}