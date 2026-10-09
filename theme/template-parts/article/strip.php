<?php
/**
 * Мероприятие / СМИ: тёмная полоса во всю ширину с названием.
 *
 * @package MPartners
 *
 * @var array $args Ожидает ключ `section`: events|media.
 */

$mp_key   = isset( $args['section'] ) ? $args['section'] : 'events';
$mp_strip = mp_article( 'strips.' . $mp_key );
?>
<section class="art-strip">
	<div class="shell">
		<p class="h-lawyer art-strip__title"><?php echo esc_html( $mp_strip ); ?></p>
	</div>
</section>
