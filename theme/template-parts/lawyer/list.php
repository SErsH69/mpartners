<?php
/**
 * Список адвокатов: тёмный баннер и сетка карточек.
 *
 * @package MPartners
 */

$mp_members = function_exists( 'mp_lawyer_cards' ) ? mp_lawyer_cards() : mp_data( 'team.members', [] );
?>
<section class="pr lw-list">
	<div class="shell">
		<div class="pr__banner">
			<h1 class="h-pr"><?php echo esc_html( get_the_title() ); ?></h1>
		</div>

		<ul class="lw-list__grid">
			<?php foreach ( $mp_members as $mp_member ) : ?>
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
						<a class="member__more" href="<?php echo esc_url( isset( $mp_member['href'] ) ? $mp_member['href'] : '#' ); ?>">
							<span><?php echo esc_html( isset( $mp_member['more'] ) ? $mp_member['more'] : 'Подробнее' ); ?></span>
							<?php mp_icon( 'arrow-se' ); ?>
						</a>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
