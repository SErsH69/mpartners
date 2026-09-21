<?php
namespace CACFBlocks\Interfaces;

interface IACFBlock {
	/**
	 * Register the ACF block.
	 */
	public function register();

	/**
	 * Register all related functionalities for the ACF block.
	 */
	public function register_all();

	/**
	 * Register the block type for ACF.
	 */
	public function register_block_type();

	/**
	 * Render the block.
	 *
	 * @param array $block The block details.
	 */
	public function render( $block );
}
