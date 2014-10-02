
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
</div>' where option_name='dbem_single_event_format';


update wp_options
set option_value="[av_one_full first]
[av_heading heading='#s' tag='h3' color='' custom_font='' style='' size='' subheading_active='' subheading_size='15' padding='10' custom_class='']
[/av_heading]  
[/av_one_full]" where option_name='dbem_event_list_groupby_header_format';

update wp_options
set option_value="[av_one_full first]"
where option_name='dbem_event_list_item_format_header';

update wp_options
set option_value="
[av_one_fifth first]

[av_heading heading='Race' tag='h3' color='' custom_font='' style='' size='' subheading_active='' subheading_size='15' padding='10' custom_class=''][/av_heading]

[av_textblock custom_class='']
<strong><span style='font-size: 14pt;'>#_EVENTNAME</span></strong>
[/av_textblock]

[av_image src='http://bhaa.ie/wp-content/uploads/2014/01/10252603574_518b9193fb_b.jpg' attachment='3376' attachment_size='full' align='center' animation='no-animation' link='' target='' styling='' caption='' font_size='' appearance='' custom_class=''][/av_image]

[/av_one_fifth]

[av_one_fifth]

[av_heading heading='Organiser' tag='h3' color='' custom_font='' style='' size='' subheading_active='' subheading_size='15' padding='10' custom_class=''][/av_heading]

[av_textblock custom_class='']
<span style='font-size: 14pt;'><strong>#_CONTACTNAME</strong></span>
<span style='font-size: 14pt;'><strong>#_CONTACTEMAIL</strong></span>
<span style='font-size: 14pt;'><strong>#_CONTACTURL</strong></span>

[/av_textblock]

[av_image src='http://bhaa.ie/wp-content/uploads/2013/03/revenue.gif' attachment='2629' attachment_size='full' align='center' animation='no-animation' link='' target='' styling='' caption='' font_size='' appearance='' custom_class=''][/av_image]

[/av_one_fifth]

[av_one_fifth]

[av_heading heading='Race Type' tag='h3' color='' custom_font='' style='' size='' subheading_active='' subheading_size='15' padding='10' custom_class=''][/av_heading]

[av_textblock custom_class='']
<strong><span style='font-size: 14pt;'>#_CATEGORYNAME</span></strong>
<span style='font-size: 14pt;'>Footware : #_ATT{footware}</span>
[/av_textblock]

[av_image src='http://bhaa.ie/wp-content/uploads/2012/12/6904536631_48afbc60b2_z.jpg' attachment='2445' attachment_size='full' align='center' animation='no-animation' link='' target='' styling='' caption='' font_size='' appearance='' custom_class=''][/av_image]

[/av_one_fifth]

[av_one_fifth]

[av_heading heading='Distances' tag='h3' color='' custom_font='' style='' size='' subheading_active='' subheading_size='15' padding='10' custom_class=''][/av_heading]

[av_textblock custom_class='']
<span style='font-size: 14pt;'><strong>#_EVENTEXCERPT</strong></span>
<!--<span style='font-size: 14pt;'><strong>Combined Start</strong></span>
<span style='font-size: 14pt;'><strong>Women:    2 Miles</strong></span>
<span style='font-size: 14pt;'><strong>Men:           4 Miles</strong></span>
<span style='font-size: 14pt;'><strong>Footwear: Spikes </strong></span>-->
[/av_textblock]

[/av_one_fifth][av_one_fifth]

[av_heading heading='Date / Time' tag='h3' color='' custom_font='' style='' size='' subheading_active='' subheading_size='15' padding='10' custom_class=''][/av_heading]

[av_textblock custom_class='']
<span style='font-size: 14pt;'><strong>#_EVENTDATES</strong></span>

<span style='font-size: 14pt;'><strong>#_EVENTTIMES</strong></span>
[/av_textblock]

[av_button label='Enter Race' link='#_EVENTURL' link_target='' color='red' custom_bg='#444444' custom_font='#ffffff' size='small' position='left' icon_select='yes' icon='ue897' font='entypo-fontello' custom_class='']

[/av_one_fifth]
" where option_name='dbem_event_list_item_format';

update wp_options
set option_value="[/av_one_full]" 
where option_name='dbem_event_list_item_format_footer';