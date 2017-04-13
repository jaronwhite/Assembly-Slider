<?php

add_shortcode( 'slider', 'slider_func' );
/**
 * @param $atts
 */
function slider_func( $atts ) {
	$a = shortcode_atts( array(
		'name' => 'assembly'
	), $atts );

	$rgmSlider = getSlider( $a['name'] );
	$slides    = $rgmSlider->slides;
	$test      = '';


	/**
	 * @param $css object which contains key:value pairs of css styles
	 *
	 * @return string Formatted CSS code
	 */
	function serializeCSS( $css ) {
		$selector = '#';
		$style    = '';
		foreach ( $css as $key => $value ) {
			if ( $key == 'layer_id' ) {
				$selector .= $value . '{';
			} else if ( $key != 'style_id' && $key != 'date_created'
			            && $key != 'media_query' && $key != 'layer_id' && $value != ''
			) {
				$key2 = explode( '_', $key );
				if ( $key2[0] == 'perc' || $key2[0] == 'px' ) {
					$key = $key2[1];
				} else if ( sizeof( $key2 ) > 1 ) {
					$key = $key2[0] . '-' . $key2[1];
				}
				$style .= $key . ':' . $value . ';';
			}
		}
		$style .= '}';

		return $selector . $style;
	}

	echo '<style>';
	foreach ( $slides as $slide ) {
		$layers = $slide->layers;
		if ( sizeof( $layers ) > 0 ) {
			foreach ( $layers as $layer ) {
				$styles = $layer->css;
				foreach ( $styles as $style ) {
					echo serializeCSS( $style );
				}
			}
		}
	}
	echo '</style>';

	?>
	<div id="base-container">
		<div id="as_slider-wrap">
			<div id="as_loader" class="current">
				<div id="as_dot-wrap">
					<span class="as_dot"></span>
					<span class="as_dot"></span>
					<span class="as_dot"></span>
				</div>
			</div>
			<div id="as_prev" class="as_nav">&lt;</div>

			<?php
			foreach ( $slides as $slide ) {
				$layers = $slide->layers;
				?>

				<div class="as_slide"
				     style="background: <?php echo $slide->bg ?>; background-size: cover;">

					<?php

					if ( sizeof( $layers ) > 0 ) {

						foreach ( $layers as $layer ) {

							echo '<div id = ' . $layer->layer_id . ' class="layer">';

							switch ( $layer->type ) {
								case 'image' :
									echo '<img class="' . $layer->inner_classList . '" src="' . $layer->content . '" />';
									break;
								case 'text' :
									echo '<p class="' . $layer->inner_classList . '">' . $layer->content . '</p>';
									break;
								/*
								case 'button' :
								//echo '<button class="" >' . $layer->content . '</button>';
									break;
								case 'filter' :
									break;
								*/
							}
							echo '</div>';
						}
					}
					?>

				</div>

				<?php
			}
			?>

			<div id="as_next" class="as_nav">&gt;</div>
			<div id="as_controls">
			</div>
		</div>
	</div>

	<?php
}

?>