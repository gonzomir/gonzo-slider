<blockquote>
  <?php the_content(); ?>
  <?php
  $author = get_the_title();
  if( $author != '' ): ?>
  <cite><?php echo esc_html( $author ); ?> </cite>
  <?php endif; ?>
</blockquote>
