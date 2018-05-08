<?php
/*
 * General Section
*/

$this->sections[] = array(
	'title'  => esc_html__('General', 'voltroid-theme'),
	'icon'   => 'el-icon-adjust-alt',
);

// General Setting
$this->sections[] = array(
	'title'  => esc_html__('General Settings', 'voltroid-theme'),
	'subsection' => true,
	'fields' => array(

		array(
			'id'      => 'secondary_ac_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Secondary Accent color' , 'voltroid-theme' ),
			'subtile' => '',
			'desc'    => esc_html__( 'Use this colorpicker to override secondary accent color of the theme', 'voltroid-theme' ),
		),

		array(
			'id'      => 'tertiary_ac_color',
			'type'    => 'color',
			'title'   => esc_html__( 'Tertiary Accent color' , 'voltroid-theme' ),
			'subtile' => '',
			'desc'    => esc_html__( 'Use this colorpicker to override tertiary accent color of the theme', 'voltroid-theme' ),
		),


		array(
			'id'    => 'links_color',
			'type'  => 'link_color',
			'title' => esc_html__( 'Links Color Option', 'voltroid-theme' ),
			'active' => false,
			'visited' => true,
		),
	)
);
