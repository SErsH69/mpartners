<?php
namespace CACFBlocks\AbstractLayer;

use CACFBlocks\Interfaces\IACFBlock;

abstract class ACFBlock implements IACFBlock {
	protected string $key;
	protected string $title;
	protected array $fields       = [];
	protected array $style_fields = [];
	protected array $location     = [];
	protected string $slug;
	protected array $keywords = [
		'webula',
		'block',
	];
	protected array $js_deps  = [];
	protected array $css_deps = [];

	public function register(): void {
		if ( function_exists( 'acf_add_local_field_group' ) ) {
			acf_add_local_field_group(
				[
					'key'      => $this->key,
					'title'    => $this->title,
					'fields'   => $this->build_fields(),
					'location' => $this->location,
				]
			);
		}
	}

	protected function build_fields(): array {
		$fields[] = [
			'key'   => 'field_1' . esc_attr( $this->slug ),
			'label' => 'Content',
			'name'  => 'content_tab',
			'type'  => 'tab',
		];

		$fields = array_merge( $fields, $this->fields );

		if ( ! empty( $this->style_fields ) ) {
			$fields[] = [
				'key'   => 'field_2' . esc_attr( $this->slug ),
				'label' => 'Styling',
				'name'  => 'style_tab',
				'type'  => 'tab',
			];
			$fields   = array_merge( $fields, $this->style_fields );
		}

		return $fields;
	}

	public function register_all(): void {
		$this->register();
		$this->register_block_type();
	}

	public function register_block_type(): void {
		if ( function_exists( 'acf_register_block_type' ) ) {
			acf_register_block_type(
				[
					'name'            => $this->slug,
					'title'           => $this->title,
					'render_callback' => [ $this, 'render' ],
					'category'        => 'custom-blocks',
					'icon'            => 'minus',
					'keywords'        => $this->keywords,
					'supports'        => [
						'mode'   => false,
						'anchor' => true,
					],
					'mode'            => 'edit',
				]
			);
		}
	}

	public function render( $block, $content = '', $is_preview = false, $post_id = 0, $wp_block = null, $context = null ): void {
		if ( get_class( $this ) !== __CLASS__ ) {
			$this->enqueue_styles();
			$this->enqueue_scripts();
		}

		$template = get_template_directory() . '/template-parts/' . $this->slug . '.php';

		if ( ! file_exists( $template ) ) {
			error_log( 'Template file not found: ' . $template );

			return;
		}

		if ( $is_preview ) {
			echo '<div style="padding:16px;border:1px dashed #b7b7b7;background:#faf7f1;color:#5a5a5a;text-align:center;">Spacer block preview</div>';

			return;
		}

		$id         = $block['anchor'] ?? '';
		$class_name = $block['class_name'] ?? '';

		set_query_var( 'block_anchor', esc_attr( $id ) );
		set_query_var( 'block_class_name', esc_attr( $class_name ) );

		include $template;
	}

	protected function enqueue_styles(): void {
		$handle = $this->slug . '-block-style';

		wp_register_style(
			$handle,
			webula_get_asset( $this->slug . '-block.css' ),
			$this->css_deps,
			null
		);

		if ( is_admin() ) {
			add_action(
				'enqueue_block_editor_assets',
				function () use ( $handle ) {
					wp_enqueue_style( $handle );
				}
			);

			return;
		}

		wp_enqueue_style( $handle );
	}

	protected function enqueue_scripts(): void {
		if ( is_admin() ) {
			return;
		}

		$file_name = $this->slug . '-block.js';
		$file_path = get_template_directory() . webula_get_asset_hashed_filename( $file_name );

		if ( file_exists( $file_path ) && filesize( $file_path ) > 0 ) {
			wp_enqueue_script(
				$this->slug . '-block-script',
				webula_get_asset( $file_name ),
				$this->js_deps,
				null,
				true
			);
		}
	}
}
