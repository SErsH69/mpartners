<?php

if ( ! defined( 'WP_CLI' ) ) {
	return;
}

class Webula_Cli {
	private $block_content = '<?php

namespace CACFBlocks\Block;

use CACFBlocks\AbstractLayer\ACFBlock;

class %%slug_upper%%Block extends ACFBlock {
    public function __construct() {
        $this->key = \'group_%%slug%%\';
        $this->title = \'%%block_name%%\';
        $this->slug = \'%%slug%%\';
        $this->location = [
            [
                [
                    \'param\' => \'block\',
                    \'operator\' => \'==\',
                    \'value\' => \'acf/\' . $this->slug,
                ],
            ],
        ];
        $this->keywords = array_merge($this->keywords, [
            // TODO: fill me with keywords!
        ]);
        $this->css_deps = [
        ];
        $this->js_deps = [
        ];

        $this->fields = [
        ];
    }
}';
	private $view_content  = '<?php

$block_id         = get_query_var( \'block_anchor\', \'\' ) ? \' id="\' . get_query_var( \'block_anchor\', \'\' ) . \'"\' : \'\';
$block_class_name = get_query_var( \'block_class_name\', \'\' ) ? \' \' . get_query_var( \'block_class_name\', \'\' ) : \'\';
?>
<section<?php echo $block_id; ?> class="%%slug%%<?php echo $block_class_name; ?>">
    <div class="container">
        Hello World
    </div>
</section>';
	private $entry_content = 'import \'./../../scss/blocks/_%%slug%%.scss\'

console.log(\'Hello World\');
';
	private $scss_content  = '.%%slug%% {
    * { color: red !important; }
}
';

	// Usage: wp webula create [slug] [block_name]
	public function create( $args ) {
		if ( empty( $args[0] ) ) {
			WP_CLI::error( 'No slug provided' );
		}

		if ( stripos( $args[0], '-' ) !== false ) {
			WP_CLI::error( 'No hyphens allowed in slug' );
		}

		$slug             = $args[0];
		$slug_capitalized = ucfirst( $slug );
		$block_name       = $args[1] ?? $slug;

		$file_path = ABSPATH . '/assets/js/entry/' . $slug . 'Entry.js';
		$this->create_file_with_content( $file_path, $slug, $block_name, $this->entry_content );

		$file_path = ABSPATH . '/assets/scss/blocks/_' . $slug . '.scss';
		$this->create_file_with_content( $file_path, $slug, $block_name, $this->scss_content );

		$search_pattern = "/(\s*)main: \'\.\/assets\/js\/entry\/mainEntry\.js\',/";
		$replacement    = "$1main: './assets/js/entry/mainEntry.js',\n$1'{$slug}-block': './assets/js/entry/{$slug}Entry.js',";
		$file_path      = ABSPATH . '/webpack.config.js';
		$this->update_existing_file( $file_path, $search_pattern, $replacement );

		$file_path = get_template_directory() . '/src/CACFBlocks/Block/' . $slug_capitalized . 'Block.php';
		$this->create_file_with_content( $file_path, $slug, $block_name, $this->block_content );

		$file_path = get_template_directory() . '/template-parts/' . $slug . '.php';
		$this->create_file_with_content( $file_path, $slug, $block_name, $this->view_content );

		$search_pattern = '/(\s*)\$block_types = \[/';
		$replacement    = "$1\$block_types = [\n$1\t'{$slug}-block',";
		$file_path      = get_template_directory() . '/functions.php';
		$this->update_existing_file( $file_path, $search_pattern, $replacement );

		$search_pattern = '/(\s*)namespace CACFBlocks\\\\Factory;/';
		$replacement    = "$1namespace CACFBlocks\\Factory;\n\n$1use CACFBlocks\\Block\\{$slug_capitalized}Block;";
		$file_path      = get_template_directory() . '/src/CACFBlocks/Factory/BlockFactory.php';
		$this->update_existing_file( $file_path, $search_pattern, $replacement );

		$file_path = get_template_directory() . '/src/CACFBlocks/Factory/BlockFactory.php';

		$search_pattern = '/private static \$block_types = \[\s*/';
		$replacement    = "private static \$block_types = [\n    '{$slug}-block'          => {$slug_capitalized}Block::class,\n";
		$this->update_existing_file( $file_path, $search_pattern, $replacement );

		WP_CLI::success( 'Block created successfully' );
	}

	private function create_file_with_content( $file_path, $slug, $block_name, $content ) {

		if ( file_exists( $file_path ) ) {
			WP_CLI::error( 'File already exists' );
		}

		$content = str_replace( [ '%%slug%%', '%%slug_upper%%', '%%block_name%%' ], [ $slug, ucfirst( $slug ), $block_name ], $content );

		$result = file_put_contents( $file_path, $content . "\n\n" );
		if ( $result === false ) {
			WP_CLI::error( 'Failed to create file' );
		}
	}

	private function update_existing_file( $file_path, $search_pattern, $replacement ) {

		if ( ! file_exists( $file_path ) ) {
			WP_CLI::error( 'File does not exist' );
		}

		$content         = file_get_contents( $file_path );
		$updated_content = preg_replace( $search_pattern, $replacement, $content );

		if ( $updated_content === null ) {
			WP_CLI::error( 'Error updating the file' );
		}

		$result = file_put_contents( $file_path, $updated_content );
		if ( $result === false ) {
			WP_CLI::error( 'Failed to update the file' );
		} else {
			WP_CLI::success( 'File updated successfully' );
		}
	}
}
WP_CLI::add_command( 'webula', 'Webula_Cli' );
