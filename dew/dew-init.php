<?php
/**
 * Dew Theme Suite
 */

if( !defined( 'ABSPATH' ) ) exit;

get_template_part( 'dew/dew-base' );

//Hook for Developers
dew_action( 'before_init' );

/**
* Dew Theme
*/
class Dew_Theme_Suite extends Dew_Theme_Base {

	/**
	 * Dew Theme Suite version
	 */
	private $version = '1.0.0';

	/**
	 * Theme options values
	 * @var array
	 */
	protected $config = array();

	/**
     * Dew_Theme_Suite class instance
     * @var Dew_Theme
     */
	 protected static $instance = null;

	/**
	 * Main function initialize
	 */
	private function __construct() {

		$this->init_hooks();
	}

	/**
	 * initialize hooks
	 */
	private function init_hooks() {

		$this->add_action( 'after_setup_theme', 'includes', 2 );
		$this->add_action( 'after_setup_theme', 'setup_theme', 7 );
		$this->add_action( 'after_setup_theme', 'admin', 7 );
		$this->add_action( 'after_setup_theme', 'init_redux', 10 );

		// For developers to hook.
		dew_action( 'loaded' );
	}

	/**
	 * Dew_Theme initialize instance
	 */
	public static function instance() {
        if(null == self::$instance) {
            self::$instance = new Dew_Theme_Suite();
        }

        return self::$instance;
    }

	/**
	 * Dew_Theme set config
	 */
	public function set_config($config) {
		$this->config = $config;
		$this->set_option_name( $config['redux']['args']['opt_name'] );
    }

	/**
	 * Dew_Theme return config
	 */
	public function get_config() {
		return $this->config;
    }

	/**
	 * include dew theme suite files
	 */
    public function includes() {

		// Load Core

		include_once( get_template_directory() . '/dew/dew-core.php' );
		include_once( get_template_directory() . '/dew/dew-assist.php' );
		include_once( get_template_directory() . '/dew/dew-template-tags.php' );
		include_once( get_template_directory() . '/dew/dew-media.php' );
		include_once( get_template_directory() . '/dew/dew-dynamic-css.php' );

		//Redux
		include_once( get_template_directory() . '/dew/dew-redux-config.php' );

		include_once( get_template_directory() . '/dew/dew-dynamic-css.php' );


		// to be checked
		include_once( get_template_directory() . '/dew/dew-helpers.php' );

    }


	/**
	 * setup theme
	 */
	public function setup_theme() {

		// Set Content Width
		global $content_width;
		if ( ! isset( $content_width ) ) {
			$content_width = 780;
		}

		// Localization
		load_theme_textdomain( $this->config['textdomain'], trailingslashit( WP_LANG_DIR ) . 'themes/' ); // From Wp-Content
        load_theme_textdomain( $this->config['textdomain'], get_stylesheet_directory()  . '/languages' ); // From Child Theme
        load_theme_textdomain( $this->config['textdomain'], get_template_directory()    . '/languages' ); // From Parent Theme

		// Add default posts and comments RSS feed links to head.
        add_theme_support( 'automatic-feed-links' );

        // Let WordPress manage the document title.
        add_theme_support( 'title-tag' );

        // Enable support for Post Thumbnails on posts and pages.
        add_theme_support( 'post-thumbnails' );

		// Adding support for Widget edit icons in customizer
		add_theme_support( 'customize-selective-refresh-widgets' );

        // Enable support for WooCommerce
        add_theme_support( 'woocommerce' );

        // Switch default core markup for search form, comment form, and comments to output valid HTML5.
        add_theme_support( 'html5', array(
            'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
        ));

		// Allow shortcodes in widgets.
		add_filter( 'widget_text', 'do_shortcode' );

		// Get options for globals
		$GLOBALS[$this->get_option_name()] = get_option( $this->get_option_name(), array() );

		if( class_exists( 'WPBakeryVisualComposerAbstract' ) ) {

		}

		// UnderStrap Setup

		// Theme setup and custom theme supports
		$this->load_theme_part( 'setup' );

		// Register widget area
		$this->load_theme_part( 'widgets' );

	}

	/**
	 * Dew Theme Admin
	 */
	public function admin() {

		if( is_admin() ) {
			include_once( get_template_directory() . '/dew/admin/dew-admin-init.php' );
		}
	}

	/**
	 * Initialize redux framework
	 */
	public function init_redux() {
		new Dew_Redux_Config;
	}


	/**
	 * load theme part
	 */
	public function load_theme_part( $slug, $args = null ) {
		dew_core()->get_template_part( 'theme/' . $slug, $args );
	}

	/**
	 * load library files
	 */
	public function load_library( $slug, $args = null ) {
		dew_core()->get_template_part( 'dew/libs/' . $slug, $args );
	}

	/**
	 * load assets
	 */
	public function load_assets( $slug ) {
		return get_template_directory_uri() . '/dew/assets/' . $slug;
	}
}

/**
* initialize and return instance of Dew_Theme_Suite
*/
function dew_suite_init($config = array()) {


	$dew_suite_obj = Dew_Theme_Suite::instance();
	$dew_suite_obj->set_config($config);

	// For developers to hook.
	dew_action( 'init' );

	return $dew_suite_obj;
}

/**
*return instance of Dew_Theme_Suite
*/
function dew_suite() {
	return Dew_Theme_Suite::instance();
}
