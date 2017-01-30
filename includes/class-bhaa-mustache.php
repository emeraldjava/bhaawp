<?php

class Bhaa_Mustache {
	
	protected static $instance = null;
	
	private $mustache = null;
	
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	private function __construct() {
		$options =  array('extension' => '.html');
		$this->mustache = new Mustache_Engine(
			array(
				'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__) . '/templates',$options),
				'partials_loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__) . '/templates/partials',$options)
			)
		);
	}
	
	public function loadTemplate($name) {
		return $this->mustache->loadTemplate($name);
	}
}
?>