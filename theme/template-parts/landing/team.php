<?php
/**
 * Лендинг, секция 08: команда адвокатов.
 *
 * Карточки — записи «Адвокаты», оформление общее с главной страницей.
 *
 * @package MPartners
 */

$mp_team    = mp_landing( 'team' );
$mp_members = function_exists( 'mp_lawyer_cards' ) ? mp_lawyer_cards( 0, -1 ) : [];
?>
<section class="ld-team">
	<div class="shell">
		<div class="ld-team__inner">
			<div class="ld-team__intro">
				<h2 class="ld-team__title"><?php echo esc_html( $mp_team['title'] ); ?></h2>

				<div class="ld-team__note">
					<span class="ld-team__note-icon"><?php mp_icon( 'info' ); ?></span>
					<p class="ld-team__note-text"><?php echo esc_html( $mp_team['note'] ); ?></p>
				</div>

				<div class="btn-pair ld-team__actions">
					<a class="btn" href="<?php echo esc_url( mp_link( '', 'lawyers' ) ); ?>"><?php echo esc_html( $mp_team['cta'] ); ?></a>
					<a class="btn-icon" href="<?php echo esc_url( mp_link( '', 'lawyers' ) ); ?>" aria-hidden="true" tabindex="-1"><?php mp_icon( 'plus' ); ?></a>
				</div>
			</div>

			<ul class="ld-team__track" data-drag-scroll>
				<?php foreach ( $mp_members as $mp_member ) : ?>
					<li class="member ld-team__member">
						<div class="member__photo">
							<img class="member__glow" src="<?php echo esc_url( mp_img( 'decor-member', 'svg' ) ); ?>" alt="" aria-hidden="true" loading="lazy" decoding="async">
							<img class="member__portrait" src="<?php echo esc_url( mp_img( $mp_member['photo'] ) ); ?>" alt="<?php echo esc_attr( $mp_member['name'] ); ?>" loading="lazy" decoding="async">
						</div>
						<div class="member__body">
							<div class="member__text">
								<p class="member__name"><?php echo esc_html( $mp_member['name'] ); ?></p>
								<p class="member__bio"><?php echo esc_html( $mp_member['text'] ); ?></p>
							</div>
							<a class="member__more" href="<?php echo esc_url( isset( $mp_member['href'] ) ? $mp_member['href'] : mp_link( '', 'lawyers' ) ); ?>">
								<span><?php echo esc_html( isset( $mp_member['more'] ) ? $mp_member['more'] : 'Подробнее' ); ?></span>
								<?php mp_icon( 'arrow-se' ); ?>
							</a>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</section>
