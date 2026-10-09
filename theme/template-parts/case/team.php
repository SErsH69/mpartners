<?php
/**
 * Дело: «Над делом работали» — те же карточки, что в команде на главной.
 *
 * @package MPartners
 */

$mp_members = function_exists( 'mp_lawyer_cards' ) ? mp_lawyer_cards( get_the_ID() ) : mp_data( 'team.members', [] );
?>
<section class="case-team">
	<div class="shell">
		<h2 class="h-case-team case-team__title"><?php echo esc_html( mp_case( 'team.title' ) ); ?></h2>

		<ul class="case-team__track" data-drag-scroll>
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
							<span><?php echo esc_html( $mp_member['more'] ); ?></span>
							<?php mp_icon( 'arrow-se' ); ?>
						</a>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
