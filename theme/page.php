<?php
/**
 * Обычная страница: правовые документы и всё, что редактируется текстом.
 *
 * @package MPartners
 */

get_header();
?>
<section class="pr legal">
	<div class="shell">
		<div class="pr__head">
			<div class="pr__banner">
				<h1 class="h-pr pr__title"><?php the_title(); ?></h1>
			</div>
		</div>

		<article class="legal__body">
			<?php
			while ( have_posts() ) :
				the_post();
				the_content();
			endwhile;
			?>
		</article>
	</div>
</section>
<?php
get_footer();
