<?php
/**
 * Внутренняя страница: шапка статьи, оглавление, текст и форма заявки.
 *
 * @package MPartners
 */

$mp_art = function_exists( 'mp_content' ) ? mp_content( 'article' ) : mp_article_data();
?>
<section class="art">
	<div class="shell">
		<div class="art__hero">
			<p class="case-hero__badge art__badge">
				<span class="case-hero__badge-icon"><?php mp_icon( 'shield' ); ?></span>
				<span><?php echo esc_html( $mp_art['badge'] ); ?></span>
			</p>

			<div class="art__heading">
				<h1 class="h-case art__title"><?php echo esc_html( $mp_art['title'] ); ?></h1>
				<p class="art__meta">
					<span class="art__meta-item">
						<?php mp_icon( 'info' ); ?>
						<span><?php echo esc_html( $mp_art['date'] ); ?></span>
					</span>
					<span class="art__meta-item">
						<?php mp_icon( 'question' ); ?>
						<span><?php echo esc_html( $mp_art['reading'] ); ?></span>
					</span>
				</p>
			</div>
		</div>

		<div class="art__cols">
			<div class="art__main">
				<ul class="art__toc">
					<?php foreach ( $mp_art['toc'] as $mp_point ) : ?>
						<li class="lw-spec__item art__toc-item">
							<span class="lw-spec__marker" aria-hidden="true"><?php mp_icon( 'polygon' ); ?></span>
							<span><?php echo esc_html( $mp_point ); ?></span>
						</li>
					<?php endforeach; ?>
				</ul>

				<article class="art__body">
					<?php foreach ( $mp_art['sections'] as $mp_section ) : ?>
						<div class="art-sec">
							<p class="case-block__label art-sec__label">
								<span class="case-block__icon"><?php mp_icon( 'info' ); ?></span>
								<span><?php echo esc_html( $mp_section['label'] ); ?></span>
							</p>

							<?php foreach ( $mp_section['blocks'] as $mp_block ) : ?>
								<?php if ( 'callout' === $mp_block['type'] ) : ?>
									<p class="art-sec__callout"><?php echo esc_html( $mp_block['text'] ); ?></p>
								<?php else : ?>
									<p class="art-sec__text<?php echo 'muted' === $mp_block['type'] ? ' art-sec__text--muted' : ''; ?>"><?php echo esc_html( $mp_block['text'] ); ?></p>
								<?php endif; ?>
							<?php endforeach; ?>
						</div>
					<?php endforeach; ?>
				</article>
			</div>

			<aside class="art__aside">
				<?php mp_cf7( 'case', 'pg-form pg-form--case' ); ?>
			</aside>
		</div>
	</div>
</section>
