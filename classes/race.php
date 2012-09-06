<?php
class Race
{
	function hello($name)
	{
		echo "hi ".$name;
		
		$line = "a,b,c,
		d,e,f,";
		
		$parsed = str_getcsv(
				$line, # Input line
				',',   # Delimiter
				'"',   # Enclosure
				'\n'   # Escape char
				);
		var_dump( $parsed );
	}
}

$race = new Race();
$race->hello("p");

?>