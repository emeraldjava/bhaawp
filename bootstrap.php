<?php

define( 'PLUGIN_ROOT_DIR' , dirname(__FILE__) );//plugin_dir_path( __FILE__ ) );

// function template_autoloader ( $class ) {
// 	if ( file_exists ( dirname(__FILE__) ."/classes/.$class.class.php" ) )
// 		include dirname(__FILE__)."/classes/.$class.class.php";
// 	else
// 		echo "file not found ".$class;
// }
//spl_autoload_register ( 'template_autoloader' );

require_once(PLUGIN_ROOT_DIR.'/cpt/LeagueCpt.php');

require_once(PLUGIN_ROOT_DIR.'/classes/Standard.class.php');

?>