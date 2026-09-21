<?php

require_once get_template_directory() . '/src/CACFBlocks/Interfaces/IACFBlock.php';
require_once get_template_directory() . '/src/CACFBlocks/AbstractLayer/ACFBlock.php';
require_once get_template_directory() . '/src/CACFBlocks/Factory/BlockFactory.php';

spl_autoload_register(
	function ( $class_name ) {
		$base_dir = get_template_directory() . '/src/CACFBlocks/Block/';

		$relative_class = str_replace( 'CACFBlocks\\Block\\', '', $class_name );
		$file           = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';

		if ( file_exists( $file ) ) {
			require_once $file;
		}
	}
);
