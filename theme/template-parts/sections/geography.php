<?php
/**
 * 14. География.
 *
 * @package MPartners
 */

$mp_geo = mp_data( 'geography' );
?>
<section class="geography">
	<div class="geography__inner">
		<div class="geography__content">
			<h2 class="h-section geography__title">
				<?php
				// В мобильном макете «M-PARTNERS» начинается с новой строки.
				echo str_replace( 'адвокатов M-PARTNERS', 'адвокатов<br class="u-br-mobile"> M-PARTNERS', esc_html( $mp_geo['title'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above
				?>
			</h2>
			<p class="geography__note"><?php echo esc_html( $mp_geo['note'] ); ?></p>
			<ul class="geography__cities">
				<?php
				// Индексы городов, после которых на десктопе начинается новая строка.
				$mp_breaks = [];
				$mp_sum    = 0;
				foreach ( $mp_geo['rows_desktop'] ?? [] as $mp_count ) {
					$mp_sum     += $mp_count;
					$mp_breaks[] = $mp_sum - 1;
				}
				?>
				<?php foreach ( $mp_geo['cities'] as $mp_i => $mp_city ) : ?>
					<?php
					$mp_t = array_search( $mp_city, $mp_geo['order_tablet'] ?? [], true );
					$mp_m = array_search( $mp_city, $mp_geo['order_mobile'] ?? [], true );
					?>
					<li class="chip-city" style="--t-order: <?php echo (int) $mp_t; ?>; --m-order: <?php echo (int) $mp_m; ?>">
						<?php mp_icon( 'pin', 'chip-city__pin' ); ?>
						<span><?php echo esc_html( $mp_city ); ?></span>
					</li>
					<?php if ( in_array( $mp_i, $mp_breaks, true ) && $mp_i < count( $mp_geo['cities'] ) - 1 ) : ?>
						<li class="geography__break" aria-hidden="true"></li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		</div>

		<img class="geography__map" src="<?php echo esc_url( mp_img( 'map-russia', 'svg' ) ); ?>" alt="Карта России" width="978" height="505" loading="lazy" decoding="async">
	</div>
</section>
