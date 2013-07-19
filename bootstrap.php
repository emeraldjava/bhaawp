<?php
define( 'BHAA_PLUGIN_DIR' , dirname(__FILE__) );

// function template_autoloader ( $class ) {
// 	if ( file_exists ( dirname(__FILE__) ."/classes/.$class.class.php" ) )
// 		include dirname(__FILE__)."/classes/.$class.class.php";
// 	else
// 		echo "file not found ".$class;
// }
//spl_autoload_register ( 'template_autoloader' );


// admin
require_once(BHAA_PLUGIN_DIR.'/admin/import.php');
require_once(BHAA_PLUGIN_DIR.'/admin/RunnerAdmin.php');
require_once(BHAA_PLUGIN_DIR.'/admin/RaceAdmin.php');
require_once(BHAA_PLUGIN_DIR.'/admin/WPFlashMessages.php');
require_once(BHAA_PLUGIN_DIR.'/admin/admin.php');
require_once(BHAA_PLUGIN_DIR.'/admin/BHAAEventManager.php');

// ctps
require_once(BHAA_PLUGIN_DIR.'/cpt/HouseCpt.php');
require_once(BHAA_PLUGIN_DIR.'/cpt/LeagueCpt.php');
require_once(BHAA_PLUGIN_DIR.'/cpt/RaceCpt.php');

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

// tables
require_once(BHAA_PLUGIN_DIR.'/table/teamresulttable.class.php');
require_once(BHAA_PLUGIN_DIR.'/table/raceresulttable.class.php');

// classes
require_once(BHAA_PLUGIN_DIR.'/classes/connection.class.php');
require_once(BHAA_PLUGIN_DIR.'/classes/event.class.php');
require_once(BHAA_PLUGIN_DIR.'/classes/Registration.class.php');
require_once(BHAA_PLUGIN_DIR.'/classes/runner.class.php');
require_once(BHAA_PLUGIN_DIR.'/classes/Standard.class.php');
require_once(BHAA_PLUGIN_DIR.'/classes/StandardCalculator.php');

// widgets
require_once(BHAA_PLUGIN_DIR.'/widgets/RunnerSearchWidget.php');

?>