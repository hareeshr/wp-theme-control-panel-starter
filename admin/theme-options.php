<?php

$options = array(
	'general',
	'layout',
	'export'
);


$path = get_template_directory() . '/admin/theme-options/';

foreach( $options as $option ) {
	$file = "dew-{$option}.php";
	include_once $path . $file;
}
