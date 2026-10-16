<?php
/**
 * PassNhanh theme.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PN_DIR', get_template_directory() );
define( 'PN_URI', get_template_directory_uri() );

require PN_DIR . '/inc/helpers.php';
require PN_DIR . '/inc/post-types.php';
require PN_DIR . '/inc/customizer.php';
require PN_DIR . '/inc/setup-content.php';

add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 84,
			'width'       => 368,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	register_nav_menus(
		array(
			'primary' => 'Menu chính (đầu trang)',
			'footer'  => 'Menu chân trang',
		)
	);

	add_image_size( 'pn-card', 480, 240, true );
	add_image_size( 'pn-blog', 460, 200, true );
} );

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style(
		'pn-fonts',
		'https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:ital,wght@0,400;0,500;0,600;0,700;0,800;1,800;1,900&display=swap',
		array(),
		null
	);
	// Phiên bản theo thời điểm sửa file, nên trình duyệt luôn nhận CSS/JS mới sau mỗi lần deploy.
	wp_enqueue_style( 'pn-main', PN_URI . '/assets/css/main.css', array( 'pn-fonts' ), filemtime( PN_DIR . '/assets/css/main.css' ) );
	wp_add_inline_style( 'pn-main', pn_inline_css() );

	wp_enqueue_script(
		'pn-main',
		PN_URI . '/assets/js/main.js',
		array(),
		filemtime( PN_DIR . '/assets/js/main.js' ),
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
	wp_localize_script(
		'pn-main',
		'PN',
		array(
			'appUrl' => pn_app_url(),
		)
	);
} );

add_action( 'wp_head', function () {
	if ( ! has_site_icon() ) {
		echo '<link rel="icon" href="' . esc_url( PN_URI . '/assets/img/favicon.png' ) . '" type="image/png">' . "\n";
		echo '<link rel="apple-touch-icon" href="' . esc_url( PN_URI . '/assets/img/apple-touch-icon.png' ) . '">' . "\n";
	}
	echo '<meta name="theme-color" content="#00a6a6">' . "\n";
}, 2 );

// Chuyển HTTP sang HTTPS khi địa chỉ website là https.
add_action( 'template_redirect', function () {
	if ( ! is_ssl() && 0 === strpos( home_url(), 'https://' ) && ! wp_doing_cron() ) {
		$host = isset( $_SERVER['HTTP_HOST'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : '';
		$uri  = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '/';
		if ( $host ) {
			wp_safe_redirect( 'https://' . $host . $uri, 301 );
			exit;
		}
	}
}, 1 );

// Ô tìm kiếm trên trang chủ: tìm trong Tin Pass, lọc thêm theo khu vực.
add_action( 'pre_get_posts', function ( $q ) {
	if ( is_admin() || ! $q->is_main_query() ) {
		return;
	}
	$is_listing_query = $q->is_post_type_archive( 'tin_pass' ) || $q->is_tax( 'danh_muc' ) || ( $q->is_search() && 'tin_pass' === $q->get( 'post_type' ) );
	if ( ! $is_listing_query ) {
		return;
	}
	$meta_query = array( pn_not_expired_meta_query() );
	$city       = isset( $_GET['khu_vuc'] ) ? sanitize_text_field( wp_unslash( $_GET['khu_vuc'] ) ) : '';
	if ( '' !== $city ) {
		$meta_query[] = array(
			'key'     => 'pn_khu_vuc',
			'value'   => $city,
			'compare' => 'LIKE',
		);
	}
	$q->set( 'meta_query', $meta_query );
	$q->set( 'posts_per_page', 20 );
} );

// Chế độ Blog: trang /blog/ hiện danh sách bài viết, không dùng giao diện trang chủ chợ Pass.
add_filter( 'frontpage_template_hierarchy', function ( $templates ) {
	return pn_is_blog_install() ? array() : $templates;
} );

require PN_DIR . '/inc/seo.php';
