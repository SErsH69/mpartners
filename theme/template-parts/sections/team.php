<?php
/**
 * 13. Команда.
 *
 * @package MPartners
 */

$mp_team = mp_data( 'team' );
?>
<section class="team">
	<div class="team__inner">
		<div class="team__intro">
			<h2 class="h-section team__title"><?php echo esc_html( $mp_team['title'] ); ?></h2>
			<div class="note team__note">
				<span class="note__icon"><?php mp_icon( 'info' ); ?></span>
				<p class="note__text"><?php mp_nl2br( $mp_team['note'] ); ?></p>
			</div>
			<div class="btn-pair team__actions">
				<a class="btn" href="<?php echo esc_url( $mp_team['cta']['href'] ); ?>"><?php echo esc_html( $mp_team['cta']['label'] ); ?></a>
				<a class="btn-icon" href="<?php echo esc_url( $mp_team['cta']['href'] ); ?>" aria-hidden="true" tabindex="-1"><?php mp_icon( 'plus' ); ?></a>
			</div>
		</div>

		<ul class="team__track" data-drag-scroll data-dots="team-dots">
			<?php foreach ( $mp_team['members'] as $mp_member ) : ?>
				<li class="member">
					<div class="member__photo">
						<img class="member__glow" src="<?php echo esc_url( mp_img( 'decor-member', 'svg' ) ); ?>" alt="" aria-hidden="true" loading="lazy" decoding="async">
						<img class="member__portrait" src="<?php echo esc_url( mp_img( $mp_member['photo'] ) ); ?>" alt="<?php echo esc_attr( $mp_member['name'] ); ?>" loading="lazy" decoding="async">
					</div>
					<div class="member__body">
						<div class="member__text">
							<p class="member__name"><?php echo esc_html( $mp_member['name'] ); ?></p>
							<p class="member__bio"><?php echo esc_html( $mp_member['text'] ); ?></p>
						</div>
						<a class="member__more" href="#">
							<span><?php echo esc_html( $mp_member['more'] ); ?></span>
							<?php mp_icon( 'arrow-se' ); ?>
						</a>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>

		<div class="dots dots--light team__dots" id="team-dots" aria-hidden="true">
			<?php foreach ( $mp_team['members'] as $mp_index => $mp_member ) : ?>
				<span class="dots__dot<?php echo 0 === $mp_index ? ' is-active' : ''; ?>"></span>
			<?php endforeach; ?>
		</div>
	</div>
</section>
