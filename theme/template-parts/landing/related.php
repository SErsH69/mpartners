<?php
/**
 * Лендинг, секция 15: другие услуги практики.
 *
 * Список — остальные лендинги того же раздела хаба.
 *
 * @package MPartners
 */

$mp_related = mp_landing( 'related' );
$mp_group   = function_exists( 'get_field' ) ? (int) get_field( 'landing_group', get_the_ID() ) : 0;
$mp_hub     = function_exists( 'get_field' ) ? (int) get_field( 'landing_hub', get_the_ID() ) : 0;
$mp_items   = [];

foreach ( mp_landing_groups( $mp_hub ) as $mp_index => $mp_block ) {
	if ( $mp_index !== $mp_group ) {
		continue;
	}

	foreach ( (array) $mp_block['items'] as $mp_item ) {
		if ( ! empty( $mp_item['href'] ) && untrailingslashit( $mp_item['href'] ) === untrailingslashit( get_permalink() ) ) {
			continue; // Текущую услугу в списке не показываем.
		}

		$mp_items[] = $mp_item;
	}
}

// Пока других лендингов в разделе нет, показываем состав из макета.
if ( ! $mp_items ) {
	foreach ( mp_landings_fallback_items( $mp_group ) as $mp_label ) {
		if ( $mp_label === get_the_title() ) {
			continue;
		}

		$mp_items[] = [
			'label' => $mp_label,
			'href'  => '',
		];
	}
}

if ( ! $mp_items ) {
	return;
}
?>
<section class="ld-related">
	<div class="shell">
		<h2 class="ld-related__title"><?php echo esc_html( $mp_related['title'] ); ?></h2>

		<ul class="hub-group__grid ld-related__grid">
			<?php foreach ( $mp_items as $mp_item ) : ?>
				<li class="hub-card">
					<a class="hub-card__link" href="<?php echo esc_url( mp_link( $mp_item['href'] ) ); ?>">
						<span class="hub-card__title"><?php echo esc_html( $mp_item['label'] ); ?></span>
						<span class="hub-card__arrow"><?php mp_icon( 'arrow-right' ); ?></span>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
