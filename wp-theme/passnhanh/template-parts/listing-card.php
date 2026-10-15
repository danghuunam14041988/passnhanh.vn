<?php
/**
 * Thẻ một Tin Pass. Nhận $args['item'] từ pn_listing_data() hoặc tin minh họa.
 */

$item = $args['item'];
$off  = pn_off( $item );
?>
<article class="listing" data-city="<?php echo esc_attr( $item['city'] ); ?>" data-off="<?php echo esc_attr( $off ); ?>" data-hours="<?php echo esc_attr( $item['hours'] ); ?>" data-urgent="<?php echo $item['urgent'] ? '1' : '0'; ?>">
	<div class="l-media">
		<?php if ( $item['img'] ) : ?>
			<img src="<?php echo esc_url( $item['img'] ); ?>" alt="" loading="lazy">
		<?php else : ?>
			<span class="l-ph c-<?php echo esc_attr( $item['color'] ?? 'blue' ); ?>" aria-hidden="true"><?php echo esc_html( $item['icon'] ?? '📦' ); ?></span>
		<?php endif; ?>
		<?php if ( $item['urgent'] ) : ?><span class="l-urgent">Cần Pass Gấp</span><?php endif; ?>
		<button class="l-heart" type="button" data-id="<?php echo esc_attr( md5( $item['url'] . $item['title'] ) ); ?>" aria-pressed="false" aria-label="Lưu tin">
			<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 20s-7-4.4-7-10a4 4 0 0 1 7-2.6A4 4 0 0 1 19 10c0 5.6-7 10-7 10z"/></svg>
		</button>
		<?php if ( $item['left'] ) : ?><span class="l-left">⏱ <?php echo esc_html( $item['left'] ); ?></span><?php endif; ?>
	</div>
	<div class="l-body">
		<h3><a href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['title'] ); ?></a></h3>
		<?php if ( $item['city'] ) : ?><p class="l-city">📍 <?php echo esc_html( $item['city'] ); ?></p><?php endif; ?>
		<div class="l-price">
			<?php if ( $off ) : ?><s><?php echo esc_html( pn_money( $item['old'] ) ); ?></s><?php endif; ?>
			<b><?php echo esc_html( $item['price'] ? pn_money( $item['price'] ) : 'Liên hệ' ); ?></b>
			<?php if ( $off ) : ?><span class="l-off">-<?php echo esc_html( $off ); ?>%</span><?php endif; ?>
		</div>
	</div>
</article>
