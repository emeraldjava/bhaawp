<?php

class RaceMaster {

    protected static $instance = null;

    public static function get_instance() {
        // If the single instance hasn't been set, set it now.
        if ( null == self::$instance ) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('admin_action_bhaa_admin_racemaster_export_csv',array($this,'bhaa_admin_racemaster_export_csv'));
    }

    function bhaa_admin_racemaster_export_csv() {
        if ( !current_user_can( 'manage_options' ) )  {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }
        error_log("bhaa_admin_racemaster_export_csv ".$_GET['status']);

        $user = new RunnerModel();
        // array('M','I','D')
        $memberDetails = $user->exportRaceMasterData(array($_GET['status']),20000,'ARRAY_A',$resultCount);
        $csv_fields=array('id','displayname','firstname','lastname','email','status','gender','company','companyname','standard','dob');
        $output_filename = 'bhaa_members_'.$_GET['status'].'.csv';

        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-type: text/csv');
        header('Content-Description: File Transfer' );
        header('Content-Disposition: attachment; filename='.$output_filename);
        header('Expires: 0' );
        header('Pragma: public' );

        // use fputcsv : http://imtheirwebguy.com/exporting-the-results-of-a-custom-wpdb-query-to-a-downloaded-csv/
        $output_handle = @fopen( 'php://output', 'w' );
        fputcsv( $output_handle, $csv_fields );

        // Parse results to csv format
        foreach ($memberDetails as $member) {
            fputcsv( $output_handle, (array) $member);
        }

        //$end = round(microtime(true) * 1000);
        error_log('bhaa_admin_racemaster_export_csv ['.sizeof($memberDetails).']');

        // Close output file stream
        fclose( $output_handle );
        die;
    }
}
?>