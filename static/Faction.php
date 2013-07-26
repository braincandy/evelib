<?php

class Faction extends CCPEntity implements
  Owner, Identifiable, Describable, Nameable {
  
  public function __construct(array $data) {
    $this->setData($data);
  }
  
  public function id() {
    return $this->getDatum('factionID');
  }
  
  public function name() {
    return $this->getDatum('factionName');
  }
  
  public function description() {
    return $this->getDatum('description');
  }
  
  public function stationCount() {
    return $this->getDatum('stationCount');
  }
  
  public function systemCount() {
    return $this->getDatum('stationSystemCount');
  }
  
  public function getMilitiaCorporation(DataConnection $conn) {
    return Corporation::loadByID($conn, $this->getDatum('militiaCorporationID'));
  }
  
  public function getCorporations(DataConnection $conn) {
    return Corporation::loadByFaction($conn, $this->id());
  }
  
  public static function loadByID(DataConnection $conn, $id) {    
    $q = SelectQuery::fastFieldQuery('chrFactions', 'factionID', $id);
    return new Faction($q->fetchRow($conn));
  }
  
  
  
}