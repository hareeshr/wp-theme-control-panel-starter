<?php
/**
 * Dew Theme Suite
 */

if( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Dew_Redux_Config extends Dew_Theme_Base {

	public $ReduxFramework = null;
	public $theme = null;
	public $args 	 = array();
	public $sections = array();

	/**
	 * [__construct description]
	 */
	public function __construct() {

		if ( !class_exists( 'ReduxFramework' ) ) {
			return;
		}

		$this->theme = wp_get_theme();

		$this->set_arguments();

		if( ! isset( $this->args['opt_name'] ) ) {
			return;
		}

		$this->set_sections();

		// If Redux is running as a plugin, this will remove the demo notice and links
		$this->add_action( 'redux/loaded', 'remove_demo' );

		$this->ReduxFramework = new ReduxFramework($this->sections, $this->args);
	}

	/**
	 * return redux framework instance
	 */
	public function get_redux() {
		return $this->ReduxFramework;
	}

	/**
	 * set redux arguments
	 */
	public function set_arguments() {

		$args = array(

			'async_typography'     => false,
	        'admin_bar'            => false,
	        'dev_mode'             => false,
			'show_options_object'  => false,
	        'customizer'           => false

		  );
		$config = dew_suite()->get_config();

		if( ! isset($config['redux']['args']) )
			return;

		foreach ($config['redux']['args'] as $key => $value)
			$args[$key] = $value;

		$this->args = $args;

	}

	/**
	 * Remove the demo link and the notice of integrated demo from the redux-framework plugin
	 */
	function remove_demo() {

		if ( class_exists( 'ReduxFrameworkPlugin' ) ) {

			remove_filter( 'plugin_row_meta', array( ReduxFrameworkPlugin::instance(), 'plugin_metalinks' ), null, 2);
			remove_action( 'admin_notices', array( ReduxFrameworkPlugin::instance(), 'admin_notices' ) );
		}
	}

	/**
	 * Set Sections
	 */
	public function set_sections() {

		$config = dew_suite()->get_config();

		if( ! isset($config['redux']['theme-options']) )
			return;

		include_once $config['redux']['theme-options'];

	}
}
