<?php
/**
 * Хаб услуг: разделы со списком лендингов.
 *
 * @package MPartners
 */
?>
<section class="hub-groups">
	<div class="shell">
		<?php foreach ( mp_landing_groups() as $mp_group ) : ?>
			<div class="hub-group">
				<h2 class="hub-group__title"><?php echo esc_html( $mp_group['title'] ); ?></h2>

				<ul class="hub-group__grid">
					<?php foreach ( (array) $mp_group['items'] as $mp_item ) : ?>
						<li class="hub-card">
							<a class="hub-card__link" href="<?php echo esc_url( mp_link( $mp_item['href'] ) ); ?>">
								<span class="hub-card__title"><?php echo esc_html( $mp_item['label'] ); ?></span>
								<span class="hub-card__arrow"><?php mp_icon( 'arrow-right' ); ?></span>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endforeach; ?>
	</div>
</section>
