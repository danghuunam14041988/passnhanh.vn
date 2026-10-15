<?php
/**
 * Kết quả tìm kiếm: Tin Pass dùng giao diện danh sách tin, còn lại dùng giao diện bài viết.
 */

if ( 'tin_pass' === get_query_var( 'post_type' ) ) {
	require __DIR__ . '/archive-tin_pass.php';
} else {
	require __DIR__ . '/index.php';
}
