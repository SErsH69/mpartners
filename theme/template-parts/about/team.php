<?php
/**
 * О компании: сетка адвокатов.
 *
 * @package MPartners
 */

$mp_team    = mp_about( 'team' );
$mp_members = function_exists( 'mp_lawyer_cards' ) ? mp_lawyer_cards( 0, -1 ) : [];
?>
<section class="ab-team">
	<div class="shell">
		<div class="ab-team__head">
			<h2 class="ab-team__title"><?php echo esc_html( $mp_team['title'] ); ?></h2>

			<div class="ab-team__note">
				<span class="ab-team__note-icon"><?php mp_icon( 'info' ); ?></span>
				<p class="ab-team__note-text"><?php echo esc_html( $mp_team['note'] ); ?></p>
			</div>
		</div>

		<ul class="ab-team__grid">
			<?php foreach ( $mp_members as $mp_member ) : ?>
				<li class="member ab-team__member">
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
</section>
