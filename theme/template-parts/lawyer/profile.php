<?php
/**
 * Адвокат: фото, контактная плашка, специализация и биография по годам.
 *
 * @package MPartners
 */

$mp_lawyer   = function_exists( 'mp_content' ) ? mp_content( 'lawyer' ) : mp_lawyer_data();

// У записи адвоката имя — заголовок, фото — изображение записи.
if ( is_singular( 'mp_lawyer' ) ) {
	$mp_lawyer['name']  = get_the_title();
	$mp_photo           = get_the_post_thumbnail_url( get_the_ID(), 'full' );
	$mp_lawyer['photo'] = $mp_photo ? $mp_photo : $mp_lawyer['photo'];
}
$mp_registry = $mp_lawyer['registry'];
$mp_spec     = $mp_lawyer['spec'];
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
							<a class="lw-card__link" href="mailto:<?php echo esc_attr( $mp_lawyer['email'] ); ?>">
								<?php mp_icon( 'mail' ); ?>
								<span><?php echo esc_html( $mp_lawyer['email'] ); ?></span>
							</a>
							<a class="lw-card__link" href="tel:<?php echo esc_attr( preg_replace( '/[^+\d]/', '', $mp_lawyer['phone'] ) ); ?>">
								<?php mp_icon( 'messenger' ); ?>
								<span><?php echo esc_html( $mp_lawyer['phone'] ); ?></span>
							</a>
						</div>

						<a class="lw-card__action" href="<?php echo esc_url( $mp_lawyer['card']['href'] ); ?>">
							<span><?php echo esc_html( $mp_lawyer['card']['label'] ); ?></span>
							<?php mp_icon( 'arrow-ne' ); ?>
						</a>
					</div>

					<div class="lw-card__registry">
						<p class="lw-card__registry-value">
							<span class="lw-card__registry-label"><?php echo esc_html( $mp_registry['label'] ); ?></span>
							<strong><?php echo esc_html( $mp_registry['number'] ); ?></strong>
						</p>
						<a class="lw-card__action" href="<?php echo esc_url( $mp_registry['link']['href'] ); ?>">
							<span><?php echo esc_html( $mp_registry['link']['label'] ); ?></span>
							<?php mp_icon( 'arrow-ne' ); ?>
						</a>
					</div>
				</div>

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
					<p class="lw-spec__note"><?php echo esc_html( $mp_spec['note'] ); ?></p>
				</div>

				<ol class="lw-time">
					<?php foreach ( $mp_lawyer['timeline'] as $mp_step ) : ?>
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
