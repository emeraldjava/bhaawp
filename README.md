Overview
========

This project is a wordpress plugin for the Business Houses Athletic Assoication. It handles all aspects of
runner registration, race results and individual/team league logic.

Website
=======

http://bhaa.ie

Shortcodes
==========

|| Shortcode || Description ||
| --------- | ----------- |
|pdf|Embeds a PDF document in the html page|
|bhaa_standard_table|Displays the standard table for races belongs to this event page|
|bhaa_runner|Shows a runners race results|

Change Log
==========
- 2017.03.24 Add option to delete a specific race result. Add latest date to the generated membership json file.
- 2017.02.23 Handle runner id's.
- 2017.02.16 Update the handling of edit race results via the admin section of the site.
- 2017.01.30 Format the day runner date of birth. Add top-ten league csv file export feature.
- 2017.01.27 Updates to the runner json export endpoint.
- 2017.01.26 Add page to list runners by standard.
- 2017.01.25 Update to use mustache templates in more places.
- 2016.05.16 Add raceday-admin delete feature.
- 2016.04.22 Refactor the raceday pages to use templates correctly.
- 2016.04.03 Update to use the composer autoloader.
- 2016.03.28 Add registrar admin page.
- 2016.03.19 Add REST API user meta data support.
- 2015.04.10 Convert 'Senior' category to 'S' on import.
- 2015.02.24 Add sector team links.
- 2015.01.26 Racetec Athlete export.
- 2015.01.21 Add a text alert admin page.
- 2015.01.14 Update the bhaa_runner_status_shortcode() logic to handle dates.
- 2014.12.12 Add course cpt with p2p link to events. Tidy the race result table.
- 2014.12.11 Edit runner standard shortcode.
- 2014.12.10 Fix up age category and positions.
- 2014.11.07 Split admin shortcodes for the renewal email.
- 2014.11.03 Register the Race CPT via the public class and not the admin one.
- 2014.10.23 Add renewal and edit name shortcode for registrar user.
- 2014.10.10 Fix the team league point calculation and layout.
- 2014.09.10 Use a template_redirect to grab a shortcode house page content.
- 2014.09.09 Add jquery tablesorter support.
- 2014.09.08 Start re-work to support generic bhaa shortcodes.
- 2014.08.21 The individual league needs to update the race_details.
- 2014.08.05 Split SQL function for generating the mens and womens team summary value.
- 2014.08.01 Add sp's to calculate the correct inidividual and team league summary string.
- 2014.07.23 Individual league delete and populate actions implements via SQL.
- 2014.07.21 Fix the $postId reference in the Load Team Results action.
- 2014.07.10 Merge all race admin code to a new class-race-cpt in the admin area. Add team league SQL logic.
- 2014.07.09 Fix up the League CPT actions for GET and POST scenarios. Move the race CTP logic for columns
- 2014.07.08 Drop the race dropdown from the raceday forms. Update versioning of members.js script.
- 2014.06.23 Add user_register hook to set the default runner status and gender when a new user is added manually.
- 2014.06.10 Table styling and use correct plugin name. Support editing a race time. Add a new race result which can then be editied.
- 2014.05.15 Update the edit runner result form to use the ID.
- 2014.04.15 Add a raceresult edit form so we can fix up the league results
- 2014.04.07 New members form and the runner id. Make the raceday list page pretty. Set form autocomplete=off
- 2014.03.28 Add 'admin_action' functions and link the 'post_row_actions' via Jquery to the same for the League CPT.
- 2014.03.27 Update logic around getting the post_id for the race admin, needs more work.
- 2014.03.25 Add GitHub Timeout
- 2014.03.24 Add company name to autocomplete.Fix dob regex.Add page to edit a runners race result.
- 2014.03.14 Fix up the raceday pages.
- 2014.01.30 Update the raceday form layout.
- 2014.01.29 Update the raceday registration pages.
- 2014.01.21 Add company and sector dropdown for the registrar page with back end logic. Add a edit house url link for admin users to alter team members
- 2014.01.20 Handle the login/logout links with wp_nav_menu_items(). Don't show subscribers the wp admin screen
- 2014.01.14 Fix renewal email url link.Add support to edit the runners name and update display name. Display the runners company link.
- 2014.01.13 Update the raceday registration form layout with bootstrap 3 css.
- 2014.01.03 Add disable/enable booking option.
- 2014.01.01 Refactored the plugin to follow a more structured format.

Resources
=========

-- Virtual Pages
http://stackoverflow.com/questions/11281071/wordpress-plugin-content-after-the-content-using-add-filter
http://wordpress.stackexchange.com/questions/12295/creating-a-default-custom-post-template-that-a-theme-can-override
http://davejesch.com/wordpress/wordpress-tech/creating-virtual-pages-in-wordpress/
http://stackoverflow.com/questions/17960649/wordpress-plugin-generating-virtual-pages-and-using-theme-template
http://xaviesteve.com/2851/generate-a-custom-fakevirtual-page-on-the-fly-wordpress-plugin-development/


- AJAX
http://docs.jquery.com/UI/API/1.8/Autocomplete
http://api.jquery.com/jQuery.ajax/
http://stackoverflow.com/questions/11166981/how-to-use-jquery-to-retrieve-ajax-search-results-for-wordpress
http://wp.tutsplus.com/tutorials/theme-development/add-jquery-autocomplete-to-your-sites-search/
http://wordpress.stackexchange.com/questions/57988/jquery-ui-autocomplete-not-working-in-wordpress
http://jqueryui.com/demos/autocomplete/#remote
http://wordpress.org/support/topic/troubleshooting-wordpress-35-master-list
http://codex.wordpress.org/Using_Your_Browser_to_Diagnose_JavaScript_Errors
http://www.garyc40.com/2010/03/5-tips-for-using-ajax-in-wordpress/#admin-ajax
http://stackoverflow.com/questions/15273292/wordpress-submit-a-form-using-ajax
http://stackoverflow.com/questions/17366903/form-submission-using-ajax-and-handling-response-in-wordpress-website

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

Taxonomy
- http://net.tutsplus.com/tutorials/wordpress/introducing-wordpress-3-custom-taxonomies/
- http://wp.smashingmagazine.com/2013/01/14/using-wp_query-wordpress/

- PDF Attachement
http://wordpress.stackexchange.com/questions/18520/how-can-i-display-a-link-to-a-posts-word-doc-or-pdf-attachment
https://github.com/mozilla/pdf.js
http://www.wpbeginner.com/wp-themes/how-to-create-a-custom-single-attachments-template-in-wordpress/
filter the pdf url and add wrapping code

-- to have custom templates in the plugin rather than template
http://wordpress.stackexchange.com/questions/3396/create-custom-page-templates-with-plugins
http://wordpress.stackexchange.com/questions/15790/custom-form-shortcode-and-submit-handler

http://github.github.com/github-flavored-markdown/

http://wordpress.stackexchange.com/questions/17385/custom-post-type-templates-from-plugin-folder

-- initialising
http://tommcfarlin.com/instantiating-wordpress-plugins/
http://jespervanengelen.com/different-ways-of-instantiating-wordpress-plugins/

-- myisam

https://dev.mysql.com/doc/refman/5.7/en/myisamchk-other-options.html
http://stackoverflow.com/questions/2686032/resetting-auto-increment-on-myisam-without-rebuilding-the-table
