<?php
/**
 * curl -v http://localhost/realex/
 * curl -v -X POST --data "a=b&c=d&MD5HASH=sadsd" http://localhost/realex
 *
 * @author oconnellp
 * 
 * http://bandhattonbutton.com/payment-result/
 * 
 */
class Realex {
	
	function process() {
				
		if($_SERVER['REQUEST_METHOD']=='POST') {
			return $this->handle_post();
		}
		else {
			$out = '<h1>Custom Realex Class - '.$_SERVER['REQUEST_METHOD'].'</h1>';
			$out .= '<h2>';
			$out .= print_r($_REQUEST,true);
			$out .= '</h2>';
			return $out;			
		}
	}
	
	private function handle_post() {
		$out = '<h1>Custom Realex Class - '.$_SERVER['REQUEST_METHOD'].'</h1>';
		$out .= '<h2>';
		$out .= print_r($_REQUEST,true);
		$out .= '</h2>';
		$out .= '<h2>MD5HASH : '.$_POST['MD5HASH'].'</h2>';
		return $out;
	}
}
?>