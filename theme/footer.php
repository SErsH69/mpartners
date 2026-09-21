<?php
/**
 * Site footer — «15. Подвал» from Figma.
 *
 * @package MPartners
 */

$mp_footer   = mp_data( 'footer' );
$mp_contacts = mp_data( 'contacts' );
$mp_menu     = (array) mp_data( 'menu', [] );
?>
	</main>

	<footer class="footer">
		<div class="footer__backdrop" aria-hidden="true">
			<img class="footer__pattern" src="<?php echo esc_url( mp_img( 'hero-bg' ) ); ?>" alt="" loading="lazy" decoding="async" width="3062" height="1119">
			<span class="footer__wordmark"><?php echo mp_get_svg_file( 'wordmark' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
		</div>

		<div class="footer__inner">
			<a class="brand brand--light" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<span class="brand__mark"><?php mp_icon( 'logo' ); ?></span>
				<span class="brand__divider" aria-hidden="true"></span>
				<span class="brand__tagline"><?php mp_nl2br( $mp_contacts['tagline'] ); ?></span>
			</a>

			<div class="footer__grid">
				<div class="footer__left">
					<div class="footer__offices">
						<?php foreach ( $mp_footer['offices'] as $mp_office ) : ?>
							<div class="office">
								<p class="office__label"><?php echo esc_html( $mp_office['label'] ); ?></p>
								<div class="office__body">
									<a class="office__phone" href="<?php echo esc_url( $mp_office['href'] ); ?>"><?php echo esc_html( $mp_office['phone'] ); ?></a>
									<p class="office__address"><?php mp_nl2br( $mp_office['address'] ); ?></p>
								</div>
							</div>
						<?php endforeach; ?>
					</div>

					<div class="footer__socials">
						<p class="footer__socials-label"><?php echo esc_html( $mp_footer['socials_label'] ); ?></p>
						<div class="socials">
							<?php foreach ( $mp_contacts['socials'] as $mp_social ) : ?>
								<a class="btn-icon btn-icon--surface" href="<?php echo esc_url( $mp_social['href'] ); ?>" aria-label="<?php echo esc_attr( $mp_social['label'] ); ?>">
									<?php mp_icon( $mp_social['icon'] ); ?>
								</a>
							<?php endforeach; ?>
						</div>
					</div>
				</div>

				<nav class="footer__col footer__col--menu" aria-label="<?php echo esc_attr( $mp_footer['menu_title'] ); ?>">
					<p class="footer__col-title"><?php echo esc_html( $mp_footer['menu_title'] ); ?></p>
					<ul class="footer__list">
						<?php foreach ( $mp_menu as $mp_item ) : ?>
							<li><a href="#"><?php echo esc_html( $mp_item ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				</nav>

				<nav class="footer__col footer__col--services" aria-label="<?php echo esc_attr( $mp_footer['services_title'] ); ?>">
					<p class="footer__col-title"><?php echo esc_html( $mp_footer['services_title'] ); ?></p>
					<div class="footer__services">
						<?php // В макете две независимые колонки: 8 и 6 пунктов. ?>
						<?php foreach ( [ array_slice( $mp_footer['services'], 0, 8 ), array_slice( $mp_footer['services'], 8 ) ] as $mp_column ) : ?>
							<ul class="footer__list">
								<?php foreach ( $mp_column as $mp_service ) : ?>
									<li><a href="#"><?php echo esc_html( $mp_service ); ?></a></li>
								<?php endforeach; ?>
							</ul>
						<?php endforeach; ?>
					</div>
				</nav>
			</div>
		</div>

		<div class="footer__legal">
			<?php foreach ( $mp_footer['legal'] as $mp_legal ) : ?>
				<a class="footer__legal-link" href="<?php echo esc_url( $mp_legal['href'] ); ?>"><?php echo esc_html( $mp_legal['label'] ); ?></a>
			<?php endforeach; ?>
			<p class="footer__copyright"><?php echo esc_html( $mp_footer['copyright'] ); ?></p>
		</div>
	</footer>
</div>
<?php wp_footer(); ?>
</body>
</html>
