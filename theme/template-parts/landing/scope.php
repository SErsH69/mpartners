<?php
/**
 * Лендинг, секция 03: «Что входит в услугу».
 *
 * @package MPartners
 */

$mp_scope = mp_landing( 'scope' );
$mp_items = (array) $mp_scope['items'];

// В макете карточки разложены в две колонки сверху вниз.
$mp_columns = [ [], [] ];

foreach ( $mp_items as $mp_index => $mp_item ) {
	$mp_columns[ $mp_index % 2 ][] = $mp_item;
}
?>
<section class="ld-scope">
	<div class="shell">
		<div class="ld-scope__inner">
			<div class="ld-scope__aside">
				<div class="ld-scope__intro">
					<h2 class="ld-scope__title"><?php echo esc_html( $mp_scope['title'] ); ?></h2>

					<div class="ld-scope__note">
						<span class="ld-scope__note-icon"><?php mp_icon( 'info' ); ?></span>
						<p class="ld-scope__note-text"><?php echo esc_html( $mp_scope['note'] ); ?></p>
					</div>
				</div>

				<div class="ld-scope__cta">
					<p class="ld-scope__promise"><?php mp_nl2br( $mp_scope['promise'] ); ?></p>

					<div class="ld-scope__actions">
						<div class="socials">
							<?php foreach ( mp_data( 'contacts.socials', [] ) as $mp_social ) : ?>
								<a class="btn-icon btn-icon--surface ld-scope__social" href="<?php echo esc_url( $mp_social['href'] ); ?>" aria-label="<?php echo esc_attr( $mp_social['label'] ); ?>">
									<?php mp_icon( $mp_social['icon'] ); ?>
								</a>
							<?php endforeach; ?>
						</div>

						<a class="btn ld-scope__button" href="#form"><?php echo esc_html( $mp_scope['cta'] ); ?></a>
					</div>
				</div>
			</div>

			<div class="ld-scope__grid">
				<?php foreach ( $mp_columns as $mp_column ) : ?>
					<div class="ld-scope__column">
						<?php foreach ( $mp_column as $mp_card ) : ?>
							<article class="ld-scope-card">
								<h3 class="ld-scope-card__title"><?php echo esc_html( $mp_card['title'] ); ?></h3>
								<p class="ld-scope-card__text"><?php mp_nl2br( $mp_card['text'] ); ?></p>
							</article>
						<?php endforeach; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
