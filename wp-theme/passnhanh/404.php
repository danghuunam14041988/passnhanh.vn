<?php
/**
 * Không tìm thấy trang.
 */

get_header();
?>
<main class="container page" id="main">
	<section class="card doc-card not-found">
		<div class="nf-icon">🧭</div>
		<h1>Không tìm thấy trang</h1>
		<p class="sub">Trang này có thể đã được pass cho người khác 😉</p>
		<p><a class="btn btn-primary" href="<?php echo esc_url( home_url( '/' ) ); ?>">Về trang chủ</a> <a class="btn btn-outline" href="<?php echo esc_url( get_post_type_archive_link( 'tin_pass' ) ); ?>">Xem Tin Pass</a></p>
	</section>
</main>
<?php
get_footer();
