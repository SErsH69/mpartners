<?php
/**
 * Статья: часто задаваемые вопросы.
 *
 * @package MPartners
 */

$mp_faq = mp_article( 'faq' );

// У перенесённых материалов свой текст: вопросы из макета к ним не
// относятся, поэтому блок показываем только там, где текста нет.
if ( is_singular() && '' !== trim( (string) get_post_field( 'post_content', get_the_ID() ) ) ) {
	return;
}
?>
<section class="art-faq">
	<div class="shell shell--wide">
		<div class="art-faq__panel">
			<h2 class="h-lawyer art-faq__title"><?php echo esc_html( $mp_faq['title'] ); ?></h2>

			<ul class="art-faq__grid">
				<?php foreach ( $mp_faq['items'] as $mp_question ) : ?>
					<li class="art-faq__item"><?php echo esc_html( $mp_question ); ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</section>
