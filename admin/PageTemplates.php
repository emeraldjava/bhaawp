<?php

/**
 * Created by IntelliJ IDEA.
 * User: pauloconnell
 * Date: 21/04/16
 * Time: 18:21
 */
class PageTemplates {

    protected $plugin_slug;
    private static $instance;
    protected $templates;

    public static function get_instance() {
        if( null == self::$instance ) {
            self::$instance = new PageTemplates();
        } // end if
        return self::$instance;
    }

    private function __construct() {

        $this->templates = array();
        $this->plugin_locale = 'bhaawp';

        // Grab the translations for the plugin
        add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

        // Add a filter to the page attributes metabox to inject our template into the page template cache.
        add_filter('page_attributes_dropdown_pages_args', array( $this, 'register_project_templates' ) );

        // Add a filter to the save post in order to inject out template into the page cache
        add_filter('wp_insert_post_data', array( $this, 'register_project_templates' ) );

        // Add a filter to the template include in order to determine if the page has our template assigned and return it's path
        add_filter('template_include', array( $this, 'view_project_template') );

        // Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
        //register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

        // Add your templates to this array.
        $this->templates = array(
            'template-example.php'     => __( 'Example Page Template', $this->plugin_slug ),
            'register.php' => __( 'BHAA Page Template II', $this->plugin_slug )
        );

        // adding support for theme templates to be merged and shown in dropdown
        $templates = wp_get_theme()->get_page_templates();
        $templates = array_merge( $templates, $this->templates );

    } // end constructor

    public function load_plugin_textdomain() {
        $domain = $this->plugin_slug;
        $locale = apply_filters( 'plugin_locale', get_locale(), $domain );
        load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
        load_plugin_textdomain( $domain, FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );

    } // end load_plugin_textdomain

    public function register_project_templates( $atts ) {
        // Create the key used for the themes cache
        $cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );

        // Retrieve the cache list. If it doesn't exist, or it's empty prepare an array
        $templates = wp_cache_get( $cache_key, 'themes' );
        if ( empty( $templates ) ) {
            $templates = array();
        } // end if

        // Since we've updated the cache, we need to delete the old cache
        wp_cache_delete( $cache_key , 'themes');

        // Now add our template to the list of templates by merging our templates
        // with the existing templates array from the cache.
        $templates = array_merge( $templates, $this->templates );

        // Add the modified cache to allow WordPress to pick it up for listing
        // available templates
        wp_cache_add( $cache_key, $templates, 'themes', 1800 );

        return $atts;

    } // end register_project_templates

    public function view_project_template( $template ) {

        global $post;
        error_log('91 view_project_template '.$post);

        // If no posts found, return to
        // avoid "Trying to get property of non-object" error
        if ( !isset( $post ) ) {
            error_log('96 view_project_template '.$template);
            return $template;
        }

        if ( ! isset( $this->templates[ get_post_meta( $post->ID, '_wp_page_template', true ) ] ) ) {
            error_log('101 view_project_template '.$template);
            return $template;
        } // end if

        $file = plugin_dir_path( __FILE__ ) . 'admin/templates/' . get_post_meta( $post->ID, '_wp_page_template', true );
        error_log('106 view_project_template '.$file);

        // Just to be safe, we check if the file exist first
        if( file_exists( $file ) ) {
            error_log('110 view_project_template '.$file);
            return $file;
        } // end if

        return $template;

    } // end view_project_template
}
