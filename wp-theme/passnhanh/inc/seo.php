<?php
/**
 * SEO cơ bản: mô tả trang, canonical, Open Graph, dữ liệu có cấu trúc (JSON-LD),
 * chặn index trang không có giá trị, ẩn trang tác giả.
 * Tự tắt phần thẻ meta khi đã cài plugin SEO (Yoast, Rank Math, SEOPress, AIOSEO) để không bị trùng.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function pn_seo_plugin_active() {
	return defined( 'WPSEO_VERSION' ) || class_exists( 'RankMath' ) || defined( 'SEOPRESS_VERSION' ) || defined( 'AIOSEO_VERSION' );
}

/** Mô tả ngắn cho trang hiện tại (≤ 160 ký tự). */
function pn_meta_description() {
	if ( is_singular() ) {
		$post = get_queried_object();
		$text = has_excerpt( $post ) ? get_the_excerpt( $post ) : wp_strip_all_tags( strip_shortcodes( $post->post_content ) );
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$text = term_description();
		if ( ! $text ) {
			$text = 'Bài viết về ' . single_term_title( '', false ) . ' trên Blog PassNhanh.';
		}
	} elseif ( is_home() || is_front_page() ) {
		$text = pn_is_blog_install()
			? 'Blog PassNhanh: kinh nghiệm pass vé, phòng, tour, đồ cũ; mẹo săn deal và hướng dẫn giao dịch an toàn.'
			: 'PassNhanh.vn — chợ pass phòng khách sạn, vé, tour, phòng trọ, đồ cũ. Đăng Pass miễn phí, đăng nhu cầu để được ghép tin phù hợp.';
	} else {
		$text = get_bloginfo( 'description' );
	}
	$text = trim( preg_replace( '/\s+/u', ' ', wp_strip_all_tags( (string) $text ) ) );
	return wp_html_excerpt( $text, 158, '…' );
}

/** URL chuẩn của trang hiện tại (bỏ tham số), có tính trang 2, 3… */
function pn_canonical_url() {
	if ( is_singular() ) {
		return wp_get_canonical_url();
	}
	$paged = max( 1, (int) get_query_var( 'paged' ) );
	if ( is_home() || is_front_page() ) {
		$url = home_url( '/' );
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$url = get_term_link( get_queried_object() );
	} elseif ( is_post_type_archive() ) {
		$url = get_post_type_archive_link( get_query_var( 'post_type' ) );
	} else {
		return '';
	}
	if ( is_wp_error( $url ) || ! $url ) {
		return '';
	}
	return $paged > 1 ? trailingslashit( $url ) . user_trailingslashit( 'page/' . $paged, 'paged' ) : $url;
}

/** Ảnh chia sẻ: ảnh đại diện bài viết, nếu không có thì ảnh đầu trang. */
function pn_share_image() {
	if ( is_singular() && has_post_thumbnail() ) {
		$src = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
		if ( $src ) {
			return $src;
		}
	}
	return array( pn_mod_image( 'pn_hero_image', 'hero.webp' ), 0, 0 );
}

/* ---------- Không index: tìm kiếm; ẩn trang tác giả, ngày tháng, đính kèm ---------- */

add_filter( 'wp_robots', function ( $robots ) {
	if ( is_search() || ( isset( $_GET['khu_vuc'] ) && ! is_singular() ) ) {
		$robots['noindex'] = true;
		$robots['follow']  = true;
		unset( $robots['max-image-preview'] );
	}
	return $robots;
} );

add_action( 'template_redirect', function () {
	if ( is_author() || is_date() ) {
		wp_safe_redirect( pn_blog_url(), 301 );
		exit;
	}
	if ( is_attachment() ) {
		$parent = wp_get_post_parent_id( get_queried_object_id() );
		wp_safe_redirect( $parent ? get_permalink( $parent ) : home_url( '/' ), 301 );
		exit;
	}
} );

// Không đưa danh sách tài khoản (lộ tên đăng nhập quản trị) vào sitemap.
add_filter( 'wp_sitemaps_add_provider', function ( $provider, $name ) {
	return 'users' === $name ? false : $provider;
}, 10, 2 );

// Không đưa Nhu cầu và Tin Pass hết hạn vào sitemap.
add_filter( 'wp_sitemaps_post_types', function ( $types ) {
	unset( $types['nhu_cau'] );
	return $types;
} );
add_filter( 'wp_sitemaps_posts_query_args', function ( $args, $post_type ) {
	if ( 'tin_pass' === $post_type ) {
		$args['meta_query'] = array( pn_not_expired_meta_query() );
	}
	return $args;
}, 10, 2 );

