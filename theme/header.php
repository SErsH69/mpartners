<?php
/**
 * Site header — «01. Главный экран» top bar from Figma.
 *
 * @package MPartners
 */

$mp_contacts = mp_data( 'contacts' );
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php // Прячем контент до разметки анимаций, чтобы текст не мигал перед появлением (страховка — 2.5с). ?>
	<script>document.documentElement.classList.add('js-reveal-pending');setTimeout(function(){document.documentElement.classList.remove('js-reveal-pending')},2500);</script>
	<style>.js-reveal-pending .main{visibility:hidden}</style>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="site">
	<header class="header" id="top">
		<div class="header__inner">
			<div class="header__phones">
				<?php foreach ( $mp_contacts['phones'] as $mp_phone ) : ?>
					<span class="header__phone">
						<span class="header__phone-label"><?php echo esc_html( $mp_phone['label'] ); ?></span>
						<a class="header__phone-value" href="<?php echo esc_url( $mp_phone['href'] ); ?>"><?php echo esc_html( $mp_phone['value'] ); ?></a>
					</span>
				<?php endforeach; ?>
			</div>

			<div class="header__bar">
				<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					<span class="brand__mark"><?php mp_icon( 'logo' ); ?></span>
					<span class="brand__divider" aria-hidden="true"></span>
					<span class="brand__tagline"><?php mp_nl2br( $mp_contacts['tagline'] ); ?></span>
				</a>

				<div class="header__nav" data-dropdown>
					<button class="header__menu-toggle" type="button" data-dropdown-toggle aria-expanded="false" aria-controls="nav-drop">
						<span>Меню</span>
						<span class="burger" aria-hidden="true"><i></i><i></i></span>
					</button>
					<button class="btn-icon btn-icon--surface header__search" type="button" aria-label="Поиск">
						<?php mp_icon( 'search' ); ?>
					</button>

					<?php // Выпадающее меню для ПК: раскрывается из полосы «Меню». ?>
					<div class="nav-drop" id="nav-drop" aria-hidden="true">
						<p class="nav-drop__label">Разделы</p>
						<ul class="nav-drop__list">
							<?php foreach ( (array) mp_data( 'menu', [] ) as $mp_item ) : ?>
								<li class="nav-drop__item"><a href="#"><?php echo esc_html( $mp_item ); ?></a></li>
							<?php endforeach; ?>
						</ul>
						<hr class="nav-drop__divider nav-drop__item">
						<p class="nav-drop__label nav-drop__item">Контакты</p>
						<ul class="nav-drop__contacts">
							<?php foreach ( $mp_contacts['phones'] as $mp_phone ) : ?>
								<li class="nav-drop__item">
									<a href="<?php echo esc_url( $mp_phone['href'] ); ?>">
										<span><?php echo esc_html( $mp_phone['label'] ); ?></span>
										<b><?php echo esc_html( $mp_phone['value'] ); ?></b>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
						<a class="btn btn--block nav-drop__cta nav-drop__item" href="#form">Заказать звонок</a>
					</div>
				</div>

				<div class="header__actions">
					<div class="socials">
						<?php foreach ( $mp_contacts['socials'] as $mp_social ) : ?>
							<a class="btn-icon btn-icon--surface" href="<?php echo esc_url( $mp_social['href'] ); ?>" aria-label="<?php echo esc_attr( $mp_social['label'] ); ?>">
								<?php mp_icon( $mp_social['icon'] ); ?>
							</a>
						<?php endforeach; ?>
					</div>
					<a class="btn header__cta" href="#form">Заказать звонок</a>
				</div>

				<div class="header__compact">
					<a class="btn header__cta-compact" href="#form">
						<span class="header__cta-full">Заказать звонок</span>
						<span class="header__cta-short">Связаться</span>
					</a>
					<button class="header__icon-btn" type="button" aria-label="Поиск"><?php mp_icon( 'search' ); ?></button>
					<button class="header__icon-btn" type="button" data-menu-open aria-expanded="false" aria-controls="site-menu" aria-label="Меню"><?php mp_icon( 'menu' ); ?></button>
				</div>
			</div>
		</div>
	</header>

	<div class="menu-panel" id="site-menu" hidden>
		<div class="menu-panel__inner">
			<button class="menu-panel__close" type="button" data-menu-close aria-label="Закрыть меню"><?php mp_icon( 'close' ); ?></button>
			<nav class="menu-panel__nav" aria-label="Основное меню">
				<ul>
					<?php foreach ( (array) mp_data( 'menu', [] ) as $mp_item ) : ?>
						<li><a href="#"><?php echo esc_html( $mp_item ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</nav>
			<div class="menu-panel__contacts">
				<?php foreach ( $mp_contacts['phones'] as $mp_phone ) : ?>
					<a class="menu-panel__phone" href="<?php echo esc_url( $mp_phone['href'] ); ?>">
						<span><?php echo esc_html( $mp_phone['label'] ); ?></span>
						<strong><?php echo esc_html( $mp_phone['value'] ); ?></strong>
					</a>
				<?php endforeach; ?>
				<div class="socials">
					<?php foreach ( $mp_contacts['socials'] as $mp_social ) : ?>
						<a class="btn-icon btn-icon--surface" href="<?php echo esc_url( $mp_social['href'] ); ?>" aria-label="<?php echo esc_attr( $mp_social['label'] ); ?>">
							<?php mp_icon( $mp_social['icon'] ); ?>
						</a>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>

	<main class="main">
