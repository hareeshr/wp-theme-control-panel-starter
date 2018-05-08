<?php
/**
 * Dew Theme Suite
 */

if( !defined( 'ABSPATH' ) ) exit;

if( ! class_exists( 'Dew_Theme_Base' ) ) :

class Dew_Theme_Base {

	/**
	 * Dew_Theme add action function
	 */
	public function add_action( $hook, $function_to_add, $priority = 10, $accepted_args = 1 ) {
		add_action( $hook, array( &$this, $function_to_add ), $priority, $accepted_args );
	}

	/**
	 * Dew_Theme add filter function
	 */
	public function add_filter( $tag, $function_to_add, $priority = 10, $accepted_args = 1 ) {
		add_filter( $tag, array( &$this, $function_to_add ), $priority, $accepted_args );
	}

	/**
	 * Dew_Theme set options name function
	 */
	public function set_option_name( $name = '' ) {

		if( $name ) {
			$this->theme_options_name = $name;
		}
	}

	/**
	 * Dew_Theme get options name function
	 */
	public function get_option_name( $name = '' ) {
		return $this->theme_options_name;
	}

	/**
	 * Dew_Theme get version function
	 */
	public function get_version() {
		return $this->version;
	}


}

endif;

// Dew action setup
if( ! function_exists( 'dew_action' ) ) :
	function dew_action() {

		$args   = func_get_args();

		if( !isset( $args[0] ) || empty( $args[0] ) ) {
			return;
		}

		$action = 'dew_' . $args[0];
		unset( $args[0] );

		do_action_ref_array( $action, $args );
	}
endif;
