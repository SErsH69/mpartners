<?php
/**
 * Правоустанавливающие документы: список файлов коллегии.
 *
 * @package MPartners
 */

$mp_documents = function_exists( 'mp_documents' ) ? mp_documents() : [];

if ( ! $mp_documents ) {
	return;
}
?>
<section class="docs">
	<div class="shell">
		<div class="docs__head">
			<h1 class="h-pr docs__title">Правоустанавливающие документы</h1>
		</div>

		<div class="note docs__note">
			<span class="note__icon"><?php mp_icon( 'info' ); ?></span>
			<p class="note__text">Московская коллегия адвокатов «M-PARTNERS» зарегистрирована в 2024 году. ИНН 9707035244, ОГРН 1247700572173</p>
		</div>

		<ul class="docs__grid">
			<?php foreach ( $mp_documents as $mp_doc ) : ?>
				<?php $mp_size = function_exists( 'mp_document_size' ) ? mp_document_size( $mp_doc['file'] ) : ''; ?>
				<li class="docs__item">
					<a class="docs__link" href="<?php echo esc_url( mp_document_url( $mp_doc['file'] ) ); ?>" target="_blank" rel="noopener">
						<span class="docs__icon" aria-hidden="true"><?php mp_icon( 'document' ); ?></span>
						<span class="docs__text">
							<span class="docs__name"><?php echo esc_html( $mp_doc['title'] ); ?></span>
							<span class="docs__meta">PDF<?php echo $mp_size ? ', ' . esc_html( $mp_size ) : ''; ?></span>
						</span>
						<span class="docs__arrow" aria-hidden="true"><?php mp_icon( 'arrow-ne' ); ?></span>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
