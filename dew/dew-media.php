<?php
/**
 * Dew Theme Suite
 */

/**
 * return small image
 */
function dew_get_the_small_image( $src ) {

	if( empty( $src )  ){
		return;
	}

	@list( $width, $height ) = getimagesize( $src );

	if( ! $width ) {
		return $src;
	}
	elseif( $width > $height ) {
		$image_ratio = $height / $width;
		$width = 30;
		$height = 30 * $image_ratio;
	}
	elseif( $width < $height ) {
		$image_ratio = $width / $height;
		$height = 30;
		$width = 30 * $image_ratio;
	}
	elseif( $width == $height ) {
		$width  = 30;
		$height = 30;
	}

	$small_image = aq_resize( $src, $width, $height, false );

	return $small_image;

}

/**
 * return retina image
 */
function dew_get_retina_image( $image, $size = null ) {

	if( empty( $image ) ) {
		return;
	}

	if( $size ) {
		//Get image sizes
		$aq_size = dew_image_sizes( $size );
		$width  = $aq_size['width'];
		$height = $aq_size['height'];
	}
	else {
		@list( $width, $height ) = getimagesize( $image );

	}

	//Double the size for the retina display
	$retina_width   = $width * 2;
	$retina_height  = $height * 2;

	$retina_src = aq_resize( $image, (int) $retina_width, (int) $retina_height, true, true, true );

	return $retina_src;

}

/**
 * return thumbnail image
 */
function dew_the_post_thumbnail( $size = 'dew-thumbnail', $attr = '', $retina = true ) {

	$html = '';
	$attachment_id = get_post_thumbnail_id();
	$image         = wp_get_attachment_image_src( $attachment_id, 'full', false );

	if ( $image ) {

		@list( $src, $width, $height ) = $image;

		//Get image sizes
		$aq_size = dew_image_sizes( $size );

        if( is_array( $aq_size ) && ! empty( $aq_size['height'] ) ) {

			$resize_width  = $aq_size['width'];
			$resize_height = $aq_size['height'];
			$resize_crop   = $aq_size['crop'];

			if( $resize_width >= $width ) {
				$resize_width = $width;
			}
			if( $resize_height >= $height && ! empty( $resize_height ) ) {
				$resize_height = $height;
			}

			//Double the size for the retina display
			$retina_width   = $resize_width * 2;
			$retina_height  = $resize_height * 2;
			if( $retina_width >= $width ) {
				$retina_width = $width;
			}
			if( $retina_height >= $height ) {
				$retina_height = $height;
			}

			//Get resized images
			$retina_src  = aq_resize( $src, $retina_width, $retina_height, true );
			$resized_src = aq_resize( $src, $resize_width, $resize_height, $resize_crop );
			if( ! empty( $resized_src ) ) {
				$src = 	$resized_src;
			}

			$hwstring = image_hwstring( $resize_width, $resize_height );

			if( ! $retina ) {
				$retina_src = $src;
			}

        } else {
	        $retina_src = $src;
			$hwstring = image_hwstring( $width, $height );
        }

        $size_class = $size;
        if ( is_array( $size_class ) ) {
            $size_class = join( 'x', $size_class );
        }
        $attachment = get_post($attachment_id);
        $default_attr = array(
            'src'   => $src,
            'class' => "attachment-$size_class size-$size_class",
            'alt'   => get_the_title(),
            'data-rjs' => $retina_src,
        );

        $attr = wp_parse_args( $attr, $default_attr );

		$attr = apply_filters( 'wp_get_attachment_image_attributes', $attr, $attachment, $size );

		$attr = array_map( 'esc_attr', $attr );
        $html = rtrim("<img $hwstring");
        foreach ( $attr as $name => $value ) {
            $html .= " $name=" . '"' . $value . '"';
        }
        $html .= ' />';
    }

    echo $html;
}

/**
 * return resized image
 */
function dew_get_resized_image_src( $original_src, $size = 'dew-thumbnail' ) {

	if( empty( $original_src) ) {
		return;
	}

	@list( $src, $width, $height ) = $original_src;
	//Get image sizes
	$aq_size = dew_image_sizes( $size );

	if( ! empty( $aq_size ) ) {

		$resize_width  = $aq_size['width'];
		$resize_height = $aq_size['height'];
		$resize_crop   = $aq_size['crop'];

		if( $resize_width >= $width ) {
			$resize_width = $width;
		}
		if( $resize_height >= $height && ! empty( $resize_height ) ) {
			$resize_height = $height;
		}

		//Get resized images
		$resized_src = aq_resize( $src, $resize_width, $resize_height, $resize_crop );
	}
	else {
		return $src;
	}
	return $resized_src;

}

/**
 * return dew image sizes
 */
function dew_image_sizes( $size ) {

	$sizes = dew_suite()->get_config()['media_sizes'];

	$image_sizes = ! empty( $sizes[ $size ] ) ? $sizes[ $size ] : '';

	return $image_sizes;
}
