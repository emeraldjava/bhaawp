<?php
class StandardAdmin {

    function bhaa_admin_standards() {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        $standardModel = new StandardModel();
        $memberStandardProfile = $standardModel->getMemberStandardProfile();
        include_once('views/bhaa_admin_standards.php');
    }

    function bhaa_admin_standard_list_members() {
        if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
        $runnerModel = new RunnerModel();
        $runnersInStandard = $runnerModel->getRunnersWithStandard($_GET['standard']);
        $parentStandardsLink = add_query_arg(
            array(
                'page' => 'bhaa_admin_standards'
            ),
            admin_url('admin.php')
        );
        include_once('views/bhaa_admin_standard_list_runners.php');

    }

    function bhaa_admin_no_standard()	{
        if ( !current_user_can( 'manage_options' ) )  {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }
        add_action('pre_user_query', array($this,'match_runners_who_have_raced'));
        // http://wordpress.stackexchange.com/questions/76622/wp-user-query-to-exclude-users-with-no-posts
        $missingStandard = array(
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'bhaa_runner_status',
                    'value' => 'M',
                    'compare' => '='
                ),
                array(
                    'key' => 'bhaa_runner_standard',
                    'compare' => 'NOT EXISTS'
                )
            ),
            'orderby'=>'ID',
            'fields'=>'all',
            'query_id'=>'match_runners_who_have_raced'
        );
        $user_query = new WP_User_Query( $missingStandard );
        include_once('views/bhaa_admin_standard_none.php');
    }

    function match_runners_who_have_raced( $query ) {
        if ( isset( $query->query_vars['query_id'] ) && 'match_runners_who_have_raced' == $query->query_vars['query_id'] ) {
            $query->query_from = $query->query_from . ' LEFT OUTER JOIN (
                SELECT runner, COUNT(race) as races
                FROM wp_bhaa_raceresult
				GROUP BY runner
            ) rr ON (wp_users.ID = rr.runner)';
            $query->query_where = $query->query_where . ' AND rr.races > 0 ';
            var_dump($query);
        }
    }
}
?>