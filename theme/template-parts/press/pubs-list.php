<?php
/**
 * СМИ о нас: перечень публикаций в печатных изданиях.
 *
 * У этих материалов нет страницы в сети — только издание и выпуск,
 * поэтому показываем их списком, а не карточками.
 *
 * @package MPartners
 */

$mp_items = function_exists( 'mp_pubs_list' ) ? mp_pubs_list() : [];

if ( ! $mp_items ) {
	return;
}
?>
<section class="pubs-list">
	<div class="shell">
		<div class="pubs-list__head">
			<h2 class="h-section pubs-list__title">Другие публикации</h2>

			<div class="note pubs-list__note">
				<span class="note__icon"><?php mp_icon( 'info' ); ?></span>
				<p class="note__text">Статьи и комментарии адвокатов коллегии в печатных изданиях и отраслевых журналах: «Трудовое право», «Жилищное право», «Делопроизводство», «Управление персоналом» и других</p>
			</div>
		</div>

		<ul class="pubs-list__grid">
			<?php foreach ( $mp_items as $mp_item ) : ?>
				<li class="pubs-list__item">
					<span class="pubs-list__marker" aria-hidden="true"><?php mp_icon( 'polygon' ); ?></span>
					<span><?php echo esc_html( $mp_item ); ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
