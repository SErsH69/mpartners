<?php
/**
 * Контакты: баннер с заголовком и карточки офисов.
 *
 * @package MPartners
 */

$mp_page = mp_contacts();
?>
<section class="ct">
	<div class="shell">
		<div class="ct__banner">
			<h1 class="ct__title"><?php echo esc_html( $mp_page['title'] ); ?></h1>
		</div>

		<ul class="ct__grid">
			<?php foreach ( (array) $mp_page['offices'] as $mp_office ) : ?>
				<li class="ct-office">
					<h2 class="ct-office__title"><?php echo esc_html( $mp_office['title'] ); ?></h2>

					<ul class="ct-office__lines">
						<?php if ( ! empty( $mp_office['phone'] ) ) : ?>
							<li class="ct-office__line ct-office__line--phone">
								<span class="ct-office__icon"><?php mp_icon( 'phone' ); ?></span>
								<a href="tel:<?php echo esc_attr( preg_replace( '~\D~', '', $mp_office['phone'] ) ); ?>"><?php echo esc_html( $mp_office['phone'] ); ?></a>
							</li>
						<?php endif; ?>

						<?php if ( ! empty( $mp_office['email'] ) ) : ?>
							<li class="ct-office__line ct-office__line--mail">
								<span class="ct-office__icon"><?php mp_icon( 'mail' ); ?></span>
								<a href="mailto:<?php echo esc_attr( $mp_office['email'] ); ?>"><?php echo esc_html( $mp_office['email'] ); ?></a>
							</li>
						<?php endif; ?>

						<?php if ( ! empty( $mp_office['address'] ) ) : ?>
							<li class="ct-office__line ct-office__line--address">
								<span class="ct-office__icon"><?php mp_icon( 'pin' ); ?></span>
								<span><?php echo esc_html( $mp_office['address'] ); ?></span>
							</li>
						<?php endif; ?>
					</ul>

					<?php if ( ! empty( $mp_office['map'] ) ) : ?>
						<div class="ct-office__map">
							<iframe src="<?php echo esc_url( $mp_office['map'] ); ?>" title="<?php echo esc_attr( $mp_office['title'] ); ?>" loading="lazy" allowfullscreen></iframe>
						</div>
					<?php else : ?>
						<?php // Карта не подключена — показываем ссылку на маршрут, без сторонних скриптов. ?>
						<a class="ct-office__map ct-office__map--empty" href="<?php echo esc_url( $mp_office['route'] ); ?>" target="_blank" rel="noopener">
							<span class="ct-office__map-icon"><?php mp_icon( 'pin' ); ?></span>
							<span class="ct-office__map-label">Посмотреть на карте</span>
						</a>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
