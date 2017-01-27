<?php
/**
 * Add bhaa endpoint to dump membership json files.
 * Class Bhaa_Endpoint
 */
class Bhaa_Endpoint {

    protected static $instance = null;

    public static function get_instance() {
        // If the single instance hasn't been set, set it now.
        if ( null == self::$instance ) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('wp_loaded',array($this,'bhaa_internal_rewrite'));
        add_filter('query_vars',array($this,'bhaa_internal_query_vars'));
        add_action('parse_request',array($this,'bhaa_internal_rewrite_parse_request'));
    }

    function bhaa_internal_rewrite(){
        add_rewrite_rule( 'bhaawp', 'index.php?bhaawp&bhaa-registration', 'top' );
    }

    function bhaa_internal_query_vars( $query_vars ){
        $query_vars[] = 'bhaa-registration';
        $query_vars[] = 'bhaa-registration-token';
        return $query_vars;
    }

    function bhaa_internal_rewrite_parse_request( &$wp ){

        if (!array_key_exists( 'bhaa-registration', $wp->query_vars ) ||
            !array_key_exists('bhaa-registration-token',$wp->query_vars) ||
            ($_GET['bhaa-registration-token']!=get_option('bhaa_registration_token')) ) {
            return;
        } else {

            $status = isset($_GET['status']) ? explode(",",$_GET['status']) : array('M');
            $limit = isset($_GET['limit']) ? $_GET['limit'] : 1000;
            error_log(sprintf('json query %s %s %s limit %d',$status[0],$status[1],$status[2],$limit));
            //echo 'The BHAA endpoint xx. key:`'.$_GET['bhaa-registration'].'`, token:`'.$_GET['bhaa-registration-token'].'`.';//.printf("%s",$wp->query_vars['bhaa-registration'][3]);
            // bhaawp?bhaa-registration&bhaa-registration-token=???
            $model = new RunnerModel();
            $runners = $model->getRegistrationRunnerDetails($status,$limit,$resultCount);
            $date = date('Y-m-d');

            header('Content-Type: application/json');
            echo json_encode(array('count'=>$resultCount,'date'=>$date,'runners'=>$runners));
        }
        die();
    }
}
?>