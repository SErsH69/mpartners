<?php
/**
 * Лендинг, секция 05: «С кем мы работаем».
 *
 * Мозаика из шести карточек и тёмного блока с кнопкой: две широкие
 * карточки сверху, ниже ряд из узких, где третья колонка разбита надвое.
 *
 * @package MPartners
 */

$mp_clients = mp_landing( 'clients' );
$mp_items   = array_values( (array) $mp_clients['items'] );
$mp_cta     = $mp_clients['cta'];
?>
<section class="ld-clients">
	<div class="shell">
		<div class="ld-clients__head">
			<h2 class="ld-clients__title"><?php echo esc_html( $mp_clients['title'] ); ?></h2>

			<div class="ld-clients__note">
				<span class="ld-clients__note-icon"><?php mp_icon( 'info' ); ?></span>
				<p class="ld-clients__note-text"><?php echo esc_html( $mp_clients['note'] ); ?></p>
			</div>
		</div>

		<div class="ld-clients__grid">
			<?php
			// Пятая и шестая карточки в макете делят одну колонку.
			foreach ( $mp_items as $mp_index => $mp_item ) :
				$mp_number = $mp_index + 1;

				if ( 5 === $mp_number ) {
					echo '<div class="ld-client-stack">';
				}
				?>
				<article class="ld-client ld-client--<?php echo esc_attr( $mp_number ); ?>">
					<?php if ( ! empty( $mp_item['image'] ) ) : ?>
						<img class="ld-client__photo" src="<?php echo esc_url( mp_img( $mp_item['image'] ) ); ?>" alt="" aria-hidden="true" loading="lazy" decoding="async">
					<?php endif; ?>

					<h3 class="ld-client__title"><?php mp_nl2br( $mp_item['title'] ); ?></h3>
					<span class="ld-client__mark" aria-hidden="true"><?php mp_icon( 'arrow-right' ); ?></span>
				</article>
				<?php
				if ( 6 === $mp_number ) {
					echo '</div>';
				}
			endforeach;
			?>

			<div class="ld-client ld-client--cta">
				<p class="ld-client__cta-title"><?php echo esc_html( $mp_cta['title'] ); ?></p>
				<a class="btn btn--light ld-client__button" href="#form"><?php echo esc_html( $mp_cta['label'] ); ?></a>
			</div>
		</div>
	</div>
</section>
