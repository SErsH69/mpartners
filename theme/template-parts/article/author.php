<?php
/**
 * Статья: карточка автора на тёмной плашке.
 *
 * @package MPartners
 */

$mp_author = mp_article( 'author' );
$mp_person = function_exists( 'mp_content' ) ? mp_content( 'lawyer' ) : mp_lawyer_data();
?>
<section class="art-author">
	<div class="shell shell--wide">
		<div class="art-author__panel">
			<img class="art-author__photo" src="<?php echo esc_url( mp_img( $mp_person['photo'], 'jpg' ) ); ?>" alt="<?php echo esc_attr( $mp_person['name'] ); ?>" width="1214" height="1276" loading="lazy" decoding="async">

			<div class="art-author__main">
				<p class="art-author__label"><?php echo esc_html( $mp_author['label'] ); ?></p>
				<p class="art-author__name"><?php echo esc_html( $mp_person['name'] ); ?></p>
				<p class="art-author__role"><?php echo esc_html( $mp_person['role'] ); ?></p>
				<p class="art-author__spec"><?php echo esc_html( $mp_author['spec_title'] ); ?></p>
				<ul class="art-author__list">
					<?php foreach ( $mp_person['spec']['items'] as $mp_item ) : ?>
						<li class="lw-spec__item art-author__item">
							<span class="lw-spec__marker" aria-hidden="true"><?php mp_icon( 'polygon' ); ?></span>
							<span><?php echo esc_html( $mp_item ); ?></span>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>

			<div class="art-author__contacts">
				<p class="art-author__contacts-title"><?php echo esc_html( $mp_author['contacts'] ); ?></p>

				<a class="art-author__link" href="mailto:<?php echo esc_attr( $mp_person['email'] ); ?>">
					<?php mp_icon( 'mail' ); ?>
					<span><?php echo esc_html( $mp_person['email'] ); ?></span>
				</a>
				<a class="art-author__link" href="tel:<?php echo esc_attr( preg_replace( '/[^+\d]/', '', $mp_person['phone'] ) ); ?>">
					<?php mp_icon( 'messenger' ); ?>
					<span><?php echo esc_html( $mp_person['phone'] ); ?></span>
				</a>

				<p class="art-author__registry">
					<span><?php echo esc_html( $mp_person['registry']['label'] ); ?></span>
					<strong><?php echo esc_html( $mp_person['registry']['number'] ); ?></strong>
				</p>

				<a class="btn btn--light art-author__button" href="<?php echo esc_url( $mp_person['card']['href'] ); ?>"><?php echo esc_html( $mp_author['button'] ); ?></a>
			</div>
		</div>
	</div>
</section>
