
update wp_options
set option_value='<div eventid="#_EVENTID" postid="#_EVENTPOSTID">
[av_tab_container position=top_tab boxed=border_tabs initial=1]

{is_future}
[av_tab title="#_EVENTNAME" icon_select="yes" icon="6"]
<p>
	<strong>Date/Time</strong><br/>
	Date(s) - #_EVENTDATES<br /><i>#_EVENTTIMES</i>
</p>
[av_one_full first]
[av_hr class="default" height="50" shadow="no-shadow" position="center"]
[/av_one_full]
#_EVENTNOTES
[av_one_full first]
[av_hr class="default" height="50" shadow="no-shadow" position="center"]
[/av_one_full]
[two_third last="no"]<strong>Location</strong> : #_LOCATIONNAME
#_LOCATIONFULLBR
#_LOCATIONNOTES
[/two_third]
[one_third last="yes"]
#_LOCATIONMAP
[/one_third]
[/av_tab]

[av_tab title="Register" icon_select="yes" icon="5"]
#_BOOKINGFORM
[/av_tab]
{/is_future}

{is_past}
[av_tab title="Results"]#_BHAARACERESULTS[/av_tab]
[av_tab title="Teams"]#_BHAATEAMRESULTS[/av_tab]
[av_tab title="#_EVENTNAME"]
<p>
	<strong>Date/Time</strong><br/>
	Date(s) - #_EVENTDATES<br /><i>#_EVENTTIMES</i>
</p>
[separator top="20" style="single"]
#_EVENTNOTES
[/av_tab]
{/is_past}			

[av_tab title="Standards" icon_select="yes" icon="4"]
<h3>BHAA Standard Table</h3>
<p>Like a golf handicap the BHAA standard table gives a runner a target time for the race distance</p>
<p>#_BHAASTANDARDS</p>
[/av_tab]
[/av_tab_container]
</div>
'
where option_name='dbem_single_event_format';


update wp_options
set option_value='<table class="table-1" style="width:90%">
<thead>
<tr>
<th class="event-time" width="200">Date/Time</th>
<th class="event-description" width="150">Event</th>
<th class="event-description" width="150">Location</th>
<th class="event-description" width="*">Race Summary</th>
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
<td>#_EVENTLINK
</td>
<td>
{has_location}<i>#_LOCATIONNAME, #_LOCATIONTOWN #_LOCATIONSTATE</i>{/has_location}
</td>
<td>#_EVENTEXCERPT <a href="#_EVENTURL">Read Full Details</a></td>
</tr>
<tr class="spacer"><td></td></tr>
'
where option_name='dbem_event_list_item_format';
