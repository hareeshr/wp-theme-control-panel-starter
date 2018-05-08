<?php
/**
 * Dew Theme Suite
 *
 * Admin Initialization Class
 */

if( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Dew_Admin extends Dew_Theme_Base {

	/**
	 * [__construct description]
	 * @method __construct
	 */
	public function __construct() {

		// Envato Market

		$this->add_action( 'init', 'init', 7 );
		$this->add_action( 'admin_menu', 'fix_parent_menu', 999 );

		//not adjusted
		$this->add_action( 'admin_init', 'save_plugins' );
		$this->add_action( 'admin_enqueue_scripts', 'enqueue', 99 );
		$this->add_action( 'admin_bar_menu', 'toolbar', 999 );

	}

	/**
	 * Initialize Admin Pages and plugins
	 */
	public function init() {

		include_once( get_template_directory() . '/dew/admin/dew-reg-plugins.php' );

		include_once( get_template_directory() . '/dew/admin/dew-admin-page.php' );

		$this->load_admin_pages();

	}

	/**
	 * Load Admin Pages
	 */
    function load_admin_pages() {

    	$pages = dew_suite()->get_config()['admin_pages'];
    	foreach ($pages as $key => $page) {
    		new Dew_Admin_Page($page, $key);
    	}

    }
	/**
	 * Enqueu CSS and JS
	 */
    public function enqueue() {

    }


	/**
	 * Admin redirects
	 */
	public function admin_redirects() {

		global $pagenow;

		if ( is_admin() && 'themes.php' == $pagenow && isset( $_GET['activated'] ) ) {
			wp_redirect( admin_url( 'admin.php?page=dew' ) );
			exit;
		}
	}

	/**
	 * Add admin to toolbar
	 */
	public function toolbar( $wp_admin_bar ) {

		if(! isset( dew_suite()->get_config()['toolbar'] ))
			return;

		if ( !current_user_can( 'edit_theme_options' ) ) {
		    return;
		}

    	$pages = dew_suite()->get_config()['toolbar'];

    	foreach ($pages as $args) {

    		$wp_admin_bar->add_node( $args );

    	}

	}

	/**
	 * Parent Menu Fix
	 */
	public function fix_parent_menu() {

        if ( !current_user_can( 'edit_theme_options' ) ) {
            return;
        }

		global $submenu;

    	$pages = dew_suite()->get_config()['admin_pages'];
    	foreach ($pages as $key => $page) {
    		if(isset($page['submenu_title']) && $page['submenu_title'])
    			$submenu[$page['id']][0][0] = $page['submenu_title'];
    	}

		remove_submenu_page( 'themes.php', 'tgmpa-install-plugins' );
		remove_submenu_page( 'tools.php', 'redux-about' );
	}

	/**
	 * Save plugins
	 */
	public function save_plugins() {

    }
}
new Dew_Admin;
