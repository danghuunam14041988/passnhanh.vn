<?php
/**
 * Hàm dùng chung cho các template.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Địa chỉ web app chợ Pass (đổi trong Giao diện → Tùy biến → PassNhanh). */
function pn_app_url( $path = '/' ) {
	$base = get_theme_mod( 'pn_app_url', 'https://app.passnhanh.vn' );
	return untrailingslashit( $base ) . $path;
}

function pn_img( $file ) {
	return PN_URI . '/assets/img/' . $file;
}

/** Ảnh từ Tùy biến (ID đính kèm) hoặc ảnh mặc định của theme. */
function pn_mod_image( $mod, $default_file ) {
	$id = absint( get_theme_mod( $mod ) );
	if ( $id ) {
		$src = wp_get_attachment_image_url( $id, 'full' );
		if ( $src ) {
			return $src;
		}
	}
	return pn_img( $default_file );
}

function pn_inline_css() {
	return ':root{--pn-hero:url("' . esc_url_raw( pn_mod_image( 'pn_hero_image', 'hero.webp' ) ) . '");}';
}

function pn_money( $n ) {
	return number_format( (float) $n, 0, ',', '.' ) . 'đ';
}

function pn_show_demo() {
	return (bool) get_theme_mod( 'pn_show_demo', true );
}

/** "Còn 9 giờ", "Còn 3 ngày"… từ thời điểm hết hạn (timestamp). */
function pn_time_left( $ts ) {
	if ( ! $ts ) {
		return '';
	}
	$diff = (int) $ts - time();
	if ( $diff <= 0 ) {
		return 'Đã hết hạn';
	}
	$hours = (int) ceil( $diff / HOUR_IN_SECONDS );
	if ( $hours < 24 ) {
		return 'Còn ' . $hours . ' giờ';
	}
	return 'Còn ' . (int) floor( $hours / 24 ) . ' ngày';
}

/** Điều kiện meta: tin chưa có hạn hoặc chưa hết hạn. */
function pn_not_expired_meta_query() {
	return array(
		'relation' => 'OR',
		array(
			'key'     => 'pn_het_han',
			'compare' => 'NOT EXISTS',
		),
		array(
			'key'     => 'pn_het_han',
			'value'   => '',
			'compare' => '=',
		),
		array(
			'key'     => 'pn_het_han',
			'value'   => time(),
			'compare' => '>=',
			'type'    => 'NUMERIC',
		),
	);
}

/** Chuẩn hóa một Tin Pass thành mảng cho thẻ tin. */
function pn_listing_data( $post ) {
	$post   = get_post( $post );
	$old    = (int) get_post_meta( $post->ID, 'pn_gia_goc', true );
	$price  = (int) get_post_meta( $post->ID, 'pn_gia_pass', true );
	$expiry = (int) get_post_meta( $post->ID, 'pn_het_han', true );
	$img    = get_the_post_thumbnail_url( $post, 'pn-card' );
	$terms  = get_the_terms( $post, 'danh_muc' );
	$style  = pn_term_style( $terms && ! is_wp_error( $terms ) ? $terms[0] : null );

	return array(
		'title'  => get_the_title( $post ),
		'url'    => get_permalink( $post ),
		'img'    => $img ? $img : '',
		'icon'   => $style['icon'],
		'color'  => $style['color'],
		'city'   => (string) get_post_meta( $post->ID, 'pn_khu_vuc', true ),
		'old'    => $old,
		'price'  => $price,
		'left'   => pn_time_left( $expiry ),
		'hours'  => $expiry ? max( 0, (int) floor( ( $expiry - time() ) / HOUR_IN_SECONDS ) ) : 99999,
		'urgent' => (bool) get_post_meta( $post->ID, 'pn_can_gap', true ),
		'demo'   => false,
	);
}

function pn_off( $item ) {
	if ( empty( $item['old'] ) || $item['old'] <= $item['price'] ) {
		return 0;
	}
	return (int) round( ( 1 - $item['price'] / $item['old'] ) * 100 );
}

