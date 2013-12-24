update wp_options
set option_value='<p eventid="#_EVENTID" postid="#_EVENTPOSTID">
#_EVENTNAME

{is_future}event is in the future{/is_future}

{is_past}event is in the past
[fusion_tabs layout="horizontal" backgroundcolor="" inactivecolor=""]
[fusion_tab id="results"]#_BHAARACERESULTS[/tab]
[fusion_tab id="teams"]#_BHAATEAMRESULTS[/tab]
[fusion_tab id="standards"]
<h3>BHAA Standard Table</h3>
<p>Like a golf handicap the BHAA standard table gives a runner a target time for the race distance</p>
<p>#_BHAASTANDARDS</p>
[/fusion_tab]
[/fusion_tabs]
{/is_past}			
</p>

<div style="float:right; margin:0px 0px 15px 15px;">#_LOCATIONMAP</div>
<p>
	<strong>Date/Time</strong><br/>
	Date(s) - #_EVENTDATES<br /><i>#_EVENTTIMES</i>
</p>
{has_location}
<p>
	<strong>Location</strong><br/>
	#_LOCATIONLINK
</p>
{/has_location}
<p>
	<strong>Category(ies)</strong>
	#_CATEGORIES
</p>
<br style="clear:both" />
#_EVENTNOTES
{has_bookings}
<h3>Bookings</h3>
#_BOOKINGFORM
{/has_bookings}'
where option_name='bhaaie_wp';