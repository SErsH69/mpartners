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

		<form class="quiz__form" data-quiz data-total="<?php echo esc_attr( $mp_total ); ?>">
			<div class="quiz__intro">
				<h2 class="h-section"><?php echo esc_html( $mp_quiz['title'] ); ?></h2>
				<p class="quiz__subtitle"><?php echo esc_html( $mp_quiz['subtitle'] ); ?></p>
			</div>

			<div class="quiz__progress" role="progressbar" aria-valuemin="1" aria-valuemax="<?php echo esc_attr( $mp_total ); ?>" aria-valuenow="1">
				<?php for ( $mp_i = 0; $mp_i < $mp_total; $mp_i++ ) : ?>
					<span class="quiz__progress-item<?php echo 0 === $mp_i ? ' is-active' : ''; ?>"></span>
				<?php endfor; ?>
			</div>

			<div class="quiz__steps">
				<?php foreach ( $mp_quiz['steps'] as $mp_index => $mp_step ) : ?>
					<fieldset class="quiz__step<?php echo 0 === $mp_index ? ' is-active' : ''; ?>" data-quiz-step="<?php echo esc_attr( $mp_index ); ?>"<?php echo 0 === $mp_index ? '' : ' hidden'; ?>>
						<legend class="quiz__question">
							<span class="quiz__question-icon"><?php mp_icon( 'question' ); ?></span>
							<span><?php echo esc_html( $mp_step['question'] ); ?></span>
						</legend>
						<div class="quiz__answers">
							<?php foreach ( $mp_step['answers'] as $mp_answer ) : ?>
								<label class="quiz__answer">
									<input type="radio" name="quiz-<?php echo esc_attr( $mp_index ); ?>" value="<?php echo esc_attr( $mp_answer ); ?>">
									<?php mp_icon( 'polygon', 'quiz__answer-marker' ); ?>
									<span><?php echo esc_html( $mp_answer ); ?></span>
								</label>
							<?php endforeach; ?>
						</div>
					</fieldset>
				<?php endforeach; ?>
			</div>

			<div class="quiz__nav">
				<button class="quiz__nav-btn" type="button" data-quiz-prev aria-label="Назад" hidden><?php mp_icon( 'arrow-left' ); ?></button>
				<button class="quiz__nav-btn" type="button" data-quiz-next aria-label="Далее"><?php mp_icon( 'arrow-right' ); ?></button>
			</div>
		</form>

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
