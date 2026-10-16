<?php
/**
 * Tin Pass, Nhu cầu, Danh mục và các ô nhập thông tin trong trang quản trị.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const PN_COLORS = array(
	'red'    => 'Đỏ',
	'blue'   => 'Xanh dương',
	'green'  => 'Xanh lá',
	'orange' => 'Cam',
	'purple' => 'Tím',
	'pink'   => 'Hồng',
	'teal'   => 'Xanh ngọc',
);

add_action( 'init', 'pn_register_types' );

function pn_register_types() {
	// Chế độ Blog: chỉ dùng bài viết, Tin Pass/Nhu cầu/Danh mục nằm ở web chính.
	if ( pn_is_blog_install() ) {
		return;
	}

	register_post_type(
		'tin_pass',
		array(
			'labels'        => array(
				'name'               => 'Tin Pass',
				'singular_name'      => 'Tin Pass',
				'add_new'            => 'Thêm tin',
				'add_new_item'       => 'Thêm Tin Pass',
				'edit_item'          => 'Sửa Tin Pass',
				'new_item'           => 'Tin Pass mới',
				'view_item'          => 'Xem tin',
				'search_items'       => 'Tìm Tin Pass',
				'not_found'          => 'Chưa có tin nào',
				'not_found_in_trash' => 'Thùng rác trống',
				'all_items'          => 'Tất cả Tin Pass',
				'menu_name'          => 'Tin Pass',
			),
			'public'        => true,
			'has_archive'   => 'tin-pass',
			'rewrite'       => array( 'slug' => 'tin-pass' ),
			'menu_icon'     => 'dashicons-tickets-alt',
			'menu_position' => 5,
			'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			'show_in_rest'  => true,
		)
	);

	register_post_type(
		'nhu_cau',
		array(
			'labels'        => array(
				'name'          => 'Nhu cầu',
				'singular_name' => 'Nhu cầu',
				'add_new'       => 'Thêm nhu cầu',
				'add_new_item'  => 'Thêm nhu cầu',
				'edit_item'     => 'Sửa nhu cầu',
				'all_items'     => 'Tất cả nhu cầu',
				'not_found'     => 'Chưa có nhu cầu nào',
				'menu_name'     => 'Nhu cầu',
			),
			'public'        => false,
			'show_ui'       => true,
			'menu_icon'     => 'dashicons-search',
			'menu_position' => 6,
			'supports'      => array( 'title' ),
			'show_in_rest'  => true,
		)
	);

	register_taxonomy(
		'danh_muc',
		array( 'tin_pass', 'nhu_cau' ),
		array(
			'labels'            => array(
				'name'          => 'Danh mục Pass',
				'singular_name' => 'Danh mục',
				'add_new_item'  => 'Thêm danh mục',
				'edit_item'     => 'Sửa danh mục',
				'all_items'     => 'Tất cả danh mục',
				'menu_name'     => 'Danh mục',
			),
			'hierarchical'      => true,
			'public'            => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'danh-muc' ),
		)
	);

	$int = array(
		'type'              => 'integer',
		'single'            => true,
		'show_in_rest'      => true,
		'sanitize_callback' => 'absint',
		'auth_callback'     => function () {
			return current_user_can( 'edit_posts' );
		},
	);
	register_post_meta( 'tin_pass', 'pn_gia_goc', $int );
	register_post_meta( 'tin_pass', 'pn_gia_pass', $int );
	register_post_meta( 'tin_pass', 'pn_het_han', $int );
	register_post_meta(
		'tin_pass',
		'pn_khu_vuc',
		array_merge( $int, array( 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field' ) )
	);
	register_post_meta(
		'tin_pass',
		'pn_lien_ket',
		array_merge( $int, array( 'type' => 'string', 'sanitize_callback' => 'esc_url_raw' ) )
	);
	register_post_meta(
		'tin_pass',
		'pn_can_gap',
		array_merge( $int, array( 'type' => 'boolean', 'sanitize_callback' => 'rest_sanitize_boolean' ) )
	);
	register_post_meta(
		'nhu_cau',
		'pn_ghi_chu',
		array_merge( $int, array( 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field' ) )
	);
}

/* ---------- Ô nhập thông tin Tin Pass ---------- */

add_action( 'add_meta_boxes', function () {
	add_meta_box( 'pn_listing', 'Thông tin Tin Pass', 'pn_listing_meta_box', 'tin_pass', 'normal', 'high' );
	add_meta_box( 'pn_demand', 'Thông tin nhu cầu', 'pn_demand_meta_box', 'nhu_cau', 'normal', 'high' );
} );

