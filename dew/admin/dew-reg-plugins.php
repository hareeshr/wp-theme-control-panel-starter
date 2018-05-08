<?php
/**
 * Dew Theme Suite
 */

dew_suite()->load_library( 'class-tgm-plugin-activation' );

/**
 * Register the required plugins for this theme.
 */
add_action( 'tgmpa_register', '_s_register_required_plugins' );

function _s_register_required_plugins() {

	$plugins = dew_suite()->get_config()['tgmpa']['plugins'];

	$config = dew_suite()->get_config()['tgmpa']['config'];

	tgmpa( $plugins, $config );
}