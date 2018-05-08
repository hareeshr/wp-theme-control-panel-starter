<?php
/**
 * Dew Theme Suite
 */

if( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * get content template
 */
function dew_get_content_template() {

	// Set up an empty array and get the post type.
	$templates = array();
	$post_type = get_post_type();

	// Assume the theme developer is creating an attachment template.
	if ( 'attachment' === $post_type ) {

		remove_filter( 'the_content', 'prepend_attachment' );

		$type = dew_core()->get_attachment_type();

		$templates[] = "templates/content/attachment-{$type}.php";
	}

	// If the post type supports 'post-formats', get the template based on the format.
	if ( post_type_supports( $post_type, 'post-formats' ) ) {

		// Get the post format.
		$post_format = get_post_format() ? get_post_format() : 'standard';

		// Template based off post type and post format.
		$templates[] = "templates/content/{$post_type}-{$post_format}.php";

		// Template based off the post format.
		$templates[] = "templates/content/{$post_format}.php";
	}

	// Template based off the post type.
	$templates[] = "templates/content/{$post_type}.php";

	// Fallback 'content.php' template.
	$templates[] = 'templates/content/content.php';

	// Locate the template.
	$template = locate_template( $templates );

}
