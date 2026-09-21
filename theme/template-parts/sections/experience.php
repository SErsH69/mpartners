<?php
/**
 * 07. Опыт.
 *
 * @package MPartners
 */

$mp_exp = mp_data( 'experience' );
?>
<section class="experience">
	<div class="experience__inner">
		<div class="experience__top">
			<div class="experience__intro">
				<blockquote class="experience__quote">
					<?php
					// В макете последние строки цитаты подсвечены бежевой плашкой.
					$mp_highlight = $mp_exp['highlight'];
					$mp_pos       = $mp_highlight ? mb_strpos( $mp_exp['quote'], $mp_highlight ) : false;

					if ( false !== $mp_pos ) {
						echo esc_html( mb_substr( $mp_exp['quote'], 0, $mp_pos ) );
						echo '<mark class="experience__mark">' . esc_html( $mp_highlight ) . '</mark>';
					} else {
						echo esc_html( $mp_exp['quote'] );
					}
					?>
				</blockquote>
				<p class="experience__text"><?php echo esc_html( $mp_exp['text'] ); ?></p>
				<div class="btn-pair experience__actions">
					<a class="btn" href="<?php echo esc_url( $mp_exp['cta']['href'] ); ?>"><?php echo esc_html( $mp_exp['cta']['label'] ); ?></a>
					<a class="btn-icon" href="<?php echo esc_url( $mp_exp['cta']['href'] ); ?>" aria-hidden="true" tabindex="-1"><?php mp_icon( 'plus' ); ?></a>
				</div>
			</div>
			<img class="experience__photo" src="<?php echo esc_url( mp_img( 'experience-team' ) ); ?>" alt="Адвокаты коллегии M-PARTNERS" width="1028" height="574" loading="lazy" decoding="async">
		</div>

		<div class="experience__panel">
			<img class="experience__decor" src="<?php echo esc_url( mp_img( 'decor-swoosh', 'svg' ) ); ?>" alt="" aria-hidden="true" loading="lazy" decoding="async">

			<div class="experience__cards">
				<div class="experience__card">
					<?php
					// В макете у текста три размера: «M-PARTNERS», тире и остальной текст.
					$mp_award_rest = ltrim( preg_replace( '/^-\s*/u', '', $mp_exp['award_text'] ) );
					?>
					<p class="experience__award">
						<span class="experience__award-lead"><?php echo esc_html( $mp_exp['award_lead'] ); ?></span>
						<span class="experience__award-dash">-</span>
						<span class="experience__award-rest"><?php echo esc_html( $mp_award_rest ); ?></span>
					</p>
					<a class="experience__video" href="<?php echo esc_url( $mp_exp['video']['href'] ); ?>">
						<?php mp_icon( 'video' ); ?>
						<span><?php echo esc_html( $mp_exp['video']['label'] ); ?></span>
					</a>
				</div>
				<div class="experience__card experience__card--title">
					<p class="experience__partners-title"><?php echo esc_html( $mp_exp['partners_title'] ); ?></p>
				</div>
			</div>

			<div class="experience__partners-wrap">
			<ul class="experience__partners" data-marquee>
				<?php foreach ( array_merge( $mp_exp['partners'], $mp_exp['partners'] ) as $mp_i => $mp_partner ) : ?>
					<li class="partner-logo"<?php echo $mp_i >= count( $mp_exp['partners'] ) ? ' aria-hidden="true"' : ''; ?>>
						<?php // url() в инлайн-стиле считается от страницы, а не от CSS-файла — так путь работает и в статике. ?>
						<span class="partner-logo__mark" style="-webkit-mask-image: url('<?php echo esc_url( mp_img( $mp_partner ) ); ?>'); mask-image: url('<?php echo esc_url( mp_img( $mp_partner ) ); ?>')"></span>
					</li>
				<?php endforeach; ?>
			</ul>
			</div>
		</div>
	</div>
</section>
