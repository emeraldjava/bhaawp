<?php
// {"eid":"2121","race":"2359","leaguepoints":"10"},{"eid":"2123","race":"2362","leaguepoints":"10"}

echo 'hi';
//$str = '{"eid":"2121","race":"2359","leaguepoints":"10"},{"eid":"2123","race":"2362","leaguepoints":"10"}';
$str = '{"0":{"eid":"2121","race":"2359","leaguepoints":"10"},"1":{"eid":"2123","race":"2362","leaguepoints":"10"}}';

echo html_entity_decode($str);
var_dump(json_decode($str));
echo json_last_error();

switch (json_last_error()) {
	case JSON_ERROR_NONE:
		echo ' - No errors';
		break;
	case JSON_ERROR_DEPTH:
		echo ' - Maximum stack depth exceeded';
		break;
	case JSON_ERROR_STATE_MISMATCH:
		echo ' - Underflow or the modes mismatch';
		break;
	case JSON_ERROR_CTRL_CHAR:
		echo ' - Unexpected control character found';
		break;
	case JSON_ERROR_SYNTAX:
		echo ' - Syntax error, malformed JSON';
		break;
	case JSON_ERROR_UTF8:
		echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
		break;
	default:
		echo ' - Unknown error';
		break;
}

//var_dump(json_decode($str, true));


?>
