<?php
/**
 * Một bài viết.
 */

get_header();

while ( have_posts() ) :
	the_post();
	$cats      = get_the_category();
	$published = get_the_date( 'd/m/Y' );
	$modified  = get_the_modified_date( 'd/m/Y' );
	?>
<main class="container page" id="main">
	<article <?php post_class( 'card doc-card' ); ?>>
		<nav class="breadcrumb" aria-label="Đường dẫn">
			<a href="<?php echo esc_url( pn_main_url( '/' ) ); ?>">Trang chủ</a> ›
			<a href="<?php echo esc_url( pn_blog_url() ); ?>">Blog</a>
			<?php if ( $cats ) : ?>
				› <a href="<?php echo esc_url( get_category_link( $cats[0] ) ); ?>"><?php echo esc_html( $cats[0]->name ); ?></a>
			<?php endif; ?>
		</nav>
		<h1><?php the_title(); ?></h1>
		<p class="post-meta">
			<?php if ( $cats ) : ?><a class="post-cat" href="<?php echo esc_url( get_category_link( $cats[0] ) ); ?>"><?php echo esc_html( $cats[0]->name ); ?></a> · <?php endif; ?>
			Đăng <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( $published ); ?></time>
			<?php if ( $modified !== $published ) : ?> · Cập nhật <time datetime="<?php echo esc_attr( get_the_modified_date( 'c' ) ); ?>"><?php echo esc_html( $modified ); ?></time><?php endif; ?>
			<?php if ( get_the_author() ) : ?> · <?php echo esc_html( get_the_author() ); ?><?php endif; ?>
		</p>
		<?php if ( has_post_thumbnail() && ! pn_content_has_thumbnail() ) : ?>
			<div class="doc-thumb"><?php the_post_thumbnail( 'large' ); ?></div>
		<?php endif; ?>
		<div class="entry-content"><?php the_content(); ?></div>

		<aside class="post-cta">
			<div>
				<b>Có món cần pass hoặc đang cần tìm?</b>
				<span>Đăng tin miễn phí trên PassNhanh, hoặc đăng nhu cầu để được ghép tin phù hợp.</span>
			</div>
			<div class="post-cta-actions">
				<a class="btn btn-primary" href="<?php echo esc_url( pn_app_url( '/#/dang-pass' ) ); ?>">Đăng Pass miễn phí</a>
				<a class="btn btn-outline" href="<?php echo esc_url( pn_app_url( '/#/tim-pass' ) ); ?>">Tìm Pass</a>
			</div>
		</aside>
	</article>

	<?php
	$related = new WP_Query(
		array(
			'post_type'           => 'post',
			'posts_per_page'      => 3,
			'post__not_in'        => array( get_the_ID() ),
			'category__in'        => wp_list_pluck( $cats, 'term_id' ),
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);
	if ( $related->have_posts() ) :
		?>
	<section class="card related">
		<h2>Bài viết liên quan</h2>
		<div class="post-grid">
			<?php
			while ( $related->have_posts() ) :
				$related->the_post();
				get_template_part( 'template-parts/post-card' );
			endwhile;
			wp_reset_postdata();
			?>
		</div>
	</section>
	<?php endif; ?>
</main>
	<?php
endwhile;

get_footer();