function pn_listing_meta_box( $post ) {
	wp_nonce_field( 'pn_save_listing', 'pn_listing_nonce' );
	$old    = get_post_meta( $post->ID, 'pn_gia_goc', true );
	$price  = get_post_meta( $post->ID, 'pn_gia_pass', true );
	$city   = get_post_meta( $post->ID, 'pn_khu_vuc', true );
	$expiry = (int) get_post_meta( $post->ID, 'pn_het_han', true );
	$link   = get_post_meta( $post->ID, 'pn_lien_ket', true );
	$urgent = (bool) get_post_meta( $post->ID, 'pn_can_gap', true );
	$local  = $expiry ? wp_date( 'Y-m-d\TH:i', $expiry ) : '';
	?>
	<style>.pn-fields{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px}.pn-fields label{display:flex;flex-direction:column;gap:4px;font-weight:600}.pn-fields .full{grid-column:1/-1}.pn-fields input[type=text],.pn-fields input[type=number],.pn-fields input[type=url],.pn-fields input[type=datetime-local]{width:100%}.pn-fields small{font-weight:400;color:#646970}</style>
	<div class="pn-fields">
		<label>Giá gốc (đ)<input type="number" min="0" step="1000" name="pn_gia_goc" value="<?php echo esc_attr( $old ); ?>"></label>
		<label>Giá pass (đ)<input type="number" min="0" step="1000" name="pn_gia_pass" value="<?php echo esc_attr( $price ); ?>"></label>
		<label>Khu vực<input type="text" name="pn_khu_vuc" value="<?php echo esc_attr( $city ); ?>" placeholder="Hà Nội"></label>
		<label>Hạn sử dụng / hạn đăng<input type="datetime-local" name="pn_het_han" value="<?php echo esc_attr( $local ); ?>"><small>Tin tự ẩn khỏi trang chủ và danh sách khi hết hạn.</small></label>
		<label class="full">Liên kết đặt mua / liên hệ<input type="url" name="pn_lien_ket" value="<?php echo esc_attr( $link ); ?>" placeholder="<?php echo esc_attr( pn_app_url() ); ?>"><small>Để trống thì nút "Liên hệ người pass" dẫn tới web app.</small></label>
		<label class="full" style="flex-direction:row;align-items:center;gap:8px"><input type="checkbox" name="pn_can_gap" value="1" <?php checked( $urgent ); ?>>Gắn nhãn "Cần Pass Gấp"</label>
	</div>
	<p><small>Ảnh của tin: dùng mục <strong>Ảnh đại diện</strong> ở cột bên phải. Danh mục: chọn ở mục <strong>Danh mục Pass</strong>.</small></p>
	<?php
}

function pn_demand_meta_box( $post ) {
	wp_nonce_field( 'pn_save_demand', 'pn_demand_nonce' );
	$note = get_post_meta( $post->ID, 'pn_ghi_chu', true );
	?>
	<p><label style="font-weight:600">Dòng mô tả ngắn<br><input type="text" name="pn_ghi_chu" value="<?php echo esc_attr( $note ); ?>" class="widefat" placeholder="Ngân sách đến 1.500.000đ"></label></p>
	<p><small>Tiêu đề là nhu cầu (ví dụ: "Cần phòng Hạ Long 20/10 – 21/10"). Biểu tượng lấy theo Danh mục Pass đã chọn.</small></p>
	<?php
}

add_action( 'save_post_tin_pass', function ( $post_id ) {
	if ( ! isset( $_POST['pn_listing_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['pn_listing_nonce'] ), 'pn_save_listing' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	foreach ( array( 'pn_gia_goc', 'pn_gia_pass' ) as $key ) {
		$val = isset( $_POST[ $key ] ) ? absint( $_POST[ $key ] ) : 0;
		update_post_meta( $post_id, $key, $val );
	}
	update_post_meta( $post_id, 'pn_khu_vuc', isset( $_POST['pn_khu_vuc'] ) ? sanitize_text_field( wp_unslash( $_POST['pn_khu_vuc'] ) ) : '' );
	update_post_meta( $post_id, 'pn_lien_ket', isset( $_POST['pn_lien_ket'] ) ? esc_url_raw( wp_unslash( $_POST['pn_lien_ket'] ) ) : '' );
	update_post_meta( $post_id, 'pn_can_gap', ! empty( $_POST['pn_can_gap'] ) ? 1 : 0 );

	$raw = isset( $_POST['pn_het_han'] ) ? sanitize_text_field( wp_unslash( $_POST['pn_het_han'] ) ) : '';
	if ( '' === $raw ) {
		delete_post_meta( $post_id, 'pn_het_han' );
	} else {
		$dt = date_create_immutable_from_format( 'Y-m-d\TH:i', $raw, wp_timezone() );
		if ( $dt ) {
			update_post_meta( $post_id, 'pn_het_han', $dt->getTimestamp() );
		}
	}
} );

add_action( 'save_post_nhu_cau', function ( $post_id ) {
	if ( ! isset( $_POST['pn_demand_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['pn_demand_nonce'] ), 'pn_save_demand' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	update_post_meta( $post_id, 'pn_ghi_chu', isset( $_POST['pn_ghi_chu'] ) ? sanitize_text_field( wp_unslash( $_POST['pn_ghi_chu'] ) ) : '' );
} );

/* ---------- Cột trong danh sách Tin Pass ---------- */

add_filter( 'manage_tin_pass_posts_columns', function ( $cols ) {
	$new = array();
	foreach ( $cols as $key => $label ) {
		$new[ $key ] = $label;
		if ( 'title' === $key ) {
			$new['pn_price']  = 'Giá pass';
			$new['pn_city']   = 'Khu vực';
			$new['pn_expiry'] = 'Hạn';
		}
	}
	return $new;
} );

add_action( 'manage_tin_pass_posts_custom_column', function ( $col, $post_id ) {
	if ( 'pn_price' === $col ) {
		$price = (int) get_post_meta( $post_id, 'pn_gia_pass', true );
		echo $price ? esc_html( pn_money( $price ) ) : '—';
	} elseif ( 'pn_city' === $col ) {
		echo esc_html( get_post_meta( $post_id, 'pn_khu_vuc', true ) ?: '—' );
	} elseif ( 'pn_expiry' === $col ) {
		$ts = (int) get_post_meta( $post_id, 'pn_het_han', true );
		echo $ts ? esc_html( wp_date( 'd/m/Y H:i', $ts ) . ' · ' . pn_time_left( $ts ) ) : '—';
	}
}, 10, 2 );

/* ---------- Biểu tượng, màu, thứ tự của Danh mục ---------- */

function pn_term_fields( $term = null ) {
	$icon  = $term ? get_term_meta( $term->term_id, 'pn_icon', true ) : '';
	$color = $term ? get_term_meta( $term->term_id, 'pn_color', true ) : 'blue';
	$order = $term ? get_term_meta( $term->term_id, 'pn_order', true ) : '';
	wp_nonce_field( 'pn_save_term', 'pn_term_nonce' );
	$fields = array(
		'icon'  => '<input type="text" name="pn_icon" value="' . esc_attr( $icon ) . '" maxlength="8" style="width:80px"> <p class="description">Một emoji, ví dụ 🎬 🏨 🏠</p>',
		'color' => '',
		'order' => '<input type="number" name="pn_order" value="' . esc_attr( $order ) . '" style="width:80px"> <p class="description">Số nhỏ đứng trước trên thanh danh mục.</p>',
	);
	$opts = '';
	foreach ( PN_COLORS as $key => $label ) {
		$opts .= '<option value="' . esc_attr( $key ) . '"' . selected( $color, $key, false ) . '>' . esc_html( $label ) . '</option>';
	}
	$fields['color'] = '<select name="pn_color">' . $opts . '</select>';
	return $fields;
}

add_action( 'danh_muc_add_form_fields', function () {
	$f = pn_term_fields();
	echo '<div class="form-field"><label>Biểu tượng</label>' . $f['icon'] . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput
	echo '<div class="form-field"><label>Màu nền biểu tượng</label>' . $f['color'] . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput
	echo '<div class="form-field"><label>Thứ tự</label>' . $f['order'] . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput
} );

add_action( 'danh_muc_edit_form_fields', function ( $term ) {
	$f = pn_term_fields( $term );
	echo '<tr class="form-field"><th>Biểu tượng</th><td>' . $f['icon'] . '</td></tr>'; // phpcs:ignore WordPress.Security.EscapeOutput
	echo '<tr class="form-field"><th>Màu nền biểu tượng</th><td>' . $f['color'] . '</td></tr>'; // phpcs:ignore WordPress.Security.EscapeOutput
	echo '<tr class="form-field"><th>Thứ tự</th><td>' . $f['order'] . '</td></tr>'; // phpcs:ignore WordPress.Security.EscapeOutput
} );

function pn_save_term_fields( $term_id ) {
	if ( ! isset( $_POST['pn_term_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['pn_term_nonce'] ), 'pn_save_term' ) || ! current_user_can( 'manage_categories' ) ) {
		return;
	}
	update_term_meta( $term_id, 'pn_icon', isset( $_POST['pn_icon'] ) ? sanitize_text_field( wp_unslash( $_POST['pn_icon'] ) ) : '' );
	$color = isset( $_POST['pn_color'] ) ? sanitize_key( $_POST['pn_color'] ) : 'blue';
	update_term_meta( $term_id, 'pn_color', array_key_exists( $color, PN_COLORS ) ? $color : 'blue' );
	update_term_meta( $term_id, 'pn_order', isset( $_POST['pn_order'] ) ? intval( $_POST['pn_order'] ) : 0 );
}
add_action( 'created_danh_muc', 'pn_save_term_fields' );
add_action( 'edited_danh_muc', 'pn_save_term_fields' );