/** Tin Pass còn hạn mới nhất; nếu chưa có tin nào thì trả về tin minh họa. */
function pn_get_listings( $count = 10 ) {
	$q = new WP_Query(
		array(
			'post_type'      => 'tin_pass',
			'posts_per_page' => $count,
			'no_found_rows'  => true,
			'meta_query'     => array( pn_not_expired_meta_query() ),
		)
	);
	$items = array_map( 'pn_listing_data', $q->posts );
	if ( ! $items && pn_show_demo() ) {
		$items = pn_demo_listings();
	}
	return $items;
}

function pn_demo_listings() {
	$app  = pn_app_url();
	$rows = array(
		array( 'halong-hotel', 'Phòng 5★ Hạ Long View biển, Deluxe', 'Hạ Long, Quảng Ninh', 2800000, 1500000, 9, true ),
		array( 'movie', 'Vé xem phim cuối tuần, 2 ghế đôi', 'Hà Nội', 400000, 270000, 24, false ),
		array( 'sapa-tour', 'Tour Sapa 2N1Đ Xe giường nằm + KS 3★', 'Sapa, Lào Cai', 3500000, 2200000, 48, false ),
		array( 'sofa', 'Sofa da cao cấp 99% mới, thanh lý', 'Hà Nội', 15000000, 7500000, 72, false ),
		array( 'room', 'Phòng trọ full đồ Gần ĐH Bách Khoa', 'Hà Nội', 4000000, 2800000, 96, false ),
		array( 'concert', 'Vé concert tháng 11, khu GA', 'Hà Nội', 2500000, 1600000, 5, true ),
		array( 'danang-hotel', 'Phòng 4★ Đà Nẵng Gần biển Mỹ Khê', 'Đà Nẵng', 2200000, 1200000, 24, false ),
		array( 'scooter', 'Xe máy tay ga 2022 Chính chủ', 'Hà Nội', 28000000, 21000000, 120, false ),
		array( 'phuquoc-tour', 'Tour Phú Quốc 3N2Đ Resort 4★', 'Phú Quốc, Kiên Giang', 5200000, 3100000, 120, false ),
		array( 'apartment', 'Căn hộ 1PN đủ nội thất, pass hợp đồng', 'Hà Nội', 12000000, 8500000, 240, false ),
	);
	return array_map(
		function ( $r ) use ( $app ) {
			return array(
				'title'  => $r[1],
				'url'    => $app,
				'img'    => pn_img( $r[0] . '.webp' ),
				'city'   => $r[2],
				'old'    => $r[3],
				'price'  => $r[4],
				'left'   => pn_time_left( time() + $r[5] * HOUR_IN_SECONDS ),
				'hours'  => $r[5],
				'urgent' => $r[6],
				'demo'   => true,
			);
		},
		$rows
	);
}

/** Biểu tượng và màu của một danh mục. */
function pn_term_style( $term ) {
	$icon  = $term ? get_term_meta( $term->term_id, 'pn_icon', true ) : '';
	$color = $term ? get_term_meta( $term->term_id, 'pn_color', true ) : '';
	return array(
		'icon'  => $icon ? $icon : '📦',
		'color' => $color ? $color : 'blue',
	);
}

/** Danh mục cho thanh danh mục trang chủ. */
function pn_get_categories() {
	$terms = get_terms(
		array(
			'taxonomy'   => 'danh_muc',
			'hide_empty' => false,
			'parent'     => 0,
			'meta_key'   => 'pn_order',
			'orderby'    => 'meta_value_num',
			'order'      => 'ASC',
		)
	);
	if ( is_wp_error( $terms ) || ! $terms ) {
		$terms = get_terms(
			array(
				'taxonomy'   => 'danh_muc',
				'hide_empty' => false,
				'parent'     => 0,
			)
		);
	}
	if ( is_wp_error( $terms ) ) {
		return array();
	}
	return array_map(
		function ( $t ) {
			$s = pn_term_style( $t );
			return array(
				'name'  => $t->name,
				'url'   => get_term_link( $t ),
				'icon'  => $s['icon'],
				'color' => $s['color'],
			);
		},
		$terms
	);
}

