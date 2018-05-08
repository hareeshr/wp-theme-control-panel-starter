<?php
/**
* WP-dew
*
* @package WP-dew
*/

$dir = get_template_directory();
$uri = get_template_directory_uri();
$images = get_template_directory_uri() . '/images/';
$textdomain = 'voltroid-theme';

$theme_config = array(
	'textdomain' => $textdomain,
	'media_sizes' => array(
		'dew-medium'=> array( 'width'=> '300', 'height' => '300','crop' => true ),
		'dew-large' => array( 'width'=> '1024', 'height' => '','crop' => false ),
		'dew-large-slider'=> array( 'width'=> '1024', 'height' => '700', 'crop' => true ),
		'dew-def1ault-blog'=> array( 'width'=> '690','height' => '460', 'crop' => true )
	),
	'tgmpa' => array(
		'config' 				=> array(
			'id' 				=> 'tgmpa',		// Unique ID for hashing notices for multiple instances of TGMPA.
			'default_path' 		=> '',			// Default absolute path to bundled plugins.
		),
		'plugins' => array(
			array(
				'name'				=> esc_html__( 'Visual Composer', $textdomain ),
				'slug'				=> 'js_composer',
				'required'			=> true,
				'source'			=> 'http://example.com/Visual-Composer.zip',
				'dew_logo'			=> $images . '/vcomposer.png',
				'version' 			=> '5.4.5',
				'dew_author'		=> 'Wpbakery',
				'dew_description' 	=> 'Bundled Plugin'
			),
			array(
				'name'				=> esc_html__( 'Redux Framework', $textdomain ),
				'slug'				=> 'redux-framework',
				'required'			=> true,
				'dew_logo'			=> $images . '/redux.png',
				'dew_author'		=> 'Team Redux',
				'dew_description'	=> 'Redux is a simple, truly extensible and fully responsive options framework for WordPress themes and plugins.'
			),
			array(
				'name'				=> esc_html__( 'Contact Form 7', $textdomain ),
				'slug'				=> 'contact-form-7',
				'required'			=> false,
				'dew_logo'			=> $images . '/placeholder.jpg',
				'dew_author'		=> 'Takayuki Miyoshi',
				'dew_description'	=> 'Contact Form 7 can manage multiple contact forms, plus you can customize the form and the mail contents flexibly with simple markup.'
			)
		)
	),
	'redux' => array(
		'args' => array(

			'opt_name'				=> 'dew_options',

			'display_name'			=> esc_html__( 'Voltroid Theme', $textdomain ),
			'display_version'		=> '1.0.0',

			'page_parent'			=> 'dew',
			'menu_type'				=> 'submenu',
			'page_slug'				=> 'dew-theme-options',
			'global_variable'		=> 'dew_options',

			'menu_title'			=> esc_html__( 'Theme Options', $textdomain ),
			'page_title'			=> esc_html__( 'Theme Options', $textdomain ),


			//'templates_path'		=> $dir . '/admin/templates/',

			'page_permissions'		=> 'manage_options',

			'async_typography'		=> false,
			'admin_bar'				=> false,
			'dev_mode'				=> false,
			'show_options_object'	=> false,
			'customizer'			=> false

		),
		'theme-options' => $dir . '/admin/theme-options.php'
	),
	'admin_pages' => array(
		array(
			'id' 			=> 'dew',
			'page_title' 	=> 'Dew',
			'menu_title' 	=> 'Dew',
			'position' 		=> 2,
			'view' 			=> $dir . '/admin/views/dew-dashboard.php',
			'icon' 			=> $dir . '/logo.png',
			'submenu_title' => 'Dashboard'
		),
		array(
			'id' 			=> 'dew-plugins',
			'page_title' 	=> 'Dew Plugins',
			'menu_title' 	=> 'Plugins',
			'parent' 		=> 'dew',
			'position' 		=> 5,
			'view' 			=> $dir . '/admin/views/dew-plugins.php'
		),
		array(
			'id' 			=> 'dew-import-demos',
			'page_title' 	=> 'Dew Import Demos',
			'menu_title' 	=> 'Import Demos',
			'parent' 		=> 'dew',
			'position' 		=> 10,
			'view' 			=> $dir . '/admin/views/dew-demos.php'
		),
		array(
			'id' 			=> 'dew-license',
			'page_title' 	=> 'Dew License',
			'menu_title' 	=> 'License',
			'parent' 		=> 'dew',
			'position' 		=> 15,
			'view' 			=> $dir . '/admin/views/dew-login.php'
		)
	),
	'toolbar' => array(
		array(
			'id' 		=> 'dew',
			'title' 	=> 'Dew',
			'href' 		=> admin_url( 'admin.php?page=dew' ),
			'meta' 		=> array(
				'class' 	=> 'dew-options',
				'title' 	=> 'Dew Title'
			)
		),
		array(
			'id' 		=> 'dew-dashboard',
			'title' 	=> 'Dashboard',
			'href' 		=> admin_url( 'admin.php?page=dew' ),
			'parent' 	=> 'dew',
			'meta'		=> array(
				'class' 	=> 'dew-dashboard',
				'title' 	=> 'Dew Dashboard'
			)
		),
		array(
			'id' 		=> 'dew-options',
			'title' 	=> 'Theme Options',
			'href' 		=> admin_url( 'admin.php?page=dew-theme-options' ),
			'parent' 	=> 'dew',
			'meta'		=> array(
				'class' 	=> 'dew-theme-options',
				'title' 	=> 'Dew Theme Options'
			)
		),
		array(
			'id' 		=> 'dew-plugins',
			'title' 	=> 'Plugins',
			'href' 		=> admin_url( 'admin.php?page=dew-plugins' ),
			'parent' 	=> 'dew',
			'meta'		=> array(
				'class' 	=> 'dew-plugin',
				'title' 	=> 'Dew Plugins'
			)
		)
	)
);

get_template_part( 'dew/dew-init' );
dew_suite_init($theme_config); // init it

?>
