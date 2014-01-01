<?php

use \TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

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
	
	public function inlineCssStyles($email_html) {
		$inlineCss = true;
		if($inlineCss) {
			// create instance
			$cssToInlineStyles = new CssToInlineStyles();
			$css = file_get_contents('./templates/email.css');
		
			$cssToInlineStyles->setHTML($email_html);
			$cssToInlineStyles->setCSS($css);
		
			return $cssToInlineStyles->convert();
			//error_log($message);
		} else {
			return $email_html;
		}
	}
}
?>