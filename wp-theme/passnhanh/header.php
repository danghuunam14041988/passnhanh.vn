<?php
/**
 * Đầu trang.
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header" id="top">
	<div class="container header-row">
		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?> — Trang chủ">
			<?php
			$logo_id = get_theme_mod( 'custom_logo' );
			if ( $logo_id ) {
				echo wp_get_attachment_image( $logo_id, 'full', false, array( 'alt' => get_bloginfo( 'name' ) ) );
			} else {
				echo '<img src="' . esc_url( pn_img( 'logo.webp' ) ) . '" alt="PassNhanh — Đăng nhanh, tìm nhanh, chốt nhanh" width="184" height="42">';
			}
			?>
		</a>
		<nav class="nav" id="nav" aria-label="Điều hướng chính">
			<?php foreach ( pn_menu_links( 'primary' ) as $link ) : ?>
				<a class="<?php echo $link['current'] ? 'active' : ''; ?>" href="<?php echo esc_url( $link['url'] ); ?>"<?php echo $link['current'] ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $link['title'] ); ?></a>
			<?php endforeach; ?>
			<div class="nav-auth">
				<a class="btn btn-outline" href="<?php echo esc_url( pn_app_url() ); ?>">Đăng nhập</a>
				<a class="btn btn-primary" href="<?php echo esc_url( pn_app_url() ); ?>">Đăng ký</a>
			</div>
		</nav>
		<div class="header-tools">
			<form class="city-select" action="<?php echo esc_url( get_post_type_archive_link( 'tin_pass' ) ); ?>" method="get">
				<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 22s7-6.2 7-12a7 7 0 1 0-14 0c0 5.8 7 12 7 12z"/><circle cx="12" cy="10" r="2.5" fill="#fff"/></svg>
				<label class="sr-only" for="citySelect">Khu vực</label>
				<?php $current_city = isset( $_GET['khu_vuc'] ) ? sanitize_text_field( wp_unslash( $_GET['khu_vuc'] ) ) : 'Hà Nội'; ?>
				<select id="citySelect" name="khu_vuc">
					<?php foreach ( array( 'Hà Nội', 'TP. Hồ Chí Minh', 'Đà Nẵng', 'Hạ Long', 'Đà Lạt', 'Phú Quốc', 'Nha Trang', 'Sapa' ) as $city ) : ?>
						<option value="<?php echo esc_attr( $city ); ?>" <?php selected( $current_city, $city ); ?>><?php echo esc_html( $city ); ?></option>
					<?php endforeach; ?>
				</select>
			</form>
			<a class="icon-btn" href="<?php echo esc_url( home_url( '/#tim-kiem' ) ); ?>" aria-label="Tìm kiếm"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg></a>
			<a class="icon-btn" href="<?php echo esc_url( pn_app_url() ); ?>" aria-label="Thông báo"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 16V11a6 6 0 1 1 12 0v5l2 2H4z"/><path d="M10 20a2 2 0 0 0 4 0"/></svg></a>
			<a class="btn btn-outline hide-md" href="<?php echo esc_url( pn_app_url() ); ?>">Đăng nhập</a>
			<a class="btn btn-primary hide-md" href="<?php echo esc_url( pn_app_url() ); ?>">Đăng ký</a>
			<button class="icon-btn menu-toggle" id="menuToggle" aria-label="Mở menu" aria-expanded="false" aria-controls="nav">
				<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
			</button>
		</div>
	</div>
</header>