/** Nhu cầu mới nhất; nếu chưa có thì trả về ví dụ minh họa. */
function pn_get_demands( $count = 5 ) {
	$q     = new WP_Query(
		array(
			'post_type'      => 'nhu_cau',
			'posts_per_page' => $count,
			'no_found_rows'  => true,
		)
	);
	$items = array();
	foreach ( $q->posts as $p ) {
		$terms   = get_the_terms( $p, 'danh_muc' );
		$style   = pn_term_style( $terms && ! is_wp_error( $terms ) ? $terms[0] : null );
		$items[] = array(
			'title' => get_the_title( $p ),
			'note'  => (string) get_post_meta( $p->ID, 'pn_ghi_chu', true ),
			'icon'  => $style['icon'],
			'color' => $style['color'],
		);
	}
	if ( ! $items && pn_show_demo() ) {
		$items = array(
			array( 'title' => 'Cần phòng Hạ Long (20/10 – 21/10)', 'note' => 'Ngân sách đến 1.500.000đ', 'icon' => '🏨', 'color' => 'blue' ),
			array( 'title' => 'Cần vé xem phim cuối tuần', 'note' => 'Hà Nội · 2 vé', 'icon' => '🎬', 'color' => 'red' ),
			array( 'title' => 'Cần tour Sapa cuối tuần', 'note' => 'Ngân sách đến 2.500.000đ', 'icon' => '🏞️', 'color' => 'green' ),
			array( 'title' => 'Cần phòng trọ Hà Nội', 'note' => 'Gần ĐH Bách Khoa · đến 3.000.000đ', 'icon' => '🏠', 'color' => 'orange' ),
			array( 'title' => 'Cần vé concert tháng 11', 'note' => 'TP. Hồ Chí Minh · 2 vé', 'icon' => '🎟️', 'color' => 'purple' ),
		);
	}
	return $items;
}

/** Bài viết mới nhất cho mục Bài viết & Cẩm nang. */
function pn_get_blog_posts( $count = 4 ) {
	$q     = new WP_Query(
		array(
			'post_type'           => 'post',
			'posts_per_page'      => $count,
			'no_found_rows'       => true,
			'ignore_sticky_posts' => true,
		)
	);
	$items = array();
	foreach ( $q->posts as $p ) {
		$img     = get_the_post_thumbnail_url( $p, 'pn-blog' );
		$items[] = array(
			'title' => get_the_title( $p ),
			'url'   => get_permalink( $p ),
			'img'   => $img ? $img : pn_img( 'blog-halong.webp' ),
		);
	}
	return $items;
}

/**
 * Chế độ Blog: WordPress chỉ cài cho passnhanh.vn/blog, trang chủ chính vẫn là web khác.
 * Tự bật khi địa chỉ WordPress có đường dẫn con (ví dụ /blog), hoặc khi đặt option pn_blog_mode.
 */
function pn_is_blog_install() {
	static $mode = null;
	if ( null === $mode ) {
		$path = (string) wp_parse_url( home_url(), PHP_URL_PATH );
		$mode = (bool) apply_filters( 'pn_blog_mode', '' !== trim( $path, '/' ) || (bool) get_option( 'pn_blog_mode' ) );
	}
	return $mode;
}

/** Địa chỉ trên trang chủ chính passnhanh.vn (ở chế độ Blog là tên miền gốc, không phải /blog). */
function pn_main_url( $path = '/' ) {
	if ( ! pn_is_blog_install() ) {
		return home_url( $path );
	}
	$parts = wp_parse_url( home_url() );
	$port  = isset( $parts['port'] ) ? ':' . $parts['port'] : '';
	return $parts['scheme'] . '://' . $parts['host'] . $port . $path;
}

/** Trang danh sách Tin Pass (ở chế độ Blog thì dẫn sang web app). */
function pn_listings_url() {
	if ( pn_is_blog_install() ) {
		return pn_app_url();
	}
	$link = get_post_type_archive_link( 'tin_pass' );
	return $link ? $link : pn_app_url();
}

/** Trang danh sách bài viết (Blog). */
function pn_blog_url() {
	if ( pn_is_blog_install() ) {
		return home_url( '/' );
	}
	$page = (int) get_option( 'page_for_posts' );
	return $page ? get_permalink( $page ) : home_url( '/#blog' );
}

