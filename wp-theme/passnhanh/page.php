<?php
/**
 * Trang thường (Điều khoản, Quyền riêng tư…).
 */

get_header();

while ( have_posts() ) :
	the_post();
	?>
<main class="container page" id="main">
	<article <?php post_class( 'card doc-card' ); ?>>
		<h1><?php the_title(); ?></h1>
		<div class="entry-content"><?php the_content(); ?></div>
	</article>
</main>
	<?php
endwhile;

get_footer();
