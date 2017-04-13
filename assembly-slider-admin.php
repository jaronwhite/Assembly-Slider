<?php

global $wpdb;
$sliders     = $wpdb->get_results( "SELECT * FROM wp_assembly_sliders" );
$rgmSlider   = new as_slider();
$slides      = null;
$newSliderID = "slider" . round( microtime( true ) * 1000 );
$rgmSlider->setSliderId( $newSliderID );
$rgmSlider->setDateCreated( current_time( 'mysql' ) );

if ( isset( $_POST['go'] ) ) {
	if ( $_POST['slider-selector'] == "new-slider" ) {
		$selectedSliderID = $newSliderID;
	} else {
		$rgmSlider = getSlider( $_POST['slider-selector'] );
	}
} else {
	$selectedSliderID = $newSliderID;
}

function serializeCSS() {
}

function parseCSS() {
}

///*mobile & default style*/
///*mobile landscape*/
//@media only screen and (orientation: landscape) {}
///*tablet*/
//@media only screen and (min-width: 700px) and (orientation: portrait) {}
///*tablet landscape*/
//@media only screen and (min-width: 700px) and (orientation: landscape) {}
///*Desktop*/
//@media only screen and (min-width: 700px) {}


$rgmSlider = json_encode( $rgmSlider );

function padWithZero( $num ) {
	if ( $num < 10 ) {
		$num = "0" . $num;
	}

	return $num;
}

?>
<script type="text/javascript">
	var rgmSlider = JSON.parse('<?php echo $rgmSlider; ?>');
</script>

