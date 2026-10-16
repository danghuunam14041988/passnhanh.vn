<?php
/**
 * Chân trang.
 */

$pn_email    = pn_text( 'pn_support_email', 'hotro@passnhanh.vn' );
$pn_terms    = pn_page_url( 'dieu-khoan' );
$pn_privacy  = pn_page_url( 'quyen-rieng-tu' );
$pn_extra    = pn_menu_links( 'footer' );
?>
<footer class="site-footer">
	<div class="container">
		<div class="footer-grid">
			<div>
				<a class="brand" href="<?php echo esc_url( pn_main_url( '/' ) ); ?>" aria-label="PassNhanh.vn — Trang chủ"><?php pn_brand_markup(); ?></a>
				<p><?php echo esc_html( get_bloginfo( 'description' ) ? get_bloginfo( 'description' ) : 'Chợ pass thông minh — kết nối người cần pass với người đang cần.' ); ?></p>
			</div>
			<div>
				<h4>Sản phẩm</h4>
				<ul>
					<li><a href="<?php echo esc_url( pn_listings_url() ); ?>">Tìm Pass</a></li>
					<li><a href="<?php echo esc_url( pn_app_url() ); ?>">Đăng Pass</a></li>
					<li><a href="<?php echo esc_url( pn_main_url( '/#ctv' ) ); ?>">Cộng tác viên</a></li>
					<li><a href="<?php echo esc_url( pn_blog_url() ); ?>">Blog</a></li>
				</ul>
			</div>
			<div>
				<h4>Hỗ trợ</h4>
				<ul>
					<li><a href="<?php echo esc_url( pn_main_url( '/#ho-tro' ) ); ?>">Hỏi đáp</a></li>
					<li><a href="mailto:<?php echo esc_attr( $pn_email ); ?>"><?php echo esc_html( $pn_email ); ?></a></li>
					<?php foreach ( $pn_extra as $link ) : ?>
						<li><a href="<?php echo esc_url( $link['url'] ); ?>"><?php echo esc_html( $link['title'] ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</div>
			<div>
				<h4>Pháp lý</h4>
				<ul>
					<?php if ( $pn_terms ) : ?><li><a href="<?php echo esc_url( $pn_terms ); ?>">Điều khoản sử dụng</a></li><?php endif; ?>
					<?php if ( $pn_privacy ) : ?><li><a href="<?php echo esc_url( $pn_privacy ); ?>">Chính sách quyền riêng tư</a></li><?php endif; ?>
				</ul>
			</div>
		</div>
		<div class="footer-bottom">
			<span>© <?php echo esc_html( wp_date( 'Y' ) ); ?> PassNhanh.vn</span>
			<span>Phiên bản thử nghiệm</span>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
