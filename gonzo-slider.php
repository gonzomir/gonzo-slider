<?php
/**
 * Plugin Name:       Gonzo Slider
 * Description:       A simple slider.
 * Version:           0.1
 * Author:            Milen Petrinski - Gonzo
 * Author URI:        https://greatgonzo.net
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       gonso-slider
 * Domain Path:       /languages
 */

namespace Gonzo\Slider;

require 'inc/assets.php';
require 'inc/slides.php';

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

	$this_plugin_dir = plugin_dir_path( __FILE__ ) . 'templates/' ;

	foreach ( (array) $template_names as $template_name ) {
		if ( !$template_name ) {
			continue;
		}
		if ( file_exists(get_stylesheet_directory() . '/gonzo-slider/' . $template_name ) ) {
			$located = get_stylesheet_directory() . '/gonzo-slider/' . $template_name;
		} elseif ( file_exists(get_template_directory() . '/gonzo-slider/' . $template_name ) ) {
			$located = get_template_directory() . '/gonzo-slider/' . $template_name;
		} elseif ( file_exists( $this_plugin_dir . $template_name ) ) {
			$located =  $this_plugin_dir . $template_name;
		}
	}

	if ( $located !== '' ) {
		load_template( $located, false );
	}
}

/**
 * Display responsive header image.
 *
 * @param   intiger
 * @return  void
 */
function header_picture( $attachment_id ){
  ?>
  <picture>
    <!--[if IE 9]><video style="display: none;"><![endif]-->
    <?php
    $srcset_value = wp_get_attachment_image_srcset( $attachment_id, 'libe-wide-small' );
    $srcset = $srcset_value ? ' srcset="' . esc_attr( $srcset_value ) . '"' : '';
    ?>
    <source media="(orientation: landscape)" <?php echo $srcset; ?> />
    <?php
    $srcset_value = wp_get_attachment_image_srcset( $attachment_id, 'libe-square-small' );
    $srcset = $srcset_value ? ' srcset="' . esc_attr( $srcset_value ) . '"' : '';
    ?>
    <source media="(orientation: portrait)" <?php echo $srcset; ?> />
    <!--[if IE 9]></video><![endif]-->
    <img src="<?php echo esc_url(wp_get_attachment_image_src($attachment_id, 'libe-wide-medium', false)[0]); ?>" alt="" />
  </picture>
  <?php
}

/**
 * Display slider with shortcode.
 */
function slider_shortcode( $attributes ) {
	ob_start();
	display_slider();
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}
add_action( 'init', function(){ add_shortcode( 'gonzo-slider' , __NAMESPACE__ . '\\slider_shortcode' ); } );
