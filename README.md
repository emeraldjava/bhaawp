bhaawp
======

Wordpress Plugin for the Business Houses Athletic Assoication

# Database Tables

A summary of the bhaa tables used

## RaceResult

<table>
  <tr>
    <th>Field</th>
    <th>Intended Use</th>
  </tr>
<tr>
    <td>id</td>
    <td>raceresult id for specific row editing</td>
</tr>
<tr>
    <td>race</td>
    <td>race id</td>
</tr>
<tr>
    <td>runner</td>
    <td>wp user id</td>
</tr>
<tr>
    <td>time</td>
    <td>race time</td>
</tr>
<tr>
    <td>position</td>
    <td></td>
</tr>
<tr>
    <td>racenumber</td>
    <td></td>
</tr>
<tr>
    <td>category</td>
    <td></td>
</tr>
<tr>
    <td>standard</td>
    <td>standard entering the race - use for team standard</td>
</tr>
<tr>
    <td>postracestandard</td>
    <td>standard afterwards</td>
</tr>
<tr>
    <td>class</td>
    <td>RAN | RACE_ORG | PRE_PAID : The registration app should really insert a row here, this row is updated then with results.</td>
</tr>
<tr>
    <td>company</td>
    <td>The id of the house from which the company name will be displayed.</td>
</tr>
<tr>
    <td>teamresult?</td>
    <td>Foreign ket to the id of the team result table to indicate this runner was member of a scoreing team. Bmcl - Should we drop this column and use  teamresult Not sure how </td>
</tr>
<tr>
    <td>leaguepoints?</td>
    <td>The league points this runner earned. BMcL : should we move all league data to a seperate table?</td>
</tr>
</table>		

## TeamResult

Ideally a single row per team result, the ID will be FK linked back to the RaceResult so three runners link to the same team.

<table>
<tr>
    <td>id</td>
    <th>The unique id, or a foreign key to the raceresult.id?</th>
</tr>
<tr>
    <td>team</td>
    <td>The id of the company or sector team</td>
</tr>
<tr>
    <td>race</td>
    <td>the race this result was achieved at - duplicate?</td>
</tr>
<tr>
    <td>league</td>
    <td>the leagee id</td>
</tr>
<tr>
    <td>standardtotal</td>
    <td>sum of the three standards</td>
</tr>
<tr>
    <td>positiontotal</td>
    <td>sum of the three positions</td>
</tr>
<tr>
    <td>leaguepoints</td>
    <td>league points</td>
</tr>
<tr>
    <td>class</td>
    <td>The class or division that the result was achieved in A,B,C,D,W,O,OW</td>
</tr>
<tr>
    <td>status</td>
    <td>ACTIVE or PENDING to allow editing pre publication</td>
</tr>
</table>
						
## LeagueSummary

Should handle individual and team league. Might be difficult with new team league format.
Same as existing but maintain a summary of the runners league results in a json format.

<table>
  <tr>
    <td>id</th>
    <th>unique id in case we need to edit the row</th>
  </tr>
<tr>
    <td>participant</td>
    <td>the runner or team id</td>
</tr>
<tr>
    <td>league</td>
    <td>the league id</td>
</tr>
<tr>
    <td>type</td>
    <td>individual or team league</td>
</tr>
<tr>
    <td>summary</td>
    <td>A summary of the participants details, sdcc2013:9.9;eircom2013:8.8;ncf2013:NA</td>
</tr>
<tr>
    <td></td>
    <td></td>
</tr>
</table>

# User Data

- metadata fields store the name value pairs below
- p2p is used to link a runner to a house (aka company/sector team).

## User Meta Data Fields

	const BHAA_RUNNER_ADDRESS1 = 'bhaa_runner_address1';
	const BHAA_RUNNER_ADDRESS2 = 'bhaa_runner_address2';
	const BHAA_RUNNER_ADDRESS3 = 'bhaa_runner_address3';
	
	const BHAA_RUNNER_FIRSTNAME = 'bhaa_runner_firstname'; - wp
	const BHAA_RUNNER_LASTNAME = 'bhaa_runner_lastname'; - wp
	
	const BHAA_RUNNER_DATEOFBIRTH = 'bhaa_runner_dateofbirth';
	const BHAA_RUNNER_COMPANY = 'bhaa_runner_company'; - hidden
	const BHAA_RUNNER_NEWSLETTER = 'bhaa_runner_newsletter';
	const BHAA_RUNNER_TEXTALERT = 'bhaa_runner_textalert';
	const BHAA_RUNNER_MOBILEPHONE = 'bhaa_runner_mobilephone';
	const BHAA_RUNNER_INSERTDATE = 'bhaa_runner_insertdate';
	const BHAA_RUNNER_DATEOFRENEWAL = 'bhaa_runner_dateofrenewal';
	const BHAA_RUNNER_TERMS_AND_CONDITIONS = 'bhaa_runner_terms_and_conditions';

