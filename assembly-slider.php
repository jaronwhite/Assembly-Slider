<?php
/*
Plugin Name: Assembly Slider
Plugin URI:  
Description: A simple to use drag-and-drop slider/carousel builder.
Version:     1.0.0
Author:      Jaron White
Author URI:  
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

//add page into admin menu
add_action( 'admin_menu', 'assembly_slider_admin_actions' );
function assembly_slider_admin_actions() {
	add_menu_page( 'assemblyslider', 'Assembly Slider', 'manage_options', __FILE__, 'assembly_slider_admin', 'dashicons-migrate' );
}

//Create tables
require_once( 'assembly-slider-install.php' );
register_activation_hook( __FILE__, 'as_install' );
register_activation_hook( __FILE__, 'as_install_data' );

//include Assembly custom widgets
require_once( 'classes/class-assembly-image-widget.php' );
require_once( 'classes/class-assembly-contact-widget.php' );

//include Assembly slide class
include( 'classes/class-assembly-slider.php' );

//Add admin scripts, styles and media uploader js
add_action( 'admin_enqueue_scripts', 'assembly_slider_admin_enqueue' );
function assembly_slider_admin_enqueue( $hook ) {
	if ( 'toplevel_page_rgm_assembly-slider/assemblyslider' != $hook ) {
		return;
	}
	wp_register_script( 'assembly-slider-script-js', plugins_url( '/js/assembly-slider-admin-script.js', __FILE__ ), array(), '1.0.0', true );
	wp_enqueue_script( 'assembly-slider-script-js' );

	wp_enqueue_media();
	wp_register_script( 'assembly-slider-media-js', plugins_url( '/js/assembly-slider-media.js', __FILE__ ), array( 'jquery' ), '1.0.0', true );
	wp_enqueue_script( 'assembly-slider-media-js' );

	wp_enqueue_script( 'ajax-script', plugins_url( '/js/ajax.js', __FILE__ ), array( 'jquery' ) );
	wp_localize_script( 'ajax-script', 'ajax_object',
		array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

	wp_register_style( 'assembly-slider-admin-style', plugins_url( '/css/assembly-slider-admin-style.css', __FILE__ ), array(), '1.0.0' );
	wp_enqueue_style( 'assembly-slider-admin-style' );
}

//Add scripts and styles doc
add_action( 'wp_enqueue_scripts', 'assembly_slider_enqueue' );
function assembly_slider_enqueue() {
	wp_register_script( 'assembly-slider-base-js', plugins_url( '/js/assembly-slider.js', __FILE__ ), array(), '1.0.0', true );
	wp_enqueue_script( 'assembly-slider-base-js' );
	wp_register_style( 'assembly-slider-style', plugins_url( '/css/assembly-slider-style.css', __FILE__ ), array(), '1.0.0' );
	wp_enqueue_style( 'assembly-slider-style' );
}

//Shortcode for slider
require_once( 'assembly-slider-shortcode.php' );

//Main function for admin
function assembly_slider_admin() {
	require_once( 'assembly-slider-admin.php' );
}

//AJAXTEST*****************************************************
add_action( 'wp_ajax_rgm_slider', 'rgm_slider' );

/**
 *AJAX function used to inesrt/update DB
 */
