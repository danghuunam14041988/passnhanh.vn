<?php
/**
 * Chi tiết một Tin Pass.
 */

get_header();

while ( have_posts() ) :
	the_post();
	$item    = pn_listing_data( get_post() );
	$off     = pn_off( $item );
	$expiry  = (int) get_post_meta( get_the_ID(), 'pn_het_han', true );
	$expired = $expiry && $expiry < time();
	$link    = get_post_meta( get_the_ID(), 'pn_lien_ket', true );
	$link    = $link ? $link : pn_app_url();
	$terms   = get_the_terms( get_the_ID(), 'danh_muc' );
	$img     = get_the_post_thumbnail_url( get_the_ID(), 'large' );
	?>
<main class="container page" id="main">
	<nav class="breadcrumb" aria-label="Đường dẫn">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Trang chủ</a> ›
		<a href="<?php echo esc_url( get_post_type_archive_link( 'tin_pass' ) ); ?>">Tin Pass</a>
		<?php if ( $terms && ! is_wp_error( $terms ) ) : ?>
			› <a href="<?php echo esc_url( get_term_link( $terms[0] ) ); ?>"><?php echo esc_html( $terms[0]->name ); ?></a>
		<?php endif; ?>
	</nav>

	<article class="card listing-detail">
		<div class="ld-media">
			<?php if ( $img ) : ?>
				<img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>">
			<?php else : ?>
				<span class="l-ph c-<?php echo esc_attr( $item['color'] ); ?>" aria-hidden="true"><?php echo esc_html( $item['icon'] ); ?></span>
			<?php endif; ?>
			<?php if ( $item['urgent'] ) : ?><span class="l-urgent">Cần Pass Gấp</span><?php endif; ?>
		</div>
		<div class="ld-info">
			<h1><?php the_title(); ?></h1>
			<?php if ( $item['city'] ) : ?><p class="l-city">📍 <?php echo esc_html( $item['city'] ); ?></p><?php endif; ?>
			<div class="l-price ld-price">
				<b><?php echo esc_html( $item['price'] ? pn_money( $item['price'] ) : 'Liên hệ' ); ?></b>
				<?php if ( $off ) : ?><s><?php echo esc_html( pn_money( $item['old'] ) ); ?></s><span class="l-off">-<?php echo esc_html( $off ); ?>%</span><?php endif; ?>
			</div>
			<?php if ( $expiry ) : ?>
				<p class="ld-expiry<?php echo $expired ? ' expired' : ''; ?>">⏱ <?php echo esc_html( $expired ? 'Tin đã hết hạn' : pn_time_left( $expiry ) . ' · đến ' . wp_date( 'H:i d/m/Y', $expiry ) ); ?></p>
			<?php endif; ?>
			<?php if ( ! $expired ) : ?>
				<a class="btn btn-primary ld-cta" href="<?php echo esc_url( $link ); ?>">Liên hệ người pass →</a>
			<?php endif; ?>
			<div class="ld-note"><b>Lưu ý an toàn:</b> kiểm tra quyền chuyển nhượng (vé ghi tên, đổi tên khách phòng, hợp đồng thuê…) và không chuyển tiền khi chưa xác minh người bán. PassNhanh chưa giữ tiền hay bảo đảm giao dịch.</div>
		</div>
	</article>

	<?php if ( get_the_content() ) : ?>
	<section class="card entry-content">
		<h2>Mô tả</h2>
		<?php the_content(); ?>
	</section>
	<?php endif; ?>
</main>
	<?php
endwhile;

get_footer();
