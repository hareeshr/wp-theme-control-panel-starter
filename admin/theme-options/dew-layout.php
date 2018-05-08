<?php
/*
 * Layout Section
*/

$this->sections[] = array(

	'title'  => esc_html__( 'Layout Settings', 'voltroid-theme' ),
	'icon'   => 'el-icon-website',
	'fields' => array(
		//Content Background
		array(
			'id'       => 'page-background-type',
			'type'     => 'select',
			'title'    => esc_html__( 'Content Background Type', 'voltroid-theme' ),
			'options'  => array(
				'solid'    => 'Solid',
				'gradient' => 'Gradient'
			),
		),

		array(
			'id'       => 'page-bg',
			'type'     => 'background',
			'preview'  => false,
			'title'  => esc_html__( 'Content Background', 'voltroid-theme' ),
			'required' => array(
				'page-background-type',
				'equals',
				'solid'
			),
		),

		array(
			'id'    => 'content-padding',
			'type'	=> 'spacing',
			'title' => esc_html__('Content Padding', 'voltroid-theme'),
			'left'  => false, 'right' => false,
			'units' => array(
				'px',
				'%',
				'em',
				'rem'
			)
		)

	)
);
