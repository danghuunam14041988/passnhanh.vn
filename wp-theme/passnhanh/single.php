<?php
/**
 * Một bài viết.
 */

get_header();

while ( have_posts() ) :
	the_post();
	?>
<main class="container page" id="main">
	<article <?php post_class( 'card doc-card' ); ?>>
		<nav class="breadcrumb" aria-label="Đường dẫn">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Trang chủ</a> › <a href="<?php echo esc_url( pn_blog_url() ); ?>">Blog</a>
		</nav>
		<h1><?php the_title(); ?></h1>
		<p class="post-meta"><?php echo esc_html( get_the_date() ); ?></p>
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="doc-thumb"><?php the_post_thumbnail( 'large' ); ?></div>
		<?php endif; ?>
		<div class="entry-content"><?php the_content(); ?></div>
	</article>
</main>
	<?php
endwhile;

get_footer();
