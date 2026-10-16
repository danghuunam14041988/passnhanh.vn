<?php
/**
 * Thẻ một bài viết trong danh sách.
 */

$pn_cats = get_the_category();
?>
<article <?php post_class( 'post-card' ); ?>>
	<a href="<?php the_permalink(); ?>" class="post-thumb" tabindex="-1" aria-hidden="true">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php the_post_thumbnail( 'pn-blog', array( 'loading' => 'lazy', 'alt' => '' ) ); ?>
		<?php else : ?>
			<img src="<?php echo esc_url( pn_img( 'blog-halong.webp' ) ); ?>" alt="" loading="lazy">
		<?php endif; ?>
	</a>
	<div class="post-body">
		<p class="post-meta">
			<?php if ( $pn_cats ) : ?><span class="post-cat"><?php echo esc_html( $pn_cats[0]->name ); ?></span> · <?php endif; ?>
			<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'd/m/Y' ) ); ?></time>
		</p>
		<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p>
	</div>
</article>
