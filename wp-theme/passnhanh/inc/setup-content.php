<?php
/**
 * Khi kích hoạt theme lần đầu: tạo danh mục, trang Điều khoản / Quyền riêng tư, trang chủ và trang Blog.
 * Không ghi đè thứ gì đã có.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'after_switch_theme', 'pn_setup_content' );

function pn_setup_content() {
	// Đăng ký post type/taxonomy ngay để tạo được danh mục và flush đường dẫn.
	pn_register_types();

	if ( pn_is_blog_install() ) {
		pn_setup_blog();
		return;
	}

	$cats = array(
		array( 'Vé xem phim', 've-xem-phim', '🎬', 'red' ),
		array( 'Phòng khách sạn', 'phong-khach-san', '🏨', 'blue' ),
		array( 'Tour du lịch', 'tour-du-lich', '🏞️', 'green' ),
		array( 'Phòng trọ', 'phong-tro', '🏠', 'orange' ),
		array( 'Đồ cũ', 'do-cu', '♻️', 'orange' ),
		array( 'Vé sự kiện', 've-su-kien', '🎟️', 'purple' ),
		array( 'Xe cộ', 'xe-co', '🛵', 'green' ),
		array( 'Đồ điện tử', 'do-dien-tu', '💻', 'blue' ),
		array( 'Nội thất', 'noi-that', '🛋️', 'red' ),
		array( 'Mỹ phẩm', 'my-pham', '💄', 'pink' ),
		array( 'Thẻ & dịch vụ', 'the-dich-vu', '💳', 'teal' ),
		array( 'Khác', 'khac', '⋯', 'blue' ),
	);
	foreach ( $cats as $i => $c ) {
		if ( term_exists( $c[1], 'danh_muc' ) ) {
			continue;
		}
		$t = wp_insert_term( $c[0], 'danh_muc', array( 'slug' => $c[1] ) );
		if ( ! is_wp_error( $t ) ) {
			update_term_meta( $t['term_id'], 'pn_icon', $c[2] );
			update_term_meta( $t['term_id'], 'pn_color', $c[3] );
			update_term_meta( $t['term_id'], 'pn_order', $i + 1 );
		}
	}

	$pages = array(
		'dieu-khoan'     => array( 'Điều khoản sử dụng', PN_DIR . '/inc/content/dieu-khoan.html' ),
		'quyen-rieng-tu' => array( 'Chính sách quyền riêng tư', PN_DIR . '/inc/content/quyen-rieng-tu.html' ),
	);
	foreach ( $pages as $slug => $p ) {
		if ( get_page_by_path( $slug ) || ! file_exists( $p[1] ) ) {
			continue;
		}
		wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_title'   => $p[0],
				'post_name'    => $slug,
				'post_content' => file_get_contents( $p[1] ), // phpcs:ignore WordPress.WP.AlternativeFunctions
			)
		);
	}

	// Trang chủ tĩnh + trang Blog, chỉ khi website đang để mặc định "Bài viết mới nhất".
	if ( 'posts' === get_option( 'show_on_front' ) ) {
		$home = get_page_by_path( 'trang-chu' );
		$home = $home ? $home->ID : wp_insert_post(
			array(
				'post_type'   => 'page',
				'post_status' => 'publish',
				'post_title'  => 'Trang chủ',
				'post_name'   => 'trang-chu',
			)
		);
		$blog = get_page_by_path( 'blog' );
		$blog = $blog ? $blog->ID : wp_insert_post(
			array(
				'post_type'   => 'page',
				'post_status' => 'publish',
				'post_title'  => 'Blog',
				'post_name'   => 'blog',
			)
		);
		if ( $home && $blog && ! is_wp_error( $home ) && ! is_wp_error( $blog ) ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $home );
			update_option( 'page_for_posts', $blog );
		}
	}

	flush_rewrite_rules();
}

/**
 * Chế độ Blog (cài ở passnhanh.vn/blog): tạo chuyên mục theo cụm nội dung SEO,
 * đặt đường dẫn đẹp nếu đang để mặc định. Không ghi đè thứ đã có.
 */
function pn_setup_blog() {
	$cats = array(
		array( 'Cách pass nhanh', 'cach-pass-nhanh', 'Mẹo đăng tin, định giá và tìm người nhận pass nhanh cho vé, phòng, tour, đồ cũ.' ),
		array( 'Kinh nghiệm mua pass', 'kinh-nghiem-mua-pass', 'Kinh nghiệm săn deal pass giá tốt và những điều cần hỏi trước khi nhận chuyển nhượng.' ),
		array( 'Chuyển nhượng đặt phòng', 'chuyen-nhuong-dat-phong', 'Cách đổi tên khách, điều kiện hủy/đổi và quy định của khách sạn, homestay, resort.' ),
		array( 'Kiểm tra voucher', 'kiem-tra-voucher', 'Cách kiểm tra voucher, vé, thẻ dịch vụ còn hạn và còn được phép chuyển nhượng.' ),
		array( 'Giao dịch an toàn', 'giao-dich-an-toan', 'Dấu hiệu lừa đảo, cách xác minh người bán và các bước giao dịch an toàn.' ),
		array( 'Hướng dẫn đăng tin', 'huong-dan-dang-tin', 'Hướng dẫn từng bước đăng Pass và đăng nhu cầu trên PassNhanh.' ),
		array( 'Hướng dẫn cộng tác viên', 'huong-dan-cong-tac-vien', 'Cách lấy link giới thiệu, chia sẻ tin và theo dõi kết quả cộng tác viên.' ),
	);
	foreach ( $cats as $c ) {
		if ( ! term_exists( $c[1], 'category' ) ) {
			wp_insert_term( $c[0], 'category', array( 'slug' => $c[1], 'description' => $c[2] ) );
		}
	}

	// Chuyên mục mặc định "Uncategorized"/"Chưa phân loại" đổi tên cho dễ hiểu.
	$default = get_term( (int) get_option( 'default_category' ), 'category' );
	if ( $default && ! is_wp_error( $default ) && in_array( $default->slug, array( 'uncategorized', 'chua-phan-loai' ), true ) ) {
		wp_update_term( $default->term_id, 'category', array( 'name' => 'Tin tức', 'slug' => 'tin-tuc' ) );
	}

	// Đường dẫn bài viết dạng /blog/ten-bai/. Chỉ đổi khi blog còn mới (chưa quá 1 bài, tức chỉ có bài mẫu),
	// để không làm hỏng đường dẫn của bài đã đăng.
	$published = (int) wp_count_posts( 'post' )->publish;
	if ( '/%postname%/' !== get_option( 'permalink_structure' ) && $published <= 1 ) {
		update_option( 'permalink_structure', '/%postname%/' );
	}
	if ( '' === (string) get_option( 'category_base' ) ) {
		update_option( 'category_base', 'chuyen-muc' );
	}
	if ( ! get_option( 'blogdescription' ) || 'Just another WordPress site' === get_option( 'blogdescription' ) ) {
		update_option( 'blogdescription', 'Kinh nghiệm pass đồ, săn deal và giao dịch an toàn' );
	}

	flush_rewrite_rules();
}
