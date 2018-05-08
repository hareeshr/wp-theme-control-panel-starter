<?php
/**
 * Dew Theme Suite
 */

if( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Dew_Core {

	/**
	 * Hold an instance of Dew_Core class
	 */
	protected static $instance = null;

	/**
	 * Main Dew_Core instance
	 */
	public static function instance() {

		if(null == self::$instance) {
			self::$instance = new Dew_Core();
		}

		return self::$instance;
	}

	/**
	 * Initialize and Return Filesystem
	 */
	public function init_filesystem() {

		if ( ! defined( 'FS_METHOD' ) ) {
			define( 'FS_METHOD', 'direct' );
		}

		// The Wordpress filesystem.
		global $wp_filesystem;

		if ( empty( $wp_filesystem ) ) {
			require_once( ABSPATH . '/wp-admin/includes/file.php' );
			WP_Filesystem();
		}

		return $wp_filesystem;
	}

	/**
	 * Return Current Theme
	 */
	public function get_current_theme() {
		$current_theme = wp_get_theme();
		if( $current_theme->parent_theme ) {
			$template_dir  = basename( get_template_directory() );
			$current_theme = wp_get_theme( $template_dir );
		}

		return $current_theme;
	}

	/**
	 * Return Ajax URL
	 */
	public function ajax_url() {
		return admin_url( 'admin-ajax.php', 'relative' );
	}

	/**
	 * Return Sidebars
	 */
	public function get_sidebars( $data = array() ) {
		global $wp_registered_sidebars;

        foreach ( $wp_registered_sidebars as $key => $value ) {
            $data[ $key ] = $value['name'];
        }

		return $data;
	}

	/**
	 * Include Template Part
	 */
	public function get_template_part( $template, $args = null ) {

		if ( $args && is_array( $args ) ) {
			extract( $args );
		}

		$located = locate_template( $template . '.php' );

		if ( ! file_exists( $located ) ) {
			_doing_it_wrong( __FUNCTION__, sprintf( wp_kses_post( __( '<code>%s</code> does not exist.', 'voltroid-theme' ) ), $located ), null );
			return;
		}

		include $located;
	}

	/**
	 * Check SEO plugins enabled
	 */
	public function has_seo_plugins() {

		$plugins = array(
			'yoast' => defined( 'WPSEO_VERSION' ),
			'ainseop' => defined( 'AIOSEOP_VERSION' )
		);

		foreach( $plugins as $item ) {
			if( $item ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Return menu location name
	 */
	public function get_menu_location_name( $location ) {

		$locations = get_registered_nav_menus();

		return isset( $locations[ $location ] ) ? $locations[ $location ] : '';
	}

	/**
	 * Return attachment types
	 */
	public function get_attachment_types( $post_id = 0 ) {

		$post_id   = empty( $post_id ) ? get_the_ID() : $post_id;
		$mime_type = get_post_mime_type( $post_id );

		list( $type, $subtype ) = false !== strpos( $mime_type, '/' ) ? explode( '/', $mime_type ) : array( $mime_type, '' );

		return (object) array( 'type' => $type, 'subtype' => $subtype );
	}

	/**
	 * Return attachment Type
	 */
	public function get_attachment_type( $post_id = 0 ) {
		return $this->get_attachment_types( $post_id )->type;
	}

	/**
	 * Return attachment subtype
	 */
	public function get_attachment_subtype( $post_id = 0 ) {
		return $this->get_attachment_types( $post_id )->subtype;
	}

	/**
	 * Check if attachment is audio
	 */
	public function is_attachment_audio( $post_id = 0 ) {
		return 'audio' === $this->get_attachment_type( $post_id );
	}

	/**
	 * Check if attachment is video
	 */
	public function is_attachment_video( $post_id = 0 ) {
		return 'video' === $this->get_attachment_type( $post_id );
	}

	/**
	 * Multipost page
	 */
	public function is_plural() {
		return ( is_home() || is_archive() || is_search() );
	}

	/**
	 * Return Visual Composer CSS
	 */
	public function get_vc_custom_css( $id ) {

		$out = '';

		if ( ! $id ) {
			return;
		}

		$post_custom_css = get_post_meta( $id, '_wpb_post_custom_css', true );
		if ( ! empty( $post_custom_css ) ) {
			$post_custom_css = strip_tags( $post_custom_css );
			$out .= '<style type="text/css" data-type="vc_custom-css">';
			$out .= $post_custom_css;
			$out .= '</style>';
		}

		$shortcodes_custom_css = get_post_meta( $id, '_wpb_shortcodes_custom_css', true );
		if ( ! empty( $shortcodes_custom_css ) ) {
			$shortcodes_custom_css = strip_tags( $shortcodes_custom_css );
			$out .= '<style type="text/css" data-type="vc_shortcodes-custom-css">';
			$out .= $shortcodes_custom_css;
			$out .= '</style>';
		}

		return $out;
	}

	/**
	 * Return post meta
	 */
	public function get_post_meta( $id, $post_id = null ) {

		if ( is_null( $post_id ) ) {
			$post_id = $this->get_current_page_id();
		}

		if ( ! $post_id ) {
			return;
		}

		$value = get_post_meta( $post_id, $id, true );
		if( is_array( $value ) ) {
			$value = array_filter($value);

			if( empty( $value ) ) {
				return '';
			}
		}
		return $value ? $value : '';
	}

	/**
	 * Return current post ID
	 */
	public function get_current_page_id() {

		global $post;
		$page_id = false;
		$object_id = is_null($post) ? get_queried_object_id() : $post->ID;

		// If we're on search page, set to false
		if( is_search() ) {
			$page_id = false;
		}
		// If we're not on a singular post, set to false
		if( ! is_singular() ) {
			$page_id = false;
		}
		// Use the $object_id if available
		if( ! is_home() && ! is_front_page() && ! is_archive() && isset( $object_id ) ) {
			$page_id = $object_id;
		}
		// if we're on front-page
		if( ! is_home() && is_front_page() && isset( $object_id ) ) {
			$page_id = $object_id;
		}
		// if we're on posts-page
		if( is_home() && ! is_front_page() ) {
			$page_id = get_option( 'page_for_posts' );
		}
		// The woocommerce shop page
		if ( class_exists( 'WooCommerce' ) && ( is_shop() || is_tax( 'product_cat' ) || is_tax( 'product_tag' ) ) ) {
			if( $shop_page = wc_get_page_id( 'shop' ) ) {
				$page_id = $shop_page;
			}
		}
		// if in the loop
		if( in_the_loop() ) {
			$page_id = get_the_ID();
		}

		return $page_id;
	}

	/**
	 * Check if woocommerce class exists
	 */

	public function is_woocommerce_active() {
		if ( class_exists( 'woocommerce' ) ) { return true; } else { return false; }
	}

	/**
	 * Print site title
	 */
	public function site_title() {
		echo $this->get_site_title();
	}

	/**
	 * Return site title
	 */
	public function get_site_title() {

		if ( $title = get_bloginfo( 'name' ) ) {
			$title = sprintf( '<h1 %s><a href="%s" rel="home">%s</a></h1>', dew_assist()->get_attr( 'site-title' ), esc_url( home_url() ), $title );
		}

		return apply_filters( 'dew_site_title', $title );
	}

	/**
	 * Print site description
	 */
	public function site_description() {
		echo $this->get_site_description();
	}

	/**
	 * Return site description
	 */
	public function get_site_description() {

		if ( $desc = get_bloginfo( 'description' ) ) {
			$desc = sprintf( '<h2 %s>%s</h2>', dew_assist()->get_attr( 'site-description' ), $desc );
		}

		return apply_filters( 'dew_site_description', $desc );
	}

}
/**
*return instance of Dew_Core
*/
function dew_core() {
	return Dew_Core::instance();
}
