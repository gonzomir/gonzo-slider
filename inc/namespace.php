<?php

namespace Gonzo\Slider;

use function Gonzo\Slider\ImageSizes\image_sizes;

/**
 * Declare hooks.
 */
function bootstrap() {
	add_action(
		'init',
		function() {
			add_shortcode( 'gonzo-slider', __NAMESPACE__ . '\\slider_shortcode' );
		}
	);
}

/**
 * Display slider.
 *
 * @return void
 */
function display_slider() {
	global $post;

	$args = array(
		'post_type'         => 'slide',
		'posts_per_page'    => 5,
		'order'             => 'ASC',
		'orderby'           => 'menu_order',
	);
	$slides = get_posts( $args );

	if ( count( $slides ) > 0 ) {
		// Temporarily register our image sizes so that we can use them in templates.
		$sizes = image_sizes();
		foreach ( $sizes as $size => $atts ) {
			add_image_size( $size, $atts['width'], $atts['height'], $atts['crop'] );
		}
		?>
		<div class="gonzo-slider">
			<?php
			foreach ( $slides as $post ) {
				setup_postdata( $post );
				?>
				<article id="slide-<?php echo esc_attr( get_the_ID() ); ?>" class="slide">
					<?php get_plugin_template( 'slide', get_post_format() ); ?>
				</article>
				<?php
			}
			wp_reset_postdata();
			?>
		</div>
		<?php
		foreach ( $sizes as $size => $atts ) {
			remove_image_size( $size );
		}
	}
}

/**
 * Load plugin template, allowing overwrites from theme.
 *
 * @param string $slug Template slug.
 * @param string $name Template name.
 */
function get_plugin_template( $slug, $name ) {
	$located = '';

	$template_names = array();
	$template_names[] = $slug . '.php';
	if ( ! empty( $name ) ) {
		$template_names[] = $slug . '-' . $name . '.php';
	}

	$this_plugin_dir = plugin_dir_path( dirname( __FILE__ ) ) . 'templates/';

	foreach ( (array) $template_names as $template_name ) {
		if ( ! $template_name ) {
			continue;
		}
		if ( file_exists( get_stylesheet_directory() . '/gonzo-slider/' . $template_name ) ) {
			$located = get_stylesheet_directory() . '/gonzo-slider/' . $template_name;
		} elseif ( file_exists( get_template_directory() . '/gonzo-slider/' . $template_name ) ) {
			$located = get_template_directory() . '/gonzo-slider/' . $template_name;
		} elseif ( file_exists( $this_plugin_dir . $template_name ) ) {
			$located = $this_plugin_dir . $template_name;
		}
	}

	if ( $located !== '' ) {
		load_template( $located, false );
	}
}

/**
 * Display responsive header image using the <picture> tag.
 *
 * @param   intiger $attachment_id Attachment ID.
 * @return  void
 */
function header_picture( $attachment_id ) {
	?>
	<picture>
		<!--[if IE 9]><video style="display: none;"><![endif]-->
		<?php
		$srcset_value = wp_get_attachment_image_srcset( $attachment_id, 'gonzo-slider-landscape-small' );
		$srcset = $srcset_value ? ' srcset="' . esc_attr( $srcset_value ) . '"' : '';
		?>
		<source media="(orientation: landscape)" <?php echo$srcset; // WPCS: XSS ok. ?> />
		<?php
		$srcset_value = wp_get_attachment_image_srcset( $attachment_id, 'gonzo-slider-portrait-small' );
		$srcset = $srcset_value ? ' srcset="' . esc_attr( $srcset_value ) . '"' : '';
		?>
		<source media="(orientation: portrait)" <?php echo $srcset; // WPCS: XSS ok. ?> />
		<!--[if IE 9]></video><![endif]-->
		<img src="<?php echo esc_url( wp_get_attachment_image_src( $attachment_id, 'gonzo-slider-landscape-medium', false )[0] ); ?>" alt="" />
	</picture>
	<?php
}

/**
 * Display slider with shortcode.
 *
 * @param array $attributes Shortcode attributes.
 * @return string Rendered shortcode content.
 */
function slider_shortcode( $attributes ) {
	ob_start();
	display_slider();
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}
