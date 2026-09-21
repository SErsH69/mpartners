<?php
/**
 * 404 template.
 *
 * @package WebulaStarter
 */

get_header();
?>
<main id="primary" class="site-main">
	<div class="site-shell site-content">
		<section class="entry-card">
			<h1 class="entry-card__title"><?php esc_html_e( 'This page could not be found', 'webula-starter' ); ?></h1>
			<p><?php esc_html_e( 'Try another link or return to the homepage.', 'webula-starter' ); ?></p>
		</section>
	</div>
</main>
<?php
get_footer();
