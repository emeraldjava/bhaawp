<?php
/**
 * Represent an age category
 * @author oconnellp
 *
 */
class AgeCategory
{
	var $category;
	var $min;
	var $max;

	function __construct($category,$min,$max) {
		$this->category = (string) $category;
		$this->min = (int) $min;
		$this->max = (int) $max;
	}

	function toString()
	{
		return 'AgeCategory '.$this->category;
	}
}
?>