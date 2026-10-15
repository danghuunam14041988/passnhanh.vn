<?php
/**
 * Danh sách Tin Pass (cũng dùng cho trang danh mục và kết quả tìm kiếm Tin Pass).
 */

get_header();

$city    = isset( $_GET['khu_vuc'] ) ? sanitize_text_field( wp_unslash( $_GET['khu_vuc'] ) ) : '';
$keyword = get_search_query();
$term    = is_tax( 'danh_muc' ) ? get_queried_object() : null;
$archive = get_post_type_archive_link( 'tin_pass' );

if ( $term ) {
	$heading = $term->name;
} elseif ( '' !== $keyword ) {
	$heading = 'Kết quả cho “' . $keyword . '”';
} else {
	$heading = 'Tất cả Tin Pass';
}
?>
<main class="container page" id="main">
	<section class="card archive-head">
		<div>
			<h1><?php echo esc_html( $heading ); ?></h1>
			<p class="sub">
				<?php
				if ( $city ) {
					echo 'Khu vực: <b>' . esc_html( $city ) . '</b> · <a href="' . esc_url( remove_query_arg( 'khu_vuc' ) ) . '">bỏ lọc</a>';
				} elseif ( $term && $term->description ) {
					echo esc_html( $term->description );
				} else {
					echo 'Chỉ hiện tin còn hạn. Tin tự ẩn khi hết hạn.';
				}
				?>
			</p>
		</div>
		<form class="archive-search" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get">
			<input type="hidden" name="post_type" value="tin_pass">
			<input type="search" name="s" value="<?php echo esc_attr( $keyword ); ?>" placeholder="Tìm vé, phòng, tour…" aria-label="Từ khóa">
			<input type="text" name="khu_vuc" value="<?php echo esc_attr( $city ); ?>" placeholder="Khu vực" aria-label="Khu vực">
			<button class="btn btn-primary" type="submit">Tìm</button>
		</form>
	</section>

	<?php $cats = pn_get_categories(); ?>
	<?php if ( $cats ) : ?>
	<nav class="cat-chips" aria-label="Danh mục">
		<a class="chip<?php echo $term ? '' : ' active'; ?>" href="<?php echo esc_url( $archive ); ?>">Tất cả</a>
		<?php foreach ( $cats as $cat ) : ?>
			<a class="chip<?php echo ( $term && get_term_link( $term ) === $cat['url'] ) ? ' active' : ''; ?>" href="<?php echo esc_url( $cat['url'] ); ?>"><?php echo esc_html( $cat['icon'] . ' ' . $cat['name'] ); ?></a>
		<?php endforeach; ?>
	</nav>
	<?php endif; ?>

	<section class="card">
		<?php if ( have_posts() ) : ?>
			<div class="listing-grid">
				<?php
				while ( have_posts() ) {
					the_post();
					get_template_part( 'template-parts/listing-card', null, array( 'item' => pn_listing_data( get_post() ) ) );
				}
				?>
			</div>
			<div class="pagination"><?php echo paginate_links( array( 'prev_text' => '←', 'next_text' => '→' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
		<?php else : ?>
			<p class="empty">Chưa có tin phù hợp. <a href="<?php echo esc_url( pn_app_url() ); ?>">Đăng nhu cầu</a> để được báo khi có tin.</p>
		<?php endif; ?>
	</section>
</main>
<?php
get_footer();
