<?php
/**
 * Дело: первый экран — бежевая плашка с текстом и обложкой.
 *
 * @package MPartners
 */

$mp_hero = mp_case( 'hero' );

// На странице услуги заголовок, текст и обложка — у самой записи.
$mp_is_post = is_singular( 'mp_practice' );
$mp_title   = $mp_is_post ? get_the_title() : $mp_hero['title'];
$mp_lead    = $mp_is_post && function_exists( 'get_field' ) ? (string) get_field( 'practice_text' ) : $mp_hero['text'];
$mp_cover   = $mp_is_post ? get_the_post_thumbnail_url( get_the_ID(), 'full' ) : '';

if ( ! $mp_cover ) {
	$mp_cover = $mp_hero['image'];
}

if ( ! $mp_lead ) {
	$mp_lead = $mp_hero['text'];
}
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
					<h1 class="h-case case-hero__title"><?php echo esc_html( $mp_title ); ?></h1>
					<p class="case-hero__lead"><?php echo esc_html( $mp_lead ); ?></p>
				</div>
			</div>

			<div class="case-hero__cover">
				<img src="<?php echo esc_url( mp_img( $mp_cover, 'jpg' ) ); ?>" alt="" width="1186" height="650" fetchpriority="high" decoding="async">
			</div>
		</div>
	</div>
</section>
