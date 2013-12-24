update wp_options
set option_value='<div eventid="#_EVENTID" postid="#_EVENTPOSTID">
<p>#_EVENTNAME</p>
[fusion_tabs layout="horizontal" backgroundcolor="" inactivecolor=""]

{is_future}
[fusion_tab title="Details"]
<p>
	<strong>Date/Time</strong><br/>
	Date(s) - #_EVENTDATES<br /><i>#_EVENTTIMES</i>
</p>

[two_third last="no"]<strong>Location</strong> : #_LOCATIONNAME
#_LOCATIONFULLBR
#_LOCATIONNOTES
[/two_third]
[one_third last="yes"]
#_LOCATIONMAP
[/one_third]

<p><strong>Location</strong> : #_LOCATIONNAME</p>
#_LOCATIONFULLBR
<p>#_LOCATIONNOTES</p>
</p>
<p>
	<strong>Category(ies)</strong>
	#_CATEGORIES
</p>
<br style="clear:both" />
#_EVENTNOTES
{has_bookings}
<h3>Bookings</h3>
#_BOOKINGFORM
{/has_bookings}
[/fusion_tab]
{/is_future}

{is_past}
[fusion_tab title="Results"]#_BHAARACERESULTS[/fusion_tab]
[fusion_tab title="Teams"]#_BHAATEAMRESULTS[/fusion_tab]
[fusion_tab title="Details"]Details[/fusion_tab]
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