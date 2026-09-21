<?php
/**
 * Статическая выгрузка главной для GitHub Pages (или любого хостинга без PHP).
 *
 * Рендерит theme/front-page.php с минимальной заглушкой WordPress и кладёт
 * index.html + собранные ассеты в папку build-static/.
 *
 *   yarn prod && php scripts/export-static.php
 *
 * @package MPartners
 */

error_reporting( E_ALL & ~E_WARNING & ~E_DEPRECATED );

define( 'MP_THEME', dirname( __DIR__ ) . '/theme' );
define( 'MP_OUT', dirname( __DIR__ ) . '/build-static' );
define( 'MP_CONTACT_NONCE', 'mp_contact' );

// --- минимальная заглушка WordPress ---------------------------------------
function get_template_directory() { return MP_THEME; }
function get_template_directory_uri() { return '.'; }
function home_url( $p = '/' ) { return './'; }
function admin_url( $p = '' ) { return '#'; }
function language_attributes() { echo 'lang="ru-RU"'; }
function bloginfo( $k ) { echo 'charset' === $k ? 'UTF-8' : 'M-PARTNERS'; }
function body_class( $c = '' ) { echo 'class="home"'; }
function wp_body_open() {}
function esc_html( $s ) { return htmlspecialchars( (string) $s, ENT_QUOTES, 'UTF-8' ); }
function esc_attr( $s ) { return esc_html( $s ); }
function esc_url( $s ) { return esc_html( $s ); }
function esc_attr_e( $s, $d = '' ) { echo esc_html( $s ); }
function esc_html_e( $s, $d = '' ) { echo esc_html( $s ); }
function __( $s, $d = '' ) { return $s; }
function apply_filters( $tag, $value ) { return $value; }
function get_field( $k ) { return null; }
function is_admin() { return false; }
function wp_nonce_field( $a = '', $n = '', $r = true ) { echo '<input type="hidden" name="' . esc_attr( $n ) . '" value="static">'; }
function get_header() { include MP_THEME . '/header.php'; }
function get_footer() { include MP_THEME . '/footer.php'; }
function get_template_part( $slug ) { include MP_THEME . '/' . $slug . '.php'; }

function mp_latest( $pattern ) {
	$files = glob( MP_THEME . '/dist/' . $pattern );
	usort( $files, fn( $a, $b ) => filemtime( $b ) <=> filemtime( $a ) );
	return $files ? basename( $files[0] ) : '';
}
function wp_head() {
	// В WordPress <title> выводит сам движок (title-tag), в статике — ставим вручную.
	echo '<title>M-PARTNERS — уголовная защита для бизнеса</title>';
	echo '<link rel="stylesheet" href="./dist/' . mp_latest( 'main.*.css' ) . '">';
}
function wp_footer() { echo '<script src="./dist/' . mp_latest( 'main.*.js' ) . '"></script>'; }

require_once MP_THEME . '/inc/icons.php';
require_once MP_THEME . '/inc/home-data.php';

// --- рендер ---------------------------------------------------------------
ob_start();
include MP_THEME . '/front-page.php';
$html = ob_get_clean();

// --- выгрузка -------------------------------------------------------------
// Лицензионный шрифт в публичную выгрузку кладём только по флагу --with-font.
$with_font = in_array( '--with-font', $argv ?? [], true );

$copy = function ( $from, $to ) use ( &$copy, $with_font ) {
	if ( ! $with_font && str_contains( basename( $from ), 'AAStetica' ) ) {
		return;
	}

	if ( is_dir( $from ) ) {
		@mkdir( $to, 0777, true );
		foreach ( scandir( $from ) as $f ) {
			if ( '.' !== $f && '..' !== $f && '.DS_Store' !== $f && ! str_ends_with( $f, '.map' ) ) {
				$copy( "$from/$f", "$to/$f" );
			}
		}
	} else {
		copy( $from, $to );
	}
};

exec( 'rm -rf ' . escapeshellarg( MP_OUT ) );
@mkdir( MP_OUT, 0777, true );
$copy( MP_THEME . '/dist', MP_OUT . '/dist' );
file_put_contents( MP_OUT . '/index.html', $html );
file_put_contents( MP_OUT . '/.nojekyll', '' ); // Pages не должен прогонять сайт через Jekyll

echo 'Готово: ' . MP_OUT . "/index.html\n";
