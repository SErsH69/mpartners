<?php
namespace CACFBlocks\Factory;

use CACFBlocks\Block\SpacerBlock;
use Exception;

class BlockFactory {
	private static $block_types = [
		'spacer-block' => SpacerBlock::class,
	];

	public static function create_block( $type ) {
		if ( ! isset( self::$block_types[ $type ] ) ) {
			throw new Exception( 'Block type not recognized.' );
		}

		$class_name = self::$block_types[ $type ];

		return new $class_name();
	}
}
