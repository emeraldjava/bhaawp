<?php
define( 'BHAA_PLUGIN_DIR' , dirname(__FILE__) );

// admin
require_once(BHAA_PLUGIN_DIR.'/admin/class-runner-admin.php');
require_once(BHAA_PLUGIN_DIR.'/admin/class-event-admin.php');
require_once(BHAA_PLUGIN_DIR.'/admin/class-race-cpt.php');
require_once(BHAA_PLUGIN_DIR.'/admin/class-league-cpt.php');
require_once(BHAA_PLUGIN_DIR.'/admin/WPFlashMessages.php');

// 3rd party libs
// Register Mustache
require_once( plugin_dir_path( __FILE__ ) . '/includes/Mustache/Autoloader.php');
Mustache_Autoloader::register();
require_once( plugin_dir_path( __FILE__ ) . '/includes/CssToInlineStyles/CssToInlineStyles.php');
// common includes
require_once(BHAA_PLUGIN_DIR.'/includes/class-bhaa-mustache.php');
require_once(BHAA_PLUGIN_DIR.'/includes/listtable/raceresulttable.class.php');
require_once(BHAA_PLUGIN_DIR.'/includes/class-runner-manager.php');
require_once(BHAA_PLUGIN_DIR.'/includes/class-runner.php');
require_once(BHAA_PLUGIN_DIR.'/includes/class-house-manager.php');
require_once(BHAA_PLUGIN_DIR.'/includes/class-house.php');
require_once(BHAA_PLUGIN_DIR.'/includes/class-bhaa-events-manager.php');
require_once(BHAA_PLUGIN_DIR.'/includes/class-bhaa-connections.php');
require_once(BHAA_PLUGIN_DIR.'/includes/class-race.php');
require_once(BHAA_PLUGIN_DIR.'/includes/class-event.php');
// public class
require_once(BHAA_PLUGIN_DIR.'/public/includes/class-realex.php');
require_once(BHAA_PLUGIN_DIR.'/public/includes/HouseCpt.php');
require_once(BHAA_PLUGIN_DIR.'/public/includes/class-standard.php');
require_once(BHAA_PLUGIN_DIR.'/public/includes/class-standardcalculator.php');
require_once(BHAA_PLUGIN_DIR.'/public/includes/class-raceday.php');
require_once(BHAA_PLUGIN_DIR.'/public/includes/class-raceday-form.php');
require_once(BHAA_PLUGIN_DIR.'/public/includes/class-raceday-registration-form.php');
require_once(BHAA_PLUGIN_DIR.'/public/includes/class-raceday-daymember-form.php');
require_once(BHAA_PLUGIN_DIR.'/public/includes/raceresult-form.php');

// model
require_once(BHAA_PLUGIN_DIR.'/includes/model/interface-table.php');
require_once(BHAA_PLUGIN_DIR.'/includes/model/class-basemodel.php');
require_once(BHAA_PLUGIN_DIR.'/includes/model/class-leaguesummary.php');
require_once(BHAA_PLUGIN_DIR.'/includes/model/class-eventmodel.php');
require_once(BHAA_PLUGIN_DIR.'/includes/model/class-raceresult.php');
require_once(BHAA_PLUGIN_DIR.'/includes/model/class-teamresult.php');
require_once(BHAA_PLUGIN_DIR.'/includes/model/class-user.php');
require_once(BHAA_PLUGIN_DIR.'/includes/model/interface-league.php');
require_once(BHAA_PLUGIN_DIR.'/includes/model/class-team-league.php');
require_once(BHAA_PLUGIN_DIR.'/includes/model/class-individual-league.php');
?>