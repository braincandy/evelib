<?php
define('SERVICE_BOUNTY', 1);
define('SERVICE_ASSASSIN', 2);
define('SERVICE_COURIER', 4);
define('SERVICE_INTERBUS', 8);
define('SERVICE_REPROCESSING', 16);
define('SERVICE_REFINERY', 32);
define('SERVICE_MARKET', 64);
define('SERVICE_BLACK_MARKET', 128);
define('SERVICE_STOCKS' ,256);
define('SERVICE_CLONES', 512);
define('SERVICE_SURGERY', 1024);
define('SERVICE_DNA', 2048);
define('SERVICE_REPAIR', 4096);
define('SERVICE_FACTORY', 8192);
define('SERVICE_LABS', 16384);
define('SERVICE_GAMBLING', 32768);
define('SERVICE_FITTING', 65536);
define('SERVICE_PAINT', 131072);
define('SERVICE_NEWS', 262144);
define('SERVICE_STORAGE', 524288);
define('SERVICE_INSURANCE', 1048576);
define('SERVICE_DOCKING', 2097152);
define('SERVICE_OFFICES', 4194304);
define('SERVICE_JUMP_CLONES', 8388608);
define('SERVICE_LP', 16777216);
define('SERVICE_NAVY', 33554432);
define('SERVICE_SECURITY', 67108864);

class Station extends CCPEntity implements
  Identifiable, Nameable, Positionable {
  
  public static $SERVICES = array(
    SERVICE_BOUNTY => 'Bounty Missions',
    SERVICE_ASSASSIN => 'Assassination Missions',
    SERVICE_COURIER => 'Courier Missions',
    SERVICE_INTERBUS => 'Interbus',
    SERVICE_REPROCESSING => 'Reprocessing Plant',
    SERVICE_REFINERY => 'Refinery',
    SERVICE_MARKET => 'Market',
    SERVICE_BLACK_MARKET => 'Black Market',
    SERVICE_STOCKS => 'Stock Exchange',
    SERVICE_CLONES => 'Cloning',
    SERVICE_SURGERY => 'Surgery',
    SERVICE_DNA => 'DNA Therapy',
    SERVICE_REPAIR => 'Repair Facilities',
    SERVICE_FACTORY => 'Factory',
    SERVICE_LABS => 'Laboratory',
    SERVICE_GAMBLING => 'Gambling',
    SERVICE_FITTING => 'Fitting',
    SERVICE_PAINT => 'Paintshop',
    SERVICE_NEWS => 'News',
    SERVICE_STORAGE => 'Storage',
    SERVICE_INSURANCE => 'Insurance',
    SERVICE_DOCKING => 'Docking',
    SERVICE_OFFICES => 'Office Rental',
    SERVICE_JUMP_CLONES => 'Jump Clone Facility',
    SERVICE_LP => 'Loyalty Point Store',
    SERVICE_NAVY => 'Navy Offices',
    SERVICE_SECURITY => 'Security Office',
  );
  
  private $servicesOpen = NULL;
  
  
  public function __construct(array $data, DataConnection $c) {
    $this->setData($data);
    $this->verifyServices($c);
  }
  
  public function verifyServices(DataConnection $c) {
    $q = new SelectQuery('staOperationServices', 'os');
    $this->servicesOpen = $q
        ->field('SUM(serviceID)')
        ->condition(new FieldValueCondition('operationID', $this->getDatum('operationID')))
        ->fetchValue($c);
    
        
  }
  
  public function id() {
    return $this->getDatum('stationID');
  }
  
  public function name() {
    return $this->getDatum('stationName');
  }
  
  public function position() {
    return array(
      'x' => $this->getDatum('x'),
      'y' => $this->getDatum('y'),
      'z' => $this->getDatum('z'),
    );
  }
  
  public function hasService($serviceid) {
    return $this->servicesOpen & $serviceid;
  }
  
  public function reprocessingEfficiency() {
    return $this->getDatum('reprocessingEfficiency');
  }
  
  public function reprocessingTax() {
    return $this->getDatum('reprocessingStationsTake');
  }
  
  public function getCorporation(DataConnection $c) {
    return Corporation::loadByID($c, $this->getDatum('corporationID'));
  }
  
  public function getSolarSystem(DataConnection $c) {
    return SolarSystem::loadByID($c, $this->getDatum('solarSystemID'));
  }
  
  public function getConstellation(DataConnection $c) {
    return Constellation::loadByID($c, $this->getDatum('constellationID'));
  }
  
  public function getRegion(DataConnection $c) {
    return Region::loadByID($c, $this->getDatum('regionID'));
  }
  
  public static function loadByID(DataConnection $c, $id) {
    return self::loadByField($c, 'stationID', $id, TRUE);
  }
  
  public static function loadBySolarSystem(DataConnection $c, $systemID) {
    return self::loadByField($c, 'solarSystemID', $systemID);
  }
  
  public static function loadByCorporation(DataConnection $c, $corpID) {
    return self::loadByField($c, 'corporationID', $corpID);
  }
  
  public static function loadByConstellation(DataConnection $c, $constellationID) {
    return self::loadByField($c, 'constellationID', $constellationID);
  }
  
  public static function loadByRegion(DataConnection $c, $regionID) {
    return self::loadByField($c, 'regionID', $regionID);
  }
  
  public static function loadByField(DataConnection $c, $field, $value, $single = FALSE) {
    return CCPEntity::genericLoadByField($c, array(
      'field' => $field,
      'value' => $value,
      'single' => $single,
      'table' => 'staStations',
      'index' => 'stationID',
      'class' => __CLASS__,
    ));
  }
  
}