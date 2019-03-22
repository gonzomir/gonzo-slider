<div class="gonzo-slider">
	<?php
	foreach ( $slides as $post ) {
		setup_postdata( $post );
		$alignment = get_post_meta( get_the_ID(), '_slide_alignment', true );
		?>
		<article id="slide-<?php echo esc_attr( get_the_ID() ); ?>" class="slide <?php echo esc_attr( 'slide-align-' . $alignment ); ?>">
			<?php Gonzo\Slider\ get_plugin_template( 'slide', get_post_format() ); ?>
		</article>
		<?php
	}
	wp_reset_postdata();
	?>
</div>
