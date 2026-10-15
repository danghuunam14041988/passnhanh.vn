<?php
/**
 * Danh sách bài viết (Blog), lưu trữ và kết quả tìm kiếm.
 */

get_header();

if ( is_home() ) {
	$heading = 'Bài viết & Cẩm nang';
} elseif ( is_search() ) {
	$heading = 'Kết quả cho “' . get_search_query() . '”';
} else {
	$heading = wp_strip_all_tags( get_the_archive_title() );
}
?>
<main class="container page" id="main">
	<section class="card archive-head">
		<div>
			<h1><?php echo esc_html( $heading ); ?></h1>
			<?php if ( is_home() ) : ?><p class="sub">Kinh nghiệm pass đồ, săn deal và đi chơi tiết kiệm.</p><?php endif; ?>
		</div>
		<?php get_search_form(); ?>
	</section>

	<section class="card">
		<?php if ( have_posts() ) : ?>
			<div class="post-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<article <?php post_class( 'post-card' ); ?>>
						<a href="<?php the_permalink(); ?>" class="post-thumb">
							<?php if ( has_post_thumbnail() ) : ?>
								<?php the_post_thumbnail( 'pn-blog', array( 'loading' => 'lazy' ) ); ?>
							<?php else : ?>
								<img src="<?php echo esc_url( pn_img( 'blog-halong.webp' ) ); ?>" alt="" loading="lazy">
							<?php endif; ?>
						</a>
						<div class="post-body">
							<p class="post-meta"><?php echo esc_html( get_the_date() ); ?></p>
							<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p>
						</div>
					</article>
				<?php endwhile; ?>
			</div>
			<div class="pagination"><?php echo paginate_links( array( 'prev_text' => '←', 'next_text' => '→' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
		<?php else : ?>
			<p class="empty">Chưa có bài viết nào.</p>
		<?php endif; ?>
	</section>
</main>
<?php
get_footer();
