<?php

namespace Gonzo\Slider\Assets;

function load_assets() {
	wp_enqueue_script(
		'gonzo-slider-script',
		plugins_url( 'assets/js/slider.js', dirname( __FILE__ ) ),
		array(),
		filemtime( plugin_dir_path( dirname( __FILE__ ) ) . 'assets/js/slider.js' ),
		false
	);
	wp_enqueue_style(
		'gonzo-slider-style',
		plugins_url( 'assets/css/slider.css', dirname( __FILE__ ) ),
		array(),
		filemtime( plugin_dir_path( dirname( __FILE__ ) ) . 'assets/css/slider.css' )
	);
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\load_assets' );
