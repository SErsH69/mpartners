<?php
/**
 * Партнерство: баннер, вступление и лента логотипов.
 *
 * @package MPartners
 */

$mp_lead  = mp_partners( 'lead' );
$mp_logos = (array) mp_partners( 'logos', [] );
?>
<section class="pn">
	<div class="shell">
		<?php get_template_part( 'template-parts/page-banner', null, [ 'title' => mp_partners( 'title' ) ] ); ?>

		<div class="pn__head">
			<h2 class="pn__title"><?php echo esc_html( $mp_lead['title'] ); ?></h2>

			<div class="pn__note">
				<span class="pn__note-icon"><?php mp_icon( 'info' ); ?></span>
				<p class="pn__note-text"><?php echo esc_html( $mp_lead['note'] ); ?></p>
			</div>
		</div>
	</div>

	<ul class="pn__logos" data-drag-scroll>
		<?php foreach ( $mp_logos as $mp_logo ) : ?>
			<li class="ld-logo">
				<img src="<?php echo esc_url( mp_img( $mp_logo ) ); ?>" alt="" aria-hidden="true" loading="lazy" decoding="async">
			</li>
		<?php endforeach; ?>
	</ul>
</section>
