<?php
get_header();
echo '<section id="primary">';
echo '<h1>bhaa wp template league</h1>';

//echo '<h2>'.$_GET('division').'</h2>';
if(isset($wp_query->query_vars['division'])) {
	$division = urldecode($wp_query->query_vars['division']);
	echo '<h2>Division '.$division.'</h2>';
}
else
	echo '<h2>League Summary</h2>';

//error_log("league ".get_the_ID());

$leagueSummary = new LeagueSummary(get_the_ID());
//var_dump($leagueSummary);
if(!isset($wp_query->query_vars['division']))
{	

	$divisions = $leagueSummary->getDivisions();
	//var_dump($divisions);
	
	//echo '<p>After a long delay the league tables arhttp://wordpress.stackexchange.com/questions/17385/custom-post-type-templates-from-plugin-foldere nearly back. There might be an odd sum or two incorrect and we still have to update the Race Organiser points. Hopefully St Patrick will rub a bit of polish onto proceeding over the weekend.</p>';
	//echo '<h2>'.$leagueSummary->getName().''.get_the_ID().'</h2>';
	
	$rows_in_summary=10;
	$leagueSummaryByDivision=$leagueSummary->getLeagueSummaryByDivision($rows_in_summary);
	//var_dump($leagueSummaryByDivision);
	
	foreach($divisions as $division) :
	//	echo $division->code;
	endforeach;
	
	$i = 0;
	$division='';
	$divisionTable = '';
	
	//$last_index = count($leagueSummaryByDivision) - 1;
	
	foreach($leagueSummaryByDivision as $summary) :
	
// 	if(!isset($summary->leaguedivision))
// 	{
// 		$rows_in_summary=10;
// 		break;
// 	}
	$i++;
	if($summary->leaguedivision!=$division)
	{
		$division=$summary->leaguedivision;
		$divisionTable = '';
		
		
		$divisionTable .= sprintf('<h3><a href="%s">Division %s</a><h3>'.PHP_EOL,
			add_query_arg(array('division'=>$division)),
			$division);
		
		$divisionTable .= '<table>'.PHP_EOL;
		$divisionTable .= '<tr>
		<th>Position</th>
	    <th>Name</th>
	    <th>Points</th>
		<th>Races</th>
	  	</tr>'.PHP_EOL;
		//error_log("header");
	}
	
	if($division!='')
	{
		// specific row
		$divisionTable .= '<tr>
		<td>'.$summary->leagueposition.'</td>';
		if($leagueSummary->getType()=='I')
			$divisionTable .= '<td>'.sprintf('<a href="/runner?id=%d"><b>%s</b></a>',$summary->leagueparticipant,$summary->display_name).'</td>';
		else
			$divisionTable .= '<td>'.sprintf('<a href="/?post_type=house&p=%d"><b>%s</b></a>',$summary->leagueparticipant,$summary->display_name).'</td>';
	    $divisionTable .= '<td>'.$summary->leaguepoints.'</td>
		<td>'.$summary->leaguescorecount.'</td>
	  	</tr>'.PHP_EOL;
	}
	
	// close the table
	if($summary->leagueposition==$rows_in_summary || (count($leagueSummaryByDivision) == $i) )
	{
		$divisionTable .= '</table>'.PHP_EOL;
		$division='';
		echo $divisionTable;
	}
	
	endforeach;
}
else
{
	$table = $leagueSummary->getDivisionSummary($division);
	if(strpos($division, 'L'))
		$events = $leagueSummary->getLeagueRaces('W');
	else
		$events = $leagueSummary->getLeagueRaces('M');
	
	//error_log(print_r($events,true));
	//error_log(print_r($table,true));
		
	echo '<table>';
	echo '<tr><th>Position</th>';
	// only show name for individual league
	if($leagueSummary->getType()=='I')
		echo '<th>Name</th>';
	else
		echo '<th>Company</th>';
	
	//echo '<th>Standard</th>';
	foreach ( $events as $event )
	{
		//  [lid] => 2492 [post_title] => Winter League 2012/2013 [eid] => 2121 [etitle] => South Dublin County Council 2013 [rid] => 2359 [rtitle] => sdcc2013_4M_M [rtype] => M
		echo '<th>'.substr($event->etitle,0,12).'</th>';
	}
	echo '<th>Races</th>
  	<th>Points</th>
	</tr>';
	$i=1;
	foreach($table as $row)
	{
		//[ID] => 1600 [user_login] => martin.prunty
		// [user_pass] => $.8/
		// [user_nicename] => martin-prunty
		// [user_email] =>[user_url] => [user_registered] => 2012-12-01 15:03:58
		// [user_activation_key] => [user_status] => 0 [display_name] => Martin Prunty )
		// [2] => stdClass Object ( [league] => 2492 [leaguetype] => I
		// [leagueparticipant] => 1628 [leaguestandard] => 7 [leaguedivision] => A
		// [leagueposition] => 37 [leaguescorecount] => 1 [leaguepoints] => 10
		// [leaguesummary] => {"eid":"2121","race":"2359","leaguepoints":"10"},{"eid":"2123","race":"2362","leaguepoints":"10"}
		if($row->leaguedivision!=$division)
		{
			$i++;
		}
		else
		{
			// specific row
			echo '<tr><td>'.($i++).'</td>';
			if($leagueSummary->getType()=='I')
				echo '<td>'.sprintf('<a href="/runner/?id=%d"><b>%s</b></a>',$row->leagueparticipant,$row->display_name).'</td>';
			else
				echo '<td>'.sprintf('<a href="/?post_type=house&p=%d"><b>%s</b></a>',$row->ID,$row->post_title).'</td>';
			//echo '<td>'.$row->leaguesummary.'</td>';
			
			$points = explode(',', $row->leaguesummary);
			//var_dump($points);
			//$points = json_decode(html_entity_decode($row->leaguesummary));
			// for each event id - look for a matching json row
			foreach ( $events as $event )
			{
				// 9925 2278:9.7,2280:p=10
				// nasty - loops the points
				$match = false;
				if(!empty($points))
				{
					$r = 0;
					foreach ( $points as $point )
					{
						$result = explode(':', $point);
						if($event->eid==$result[0])
						{
							$r = $result[1];//$point->leaguepoints;
							if($r!=0 && sizeof($result)==3) {
								echo '<td>'.$r.'RO</td>';
								$match=true;
								break;
							} else if($r!=0) {
								echo '<td>'.$r.'</td>';
								$match=true;
								break;
							}
						}
					}
					//if($r!=0)
					//	echo '<td>'.$r.'</td>';
					//else
					//echo '<td>-</td>';
				}
				//else
				if(!$match)
				{
					echo '<td>-</td>';
					$match=false;
				}
				//else
				//{
				//echo '<td>e</td>';
				//}
			}
			echo '<td>'.$row->leaguescorecount.'</td>
    <td><b>'.$row->leaguepoints.'</b></td>
  	</tr>';
		}
	
	}//endforeach;
	echo '</table>';
}
echo '</section>';

if($data['blog_comments']):
	wp_reset_query();
	comments_template();
endif;
			
get_footer();
?>