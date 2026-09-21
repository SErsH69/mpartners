<?php
/**
 * 05. Специализация.
 *
 * @package MPartners
 */

$mp_ind = mp_data( 'industries' );
?>
<section class="industries">
	<div class="industries__head">
		<h2 class="h-section h-section--light industries__title"><?php echo esc_html( $mp_ind['title'] ); ?></h2>
		<div class="note note--dark industries__note">
			<span class="note__icon"><?php mp_icon( 'info' ); ?></span>
			<p class="note__text"><?php echo esc_html( $mp_ind['note'] ); ?></p>
		</div>
	</div>

	<ul class="industries__track" data-drag-scroll data-center-mobile>
		<?php
		// В макете набор карточек повторён дважды, чтобы лента не обрывалась.
		$mp_count = count( $mp_ind['items'] );
		foreach ( array_merge( $mp_ind['items'], $mp_ind['items'] ) as $mp_i => $mp_item ) :
			?>
			<li class="industry-card"<?php echo $mp_i >= $mp_count ? ' aria-hidden="true"' : ''; ?>>
				<img class="industry-card__image" src="<?php echo esc_url( mp_img( $mp_item['image'] ) ); ?>" alt="" loading="lazy" decoding="async">
				<p class="industry-card__title">
					<?php mp_icon( 'polygon', 'industry-card__marker' ); ?>
					<span><?php mp_nl2br( $mp_item['title'] ); ?></span>
				</p>
			</li>
		<?php endforeach; ?>
	</ul>
</section>
