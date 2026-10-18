<?php
/**
 * Статья: карточка автора на тёмной плашке.
 *
 * @package MPartners
 */

$mp_author = mp_article( 'author' );
$mp_person = function_exists( 'mp_content' ) ? mp_content( 'lawyer' ) : mp_lawyer_data();

// Автор — настоящая карточка адвоката: имя, фото и контакты из записи.
$mp_author_id = function_exists( 'mp_article_author_id' ) ? mp_article_author_id() : 0;
$mp_photo     = '';

if ( $mp_author_id ) {
	$mp_own = mp_lawyer_fields( $mp_author_id );

	$mp_person['name'] = get_the_title( $mp_author_id );

	foreach ( [ 'role', 'email', 'phone' ] as $mp_key ) {
		if ( '' !== $mp_own[ $mp_key ] ) {
			$mp_person[ $mp_key ] = $mp_own[ $mp_key ];
		}
	}

	if ( ! empty( $mp_own['spec']['items'] ) ) {
		$mp_person['spec'] = $mp_own['spec'];
	}

	if ( ! empty( $mp_own['registry']['number'] ) ) {
		$mp_person['registry'] = $mp_own['registry'];
	}

	$mp_person['card'] = [
		'label' => $mp_person['card']['label'],
		'href'  => function_exists( 'mp_lawyer_vcard_url' ) ? mp_lawyer_vcard_url( $mp_author_id ) : get_permalink( $mp_author_id ),
	];

	$mp_photo = (string) get_the_post_thumbnail_url( $mp_author_id, 'full' );
}

if ( ! $mp_photo ) {
	$mp_photo = mp_img( $mp_person['photo'], 'jpg' );
}

?>
<section class="art-author">
	<div class="shell shell--wide">
		<div class="art-author__panel">
			<img class="art-author__photo" src="<?php echo esc_url( $mp_photo ); ?>" alt="<?php echo esc_attr( $mp_person['name'] ); ?>" width="1214" height="1276" loading="lazy" decoding="async">

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
				<a class="art-author__link" href="<?php echo esc_url( mp_tel_href( $mp_person['phone'] ) ); ?>">
					<?php mp_icon( 'messenger' ); ?>
					<span><?php echo esc_html( $mp_person['phone'] ); ?></span>
				</a>

				<p class="art-author__registry">
					<span><?php echo esc_html( $mp_person['registry']['label'] ); ?></span>
					<strong><?php echo esc_html( $mp_person['registry']['number'] ); ?></strong>
				</p>

				<a class="btn btn--light art-author__button" href="<?php echo esc_url( mp_link( $mp_person['card'], 'lawyers' ) ); ?>"><?php echo esc_html( $mp_author['button'] ); ?></a>
			</div>
		</div>
	</div>
</section>