<div id="outer-wrap">
	<div id="inner-wrap">
		<h1 id="app-title">Assembly Slider</h1>
		<form id="slider-selection-form" method="POST" action="">
			<label for="slider-selector">Select Slider: </label>
			<select id="slider-selector" name="slider-selector">
				<!-- Iterate through DB for slider names -->
				<?php
				$selected = '';
				( $selectedSliderID == $newSliderID ) ? $selected = 'selected' : $selected = '';
				echo '<option id="new-slider" value="new-slider" ' . $selected . '>New Slider</option>';
				foreach ( $sliders as $slider ) {
					$sliderName = $slider->slider_name;
					$sliderId   = $slider->slider_id;
					( $selectedSliderID == $slider->slider_id ) ? $selected = 'selected' : $selected = '';
					echo '<option id="' . $sliderId . '" value="' . $sliderName . '" ' . $selected . '">'
					     . $sliderName . '</option>';
				}
				?>
				<!--                <option id="" class="" value="new-slider">New Slider</option>-->
			</select>
			<input type="submit" id="slider-go" name="go" value="Go"/>

			<div id="slider-pane">
				<label for="slider-name">Slider Name:</label>
				<input type="text" id="slider-name" name="slider-name"/>
				<label for="slider-code">Copy and paste this code:</label>
				<input type="text" id="slider-code" name="slider-code" readonly/>
				<div id="slider-options">
					<label for="slider-speed">Set Slider Speed: </label>
					<select id="slider-speed" name="slider-speed">
						<?php
						/**
						 * Builds option tags in sliderSpeed select tag. Sets 6 (seconds) as the default value.
						 */
						for ( $i = 0; $i < 10; $i ++ ) {
							$selected = "";
							if ( $i === 5 ) {
								$selected = "selected";
							}
							echo "<option " . $selected . ">" . ( $i + 1 ) . "</option>";
						}
						?>
					</select>
					<span>second(s)</span>
					<label for="b-control">Show bottom controls?</label>
					<input type="checkbox" id="b-control" name="b-control"/>
					<label for="lr-control">Show left/right controls?</label>
					<input type="checkbox" id="lr-control" name="lr-control"/>
				</div>
			</div>
			<div id="slides-pane">
				<h2 id="slides-pane-title">Slides</h2>
				<p id="slides-pane-subtitle">Click a slide to edit</p>
				<div id="slides-outer-wrap" class="clearfix">
					<div id="slides-inner-wrap">
						<!--JS insert slides here-->
						<!--class="slide"-->
					</div>
					<div id="add-slide" class="slide">
						<span></span>
						<span>Add Slide</span>
					</div>
				</div>
			</div>
			<div id="slide-editor">
				<div id="tool-pane">
					<div id="slide-bg-tool">
						<input type="button" id="bg-img-select" class="media-select" value="Select Image"/>
						<input type="text" id="bg-img-url" placeholder="Background Image URL"/>
					</div>
					<div id="layer-align-tool" class="tool-squares-wrap">
						<div id="align-top" class="tool-square"></div>
						<div id="align-middle" class="tool-square"></div>
						<div id="align-bottom" class="tool-square"></div>
						<div id="align-left" class="tool-square"></div>
						<div id="align-center" class="tool-square"></div>
						<div id="align-right" class="tool-square"></div>
					</div>
					<div id="text-align-tool" class="tool-squares-wrap">
						<div id="text-left" class="tool-square"></div>
						<div id="text-center" class="tool-square"></div>
						<div id="text-right" class="tool-square"></div>
						<div id="text-justify" class="tool-square"></div>
						<div id="text-top" class="tool-square"></div>
						<div id="text-middle" class="tool-square"></div>
						<div id="text-bottom" class="tool-square"></div>
					</div>
					<div id="device-preview-tool" class="tool-squares-wrap">
						<div id="mobile" class="tool-square"></div>
						<div id="tablet" class="tool-square"></div>
						<div id="desktop" class="tool-square"></div>
						<div id="rotate-device" class="tool-square"></div>
					</div>
					<!-- Add full width/height tool -->
					<!-- Add color picker tool for text/backgrounds -->
					<!-- Add font size/weight/style tools -->
					<!-- Add font-family tool using google fonts api -->
				</div>
				<div id="slide-mirror">
					<div id="layer-content-pane">
						<label for="layer-type">Type: </label>
						<select id="layer-type" name="layer-type">
							<option value="image">Image</option>
							<option value="text">Text/HTML</option>
							<option value="button" disabled>Button</option>
							<option value="filter" disabled>Filter</option>
						</select><br/><br/>
						<label for="content-area">Content: </label><br/>
						<textarea id="content-area" name="content-area"></textarea><br/>
						<input type="button" id="layer-img-select" class="media-select" value="Select Image"/><br/>
						<div id="static-options-wrap">
							<input type="checkbox" id="stationary-layer" name="stationary-layer"
							       value="stationary-layer"/>
							<label for="stationary-layer">I do not want the layer to move with screen/device
								size.</label><br/>
							<input type="checkbox" id="static-layer" name="static-layer" value="static-layer"/>
							<label for="static-layer">I do not want the layer to resize with screen/device
								size.</label>
						</div>
						<div id="layer-contents-save-wrap">
							<input type="button" id="save-content" class="save-btn" value="Save" disabled/>
							<input type="button" id="close-content" class="save-btn" value="Cancel"/>
						</div>
					</div>
					<div id="device-details">
						<p id="device-desc">Mobile (375x667)</p>
						<p id="device-orientation">Portrait</p>
					</div>
					<div id="device-emulator"></div>
				</div>
				<div id="layer-pane">
					<div id="layer-options">
						<!-- Include buttons for
							*Deleting layer
							*
						 -->
						<div class="layer-option"><span>n</span></div>
					</div>
					<input type="button" id="add-layer" value="Add Layer"/>
					<p id="layer-tip">Tip: Remove as much blank space from images with transparent backgrounds for best
						results.</p>
				</div>
				<!--				<div id="save-pane">-->
				<!--					<input type="submit" id="save-quit" name="save-quit" value="Save & Quit"/>-->
				<!--					<input type="submit" id="save-cont" name="save-cont" value="Save & Continue" disabled/>-->
				<!--					<input type="button" id="cancel-build" name="cancel-build" value="Cancel"/>-->
				<!--					<div id="slider-commit">-->
				<!--						<h2 id="commit-stmt">If you leave without saving, you're work will be forever lost!</h2>-->
				<!--						<input type="submit" id="save-confirm" name="save-confirm" value="Heck yeah, let's do this!"/>-->
				<!--						<input type="submit" id="save-decline" name="save-decline" value="Hold on thar, Baba Looey!"/>-->
				<!--					</div>-->
				<!--				</div>-->
			</div>
			<input type="submit" id="save-slider" name="save-slider" value="Save"/>
			<textarea id="sliderJSON" name="sliderJSON" hidden></textarea>
		</form>
	</div>
	<!-- End inner wrap -->
</div>
<!-- End outer wrap -->