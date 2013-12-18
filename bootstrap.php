<?php
define( 'BHAA_PLUGIN_DIR' , dirname(__FILE__) );

// admin
//require_once(BHAA_PLUGIN_DIR.'/admin/import.php');
require_once(BHAA_PLUGIN_DIR.'/admin/class-runner-admin.php');
require_once(BHAA_PLUGIN_DIR.'/admin/class-race-admin.php');
require_once(BHAA_PLUGIN_DIR.'/admin/WPFlashMessages.php');
//require_once(BHAA_PLUGIN_DIR.'/admin/admin.php');
require_once(BHAA_PLUGIN_DIR.'/admin/BHAAEventManager.php');

// common includes
require_once(BHAA_PLUGIN_DIR.'/includes/class-bhaa-mustache.php');
// Register Mustache
require_once( plugin_dir_path( __FILE__ ) . '/includes/Mustache/Autoloader.php');
Mustache_Autoloader::register();
require_once(BHAA_PLUGIN_DIR.'/includes/listtable/raceresulttable.class.php');
require_once(BHAA_PLUGIN_DIR.'/includes/class-runner-manager.php');
require_once(BHAA_PLUGIN_DIR.'/includes/class-runner.php');

// ctps
require_once(BHAA_PLUGIN_DIR.'/public/includes/HouseCpt.php');
require_once(BHAA_PLUGIN_DIR.'/public/includes/LeagueCpt.php');
require_once(BHAA_PLUGIN_DIR.'/public/includes/RaceCpt.php');

// model
require_once(BHAA_PLUGIN_DIR.'/model/BaseModel.php');
require_once(BHAA_PLUGIN_DIR.'/model/Table.php');
require_once(BHAA_PLUGIN_DIR.'/model/LeagueSummary.php');
require_once(BHAA_PLUGIN_DIR.'/model/EventModel.php');
require_once(BHAA_PLUGIN_DIR.'/model/Race.php');
require_once(BHAA_PLUGIN_DIR.'/model/RaceResult.php');
require_once(BHAA_PLUGIN_DIR.'/model/TeamResult.php');
require_once(BHAA_PLUGIN_DIR.'/model/House.php');
require_once(BHAA_PLUGIN_DIR.'/model/HouseManager.php');
require_once(BHAA_PLUGIN_DIR.'/model/AgeCategory.php');

// classes
require_once(BHAA_PLUGIN_DIR.'/classes/connection.class.php');
require_once(BHAA_PLUGIN_DIR.'/classes/event.class.php');
require_once(BHAA_PLUGIN_DIR.'/classes/Raceday.class.php');
require_once(BHAA_PLUGIN_DIR.'/classes/Registration.class.php');
require_once(BHAA_PLUGIN_DIR.'/classes/Standard.class.php');
require_once(BHAA_PLUGIN_DIR.'/classes/StandardCalculator.php');
require_once(BHAA_PLUGIN_DIR.'/classes/Realex.php');

// widgets
//require_once(BHAA_PLUGIN_DIR.'/widgets/RunnerSearchWidget.php');


?>