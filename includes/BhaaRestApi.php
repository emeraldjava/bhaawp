<?php

/**
 * Created by IntelliJ IDEA.
 * User: pauloconnell
 * Date: 19/03/16
 * Time: 16:44
 */
class BhaaRestApi {

    protected static $instance = null;

    public static function get_instance() {
        // If the single instance hasn't been set, set it now.
        if ( null == self::$instance ) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * BhaaRestApi constructor.
     */
    private function __construct() {
        add_action('rest_api_init',array(&$this,'bhaa_rest_api_runner_metadata'));
    }

    function bhaa_rest_api_runner_metadata() {
        register_rest_field( 'user',
            Runner::BHAA_RUNNER_STATUS,
            array(
                'get_callback'    => array(&$this,'bhaa_user_meta_data'),
                'update_callback' => null,
                'schema'          => null,
            )
        );
        register_rest_field( 'user',
            Runner::BHAA_RUNNER_DATEOFRENEWAL,
            array(
                'get_callback'    => array(&$this,'bhaa_user_meta_data'),
                'update_callback' => null,
                'schema'          => null,
            )
        );
        register_rest_field( 'user',
            Runner::BHAA_RUNNER_GENDER,
            array(
                'get_callback'    => array(&$this,'bhaa_user_meta_data'),
                'update_callback' => null,
                'schema'          => null,
            )
        );
        register_rest_field( 'user',
            Runner::BHAA_RUNNER_DATEOFBIRTH,
            array(
                'get_callback'    => array(&$this,'bhaa_user_meta_data'),
                'update_callback' => null,
                'schema'          => null,
            )
        );
    }

    function bhaa_user_meta_data( $object, $field_name, $request ) {
        return get_user_meta( $object[ 'id' ], $field_name, true );
    }
}