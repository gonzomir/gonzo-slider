<div class="gonzo-slider">
	<?php
	foreach ( $slides as $post ) {
		setup_postdata( $post );
		?>
		<article id="slide-<?php echo esc_attr( get_the_ID() ); ?>" class="slide">
			<?php Gonzo\Slider\ get_plugin_template( 'slide', get_post_format() ); ?>
		</article>
		<?php
	}
	wp_reset_postdata();
	?>
</div>
