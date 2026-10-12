<?php
/**
 * О компании: первый экран — фотография и заголовок.
 *
 * @package MPartners
 */

$mp_hero = mp_about( 'hero' );
?>
<section class="ab-hero">
	<div class="shell">
		<div class="ab-hero__photo">
			<img src="<?php echo esc_url( mp_img( $mp_hero['photo'] ) ); ?>" alt="" aria-hidden="true" decoding="async">
		</div>

		<h1 class="ab-hero__title"><?php echo esc_html( $mp_hero['title'] ); ?></h1>
	</div>
</section>