/** Liên kết menu: lấy từ Giao diện → Menu, hoặc dùng menu mặc định. */
function pn_menu_links( $location ) {
	$locations = get_nav_menu_locations();
	if ( ! empty( $locations[ $location ] ) ) {
		$items = wp_get_nav_menu_items( $locations[ $location ] );
		if ( $items ) {
			_wp_menu_item_classes_by_context( $items );
			$links = array();
			foreach ( $items as $item ) {
				if ( (int) $item->menu_item_parent ) {
					continue;
				}
				$links[] = array(
					'title'   => $item->title,
					'url'     => $item->url,
					'current' => in_array( 'current-menu-item', (array) $item->classes, true ),
				);
			}
			return $links;
		}
	}

	if ( 'footer' === $location ) {
		return array();
	}
	$blog_mode = pn_is_blog_install();
	return array(
		array( 'title' => 'Trang chủ', 'url' => pn_main_url( '/' ), 'current' => ! $blog_mode && is_front_page() ),
		array( 'title' => 'Tìm Pass', 'url' => pn_listings_url(), 'current' => ! $blog_mode && ( is_post_type_archive( 'tin_pass' ) || is_tax( 'danh_muc' ) ) ),
		array( 'title' => 'Đăng Pass', 'url' => pn_app_url(), 'current' => false ),
		array( 'title' => 'Đăng nhu cầu', 'url' => pn_app_url(), 'current' => false ),
		array( 'title' => 'Cộng tác viên', 'url' => pn_main_url( '/#ctv' ), 'current' => false ),
		array( 'title' => 'Blog', 'url' => pn_blog_url(), 'current' => is_home() || is_singular( 'post' ) || is_category() || ( $blog_mode && is_search() ) ),
		array( 'title' => 'Hỗ trợ', 'url' => pn_main_url( '/#ho-tro' ), 'current' => false ),
	);
}

/** Trang theo slug (dùng cho liên kết Điều khoản, Quyền riêng tư ở chân trang). */
function pn_page_url( $slug ) {
	if ( pn_is_blog_install() ) {
		// Trang pháp lý nằm trên web chính.
		return pn_main_url( '/' . $slug . '.html' );
	}
	$page = get_page_by_path( $slug );
	return $page ? get_permalink( $page ) : '';
}

/** Logo chính thức (ảnh). */
function pn_brand_markup() {
	echo '<img class="brand-logo" src="' . esc_url( pn_img( 'logo.webp' ) ) . '" alt="PassNhanh — Đăng nhanh, tìm nhanh, chốt nhanh" width="596" height="144">';
}

/**
 * Nội dung bài đã chèn sẵn ảnh đại diện chưa (cùng ảnh trong thư viện, hoặc cùng file ảnh).
 * Dùng để không in ảnh đại diện lần nữa ở đầu bài.
 */
function pn_content_has_thumbnail( $post = null ) {
	$post     = get_post( $post );
	$thumb_id = $post ? (int) get_post_thumbnail_id( $post ) : 0;
	if ( ! $thumb_id ) {
		return false;
	}
	$content = (string) $post->post_content;
	if ( false !== strpos( $content, 'wp-image-' . $thumb_id . '"' ) || false !== strpos( $content, 'wp-image-' . $thumb_id . ' ' ) || false !== strpos( $content, '"id":' . $thumb_id . ',' ) || false !== strpos( $content, '"id":' . $thumb_id . '}' ) ) {
		return true;
	}
	$file = get_attached_file( $thumb_id );
	if ( $file ) {
		// So theo tên file gốc (bỏ đuôi kích thước -1024x683, -scaled).
		$base = preg_replace( '/(-\d+x\d+|-scaled)?\.[a-z0-9]+$/i', '', wp_basename( $file ) );
		if ( '' !== $base && preg_match( '/' . preg_quote( $base, '/' ) . '(-\d+x\d+|-scaled)?\.[a-z0-9]+/i', $content ) ) {
			return true;
		}
	}
	return false;
}