<table>
  <tr>
    <th></th>
    <th>Tables</th>
    <th>Are</th>
    <th>Cool</th>
  </tr>
  <tr>
    <th>Zebra</th>
    <td>Stripes</td>
    <td>Are</td>
    <td>Pretty</td>
  </tr>
  <tr>
    <th>Here</th>
    <td>Is</td>
    <td>Another</td>
    <td>Row</td>
  </tr>
</table>

code 

```php
echo {}
```
 
ToDo
- race day registration
- race day racetec export
- company ajax
- team report
- league report
- standard report

- AJAX
http://docs.jquery.com/UI/API/1.8/Autocomplete
http://api.jquery.com/jQuery.ajax/
http://stackoverflow.com/questions/11166981/how-to-use-jquery-to-retrieve-ajax-search-results-for-wordpress
http://wp.tutsplus.com/tutorials/theme-development/add-jquery-autocomplete-to-your-sites-search/
http://wordpress.stackexchange.com/questions/57988/jquery-ui-autocomplete-not-working-in-wordpress
http://jqueryui.com/demos/autocomplete/#remote

- Widget
http://wp.tutsplus.com/tutorials/widgets/create-a-tabbed-widget-for-custom-post-types/

-htaccess
http://stackoverflow.com/questions/163302/how-do-i-ignore-a-directory-in-mod-rewrite

User profile
- http://stackoverflow.com/questions/6755921/wordpress-adding-columns-to-user-profile
- http://wordpress.org/extend/plugins/wordpress-users/developers/
- http://stackoverflow.com/questions/11669817/issue-with-wordpress-adding-extra-user-profile-fields
- http://stackoverflow.com/questions/2982047/wordpress-create-profile-pages-for-users

Custom Post Types
- http://wp.smashingmagazine.com/2012/02/03/custom-post-types-organize-online-marketing-campaigns/

Linking post types
- http://wordpress.org/extend/plugins/posts-to-posts/

Event Manager
- http://wp-events-plugin.com/documentation/shortcodes/
- http://wp-events-plugin.com/documentation/event-search-attributes/
- http://wp-events-plugin.com/documentation/placeholders/

WP_List_Table
- http://mac-blog.org.ua/942/ edit/delete links on the WP_Table_List
- http://pippinsplugins.com/add-custom-links-to-user-row-actions/
- http://stackoverflow.com/questions/12092707/wordpress-how-to-enable-the-edit-and-delete-action-button-in-wp-list-table-clas
- http://www.binnash.com/2012/08/13/using-wordpress-wp_list_table-in-the-frontend/#comment-9
- http://codex.wordpress.org/Class_Reference/WP_List_Table
- http://wp.smashingmagazine.com/2011/11/03/native-admin-tables-wordpress/

PHP Manual
- http://www.php.net/manual/en/function.strchr.php

Email Notification
- http://codex.wordpress.org/Function_Reference/wp_new_user_notification

Caching
- http://scotty-t.com/2012/01/20/wordpress-memcached/
- http://wp.smashingmagazine.com/2012/06/26/diy-caching-methods-wordpress/
- http://wordpress.org/extend/plugins/w3-total-cache/
- http://codex.wordpress.org/Class_Reference/WP_Object_Cache
- http://ocaoimh.ie/

Archive
- http://codex.wordpress.org/Creating_an_Archive_Index
- http://wp.tutsplus.com/tutorials/create-a-wordpress-archives-template-for-your-theme/
- http://wphacks.com/how-to-create-an-archive-page-for-your-wordpress-blog/

Email
- http://wp.smashingmagazine.com/2011/10/25/create-perfect-emails-wordpress-website/

http://github.github.com/github-flavored-markdown/