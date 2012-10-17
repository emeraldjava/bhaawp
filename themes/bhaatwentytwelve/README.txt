
Outlines the theme customisations we've done
- http://wordpress.org/support/theme/twentytwelve

Home Page
- landing page with next event, recent results, join & news.

News
- Current blog, newsletters etc

Events Page
- list of all upcoming and past events

	Event Page
	- details, location, results, team, media

Houses
- Lists the companies and sectors
	
Membership
- Sell the benefits of membership. Cost, teams, leagues.
The user will be asked to register and pay via their profile page.

Leagues
- League tables

BHAA/About
- Explain the organisation

User Registration

User Emails

function my_custom_login_logo() {
    echo '<style type="text/css">
        h1 a { background-image:url('.get_bloginfo('template_directory').'/images/custom-login-logo.gif) !important; }
    </style>';
}

add_action('login_head', 'my_custom_login_logo');