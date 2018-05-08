<?php
/**
 * Dew Theme Suite
 */

if( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Dew_Assist {

	/**
	 * Hold an instance of Dew_Assist class
	 */
	protected static $instance = null;

	/**
	 * Main Dew_Assist instance
	 */
	public static function instance() {

		if(null == self::$instance) {
			self::$instance = new Dew_Assist();
		}

		return self::$instance;
	}

	/**
	 * Sanitize HTML Classes
	 */
	public function sanitize_html_classes( $class, $fallback = null ) {

		// Explode it, if it's a string
		if ( is_string( $class ) ) {
			$class = explode( ' ', $class );
		}

		if ( is_array( $class ) && !empty( $class ) ) {
			$class = array_map( 'sanitize_html_class', $class );
			return join( ' ', $class );
		}
		else {
			return sanitize_html_class( $class, $fallback );
		}

	}

	/**
	 * Build a url from vars of _GET
	 */
	public function add_to_url_from_get( $url, $skip = array() ) {

		if ( isset( $_GET ) && is_array( $_GET ) ) {
			foreach ( $_GET as $key => $val ) {
				if ( in_array( $key, $skip ) ) {
					continue;
				}
				$url = add_query_arg( $key . '=' . $val, '', $url );
			}
		}
		return $url;
	}

	/**
	 * Return CSS
	 */
	public function output_css( $styles = array() ) {

		// If empty return false
		if ( empty( $styles ) ) {
			return false;
		}

		$out = '';
		foreach ( $styles as $key => $value ) {

			if( ! $value ) {
				continue;
			}

			if( is_array( $value ) ) {

				switch( $key ) {

					case 'padding':
					case 'margin':
						$new_value = '';
						foreach( $value as $k => $v ) {

							if( '' != $v ) {
								$out .= sprintf( '%s: %s;', esc_html( $k ), $this->sanitize_unit($v) );
							}
						}
						break;

					default:
						$value = join( ';', $value );
				}
			}
			else {
				$out .= sprintf( '%s: %s;', esc_html( $key ), $value );
			}
		}

		return rtrim( $out, ';' );
	}

	/**
	 * Return Sanitized Unit
	 */
	public function sanitize_unit( $value ) {

		if( $this->str_contains( 'em', $value ) || $this->str_contains( 'rem', $value ) || $this->str_contains( '%', $value ) || $this->str_contains( 'px', $value ) ) {
			return $value;
		}

		return $value.'px';
	}

	/**
	 * Check substring
	 */
    public function str_contains( $needle, $haystack ) {
        return strpos( $haystack, $needle ) !== false;
    }

	/**
	 * Check starts with
	 */
	public function str_starts_with( $needle, $haystack ) {
		return substr( $haystack, 0, strlen( $needle ) ) === $needle;
	}

	/**
	 * Retrun html attributes
	 */
	public function html_attributes( $attributes = array(), $prefix = '' ) {

		// If empty return false
		if ( empty( $attributes ) ) {
			return false;
		}

		$options = false;
		if( isset( $attributes['data-options'] ) ) {
			$options = $attributes['data-options'];
			unset( $attributes['data-options'] );
		}

		$out = '';
		foreach ( $attributes as $key => $value ) {

			if( ! $value ) {
				continue;
			}

			$key = $prefix . $key;
			if( true === $value ) {
				$value = 'true';
			}

			if( false === $value ) {
				$value = 'false';
			}

			if( is_array( $value ) ) {
				$out .= sprintf( ' %s=\'%s\'', esc_html( $key ), json_encode( $value ) );
			}
			else {
				$out .= sprintf( ' %s="%s"', esc_html( $key ), esc_attr( $value ) );
			}
		}

		if( $options ) {
			$out .= sprintf( ' data-options=\'%s\'', $options );
		}

		return $out;
	}

	/**
	 * Print attributes
	 */
	public function attr( $context, $attributes = array() ) {
		echo $this->get_attr( $context, $attributes );
	}

	/**
	 * Return Attributes
	 */
	public function get_attr( $context, $attributes = array() ) {

		$defaults = array(
			'class' => sanitize_html_class( $context )
		);

		$attributes = wp_parse_args( $attributes, $defaults );
		$attributes = apply_filters( "dew_attr_{$context}", $attributes, $context );

		$output = $this->html_attributes( $attributes );
	    $output = apply_filters( "dew_attr_{$context}_output", $output, $attributes, $context );

	    return trim( $output );
	}

	/**
	 * Return theme option
	 */
	public function get_theme_option( $id ) {

		$options = $GLOBALS[dew_suite()->get_option_name()];

		if( empty( $options ) || ! isset( $options[$id] ) ) {
			return '';
		}

		return $options[$id];
	}

	/**
	 * Print option
	 */
    public function get_option_echo( $id, $esc = 'raw', $default = false, $context = 'all' ) {
        echo $this->get_option( $id, $esc, $default, $context );
    }

	/**
	 * Return option
	 */
	public function get_option( $id, $esc = 'raw', $default = '', $context = 'all' ) {

		$value = false;
		$keys = explode( '.', $id );
		$id = array_shift( $keys );

		// Get first value from context
		switch( $context ) {

			case 'options':
				$value = $this->get_theme_option( $id );
				break;

			case 'post':
				$value = $this->get_post_meta( $id );
				break;

			default:
				$value = $this->get_post_meta( $id );
				$value = '' != $value ? $value : $this->get_theme_option( $id );
				break;
		}

		// parsing dot notation
		if( ! empty( $keys ) ) {
			foreach( $keys as $inner_key ) {

				if( isset( $value[$inner_key] ) ) {
					$value = $value[$inner_key];
				}
				else {
					break;
				}
			}
		}

		// Set default value if no value
		$value = ! empty( $value ) ? $value : $default;

		// Escape the value
		switch( $esc ) {

			case 'attr':
				$value = esc_attr( $value );
				break;

			case 'url':
				$value = esc_url( $value );
				break;

			case 'html':
				$value = esc_html( $value );
				break;

			case 'post':
				$value = wp_kses_post( $value );
				break;
		}

		// Return default
		return $value;
	}

	/**
	 * Return option
	 */
	public function get_shadow_css( $atts = array(), $id = '' ) {

		if( empty( $atts ) ){
			return;
		}

		$css_arr = array();
		$res_css = $shadow_css = '';

		$res_styles = array( '-webkit-box-shadow', '-moz-box-shadow', 'box-shadow' );
		foreach( $atts as $att ) {
			$css_arr[] = $this->create_box_shadow_property( $att );
		}
		$shadow_css = join( ', ', $css_arr );

		$res_css .= '.' . $id . '{';
			foreach( $res_styles as $style ) {
				$res_css .= $style . ':' . $shadow_css . ';';
			}
		$res_css .= '}';

		return $res_css;

	}

	/**
	 * Return option
	 */
	public function create_box_shadow_property( $param = array() ) {

		$param = array_filter( $param );
		if( empty( $param ) ) {
			return;
		}

		$res = '';

		$res .= ! empty( $param['inset'] ) ? $param['inset'] . ' ' : '';
		$res .= isset( $param['x_offset'] ) ? $param['x_offset'] . ' ' : '0px ';
		$res .= isset( $param['y_offset'] ) ? $param['y_offset'] . ' ' : '0px ';
		$res .= isset( $param['blur_radius'] ) ? $param['blur_radius'] . ' ' : '0px ';
		$res .= isset( $param['spread_radius'] ) ? $param['spread_radius'] . ' ' : '0px ';
		$res .= ! empty( $param['shadow_color'] ) ? $param['shadow_color'] : '#000';

		return $res;

	}

	/**
	 * Return envato-market page
	 */
	public function envato_market_page_url() {

		if( ! class_exists( 'Envato_Market' ) ) {
			return '#';
		}

		return admin_url( 'admin.php?page=envato-market' );
	}


	/**
	 * Generate plugin action link
	 */
	public function generate_plugin_action( $plugin, $status ) {

		$btn_class = $btn_text = $nonce_url = '';
		$page = admin_url( 'admin.php?page=' . $_GET['page'] );

		switch( $status ) {
			case 'not-installed':
				$btn_class = 'white';
				$btn_text = esc_html_x( 'Install', 'Dew plugin installation page.', 'voltroid-theme' );

				$nonce_url = wp_nonce_url(
					add_query_arg(
						array(
							'plugin' => urlencode( $plugin['slug'] ),
							'tgmpa-install' => 'install-plugin',
							'return_url' => $_GET['page']
						),
						TGM_Plugin_Activation::$instance->get_tgmpa_url()
					),
					'tgmpa-install',
					'tgmpa-nonce'
				);
				break;

			case 'installed':
				$btn_class = 'success';
				$btn_text = esc_html_x( 'Activate', 'Dew plugin installation page.', 'voltroid-theme' );

				$nonce_url = wp_nonce_url(
					add_query_arg(
						array(
							'plugin' => urlencode( $plugin['slug'] ),
							'dew-activate' => 'activate-plugin'
						),
						$page
					),
					'dew-activate',
					'dew-activate-nonce'
				);
				break;

			case 'active':
				$btn_class = 'danger';
				$btn_text = esc_html_x( 'Deactivate', 'Dew plugin installation page.', 'voltroid-theme' );

				$nonce_url = wp_nonce_url(
					add_query_arg(
						array(
							'plugin' => urlencode( $plugin['slug'] ),
							'dew-deactivate' => 'deactivate-plugin'
						),
						$page
					),
					'dew-deactivate',
					'dew-deactivate-nonce'
				);
				break;
		}

		printf(
			'<a class="dew-button" href="%4$s" title="%2$s %1$s"><span>%2$s</span> <i class="fa fa-angle-right"></i></a>',
			$plugin['name'], $btn_text, $btn_class, esc_url( $nonce_url )
		);
	}

	/**
	 * Return Dashboard Page URL
	 */
	public function dashboard_page_url() {

		if( isset( $_GET['page'] ) && 'dew-theme' === $_GET['page'] ) {
			return '';
		}
		return admin_url( 'admin.php?page=dew-theme' );
	}


}
/**
*return instance of Dew_Assist
*/
function dew_assist() {
	return Dew_Assist::instance();
}
