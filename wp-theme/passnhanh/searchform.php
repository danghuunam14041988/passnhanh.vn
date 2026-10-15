<?php
/**
 * Ô tìm kiếm bài viết.
 */
?>
<form role="search" method="get" class="search-form-wp" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="s-<?php echo esc_attr( wp_unique_id() ); ?>">Tìm kiếm</label>
	<input type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="Tìm bài viết…">
	<button class="btn btn-primary" type="submit">Tìm</button>
</form>
