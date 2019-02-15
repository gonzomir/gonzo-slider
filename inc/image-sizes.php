<?php
/**
 * Dynamically create the image sizes, needed for the slider, only when
 * the image is set as featured image for a slide.
 * The idea is borrowed from:
 * https://wordpress.stackexchange.com/a/57374
 * https://gist.github.com/mtinsley/be503d90724be73cdda4
 */

namespace Gonzo\Slider\ImageSizes;

/**
 * Declare hooks.
 */
function bootstrap() {
	add_action( 'added_post_meta', __NAMESPACE__ . '\\create_slider_images', 10, 4 );
	add_action( 'updated_post_meta', __NAMESPACE__ . '\\create_slider_images', 10, 4 );
	add_filter(
		'max_srcset_image_width',
		function( $width ) {
			return 2000;
		}
	);
}

/**
 * Generate required image sizes when a image is set as featured image for a slide.
 *
 * @param intiger $meta_id ID of the postmeta.
 * @param intiger $post_id ID of the post.
 * @param string  $meta_key Meta key.
 * @param mixed   $meta_value Meta value.
 * @return void
 */
function create_slider_images( $meta_id, $post_id, $meta_key, $meta_value ) {
	if ( $meta_key !== '_thumbnail_id' ) {
		return;
	}
	if ( empty( $meta_value ) ) {
		return;
	}
	if ( get_post_type( $post_id ) !== 'slide' ) {
		return;
	}

	$sizes = image_sizes();

	foreach ( $sizes as $size => $atts ) {
		lazy_image_size( $meta_value, $size, $atts['width'], $atts['height'], $atts['crop'] );
	}
}

/**
 * Returns the image sizes we need for the slider.
 *
 * @return array
 */
function image_sizes() {
	$sizes = array(
		'gonzo-slider-landscape-small' => array(
			'width' => 800,
			'height' => 400,
			'crop' => true,
		),
		'gonzo-slider-landscape-medium' => array(
			'width' => 1400,
			'height' => 700,
			'crop' => true,
		),
		'gonzo-slider-landscape-big' => array(
			'width' => 1980,
			'height' => 990,
			'crop' => true,
		),
		'gonzo-slider-portrait-small' => array(
			'width' => 400,
			'height' => 600,
			'crop' => true,
		),
		'gonzo-slider-portrait-medium' => array(
			'width' => 800,
			'height' => 1200,
			'crop' => true,
		),
		'gonzo-slider-portrait-big' => array(
			'width' => 1200,
			'height' => 1800,
			'crop' => true,
		),
	);
	return apply_filters( 'gonzo_slider_image_sizes', $sizes );
}

/**
 * Generate image sizes only when needed.
 *
 * Borrowed from https://gist.github.com/mtinsley/be503d90724be73cdda4
 *
 * @param integer $image_id Attachment ID of the image.
 * @param integer $size_id Image size identificator.
 * @param integer $width Image width.
 * @param integer $height Image height.
 * @param bool    $crop Should the image be cropped to exacti dimencions.
 * @return void
 */
function lazy_image_size( $image_id, $size_id, $width, $height, $crop ) {
	// Temporarily create an image size.
	add_image_size( $size_id, $width, $height, $crop );

	// Get the attachment data.
	$meta = wp_get_attachment_metadata( $image_id );

	// If the size does not exist.
	if ( ! isset( $meta['sizes'][ $size_id ] ) ) {
			require_once ABSPATH . 'wp-admin/includes/image.php';

			$file = get_attached_file( $image_id );
			$new_meta = wp_generate_attachment_metadata( $image_id, $file );

			// Merge the sizes so we don't lose already generated sizes.
			$new_meta['sizes'] = array_merge( $meta['sizes'], $new_meta['sizes'] );

			// Update the meta data.
			wp_update_attachment_metadata( $image_id, $new_meta );
	}

	// Remove the image size so new images won't be created in this size automatically.
	remove_image_size( $size_id );
}
