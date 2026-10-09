<?php
/**
 * Дело: первый экран — бежевая плашка с текстом и обложкой.
 *
 * @package MPartners
 */

$mp_hero = mp_case( 'hero' );
?>
<section class="case-hero">
	<div class="shell">
		<div class="case-hero__panel">
			<div class="case-hero__copy">
				<p class="case-hero__badge">
					<span class="case-hero__badge-icon"><?php mp_icon( 'shield' ); ?></span>
					<span><?php echo esc_html( $mp_hero['badge'] ); ?></span>
				</p>

				<div class="case-hero__text">
					<h1 class="h-case case-hero__title"><?php echo esc_html( $mp_hero['title'] ); ?></h1>
					<p class="case-hero__lead"><?php echo esc_html( $mp_hero['text'] ); ?></p>
				</div>
			</div>

			<div class="case-hero__cover">
				<img src="<?php echo esc_url( mp_img( $mp_hero['image'], 'jpg' ) ); ?>" alt="" width="1186" height="650" fetchpriority="high" decoding="async">
			</div>
		</div>
	</div>
</section>