function rgm_slider() {
	global $wpdb;
	$slider = json_decode( stripslashes( $_POST['slider'] ) );

	$style_table_name  = $wpdb->prefix . 'assembly_styles';
	$layer_table_name  = $wpdb->prefix . 'assembly_layers';
	$slide_table_name  = $wpdb->prefix . 'assembly_slides';
	$slider_table_name = $wpdb->prefix . 'assembly_sliders';


	echo $slider->slides[0]->layers[0]->css[0]->style_id;
	/*
	 * BUG :: b_control/lr_control values sporadically come in as null.
	 */
	$bControl = $slider->b_control;
	if ( is_null( $slider->b_control ) ) {
		$bControl = true;
	}
	$wpdb->replace( $slider_table_name, array(
		'slider_id'    => $slider->slider_id,
		'slider_name'  => $slider->slider_name,
		'date_created' => $slider->date_created,
		'size'         => $slider->size,
		'speed'        => $slider->speed,
		'b_control'    => $bControl,
		'lr_control'   => $slider->lr_control,
	) );

	foreach ( $slider->slides as $slide ) {
		$wpdb->replace( $slide_table_name, array(
			'slide_id'     => $slide->slide_id,
			'date_created' => $slide->date_created,
			'slide_order'  => $slide->slide_order,
			'bg'           => $slide->bg,
//			'transition'   => $slide->transition, //Defaulted to 'full'. Add size functionality
			'slider_id'    => $slide->slider_id,
		) );

		foreach ( $slide->layers as $layer ) {
			$wpdb->replace( $layer_table_name, array(
				'layer_id'        => $layer->layer_id,
				'date_created'    => $layer->date_created,
				'type'            => $layer->type,
				'content'         => $layer->content,
				'inner_classList' => $layer->inner_classList,
				'stationary'      => $layer->stationary,
				'static'          => $layer->static,
				'slide_id'        => $layer->slide_id,
			) );

			foreach ( $layer->css as $style ) {
				$wpdb->replace( $style_table_name, array(
					'style_id'      => $style->style_id,
					'date_created'  => $style->date_created,
					'media_query'   => $style->media_query,
					'top'           => $style->top,
					'left'          => $style->left,
					'px_width'      => $style->px_width,
					'perc_width'    => $style->perc_width,
					'max_width'     => $style->max_width,
					'min_width'     => $style->min_width,
					'px_height'     => $style->px_height,
					'perc_height'   => $style->perc_height,
					'max_height'    => $style->max_height,
					'min_height'    => $style->min_height,
					'transform'     => $style->transform,
					'border'        => $style->border,
					'border_radius' => $style->border_radius,
					'background'    => $style->background,
					'opacity'       => $style->opacity,
					'text_align'    => $style->text_align,
					'font_family'   => $style->font_family,
					'font_weight'   => $style->font_weight,
					'font_size'     => $style->font_size,
					'font_style'    => $style->font_style,
					'color'         => $style->color,
					'z_index'       => $style->z_index,
					'layer_id'      => $style->layer_id,
				) );
			}

		}

	}
	wp_die();
}

//*************************************************************

/**
 * Retrieve slider from DB
 *
 * @param $sliderName
 *
 * @return array|null|object|void
 */
function getSlider( $sliderName ) {
	global $wpdb;
	$rgmSlider        = $wpdb->get_row( "SELECT * FROM wp_assembly_sliders WHERE slider_name = '{$sliderName}'" );
	$selectedSliderID = $rgmSlider->slider_id;
	$slides           = $wpdb->get_results( "SELECT * FROM wp_assembly_slides WHERE slider_id = '{$selectedSliderID}' ORDER BY slide_order" );

	foreach ( $slides as $slide ) {
		$rgmSlider->slides[] = $slide;
		$layers              = $wpdb->get_results( "SELECT * FROM wp_assembly_layers WHERE slide_id = '{$slide->slide_id}'" );
		$slide->layers;

		foreach ( $layers as $layer ) {
			$slide->layers[] = $layer;
			$styles          = $wpdb->get_results( "SELECT * FROM wp_assembly_styles WHERE layer_id = '{$layer->layer_id}'" );
			$layer->css;

			foreach ( $styles as $style ) {
				$layer->css[] = $style;
			}
		}
	}

	return $rgmSlider;
}

/**
 * @param $css object which contains key:value pairs of css styles
 *
 * @return string Formatted CSS code
 */
function serializeCSSFormated( $css ) {
	$selector = '#';
	$style    = '';
	foreach ( $css as $key => $value ) {
		if ( $key == 'layer_id' ) {
			$selector .= $value . "\n";
		} else if ( $key != 'style_id' && $key != 'date_created'
		            && $key != 'media_query' && $key != 'layer_id' && $value != ''
		) {
			$style .= str_repeat( "&nbsp;", 5 ) . $key . ": " . $value . ";\n";
		}
	}
	$style .= '}';

	return $selector . $style;
}