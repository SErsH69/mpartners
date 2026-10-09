<?php
/**
 * 06. Квиз — «Консультация специалиста».
 *
 * @package MPartners
 */

$mp_quiz   = mp_data( 'quiz' );
$mp_expert = $mp_quiz['expert'];
$mp_total  = count( $mp_quiz['steps'] );
?>
<section class="quiz" id="quiz">
	<div class="shell">
	<div class="quiz__inner">
		<img class="quiz__decor" src="<?php echo esc_url( mp_img( 'decor-quiz', 'svg' ) ); ?>" alt="" aria-hidden="true" loading="lazy" decoding="async">

		<?php // Тело квиза задаётся в inc/cf7.php, чтобы ответы уходили через Contact Form 7. ?>
		<div class="quiz__host" data-quiz data-total="<?php echo esc_attr( $mp_total + 1 ); ?>">
			<?php mp_cf7( 'quiz', 'quiz__form' ); ?>
		</div>


		<aside class="quiz__expert">
			<img class="quiz__expert-decor" src="<?php echo esc_url( mp_img( 'decor-quiz-glow1', 'svg' ) ); ?>" alt="" aria-hidden="true" loading="lazy" decoding="async">

			<div class="quiz__expert-top">
				<div class="quiz__expert-person">
					<span class="quiz__expert-avatar">
						<img src="<?php echo esc_url( mp_img( $mp_expert['photo'] ) ); ?>" alt="<?php echo esc_attr( $mp_expert['name'] ); ?>" loading="lazy" decoding="async">
						<i class="quiz__expert-status" aria-hidden="true"></i>
					</span>
					<span class="quiz__expert-id">
						<span class="quiz__expert-name"><?php echo esc_html( $mp_expert['name'] ); ?></span>
						<span class="quiz__expert-role"><?php mp_nl2br( $mp_expert['role'] ); ?></span>
					</span>
				</div>
				<blockquote class="quiz__expert-quote"><?php echo esc_html( $mp_expert['quote'] ); ?></blockquote>
			</div>

			<div class="quiz__expert-bottom">
				<p class="quiz__expert-note"><?php echo esc_html( $mp_expert['note'] ); ?></p>
				<div class="socials socials--ghost">
					<?php foreach ( mp_data( 'contacts.socials', [] ) as $mp_social ) : ?>
						<a class="btn-icon btn-icon--ghost" href="<?php echo esc_url( $mp_social['href'] ); ?>" aria-label="<?php echo esc_attr( $mp_social['label'] ); ?>">
							<?php mp_icon( $mp_social['icon'] ); ?>
						</a>
					<?php endforeach; ?>
				</div>
			</div>
		</aside>
	</div>
	</div>
</section>
