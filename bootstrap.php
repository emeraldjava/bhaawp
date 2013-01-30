<?php

define( 'PLUGIN_ROOT_DIR' , dirname(__FILE__) );//plugin_dir_path( __FILE__ ) );

// function template_autoloader ( $class ) {
// 	if ( file_exists ( dirname(__FILE__) ."/classes/.$class.class.php" ) )
// 		include dirname(__FILE__)."/classes/.$class.class.php";
// 	else
// 		echo "file not found ".$class;
// }
//spl_autoload_register ( 'template_autoloader' );

// admin
require_once(PLUGIN_ROOT_DIR.'/admin/admin.php');

// ctps
require_once(PLUGIN_ROOT_DIR.'/cpt/HouseCpt.php');
require_once(PLUGIN_ROOT_DIR.'/cpt/LeagueCpt.php');
require_once(PLUGIN_ROOT_DIR.'/cpt/RaceCpt.php');

// tables
require_once(PLUGIN_ROOT_DIR.'/table/teamresulttable.class.php');
require_once(PLUGIN_ROOT_DIR.'/table/raceresulttable.class.php');

// classes
require_once(PLUGIN_ROOT_DIR.'/classes/connection.class.php');
require_once(PLUGIN_ROOT_DIR.'/classes/event.class.php');
require_once(PLUGIN_ROOT_DIR.'/classes/race.class.php');
require_once(PLUGIN_ROOT_DIR.'/classes/runner.class.php');
require_once(PLUGIN_ROOT_DIR.'/classes/Standard.class.php');
require_once(PLUGIN_ROOT_DIR.'/classes/StandardCalculator.php');

?>