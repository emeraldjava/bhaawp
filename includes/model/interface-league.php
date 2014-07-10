<?php
/**
 * An interface for League actions
 * @author oconnellp
 *
 */
interface League{
	
	/**
	 * Delete the league data
	 */
	public function deleteLeague();
	
	/**
	 * Load the league data
	 */
	public function loadLeague();
}
?>