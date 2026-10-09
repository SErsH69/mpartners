<?php
/**
 * Список материалов раздела: баннер, фильтры, сетка карточек и кнопка.
 *
 * @package MPartners
 *
 * @var array $args Ожидает ключ `section`: press|events|media.
 */

$mp_key     = isset( $args['section'] ) ? $args['section'] : 'press';
$mp_section = mp_press( $mp_key );

// Материалы и рубрики — из записей раздела; из макета, пока записей нет.
$mp_items   = function_exists( 'mp_section_cards' ) ? mp_section_cards( $mp_key ) : (array) $mp_section['items'];
$mp_terms   = function_exists( 'mp_section_filters' ) ? mp_section_filters( $mp_key ) : [];
$mp_filters = [ [ 'label' => $mp_section['filters'][0], 'href' => '' ] ];

foreach ( $mp_terms ? $mp_terms : array_slice( (array) $mp_section['filters'], 1 ) as $mp_term ) {
	$mp_filters[] = is_array( $mp_term ) ? $mp_term : [
		'label' => $mp_term,
		'href'  => '',
	];
}
?>
<section class="pr">
	<div class="shell">
		<div class="pr__head">
			<div class="pr__banner">
				<h1 class="h-pr pr__title"><?php echo esc_html( $mp_section['title'] ); ?></h1>
			</div>

			<ul class="pr__filters">
				<?php foreach ( $mp_filters as $mp_index => $mp_filter ) : ?>
					<li class="pr-chip<?php echo 0 === $mp_index ? ' pr-chip--active' : ''; ?>">
						<a href="<?php echo esc_url( $mp_filter['href'] ? $mp_filter['href'] : get_permalink() ); ?>"><?php echo esc_html( $mp_filter['label'] ); ?></a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>

		<ul class="pr__grid">
			<?php foreach ( $mp_items as $mp_item ) : ?>
				<?php $mp_lead = ! empty( $mp_item['lead'] ); ?>
				<li class="post-card pr-card <?php echo $mp_lead ? 'post-card--dark pr-card--lead' : 'post-card--light'; ?>">
					<?php if ( $mp_lead ) : ?>
						<img class="post-card__decor" src="<?php echo esc_url( mp_img( 'decor-blog', 'svg' ) ); ?>" alt="" aria-hidden="true" loading="lazy" decoding="async">
					<?php endif; ?>
					<a class="post-card__link" href="<?php echo esc_url( isset( $mp_item['href'] ) ? $mp_item['href'] : home_url( '/article/' ) ); ?>">
						<span class="post-card__category"><?php echo esc_html( $mp_item['category'] ); ?></span>
						<span class="post-card__main">
							<span class="post-card__text">
								<span class="post-card__title"><?php echo esc_html( $mp_item['title'] ); ?></span>
								<span class="post-card__excerpt"><?php echo esc_html( $mp_item['excerpt'] ); ?></span>
							</span>
							<span class="post-card__date"><?php echo esc_html( $mp_item['date'] ); ?></span>
						</span>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>

		<button class="btn btn--block pr__more" type="button"><?php echo esc_html( $mp_section['more'] ); ?></button>
	</div>
</section>
