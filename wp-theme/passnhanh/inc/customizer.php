<?php
/**
 * Giao diện → Tùy biến → PassNhanh.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function pn_text( $mod, $default ) {
	$val = get_theme_mod( $mod, $default );
	return '' === $val ? $default : $val;
}

add_action( 'customize_register', function ( WP_Customize_Manager $c ) {
	$c->add_panel( 'pn_panel', array( 'title' => 'PassNhanh', 'priority' => 30 ) );

	$c->add_section( 'pn_hero', array( 'title' => 'Ảnh lớn đầu trang', 'panel' => 'pn_panel' ) );
	$c->add_section( 'pn_side', array( 'title' => 'Bản đồ & Cộng tác viên', 'panel' => 'pn_panel' ) );
	$c->add_section( 'pn_general', array( 'title' => 'Liên kết & hiển thị', 'panel' => 'pn_panel' ) );

	$texts = array(
		array( 'pn_hero_line1', 'pn_hero', 'Dòng tiêu đề 1', 'Mọi thứ cần Pass', 'text' ),
		array( 'pn_hero_line2', 'pn_hero', 'Dòng tiêu đề 2 (phần thường)', 'đều có trên Pass', 'text' ),
		array( 'pn_hero_highlight', 'pn_hero', 'Dòng tiêu đề 2 (phần màu cam)', 'Nhanh', 'text' ),
		array( 'pn_hero_sub', 'pn_hero', 'Dòng mô tả', "Phòng khách sạn · Vé xem phim · Tour du lịch · Phòng trọ\nĐồ cũ · Vé sự kiện · Và nhiều hơn nữa…", 'textarea' ),
		array( 'pn_ctv_title', 'pn_side', 'Tiêu đề CTV', 'Trở thành CTV PassNhanh', 'text' ),
		array( 'pn_ctv_sub', 'pn_side', 'Mô tả CTV', 'Chia sẻ tin hay – Nhận hoa hồng', 'text' ),
		array( 'pn_app_url', 'pn_general', 'Địa chỉ web app chợ Pass', 'https://app.passnhanh.vn', 'url' ),
		array( 'pn_support_email', 'pn_general', 'Email hỗ trợ', 'hotro@passnhanh.vn', 'email' ),
	);
	foreach ( $texts as $t ) {
		$sanitize = 'url' === $t[4] ? 'esc_url_raw' : ( 'email' === $t[4] ? 'sanitize_email' : ( 'textarea' === $t[4] ? 'sanitize_textarea_field' : 'sanitize_text_field' ) );
		$c->add_setting( $t[0], array( 'default' => $t[3], 'sanitize_callback' => $sanitize ) );
		$c->add_control( $t[0], array( 'label' => $t[2], 'section' => $t[1], 'type' => $t[4] ) );
	}

	$images = array(
		array( 'pn_hero_image', 'pn_hero', 'Ảnh nền đầu trang', 'Nên dùng ảnh ngang, rộng ít nhất 1600px.' ),
		array( 'pn_map_image', 'pn_side', 'Ảnh bản đồ', '' ),
		array( 'pn_ctv_image', 'pn_side', 'Ảnh banner CTV', 'Ảnh người, nền trong suốt hoặc nền xanh.' ),
	);
	foreach ( $images as $i ) {
		$c->add_setting( $i[0], array( 'sanitize_callback' => 'absint' ) );
		$c->add_control(
			new WP_Customize_Media_Control(
				$c,
				$i[0],
				array(
					'label'       => $i[2],
					'description' => $i[3],
					'section'     => $i[1],
					'mime_type'   => 'image',
				)
			)
		);
	}

	$c->add_setting( 'pn_show_demo', array( 'default' => true, 'sanitize_callback' => 'rest_sanitize_boolean' ) );
	$c->add_control(
		'pn_show_demo',
		array(
			'label'       => 'Hiện tin và nhu cầu minh họa khi chưa có dữ liệu thật',
			'description' => 'Tự ẩn khi bạn đã đăng Tin Pass / Nhu cầu đầu tiên.',
			'section'     => 'pn_general',
			'type'        => 'checkbox',
		)
	);
} );
