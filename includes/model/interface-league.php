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
	function deleteLeague();
	
	/**
	 * Load the league data
	 */
	function loadLeague();
	
	/**
	 * Return the top runners or teams for a specific division
	 */
	function getTopParticipantsInDivision($division,$top);

	function exportLeagueTopTen();
}
?>