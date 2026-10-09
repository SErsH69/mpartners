<?php
/**
 * Результаты поиска — в оформлении разделов «Пресс-центр» / «Услуги».
 *
 * @package MPartners
 */

get_header();

$mp_query = get_search_query();
$mp_total = (int) $GLOBALS['wp_query']->found_posts;
?>
<section class="pr pr--search">
	<div class="shell">
		<div class="pr__head">
			<div class="pr__banner">
				<h1 class="h-pr pr__title">Поиск по сайту</h1>
			</div>

			<form class="search-form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<span class="search-form__icon" aria-hidden="true"><?php mp_icon( 'search' ); ?></span>
				<input class="search-form__input" type="search" name="s" value="<?php echo esc_attr( $mp_query ); ?>" placeholder="Что ищете?" aria-label="Поиск по сайту">
				<button class="btn search-form__submit" type="submit">Найти</button>
			</form>

			<?php if ( '' !== $mp_query ) : ?>
				<p class="pr__summary">
					<?php
					printf(
						'По запросу «%1$s» — %2$s',
						esc_html( $mp_query ),
						esc_html( $mp_total . ' ' . mp_plural( $mp_total, 'материал', 'материала', 'материалов' ) )
					);
					?>
				</p>
			<?php endif; ?>
		</div>

		<?php if ( have_posts() ) : ?>
			<ul class="pr__grid">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<li class="post-card pr-card post-card--light">
						<a class="post-card__link" href="<?php the_permalink(); ?>">
							<span class="post-card__category"><?php echo esc_html( mp_search_label( get_post() ) ); ?></span>
							<span class="post-card__main">
								<span class="post-card__text">
									<span class="post-card__title"><?php the_title(); ?></span>
									<span class="post-card__excerpt"><?php echo esc_html( wp_trim_words( wp_strip_all_tags( get_the_excerpt() ), 22 ) ); ?></span>
								</span>
								<span class="post-card__date"><?php echo esc_html( get_the_date( 'd.m.Y' ) ); ?></span>
							</span>
						</a>
					</li>
				<?php endwhile; ?>
			</ul>

			<?php
			$mp_pager = paginate_links(
				[
					'type'      => 'list',
					'prev_text' => 'Назад',
					'next_text' => 'Вперёд',
				]
			);
			?>
			<?php if ( $mp_pager ) : ?>
				<nav class="pr__pager" aria-label="Страницы результатов"><?php echo wp_kses_post( $mp_pager ); ?></nav>
			<?php endif; ?>
		<?php else : ?>
			<p class="pr__empty">
				<?php if ( '' === $mp_query ) : ?>
					Введите запрос — найдём услуги, адвокатов, новости и материалы.
				<?php else : ?>
					Ничего не нашлось. Попробуйте другой запрос или позвоните нам — подскажем.
				<?php endif; ?>
			</p>
		<?php endif; ?>
	</div>
</section>
<?php
get_template_part( 'template-parts/sections/form' );

get_footer();
