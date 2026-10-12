<?php
/**
 * Тёмный баннер с заголовком страницы.
 *
 * @package MPartners
 *
 * @var array $args Ожидает ключ `title`.
 */

$mp_title = isset( $args['title'] ) ? $args['title'] : get_the_title();
?>
<div class="ct__banner">
	<h1 class="ct__title"><?php echo esc_html( $mp_title ); ?></h1>
</div>
