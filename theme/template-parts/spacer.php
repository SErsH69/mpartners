<?php
$block_id         = get_query_var( 'block_anchor', '' ) ? ' id="' . get_query_var( 'block_anchor', '' ) . '"' : '';
$block_class_name = get_query_var( 'block_class_name', '' ) ? ' ' . get_query_var( 'block_class_name', '' ) : '';
$show_grid        = (bool) get_field( 'spacer_show_grid' );
$color            = get_field( 'spacer_color' ) ?: 'transparent';
$height           = absint( get_field( 'spacer_height' ) ?: 80 );
?>
<section<?php echo $block_id; ?> class="spacer<?php echo esc_attr( $block_class_name ); ?> spacer--<?php echo esc_attr( $color ); ?>" style="height: <?php echo esc_attr( $height ); ?>px;">
	<?php if ( is_admin() ) : ?>
		<div class="spacer__preview-label">Spacer: <?php echo esc_html( $height ); ?>px</div>
	<?php endif; ?>

	<?php if ( $show_grid ) : ?>
		<div class="spacer__grid" aria-hidden="true"></div>
	<?php endif; ?>
</section>
