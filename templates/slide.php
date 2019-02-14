<?php Gonzo\Slider\header_picture( get_post_thumbnail_id() ); ?>
<h2>
	<?php the_title(); ?>
</h2>
<?php the_excerpt(); ?>
<div>
	<a href="<?php echo esc_url( get_post_meta( get_the_ID(), '_slide_cta_url', true ) ); ?>" class="slide-button">
		<span><?php echo esc_html( get_post_meta( get_the_ID(), '_slide_cta_text', true ) ); ?></span>
	</a>
</div>