// REST API: không cho người lạ liệt kê tài khoản.
add_filter( 'rest_endpoints', function ( $endpoints ) {
	if ( ! is_user_logged_in() ) {
		unset( $endpoints['/wp/v2/users'], $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
	}
	return $endpoints;
} );

/* ---------- Thẻ meta, Open Graph, JSON-LD ---------- */

add_action( 'wp_head', function () {
	if ( pn_seo_plugin_active() || is_404() ) {
		return;
	}

	$desc      = pn_meta_description();
	$canonical = pn_canonical_url();
	$image     = pn_share_image();
	$title     = wp_get_document_title();
	$is_post   = is_singular( 'post' );

	if ( $desc ) {
		echo '<meta name="description" content="' . esc_attr( $desc ) . "\">\n";
	}
	// WordPress tự in canonical cho bài/trang; chỉ thêm cho trang danh sách.
	if ( $canonical && ! is_singular() ) {
		echo '<link rel="canonical" href="' . esc_url( $canonical ) . "\">\n";
	}

	$og = array(
		'og:locale'      => 'vi_VN',
		'og:site_name'   => 'PassNhanh.vn',
		'og:type'        => $is_post ? 'article' : 'website',
		'og:title'       => $title,
		'og:description' => $desc,
		'og:url'         => $canonical,
		'og:image'       => $image[0],
	);
	if ( $image[1] ) {
		$og['og:image:width']  = $image[1];
		$og['og:image:height'] = $image[2];
	}
	if ( $is_post ) {
		$og['article:published_time'] = get_the_date( 'c' );
		$og['article:modified_time']  = get_the_modified_date( 'c' );
	}
	foreach ( $og as $prop => $val ) {
		if ( '' !== (string) $val ) {
			echo '<meta property="' . esc_attr( $prop ) . '" content="' . esc_attr( $val ) . "\">\n";
		}
	}
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";

	$org = array(
		'@type' => 'Organization',
		'@id'   => pn_main_url( '/#organization' ),
		'name'  => 'PassNhanh.vn',
		'url'   => pn_main_url( '/' ),
		'logo'  => pn_img( 'logo.webp' ),
		'email' => pn_text( 'pn_support_email', 'hotro@passnhanh.vn' ),
	);
	$graph = array( $org );

	$crumbs = array( array( 'Trang chủ', pn_main_url( '/' ) ) );
	if ( pn_is_blog_install() || is_home() || $is_post || is_category() ) {
		$crumbs[] = array( 'Blog', pn_blog_url() );
	}

	if ( is_home() && pn_is_blog_install() ) {
		$graph[] = array(
			'@type'       => 'Blog',
			'@id'         => home_url( '/#blog' ),
			'name'        => 'Blog PassNhanh',
			'description' => $desc,
			'url'         => home_url( '/' ),
			'inLanguage'  => 'vi',
			'publisher'   => array( '@id' => $org['@id'] ),
		);
	} elseif ( $is_post ) {
		$post   = get_queried_object();
		$cats   = get_the_category( $post->ID );
		$author = get_the_author_meta( 'display_name', $post->post_author );
		if ( $cats ) {
			$crumbs[] = array( $cats[0]->name, get_category_link( $cats[0] ) );
		}
		$crumbs[] = array( get_the_title( $post ), get_permalink( $post ) );
		$graph[]  = array(
			'@type'            => 'BlogPosting',
			'@id'              => get_permalink( $post ) . '#article',
			'headline'         => wp_html_excerpt( get_the_title( $post ), 110 ),
			'description'      => $desc,
			'image'            => $image[0],
			'datePublished'    => get_the_date( 'c', $post ),
			'dateModified'     => get_the_modified_date( 'c', $post ),
			'author'           => $author ? array( '@type' => 'Person', 'name' => $author ) : array( '@id' => $org['@id'] ),
			'publisher'        => array( '@id' => $org['@id'] ),
			'mainEntityOfPage' => get_permalink( $post ),
			'inLanguage'       => 'vi',
			'articleSection'   => $cats ? $cats[0]->name : null,
		);
	} elseif ( is_category() ) {
		$crumbs[] = array( single_cat_title( '', false ), $canonical );
		$graph[]  = array(
			'@type'       => 'CollectionPage',
			'name'        => single_cat_title( '', false ),
			'description' => $desc,
			'url'         => $canonical,
			'inLanguage'  => 'vi',
		);
	}

	if ( count( $crumbs ) > 1 ) {
		$items = array();
		foreach ( $crumbs as $i => $c ) {
			$items[] = array(
				'@type'    => 'ListItem',
				'position' => $i + 1,
				'name'     => $c[0],
				'item'     => $c[1],
			);
		}
		$graph[] = array(
			'@type'           => 'BreadcrumbList',
			'itemListElement' => $items,
		);
	}

	$graph = array_map( 'array_filter', $graph );
	echo '<script type="application/ld+json">' . wp_json_encode(
		array(
			'@context' => 'https://schema.org',
			'@graph'   => $graph,
		),
		JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
	) . "</script>\n";
}, 5 );
