<?php
/**
 * Danh sách bài viết (Blog), chuyên mục và kết quả tìm kiếm.
 */

get_header();

if ( is_home() ) {
	$heading = 'Bài viết & Cẩm nang';
	$intro   = 'Kinh nghiệm pass vé, phòng, tour, đồ cũ; mẹo săn deal và hướng dẫn giao dịch an toàn.';
} elseif ( is_search() ) {
	$heading = 'Kết quả cho “' . get_search_query() . '”';
	$intro   = '';
} elseif ( is_category() ) {
	$heading = single_cat_title( '', false );
	$intro   = wp_strip_all_tags( category_description() );
} else {
	$heading = wp_strip_all_tags( get_the_archive_title() );
	$intro   = '';
}

$pn_cats = get_categories(
	array(
		'hide_empty' => true,
		'orderby'    => 'count',
		'order'      => 'DESC',
	)
);
$current = is_category() ? get_queried_object_id() : 0;
?>
<main class="container page" id="main">
	<section class="card archive-head">
		<div>
			<nav class="breadcrumb" aria-label="Đường dẫn">
				<a href="<?php echo esc_url( pn_main_url( '/' ) ); ?>">Trang chủ</a> ›
				<a href="<?php echo esc_url( pn_blog_url() ); ?>">Blog</a>
			</nav>
			<h1><?php echo esc_html( $heading ); ?></h1>
			<?php if ( $intro ) : ?><p class="sub"><?php echo esc_html( $intro ); ?></p><?php endif; ?>
		</div>
		<div id="tim-bai"><?php get_search_form(); ?></div>
	</section>

	<?php if ( $pn_cats ) : ?>
	<nav class="cat-chips" aria-label="Chuyên mục">
		<a class="chip<?php echo $current ? '' : ' active'; ?>" href="<?php echo esc_url( pn_blog_url() ); ?>">Tất cả</a>
		<?php foreach ( $pn_cats as $cat ) : ?>
			<a class="chip<?php echo $current === $cat->term_id ? ' active' : ''; ?>" href="<?php echo esc_url( get_category_link( $cat ) ); ?>"><?php echo esc_html( $cat->name ); ?></a>
		<?php endforeach; ?>
	</nav>
	<?php endif; ?>

	<section class="card">
		<?php if ( have_posts() ) : ?>
			<div class="post-grid">
				<?php
				while ( have_posts() ) {
					the_post();
					get_template_part( 'template-parts/post-card' );
				}
				?>
			</div>
			<div class="pagination"><?php echo paginate_links( array( 'prev_text' => '←', 'next_text' => '→' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
		<?php else : ?>
			<p class="empty"><?php echo is_search() ? 'Không tìm thấy bài viết phù hợp.' : 'Chưa có bài viết nào.'; ?></p>
		<?php endif; ?>
	</section>
</main>
<?php
get_footer();
