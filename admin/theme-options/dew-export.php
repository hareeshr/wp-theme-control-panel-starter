<?php
/*
 * Export options
*/

$this->sections[] = array(
	'title' => esc_html__( 'Import/Export', 'voltroid-theme' ),
	'desc' => esc_html__( 'Import/Export Options', 'voltroid-theme' ),
	'icon' => 'el-icon-arrow-down',
	'fields' => array(

		array(
			'id'            => 'opt-import-export',
			'type'          => 'import_export',
			'title'         => esc_html__( 'Import Export', 'voltroid-theme' ),
			'subtitle'      => esc_html__( 'Save and restore your Redux options', 'voltroid-theme' ),
			'full_width'    => false,
		),
	),
);
