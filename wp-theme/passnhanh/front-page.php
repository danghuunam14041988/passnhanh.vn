<?php
/**
 * Trang chủ.
 */

get_header();

$listings  = pn_get_listings( 10 );
$has_demo  = $listings && ! empty( $listings[0]['demo'] );
$demands   = pn_get_demands( 5 );
$blog      = pn_get_blog_posts( 4 );
$cats      = pn_get_categories();
$archive   = get_post_type_archive_link( 'tin_pass' );
$hero_sub  = pn_text( 'pn_hero_sub', "Phòng khách sạn · Vé xem phim · Tour du lịch · Phòng trọ\nĐồ cũ · Vé sự kiện · Và nhiều hơn nữa…" );
$sub_lines = array_map( 'esc_html', explode( "\n", $hero_sub ) );
$email     = pn_text( 'pn_support_email', 'hotro@passnhanh.vn' );
?>
<main class="container page" id="main">

	<section class="top-grid">
		<div class="hero">
			<div class="hero-text">
				<h1><?php echo esc_html( pn_text( 'pn_hero_line1', 'Mọi thứ cần Pass' ) ); ?><br><?php echo esc_html( pn_text( 'pn_hero_line2', 'đều có trên Pass' ) ); ?><span><?php echo esc_html( pn_text( 'pn_hero_highlight', 'Nhanh' ) ); ?></span></h1>
				<p><?php echo implode( '<span class="sep"> · </span><br>', $sub_lines ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above ?></p>
			</div>

			<div class="search-box" id="tim-kiem">
				<div class="search-tabs" role="tablist">
					<button class="search-tab" type="button" role="tab" aria-selected="true" data-mode="find">
						<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="3"/><path d="M3 10h18"/></svg>Tìm Pass
					</button>
					<button class="search-tab" type="button" role="tab" aria-selected="false" data-mode="demand">
						<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="4" width="16" height="16" rx="3"/><path d="M9 12h6M12 9v6"/></svg>Đăng nhu cầu
					</button>
				</div>
				<form class="search-form" id="searchForm" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get">
					<input type="hidden" name="post_type" value="tin_pass">
					<label class="field field-q">
						<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg>
						<input id="searchQ" name="s" type="search" placeholder="Bạn đang tìm gì? (ví dụ: phòng Hạ Long, vé phim, tour Đà Nẵng…)" aria-label="Từ khóa">
					</label>
					<label class="field">
						<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 22s7-6.2 7-12a7 7 0 1 0-14 0c0 5.8 7 12 7 12z"/><circle cx="12" cy="10" r="2.5"/></svg>
						<input id="searchCity" name="khu_vuc" placeholder="Địa điểm" aria-label="Địa điểm">
					</label>
					<label class="field">
						<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/></svg>
						<input id="searchDate" type="text" placeholder="Ngày" aria-label="Ngày" onfocus="this.type='date'" onblur="if(!this.value)this.type='text'">
					</label>
					<button class="btn btn-primary search-submit" type="submit">
						<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg><span id="searchBtnText">Tìm ngay</span>
					</button>
				</form>
			</div>

			<ul class="hero-features">
				<li><span class="hf-ico">🏷️</span><span><b>Giá tốt hơn</b>thấp hơn giá gốc</span></li>
				<li><span class="hf-ico">🆓</span><span><b>Đăng Pass</b>miễn phí</span></li>
				<li><span class="hf-ico">🔔</span><span><b>Ghép nhu cầu</b>tự động</span></li>
				<li><span class="hf-ico">⏳</span><span><b>Tin có hạn</b>tự ẩn khi hết</span></li>
				<li><span class="hf-ico">🤝</span><span><b>Cộng tác viên</b>nhận hoa hồng</span></li>
				<li><span class="hf-ico">✅</span><span><b>Dễ dàng</b>đăng &amp; pass</span></li>
			</ul>
		</div>

		<aside class="card whats-on">
			<h2>Có gì trên PassNhanh?</h2>
			<ul class="icon-list">
				<li><span class="il-ico green">🛍️</span>Tìm hàng Pass giá tốt</li>
				<li><span class="il-ico blue">📝</span>Đăng Pass hoàn toàn miễn phí</li>
				<li><span class="il-ico red">🔔</span>Đăng nhu cầu – Ghép tin phù hợp</li>
				<li><span class="il-ico orange">👥</span>Cộng tác viên chia sẻ – Nhận hoa hồng</li>
				<li><span class="il-ico orange">💬</span>Nhắn tin trực tiếp với người pass</li>
				<li><span class="il-ico teal">⏳</span>Tin có hạn – Tự ẩn khi hết hạn</li>
			</ul>
			<a class="btn btn-teal block" href="#ho-tro">Xem hướng dẫn sử dụng →</a>
		</aside>

		<aside class="card map-card">
			<div class="card-head"><h2>Bản đồ Pass gần bạn</h2><a class="see-all" href="<?php echo esc_url( $archive ); ?>">Xem tất cả</a></div>
			<img class="map-img" src="<?php echo esc_url( pn_mod_image( 'pn_map_image', 'map.webp' ) ); ?>" alt="Bản đồ minh họa các tin Pass" width="273" height="183" loading="lazy">
			<h3>Tìm theo khu vực phổ biến</h3>
			<div class="area-chips">
				<?php foreach ( array( 'Hà Nội', 'Hạ Long', 'Đà Nẵng', 'Nha Trang', 'TP. Hồ Chí Minh', 'Đà Lạt', 'Phú Quốc', 'Hội An', 'Sapa', 'Vũng Tàu', 'Huế' ) as $city ) : ?>
					<a href="<?php echo esc_url( add_query_arg( 'khu_vuc', rawurlencode( str_replace( 'TP. ', '', $city ) ), $archive ) ); ?>"><?php echo esc_html( $city ); ?></a>
				<?php endforeach; ?>
				<a class="more" href="<?php echo esc_url( $archive ); ?>">Xem thêm →</a>
			</div>
		</aside>
	</section>

	<?php if ( $cats ) : ?>
	<section class="card categories" aria-label="Danh mục">
		<?php foreach ( $cats as $cat ) : ?>
			<a class="cat" href="<?php echo esc_url( $cat['url'] ); ?>"><span class="cat-ico c-<?php echo esc_attr( $cat['color'] ); ?>"><?php echo esc_html( $cat['icon'] ); ?></span><?php echo esc_html( $cat['name'] ); ?></a>
		<?php endforeach; ?>
	</section>
	<?php endif; ?>

	<section class="mid-grid">
		<div class="card featured" id="noi-bat">
			<div class="featured-head">
				<div>
					<h2>Tin Pass nổi bật</h2>
					<p class="sub">Những deal tốt nhất, được nhiều người quan tâm<?php if ( $has_demo ) : ?> <span class="demo-tag">Tin minh họa</span><?php endif; ?></p>
				</div>
				<div class="filters" role="group" aria-label="Lọc tin">
					<button class="chip active" type="button" data-filter="all">Tất cả</button>
					<button class="chip" type="button" data-filter="near">Gần bạn</button>
					<button class="chip" type="button" data-filter="deal">Giá tốt</button>
					<button class="chip" type="button" data-filter="soon">Sắp hết hạn</button>
					<button class="chip" type="button" data-filter="urgent">Cần pass gấp</button>
					<a class="see-all" href="<?php echo esc_url( $archive ); ?>">Xem tất cả</a>
				</div>
			</div>
			<div class="listing-grid" id="listingGrid">
				<?php
				foreach ( $listings as $item ) {
					get_template_part( 'template-parts/listing-card', null, array( 'item' => $item ) );
				}
				?>
				<p class="empty"<?php echo $listings ? ' hidden' : ''; ?>>Chưa có tin phù hợp. <a href="<?php echo esc_url( pn_app_url() ); ?>">Đăng nhu cầu</a> để được báo khi có tin.</p>
			</div>
		</div>

		<div class="side-col">
			<?php if ( $demands ) : ?>
			<div class="card demands">
				<div class="card-head"><h2>Nhu cầu đang tìm nhiều</h2><a class="see-all" href="<?php echo esc_url( pn_app_url() ); ?>">Xem tất cả →</a></div>
				<ul class="demand-list">
					<?php foreach ( $demands as $d ) : ?>
						<li><span class="d-ico c-<?php echo esc_attr( $d['color'] ); ?>"><?php echo esc_html( $d['icon'] ); ?></span><div><b><?php echo esc_html( $d['title'] ); ?></b><span><?php echo esc_html( $d['note'] ); ?></span></div><a class="notify" href="<?php echo esc_url( pn_app_url() ); ?>">🔔 Nhận thông báo</a></li>
					<?php endforeach; ?>
				</ul>
			</div>
			<?php endif; ?>

			<div class="ctv-banner" id="ctv">
				<div class="ctv-text">
					<h2><?php echo esc_html( pn_text( 'pn_ctv_title', 'Trở thành CTV PassNhanh' ) ); ?></h2>
					<p class="ctv-sub"><?php echo esc_html( pn_text( 'pn_ctv_sub', 'Chia sẻ tin hay – Nhận hoa hồng' ) ); ?></p>
					<ul>
						<li>Kho tin Pass để chia sẻ mỗi ngày</li>
						<li>Tạo link giới thiệu riêng cho từng tin</li>
						<li>Theo dõi lượt click và đơn hàng</li>
						<li>Chính sách hoa hồng công bố trước khi áp dụng</li>
					</ul>
					<a class="btn btn-primary block" href="<?php echo esc_url( pn_app_url() ); ?>">Đăng ký ngay →</a>
				</div>
				<img class="ctv-img" src="<?php echo esc_url( pn_mod_image( 'pn_ctv_image', 'ctv.webp' ) ); ?>" alt="" width="163" height="195" loading="lazy">
			</div>
		</div>
	</section>

	<section class="bottom-grid">
		<div class="card why">
			<h2>Vì sao chọn PassNhanh?</h2>
			<p class="sub">Nền tảng kết nối người cần pass và người đang tìm – nhanh chóng, tiện lợi</p>
			<div class="why-grid">
				<div class="why-item"><span class="why-ico">🐷</span><div><b>Tiết kiệm chi phí</b><span>Giá pass thường thấp hơn giá gốc</span></div></div>
				<div class="why-item"><span class="why-ico">🆓</span><div><b>Đăng tin miễn phí</b><span>Đăng Pass và đăng nhu cầu không mất phí</span></div></div>
				<div class="why-item"><span class="why-ico">⚡</span><div><b>Nhanh chóng, tiện lợi</b><span>Tìm và pass chỉ trong vài phút</span></div></div>
				<div class="why-item"><span class="why-ico">🔔</span><div><b>Ghép nhu cầu</b><span>Gợi ý tin khớp danh mục, khu vực, ngân sách</span></div></div>
			</div>
		</div>

		<div class="card blog" id="blog">
			<div class="card-head">
				<h2>Bài viết &amp; Cẩm nang</h2>
				<?php if ( $blog ) : ?>
					<a class="see-all" href="<?php echo esc_url( pn_blog_url() ); ?>">Xem tất cả →</a>
				<?php else : ?>
					<span class="soon">Sắp ra mắt</span>
				<?php endif; ?>
			</div>
			<div class="blog-grid">
				<?php if ( $blog ) : ?>
					<?php foreach ( $blog as $b ) : ?>
						<article><a href="<?php echo esc_url( $b['url'] ); ?>"><img src="<?php echo esc_url( $b['img'] ); ?>" alt="" loading="lazy"><h3><?php echo esc_html( $b['title'] ); ?></h3></a></article>
					<?php endforeach; ?>
				<?php else : ?>
					<article><img src="<?php echo esc_url( pn_img( 'blog-halong.webp' ) ); ?>" alt="" loading="lazy"><h3>Kinh nghiệm đặt phòng Hạ Long giá tốt</h3></article>
					<article><img src="<?php echo esc_url( pn_img( 'blog-movie.webp' ) ); ?>" alt="" loading="lazy"><h3>Cách pass vé xem phim nhanh chóng</h3></article>
					<article><img src="<?php echo esc_url( pn_img( 'blog-sapa.webp' ) ); ?>" alt="" loading="lazy"><h3>Check-in Sapa mùa đẹp nhất năm</h3></article>
					<article><img src="<?php echo esc_url( pn_img( 'blog-tour.webp' ) ); ?>" alt="" loading="lazy"><h3>Bí quyết săn tour du lịch giá tốt</h3></article>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<section class="card help" id="ho-tro">
		<div class="help-head">
			<h2>Hỏi đáp &amp; hỗ trợ</h2>
			<p class="sub">Cần thêm giúp đỡ? Gửi email về <a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></p>
		</div>
		<div class="faq">
			<details>
				<summary>Đăng tin trên PassNhanh có mất phí không?</summary>
				<p>Không. Đăng tin pass và đăng nhu cầu đều miễn phí.</p>
			</details>
			<details>
				<summary>Tôi có thể pass những gì?</summary>
				<p>Phòng khách sạn, vé xem phim, vé sự kiện, tour du lịch, phòng trọ, đồ cũ, xe cộ, thẻ dịch vụ… với điều kiện bạn sở hữu hoặc được chủ sở hữu cho phép, và món đó được phép chuyển nhượng theo điều khoản của bên cung cấp.</p>
			</details>
			<details>
				<summary>“Đăng nhu cầu” hoạt động thế nào?</summary>
				<p>Bạn chọn danh mục, khu vực và ngân sách tối đa. Khi có tin cùng danh mục, cùng khu vực và nằm trong ngân sách, tin đó sẽ xuất hiện trong mục gợi ý phù hợp của bạn.</p>
			</details>
			<details>
				<summary>PassNhanh có giữ tiền hay bảo đảm giao dịch không?</summary>
				<p>Chưa. Ở giai đoạn thử nghiệm, PassNhanh chỉ kết nối người mua và người bán. Không chuyển tiền khi chưa xác minh được người bán và món hàng. Thanh toán đảm bảo đang được phát triển.</p>
			</details>
			<details>
				<summary>Làm sao để trở thành cộng tác viên?</summary>
				<p>Tạo tài khoản, vào mục CTV / Affiliate để lấy link giới thiệu riêng cho từng tin. Chính sách hoa hồng chính thức sẽ được công bố trước khi áp dụng.</p>
			</details>
		</div>
	</section>
</main>
<?php
get_footer();
