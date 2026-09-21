<?php
/**
 * 04. Помощь.
 *
 * @package MPartners
 */

$mp_help = mp_data( 'help' );
?>
<section class="help">
	<div class="help__inner">
		<header class="section-head">
			<h2 class="h-section"><?php echo esc_html( $mp_help['title'] ); ?></h2>
			<div class="note section-head__note">
				<span class="note__icon"><?php mp_icon( 'info' ); ?></span>
				<p class="note__text"><?php echo esc_html( $mp_help['note'] ); ?></p>
			</div>
		</header>

		<ul class="help__list">
			<?php foreach ( $mp_help['items'] as $mp_item ) : ?>
				<li class="help-card">
					<img class="help-card__image" src="<?php echo esc_url( mp_img( $mp_item['image'] ) ); ?>" alt="" loading="lazy" decoding="async">
					<span class="help-card__number"><?php echo esc_html( $mp_item['number'] ); ?></span>
					<div class="help-card__body">
						<h3 class="help-card__title"><?php echo esc_html( $mp_item['title'] ); ?></h3>
						<div class="chip-list">
							<?php foreach ( $mp_item['services'] as $mp_service ) : ?>
								<a class="chip-link" href="#">
									<span><?php echo esc_html( $mp_service ); ?></span>
									<?php mp_icon( 'arrow-ne' ); ?>
								</a>
							<?php endforeach; ?>
						</div>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
