
select * from wp_users
select * from wp_usermeta where user_id=1;

select post_title from wp_posts where id=201;

update wp_options
set option_value='<div eventid="#_EVENTID" postid="#_EVENTPOSTID">
[fusion_tabs layout="horizontal" backgroundcolor="" inactivecolor=""]

{is_future}
[fusion_tab title="#_EVENTNAME"]
<p>
	<strong>Date/Time</strong><br/>
	Date(s) - #_EVENTDATES<br /><i>#_EVENTTIMES</i>
</p>
[separator top="20" style="single"]
#_EVENTNOTES
[separator top="20" style="single"]
[two_third last="no"]<strong>Location</strong> : #_LOCATIONNAME
#_LOCATIONFULLBR
#_LOCATIONNOTES
[/two_third]
[one_third last="yes"]
#_LOCATIONMAP
[/one_third]
[/fusion_tab]

[fusion_tab title="Register"]
#_BOOKINGFORM
[/fusion_tab]
{/is_future}

{is_past}
[fusion_tab title="Results"]#_BHAARACERESULTS[/fusion_tab]
[fusion_tab title="Teams"]#_BHAATEAMRESULTS[/fusion_tab]
[fusion_tab title="_EVENTNAME"]
<p>
	<strong>Date/Time</strong><br/>
	Date(s) - #_EVENTDATES<br /><i>#_EVENTTIMES</i>
</p>
[separator top="20" style="single"]
#_EVENTNOTES
[/fusion_tab]
{/is_past}			

[fusion_tab title="Standards"]
<h3>BHAA Standard Table</h3>
<p>Like a golf handicap the BHAA standard table gives a runner a target time for the race distance</p>
<p>#_BHAASTANDARDS</p>
[/fusion_tab]
[/fusion_tabs]
</div>
'
where option_name='dbem_single_event_format';


update wp_options
set option_value='<table class="table-1" style="width:90%">
<thead>
<tr>
<th class="event-time" width="200">Date/Time</th>
<th class="event-description" width="150">Event</th>
<th class="event-description" width="*">Races</th>
</tr>
</thead>
<tbody>
'
where option_name='dbem_event_list_item_format_header';

update wp_options
set option_value='<tr class="event-details">
<td>
#_EVENTDATES</br>
<i>#_EVENTTIMES</i>
</td>
<td>#_EVENTLINK</br>
{has_location}<i>#_LOCATIONNAME, #_LOCATIONTOWN #_LOCATIONSTATE</i>{/has_location}
</td>
<td>#_EVENTEXCERPT</td>
</tr>
<tr class="spacer"><td></td></tr>
'
where option_name='dbem_event_list_item_format';
