<?php
/**
 * Адвокат: публикации — те же карточки, что в блоге на главной.
 *
 * @package MPartners
 */

$mp_pubs  = mp_lawyer( 'pubs' );

// Сначала материалы, где адвокат упомянут по фамилии: раньше на всех
// страницах подряд стояли три последние новости коллегии.
$mp_items = function_exists( 'mp_lawyer_posts' ) ? mp_lawyer_posts( get_the_ID(), 3 ) : [];
$mp_title = $mp_pubs['title'];

if ( ! $mp_items ) {
	$mp_items = function_exists( 'mp_recent_cards' )
		? mp_recent_cards( 3, 0, (array) $mp_pubs['items'] )
		: (array) $mp_pubs['items'];
	$mp_title = 'Материалы коллегии';
}

if ( ! $mp_items ) {
	return;
}
?>
<section class="lw-pubs">
	<div class="shell">
		<h2 class="h-lawyer lw-pubs__title"><?php echo esc_html( $mp_title ); ?></h2>

		<ul class="lw-pubs__grid">
			<?php foreach ( $mp_items as $mp_post ) : ?>
				<li class="post-card post-card--light lw-pub">
					<a class="post-card__link" href="<?php echo esc_url( isset( $mp_post['href'] ) ? $mp_post['href'] : home_url( '/press/' ) ); ?>">
						<span class="post-card__category"><?php echo esc_html( $mp_post['category'] ); ?></span>
						<span class="post-card__main">
							<span class="post-card__text">
								<span class="post-card__title"><?php echo esc_html( $mp_post['title'] ); ?></span>
								<span class="post-card__excerpt"><?php echo esc_html( $mp_post['excerpt'] ); ?></span>
							</span>
							<span class="post-card__date"><?php echo esc_html( $mp_post['date'] ); ?></span>
						</span>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
