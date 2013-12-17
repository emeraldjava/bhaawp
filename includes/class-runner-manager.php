<?php
/**
 * Handles operation on runners.
 * @author oconnellp
 *
 */
class Runner_Manager {
	
	protected static $instance = null;
	
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	private function __construct() {
		
	}
}
?>