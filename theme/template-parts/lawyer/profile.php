<?php
/**
 * Адвокат: фото, контактная плашка, специализация и биография по годам.
 *
 * @package MPartners
 */

// Биография из макета принадлежит одному адвокату: у остальных карточка
// собирается только из своих полей, иначе покажем чужие данные.
$mp_own = function_exists( 'mp_lawyer_demo_id' ) && is_singular( 'mp_lawyer' )
	&& get_the_ID() !== mp_lawyer_demo_id();

if ( $mp_own ) {
	$mp_lawyer = mp_lawyer_fields( get_the_ID() );
} else {
	$mp_lawyer = function_exists( 'mp_content' ) ? mp_content( 'lawyer' ) : mp_lawyer_data();
}

// У записи адвоката имя — заголовок, фото — изображение записи.
if ( is_singular( 'mp_lawyer' ) ) {
	$mp_lawyer['name']  = get_the_title();
	$mp_photo           = get_the_post_thumbnail_url( get_the_ID(), 'full' );
	$mp_lawyer['photo'] = $mp_photo ? $mp_photo : $mp_lawyer['photo'];
}
$mp_registry = $mp_lawyer['registry'];
$mp_spec     = $mp_lawyer['spec'];
$mp_has_spec = '' !== trim( (string) $mp_spec['title'] ) || ! empty( $mp_spec['items'] ) || '' !== trim( (string) $mp_spec['note'] );
?>
<section class="lw">
	<div class="shell">
		<div class="lw__inner">
			<img class="lw__photo" src="<?php echo esc_url( mp_img( $mp_lawyer['photo'], 'jpg' ) ); ?>" alt="<?php echo esc_attr( $mp_lawyer['name'] ); ?>" width="1214" height="1276" fetchpriority="high" decoding="async">

			<div class="lw__side">
				<div class="lw-card">
					<div class="lw-card__head">
						<h1 class="h-lawyer lw-card__name"><?php echo esc_html( $mp_lawyer['name'] ); ?></h1>
						<p class="lw-card__role"><?php echo esc_html( $mp_lawyer['role'] ); ?></p>
					</div>

					<div class="lw-card__contacts">
						<div class="lw-card__links">
							<?php if ( ! empty( $mp_lawyer['email'] ) ) : ?>
								<a class="lw-card__link" href="mailto:<?php echo esc_attr( $mp_lawyer['email'] ); ?>">
									<?php mp_icon( 'mail' ); ?>
									<span><?php echo esc_html( $mp_lawyer['email'] ); ?></span>
								</a>
							<?php endif; ?>

							<?php if ( ! empty( $mp_lawyer['phone'] ) ) : ?>
								<a class="lw-card__link" href="tel:<?php echo esc_attr( preg_replace( '/[^+\d]/', '', $mp_lawyer['phone'] ) ); ?>">
									<?php mp_icon( 'messenger' ); ?>
									<span><?php echo esc_html( $mp_lawyer['phone'] ); ?></span>
								</a>
							<?php endif; ?>
						</div>

						<?php
						// Визитка собирается на лету из полей карточки, поэтому
						// ссылка есть у каждого адвоката.
						$mp_card_label = ! empty( $mp_lawyer['card']['label'] ) ? $mp_lawyer['card']['label'] : 'Скачать визитку';
						$mp_card_href  = is_singular( 'mp_lawyer' ) && function_exists( 'mp_lawyer_vcard_url' )
							? mp_lawyer_vcard_url( get_the_ID() )
							: mp_link( $mp_lawyer['card'] );
						?>
						<?php if ( $mp_card_href ) : ?>
							<a class="lw-card__action" href="<?php echo esc_url( $mp_card_href ); ?>" download>
								<span><?php echo esc_html( $mp_card_label ); ?></span>
								<?php mp_icon( 'arrow-ne' ); ?>
							</a>
						<?php endif; ?>
					</div>

					<?php if ( ! empty( $mp_registry['number'] ) ) : ?>
						<div class="lw-card__registry">
							<p class="lw-card__registry-value">
								<span class="lw-card__registry-label"><?php echo esc_html( $mp_registry['label'] ); ?></span>
								<strong><?php echo esc_html( $mp_registry['number'] ); ?></strong>
							</p>
							<?php if ( ! empty( $mp_registry['link']['label'] ) ) : ?>
								<a class="lw-card__action" href="<?php echo esc_url( mp_link( $mp_registry['link'] ) ); ?>">
									<span><?php echo esc_html( $mp_registry['link']['label'] ); ?></span>
									<?php mp_icon( 'arrow-ne' ); ?>
								</a>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>

				<?php if ( $mp_has_spec ) : ?>
				<div class="lw-card lw-card--spec">
					<div class="lw-spec">
						<p class="lw-spec__title"><?php echo esc_html( $mp_spec['title'] ); ?></p>
						<ul class="lw-spec__list">
							<?php foreach ( $mp_spec['items'] as $mp_item ) : ?>
								<li class="lw-spec__item">
									<span class="lw-spec__marker" aria-hidden="true"><?php mp_icon( 'polygon' ); ?></span>
									<span><?php echo esc_html( $mp_item ); ?></span>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
					<?php if ( ! empty( $mp_spec['note'] ) ) : ?>
						<p class="lw-spec__note"><?php echo esc_html( $mp_spec['note'] ); ?></p>
					<?php endif; ?>
				</div>
				<?php endif; ?>

				<ol class="lw-time">
					<?php foreach ( (array) $mp_lawyer['timeline'] as $mp_step ) : ?>
						<li class="lw-time__item">
							<span class="lw-time__dot" aria-hidden="true"></span>
							<div class="lw-time__body">
								<p class="lw-time__year"><?php echo esc_html( $mp_step['year'] ); ?></p>
								<p class="lw-time__text"><?php echo esc_html( $mp_step['text'] ); ?></p>
							</div>
						</li>
					<?php endforeach; ?>
				</ol>
			</div>
		</div>
	</div>
</section>
