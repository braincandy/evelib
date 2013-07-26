<?php

class Region extends CCPEntity implements
  Nameable, Positionable, Identifiable {
  
  public function __construct(array $data) {
    $this->setData($data);
  }
  
  public function id() {
    return $this->getDatum('regionID');
  }
  
  public function name() {
    return $this->getDatum('regionName');
  }
  
  public function position() {
    return array(
      'x' => $this->getDatum('x'),
      'y' => $this->getDatum('y'),
      'z' => $this->getDatum('z'),
    );
  }
  
  public function getSystems(DataConnection $c) {
    return SolarSystem::loadByRegion($c, $this->id());
  }
  
  public function getConstellations(DataConnection $c) {
    return Constellation::loadByRegion($c, $this->id());
  }
  
  public function getStations(DataConnection $c) {
    return Station::loadByRegion($c, $this->id());
  }
  
  public static function loadByID(DataConnection $conn, $id) {
    return self::loadByField($conn, 'regionID', $id, TRUE);
  }
  
  public static function loadByField(DataConnection $conn, $field, $value, $single = FALSE) {
    return CCPEntity::genericLoadByField($conn, array(
      'field' => $field,
      'value' => $value,
      'table' => 'mapRegions',
      'single' => $single,
      'index' => 'regionID',
    ));
  }
  
}