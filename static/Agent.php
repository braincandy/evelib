<?php

define('AGENT_TYPE_BASIC', 2);
define('AGENT_TYPE_TUTORIAL', 3);
define('AGENT_TYPE_RESEARCH', 4);
define('AGENT_TYPE_CONCORD', 5);
define('AGENT_TYPE_GENERIC_STORYLINE', 6);
define('AGENT_TYPE_STORYLINE', 7);
define('AGENT_TYPE_EVENT', 8);
define('AGENT_TYPE_FACTIONAL_WAREFARE', 9);
define('AGENT_TYPE_EPIC', 10);
define('AGENT_TYPE_AURA', 11);
define('AGENT_TYPE_CAREER', 12);

define('DIVISION_RESEARCH', 18);
define('DIVISION_DISTRIBUTION', 22);
define('DIVISION_MINING', 23);
define('DIVISION_SECURITY', 24);
define('DIVISION_BUSINESS', 25);
define('DIVISION_EXPLORATION', 26);
define('DIVISION_INDUSTRY', 27);
define('DIVISION_MILITARY', 28);
define('DIVISION_ADV_MILITARY', 29);

class Agent extends CCPEntity {
  
  public static $AGENT_TYPES = array(
    AGENT_TYPE_BASIC => 'Basic',
    AGENT_TYPE_TUTORIAL => 'Tutorial',
    AGENT_TYPE_RESEARCH => 'Research',
    AGENT_TYPE_CONCORD => 'CONCORD',
    AGENT_TYPE_GENERIC_STORYLINE => 'Storyline (Generic)',
    AGENT_TYPE_STORYLINE => 'Storyline',
    AGENT_TYPE_EVENT => 'Event',
    AGENT_TYPE_FACTIONAL_WAREFARE => 'Faction Warefare',
    AGENT_TYPE_EPIC => 'Epic',
    AGENT_TYPE_AURA => 'Aura',
    AGENT_TYPE_CAREER => 'Career',
  );
  
  public static $AGENT_DIVISIONS = array(
    DIVISION_RESEARCH => 'Research',
    DIVISION_DISTRIBUTION => 'Distribution',
    DIVISION_MINING => 'Mining',
    DIVISION_SECURITY => 'Security',
    DIVISION_BUSINESS => 'Career - Business',
    DIVISION_EXPLORATION => 'Career - Exploration',
    DIVISION_INDUSTRY => 'Career - Industry',
    DIVISION_MILITARY => 'Career - Military',
    DIVISION_ADV_MILITARY => 'Career - Advanced Military',
  );
  
  public function __construct(array $data) {
    $this->setData($data);
  }
  
  public function id() {
    return $this->getDatum('agentID');
  }
  
  public function name() {
    return $this->getDatum('itemName');
  }
  
  public function level() {
    return $this->getDatum('level');
  }
  
  public function type($raw = FALSE) {
    if ($raw) {
      return $this->getDatum('agentTypeID');
    }
    return self::$AGENT_TYPES[$this->getDatum('agentTypeID')];
  }
  
  public function division($raw = FALSE) {
    if ($raw) {
      return $this->getDatum('divisionID');
    }
    return self::$AGENT_DIVISIONS[$this->getDatum('divisionID')];
  }
  
  public function isLocator() {
    return $this->getDatum('isLocator') == 1;
  }
  
  public function quality() {
    return $this->getDatum('quality');
  }
  
  public function getCorporation(DataConnection $c) {
    return Corporation::loadByID($c, $this->getDatum('corporationID'));
  }
  
  public function getLocation(DataConnection $c) {
    $id = $this->getDatum('locationID');
    if (substr($id, 0, 1) == 3) {
      return SolarSystem::loadByID($c, $id);
    } else {
      return Station::loadByID($c, $id);
    }
  }
  
  public static function loadByCorporation(DataConnection $c, $corpID) {
    return self::loadByField($c, 'corporationID', $corpID);
  }
  
  public static function loadByLocation(DataConnection $c, $locationID) {
    return self::loadByField($c, 'locationID', $locationID);
  }
  
  public static function loadByID(DataConnection $c, $id) {
    return self::loadByField($c, 'agentID', $id, TRUE);
  }
  
  public static function loadByField(DataConnection $c, $field, $value, $single = FALSE) {
    return CCPEntity::genericLoadByField($c, array(
      'field' => $field,
      'value' => $value,
      'single' => $single,
      'class' => __CLASS__,
      'table' => 'agtAgents',
      'index' => 'agentID',
      'fk_name' => 'agentID',
    ));
  }
  
}