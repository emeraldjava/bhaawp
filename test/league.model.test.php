<?php

define('WP_INSTALL_DIR','C:\oconnellp\wamp\wordpress');
define('PLUGIN_ROOT_DIR',dirname(__FILE__).'./../');

require_once( WP_INSTALL_DIR.'\wp-config.php');
require_once( WP_INSTALL_DIR.'\wp-load.php');
require_once( WP_INSTALL_DIR.'\wp-includes\wp-db.php');
require_once( WP_INSTALL_DIR . '/wp-content/plugins/posts-to-posts/core/api.php' );
require_once( PLUGIN_ROOT_DIR.'/bootstrap.php');

echo 'event model test\n';
$league = new LeagueSummary(2492);
//echo var_dump($event);

//$races = $league->getEvents();
$races = $league->getLeagueRaces();//'M');

//var_dump($races);

echo print_r($races,true);

// $rid_array = array_map(function($val) {
// 	return $val->rid;
// }, $races);

//$rid_array = $league->getRaceIds($races);
//echo print_r($rid_array,true).PHP_EOL;
//echo json_encode($rid_array).PHP_EOL;
//echo implode(",", $rid_array);

$raceSet = $league->getRaceIdSetString($races);
echo $raceSet.PHP_EOL;
echo json_encode($league->getRunnerLeagueSummary($raceSet,7713),JSON_FORCE_OBJECT).PHP_EOL;

echo json_encode($league->getRunnerLeagueSummary($raceSet,5143),JSON_FORCE_OBJECT).PHP_EOL;

// return array_values(array_filter($races, function($arrayValue) use($ID) { return $arrayValue['rd'] == $ID; } ));

// $f = array_filter(array_keys($races), function ($k){ return strlen($k)>=4; });
// var_dump(array_intersect_key($races, array_flip('rid')));

?>