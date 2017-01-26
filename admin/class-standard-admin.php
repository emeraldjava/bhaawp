<?php
class StandardAdmin {

    function bhaa_admin_standards()	{
        if ( !current_user_can( 'manage_options' ) )  {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }
        echo '<div class="wrap">';

        // standards

        echo '<p>hi</p>';

        $members = array(
            'meta_key' => 'bhaa_runner_status',
            'meta_value' => 'M',
            'meta_compare' => '='
        );

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
        echo 'members :'.$user_query->get_total();

        if ( ! empty( $user_query->results ) ) {
            foreach ( $user_query->results as $user ) {
                //echo '<p>' .$user->ID.' - '.$user->display_name . '</p>';
                echo sprintf('<div>%d <a href="%s" target="new">%s</a></div>',
                    $user->ID,
                    add_query_arg(array('id'=>$user->ID),'/runner'),$user->display_name);
            }
        }
        wp_reset_query();
    }
}
?>