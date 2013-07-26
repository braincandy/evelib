<?php

define('CORP_SIZE_HUGE', 'H');
define('CORP_SIZE_LARGE', 'L');
define('CORP_SIZE_MEDIUM', 'M');
define('CORP_SIZE_SMALL', 'S');
define('CORP_SIZE_TINY', 'T');

define('CORP_EXTENT_LOCAL', 'L');
define('CORP_EXTENT_CONSTELLATION', 'C');
define('CORP_EXTENT_REGIONAL', 'R');
define('CORP_EXTENT_NATIONAL', 'N');
define('CORP_EXTENT_GLOBAL', 'G');

class Corporation extends CCPEntity implements
  Owner, Identifiable, Describable, Nameable {
 
  public static $CORP_SIZES = array(
    CORP_SIZE_HUGE => 'Huge',
    CORP_SIZE_LARGE => 'Large',
    CORP_SIZE_MEDIUM => 'Medium',
    CORP_SIZE_SMALL => 'Small',
    CORP_SIZE_TINY => 'Tiny',
  );
  
  public static $CORP_EXTENTS = array(
    CORP_EXTENT_LOCAL => 'Local',
    CORP_EXTENT_CONSTELLATION => 'Constellation',
    CORP_EXTENT_REGIONAL => 'Regional',
    CORP_EXTENT_NATIONAL => 'National',
    CORP_EXTENT_GLOBAL => 'Global',
  );
  
  public function __construct(array $data) {
    $this->setData($data);
  }
  
  public function id() {
    return $this->getDatum('corporationID');
  }
  
  public function size($raw = FALSE) {
    if ($raw) {
      return $this->getDatum('size');
    }
    return self::$CORP_SIZES[$this->getDatum('size')];
  }
  
  public function extent($raw = FALSE) {
    if ($raw) {
      return $this->getDatum('extent');
    }
    return self::$CORP_EXTENTS[$this->getDatum('extent')];
  }
  
  public function name() {
    return $this->getDatum('itemName');
  }
  
  public function stationCount() {
    return $this->getDatum('stationCount');
  }
  
  public function systemCount() {
    return $this->getDatum('stationSystemCount');
  }
  
  public function description() {
    return $this->getDatum('description');
  }
  
  public function getFaction(DataConnection $conn) {
    return Faction::loadByID($conn, $this->getDatum('factionID'));
  }
  
  public function getHQSystem(DataConnection $conn) {
    return SolarSystem::loadByID($conn, $this->getDatum('solarSystemID'));
  }
  
  public function getStations(DataConnection $conn) {
    return Station::loadByCorporation($conn, $this->id());
  }

  public static function loadByID(DataConnection $conn, $id) {
    return Corporation::loadByField($conn, 'corporationID', $id, TRUE);
  }
  
  public static function loadByFaction(DataConnection $conn, $factionID) {
    return Corporation::loadByField($conn, 'factionID', $factionID);
  }
  
  public static function loadByField(DataConnection $conn, $field, $value, $single = FALSE) {
    return CCPEntity::genericLoadByField($conn, array(
      'field' => $field,
      'value' => $value,
      'table' => 'crpNPCCorporations',
      'fk_name' => 'corporationID',
      'single' => $single,
      'index' => 'corporationID',
      'class' => __CLASS__,
    ));
  }
  
  
}