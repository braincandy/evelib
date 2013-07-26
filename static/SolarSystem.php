<?php

define('SYSTEM_TYPE_FRINGE', 'F');
define('SYSTEM_TYPE_CORRIDOR', 'C');
define('SYSTEM_TYPE_HUB', 'H');

class SolarSystem extends CCPEntity implements
  Identifiable, Nameable, Positionable {
  
  public static $SYSTEM_TYPES = array(
    SYSTEM_TYPE_FRINGE => 'Fringe',
    SYSTEM_TYPE_CORRIDOR => 'Corridor',
    SYSTEM_TYPE_HUB => 'Hub',
  );
  
  public function __construct(array $data) {
    $this->setData($data);
  }
  
  public function id() {
    return $this->getDatum('solarSystemID');
  }
  
  public function name() {
    return $this->getDatum('solarSystemName');
  }
  
  public function position() {
    return array(
      'x' => $this->getDatum('x'),
      'y' => $this->getDatum('y'),
      'z' => $this->getDatum('z'),
    );
  }

  public function isInternational() {
    return $this->getDatum('international') == 1;
  }
  
  public function isRegionBorder() {
    return $this->getDatum('regional') == 1;
  }
  
  public function isConstellationBorder() {
    return $this->getDatum('constellation') == 1;
  }
  
  public function securityStatus($full = FALSE) {
    if ($full) {
      return $this->getDatum('security');
    }
    return round($this->getDatum('security') * 10.0) / 10.0;
  }
  
  public function isBorderSystem() {
    return $this->getDatum('border') == 1;
  }
  
  public function securityClass() {
    return $this->getDatum('securityClass');
  }
  
  private function rawSystemType() {
    if ($this->getDatum('fringe')) {
      return SYSTEM_TYPE_FRINGE;
    } elseif ($this->getDatum('corridor')) {
      return SYSTEM_TYPE_CORRIDOR;
    } elseif ($this->getDatum('hub')) {
      return SYSTEM_TYPE_HUB;
    }
    return '?';
  }
  
  public function systemType($raw = FALSE) {
    if ($raw) {
      return $this->rawSystemType();
    }
    return self::$SYSTEM_TYPES[$this->rawSystemType()];
  }
  
  public function getConnections(DataConnection $c) {
    return self::loadByConnection($c, $this->id());
  }
  
  public function getConstellation(DataConnection $c) {
    return Constellation::loadByID($c, $this->getDatum('constellationID'));
  }
  
  public function getRegion(DataConnection $c) {
    return Region::loadByID($c, $this->getDatum('regionID'));
  }
  
  public function getStations(DataConnection $c) {
    return Station::loadBySolarSystem($c, $this->id());
  }
  
  public static function loadByConnection(DataConnection $c, $fromSystem) {
    $q = SelectQuery::fastFieldQuery('mapSolarSystemJumps', 'fromSolarSystemID', $fromSystem);
    $rows = $q->fetchArray($c);
    $ids = array();
    foreach ($rows as $row) {
      $ids[] = $row['toSolarSystemID'];
    }
    return self::loadByField($c, 'solarSystemID', $ids);
  }
  
  public static function loadByID(DataConnection $c, $id) {
    return self::loadByField($c, 'solarSystemID', $id, TRUE);
  }
  
  public static function loadByRegion(DataConnection $c, $regionID) {
    return self::loadByField($c, 'regionID', $regionID);
  }
  
  public static function loadByConstellation(DataConnection $c, $constellationID) {
    return self::loadByField($c, 'constellationID', $constellationID);
  }
  
  public static function loadByField(DataConnection $c, $field, $value, $single = FALSE) {
    $q = SelectQuery::fastFieldQuery('mapSolarSystems', $field, $value);
    if ($single) {
      return new SolarSystem($q->fetchRow($c));
    }
    else {
      $results = array();
      $data = $q->fetchArray($c);
      foreach ($data as $dataset) {
        $results[$dataset['solarSystemID']] = new SolarSystem($dataset);
      }
      return $results;
    }
  }
  
}