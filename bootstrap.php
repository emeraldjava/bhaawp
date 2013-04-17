<?php

define( 'PLUGIN_ROOT_DIR' , dirname(__FILE__) );//plugin_dir_path( __FILE__ ) );
//echo 'boot'.PLUGIN_ROOT_DIR;

// function template_autoloader ( $class ) {
// 	if ( file_exists ( dirname(__FILE__) ."/classes/.$class.class.php" ) )
// 		include dirname(__FILE__)."/classes/.$class.class.php";
// 	else
// 		echo "file not found ".$class;
// }
//spl_autoload_register ( 'template_autoloader' );

// posts 2 posts
//echo 'ABSPATH '. ABSPATH.'\n';
//echo 'WP_INSTALL_DIR '.WP_INSTALL_DIR.'\n';
//echo 'PLUGIN_ROOT_DIR '. PLUGIN_ROOT_DIR.'\n';
//require_once( WP_INSTALL_DIR . '/wp-content/plugins/posts-to-posts/core/api.php' );

// admin
require_once(PLUGIN_ROOT_DIR.'/admin/import.php');
require_once(PLUGIN_ROOT_DIR.'/admin/RunnerAdmin.php');
require_once(PLUGIN_ROOT_DIR.'/admin/RaceAdmin.php');
require_once(PLUGIN_ROOT_DIR.'/admin/WPFlashMessages.php');
require_once(PLUGIN_ROOT_DIR.'/admin/admin.php');
require_once(PLUGIN_ROOT_DIR.'/admin/BHAAEventManager.php');

// ctps
require_once(PLUGIN_ROOT_DIR.'/cpt/HouseCpt.php');
require_once(PLUGIN_ROOT_DIR.'/cpt/LeagueCpt.php');
require_once(PLUGIN_ROOT_DIR.'/cpt/RaceCpt.php');

// flickr
require_once(PLUGIN_ROOT_DIR.'/flickr/BhaaFlickr.php');
require_once(PLUGIN_ROOT_DIR.'/flickr/phpFlickr.php');

// model
require_once(PLUGIN_ROOT_DIR.'/model/BaseModel.php');
require_once(PLUGIN_ROOT_DIR.'/model/Table.php');
require_once(PLUGIN_ROOT_DIR.'/model/LeagueSummary.php');
require_once(PLUGIN_ROOT_DIR.'/model/EventModel.php');
require_once(PLUGIN_ROOT_DIR.'/model/Race.php');
require_once(PLUGIN_ROOT_DIR.'/model/RaceResult.php');
require_once(PLUGIN_ROOT_DIR.'/model/TeamResult.php');
require_once(PLUGIN_ROOT_DIR.'/model/House.php');
require_once(PLUGIN_ROOT_DIR.'/model/HouseManager.php');
require_once(PLUGIN_ROOT_DIR.'/model/AgeCategory.php');

// tables
require_once(PLUGIN_ROOT_DIR.'/table/teamresulttable.class.php');
require_once(PLUGIN_ROOT_DIR.'/table/raceresulttable.class.php');

// classes
require_once(PLUGIN_ROOT_DIR.'/classes/connection.class.php');
require_once(PLUGIN_ROOT_DIR.'/classes/event.class.php');
require_once(PLUGIN_ROOT_DIR.'/classes/Registration.class.php');
require_once(PLUGIN_ROOT_DIR.'/classes/runner.class.php');
require_once(PLUGIN_ROOT_DIR.'/classes/Standard.class.php');
require_once(PLUGIN_ROOT_DIR.'/classes/StandardCalculator.php');

// widgets
require_once(PLUGIN_ROOT_DIR.'/widgets/RunnerSearchWidget.php');

?>