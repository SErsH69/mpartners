<?php

namespace CACFBlocks\Block;

use CACFBlocks\AbstractLayer\ACFBlock;

class SpacerBlock extends ACFBlock {
	public function __construct() {
		$this->key      = 'group_spacer';
		$this->title    = 'Spacer';
		$this->slug     = 'spacer';
		$this->location = [
			[
				[
					'param'    => 'block',
					'operator' => '==',
					'value'    => 'acf/' . $this->slug,
				],
			],
		];

		$this->fields = [
			[
				'key'           => 'field_spacer_height',
				'label'         => 'Height',
				'name'          => 'spacer_height',
				'type'          => 'number',
				'default_value' => 80,
				'min'           => 0,
				'step'          => 8,
				'append'        => 'px',
			],
			[
				'key'           => 'field_spacer_show_grid',
				'label'         => 'Show guide grid',
				'name'          => 'spacer_show_grid',
				'type'          => 'true_false',
				'ui'            => 1,
				'default_value' => 0,
			],
			[
				'key'     => 'field_spacer_color',
				'label'   => 'Background',
				'name'    => 'spacer_color',
				'type'    => 'select',
				'choices' => [
					'transparent'  => 'Transparent',
					'surface'      => 'Surface',
					'surface-top'  => 'Surface with top radius',
					'surface-bottom' => 'Surface with bottom radius',
				],
				'default_value' => 'transparent',
			],
		];
	}
}
