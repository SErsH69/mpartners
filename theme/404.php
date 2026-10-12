<?php
/**
 * Страница 404 — в оформлении разделов сайта.
 *
 * @package MPartners
 */

get_header();
?>
<section class="pr legal">
	<div class="shell">
		<div class="pr__head">
			<div class="pr__banner">
				<h1 class="h-pr pr__title">Страница не найдена</h1>
			</div>
		</div>

		<div class="legal__body">
			<p>Такой страницы на сайте нет: возможно, адрес набран с ошибкой или материал переехал.</p>
			<p>Вернитесь на главную или воспользуйтесь поиском — мы поможем найти нужный раздел.</p>

			<div class="btn-pair legal__actions">
				<a class="btn" href="<?php echo esc_url( home_url( '/' ) ); ?>">На главную</a>
				<a class="btn btn--surface" href="<?php echo esc_url( mp_link( '', 'contacts' ) ); ?>">Связаться с нами</a>
			</div>
		</div>
	</div>
</section>
<?php
get_footer();
