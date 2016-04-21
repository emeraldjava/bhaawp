<?php
class RacedayAdmin {

	protected static $instance = null;
    protected $templates;

	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

    /**
     * http://wordpress.stackexchange.com/questions/3396/create-custom-page-templates-with-plugins
     */
	private function __construct() {
        $this->templates = array();

        // Add a filter to the attributes metabox to inject template into the cache.
        add_filter(
            'page_attributes_dropdown_pages_args',
            array( $this, 'register_project_templates' )
        );


        // Add a filter to the save post to inject out template into the page cache
        add_filter(
            'wp_insert_post_data',
            array( $this, 'register_project_templates' )
        );


        // Add a filter to the template include to determine if the page has our
        // template assigned and return it's path
        add_filter(
            'template_include',
            array( $this, 'view_project_template')
        );


        // Add your templates to this array.
        $this->templates = array(
            'register.php'     => 'BHAA RaceDay Register',
        );

        add_action( 'admin_init', array(&$this,'bhaa_create_raceday_pages'));
	}

	function bhaa_create_raceday_pages(){
		$this->createPageWithTemplate('rd-register','register.php');
		$this->createPageWithTemplate('rd-list','register.php');
	}

	/**
	 * http://www.kvcodes.com/2014/01/how-to-create-pages-programmatically-and-add-custom-template-wordpress/
	 */
	function createPageWithTemplate($name,$template) {

		if( get_page_by_title($name) == NULL ) {
			$page_id = wp_insert_post(array(
				'post_title' => $name,
				'post_type' => 'page',
				'post_name' => $name,
				'post_status' => 'publish'
			));
			add_post_meta($page_id, '_wp_page_template', $template);
			error_log('Created page '.$name.' with ID '.$$page_id);
		}
	}

    /**
     * Adds our template to the pages cache in order to trick WordPress
     * into thinking the template file exists where it doens't really exist.
     *
     */
    public function register_project_templates( $atts ) {
        // Create the key used for the themes cache
        $cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );
        // Retrieve the cache list.
        // If it doesn't exist, or it's empty prepare an array
        $templates = wp_get_theme()->get_page_templates();
        if ( empty( $templates ) ) {
            $templates = array();
        }
        // New cache, therefore remove the old one
        wp_cache_delete( $cache_key , 'themes');
        // Now add our template to the list of templates by merging our templates
        // with the existing templates array from the cache.
        $templates = array_merge( $templates, $this->templates );
        // Add the modified cache to allow WordPress to pick it up for listing
        // available templates
        wp_cache_add( $cache_key, $templates, 'themes', 1800 );
        return $atts;
    }

    /**
     * Checks if the template is assigned to the page
     */
    public function view_project_template( $template ) {
        global $post;
        if (!isset($this->templates[get_post_meta($post->ID, '_wp_page_template', true)] ) ) {
            return $template;
        }
        $file = plugin_dir_path(__FILE__). 'admin/templates/'. get_post_meta( $post->ID, '_wp_page_template', true );

        // Just to be safe, we check if the file exist first
        if( file_exists( $file ) ) {
            return $file;
        }
        else { echo $file; }
        return $template;
    }
}
?>