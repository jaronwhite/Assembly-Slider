<?php

global $as_db_version, $wpdb;
$as_db_version = '1.0';


function as_install() {
	global $wpdb, $as_db_version;
	$style_table_name  = $wpdb->prefix . 'assembly_styles';
	$layer_table_name  = $wpdb->prefix . 'assembly_layers';
	$slide_table_name  = $wpdb->prefix . 'assembly_slides';
	$slider_table_name = $wpdb->prefix . 'assembly_sliders';
	$wpdb->sliders     = $slider_table_name;
	$wpdb->slides      = $slide_table_name;
	$wpdb->layers      = $layer_table_name;
	$wpdb->styles      = $style_table_name;

	$charset_collate = $wpdb->get_charset_collate();

	$sql = <<<QUERY
CREATE TABLE $slider_table_name (
		slider_id VARCHAR(19) NOT NULL,
		slider_name tinytext NOT NULL,
		date_created datetime NOT NULL,
		size VARCHAR(20) DEFAULT 'full' NOT NULL,
		speed VARCHAR(10) DEFAULT '6000' NOT NULL, 
		b_control BOOLEAN DEFAULT true,
		lr_control BOOLEAN DEFAULT false,
		PRIMARY KEY  (slider_id)
	) $charset_collate;
	
	CREATE TABLE $slide_table_name (
		slide_id VARCHAR(18) NOT NULL,
		date_created datetime NOT NULL,
		slide_order int NOT NULL,
		bg text NOT NULL ,
		transition VARCHAR(20) DEFAULT 'fade' NOT NULL ,
		slider_id VARCHAR(19) NOT NULL,
		PRIMARY KEY  (slide_id),
		FOREIGN KEY  (slider_id) REFERENCES $slider_table_name(slider_id)  
	) $charset_collate;
	
	CREATE TABLE $layer_table_name (
        layer_id VARCHAR(18) NOT NULL,
		date_created datetime NOT NULL,
		type ENUM('image','text','button','filter') DEFAULT 'image' NOT NULL,
		content text NOT NULL,
		inner_classList VARCHAR(35),
		stationary BOOLEAN NOT NULL,
		static BOOLEAN NOT NULL,
		slide_id VARCHAR(18) NOT NULL,
		PRIMARY KEY  (layer_id),
		FOREIGN KEY  (slide_id) REFERENCES $slide_table_name(slide_id)
	) $charset_collate;
	
	CREATE TABLE $style_table_name (
        style_id INT NOT NULL,
		date_created datetime NOT NULL,
		media_query TEXT,
		top VARCHAR(6), 
		`left` VARCHAR(6), 
		px_width VARCHAR(6), 
		perc_width VARCHAR(6), 
		max_width VARCHAR(6), 
		min_width VARCHAR(6), 
		px_height VARCHAR(6), 
		perc_height VARCHAR(6), 
		max_height VARCHAR(6),
        min_height VARCHAR(6), 
        transform TEXT, 
        border TEXT, 
        border_radius VARCHAR(6), 
        background TEXT, 
        opacity DOUBLE, 
        text_align VARCHAR(7), 
        font_family VARCHAR(55), 
        font_weight VARCHAR(7),
        font_size VARCHAR(7), 
        font_style VARCHAR(7), 
        color VARCHAR(25), 
        z_index INT(4),
		layer_id VARCHAR(18) NOT NULL,
		PRIMARY KEY  (style_id),
		FOREIGN KEY  (layer_id) REFERENCES $layer_table_name(layer_id)
	) $charset_collate;
	
QUERY;


	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	add_option( 'as_db_version', $as_db_version );

	$installed_ver = get_option( 'as_db_version' );

	if ( $installed_ver != $as_db_version ) {

		$sql = <<<QUERY
CREATE TABLE $slider_table_name (
		slider_id VARCHAR(19) NOT NULL,
		slider_name tinytext NOT NULL,
		date_created datetime NOT NULL,
		size VARCHAR(20) DEFAULT 'full' NOT NULL,
		speed VARCHAR(10) DEFAULT '6000' NOT NULL, 
		b_control BOOLEAN DEFAULT true NOT NULL,
		lr_control BOOLEAN DEFAULT false NOT NULL,
		PRIMARY KEY  (slider_id)
	) $charset_collate;
	
	CREATE TABLE $slide_table_name (
		slide_id VARCHAR(18) NOT NULL,
		date_created datetime NOT NULL,
		slide_order int NOT NULL,
		bg text NOT NULL ,
		transition VARCHAR(20) DEFAULT 'fade' NOT NULL ,
		slider_id VARCHAR(19) NOT NULL,
		PRIMARY KEY  (slide_id),
		FOREIGN KEY  (slider_id) REFERENCES $slider_table_name(slider_id)  
	) $charset_collate;
	
	CREATE TABLE $layer_table_name (
        layer_id VARCHAR(18) NOT NULL,
		date_created datetime NOT NULL,
		type ENUM('image','text','button','filter') DEFAULT 'image' NOT NULL,
		content text NOT NULL,
		inner_classList VARCHAR(35),
		stationary BOOLEAN NOT NULL,
		static BOOLEAN NOT NULL,
		slide_id VARCHAR(18) NOT NULL,
		PRIMARY KEY  (layer_id),
		FOREIGN KEY  (slide_id) REFERENCES $slide_table_name(slide_id)
	) $charset_collate;
	
	CREATE TABLE $style_table_name (
        style_id INT NOT NULL AUTO_INCREMENT,
		date_created datetime NOT NULL,
		media_query TEXT,
		top VARCHAR(6), 
		`left` VARCHAR(6), 
		px_width VARCHAR(6), 
		perc_width VARCHAR(6), 
		max_width VARCHAR(6), 
		min_width VARCHAR(6), 
		px_height VARCHAR(6), 
		perc_height VARCHAR(6), 
		max_height VARCHAR(6),
        min_height VARCHAR(6), 
        transform TEXT, 
        border TEXT, 
        border_radius VARCHAR(6), 
        background TEXT, 
        opacity DOUBLE, 
        text_align VARCHAR(7), 
        font_family VARCHAR(55), 
        font_weight VARCHAR(7),
        font_size VARCHAR(7), 
        font_style VARCHAR(7), 
        color VARCHAR(25), 
        z_index INT(4),
		layer_id VARCHAR(18) NOT NULL,
		PRIMARY KEY  (style_id),
		FOREIGN KEY  (layer_id) REFERENCES $layer_table_name(layer_id)
	) $charset_collate;
	
QUERY;

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		update_option( 'as_db_version', $as_db_version );
	}
}

function as_install_data() {
	global $wpdb;
	$style_table_name  = $wpdb->prefix . 'assembly_styles';
	$layer_table_name  = $wpdb->prefix . 'assembly_layers';
	$slide_table_name  = $wpdb->prefix . 'assembly_slides';
	$slider_table_name = $wpdb->prefix . 'assembly_sliders';

	$milliseconds = round( microtime( true ) * 1000 );
	$time         = current_time( 'mysql' );

	$slide1bg = 'url(' . plugins_url( '/img/hspvuakrjqs-kelly-sikkema.jpg', __FILE__ ) . ') center no-repeat';
	$slide2bg = 'url(' . plugins_url( '/img/ujgzssp-czy-jeremy-bishop.jpg', __FILE__ ) . ') center no-repeat';
	$slide3bg = 'url(' . plugins_url( '/img/vphcfunwdrq-emma-hall.jpg', __FILE__ ) . ') center no-repeat';

	$layer1content = plugins_url( '/img/Assembly_logo.png', __FILE__ );


	//Default slider
	$wpdb->insert(
		$slider_table_name,
		array(
			'slider_id'    => 'slider' . $milliseconds,
			'date_created' => $time,
			'slider_name'  => 'assembly',
		)
	);

	//Default slide 1
	$wpdb->insert(
		$slide_table_name,
		array(
			'slide_id'     => 'slide' . $milliseconds,
			'date_created' => $time,
			'slide_order'  => 0,
			'bg'           => $slide1bg,
			'slider_id'    => 'slider' . $milliseconds,
		)
	);

	//Default slide 2
	$wpdb->insert(
		$slide_table_name,
		array(
			'slide_id'     => 'slide' . ( $milliseconds + 1 ),
			'date_created' => $time,
			'slide_order'  => 1,
			'bg'           => $slide2bg,
			'slider_id'    => 'slider' . $milliseconds,
		)
	);

	//Default slide 3
	$wpdb->insert(
		$slide_table_name,
		array(
			'slide_id'     => 'slide' . ( $milliseconds + 2 ),
			'date_created' => $time,
			'slide_order'  => 2,
			'bg'           => $slide3bg,
			'slider_id'    => 'slider' . $milliseconds,
		)
	);

	//Default layer 1
	$wpdb->insert(
		$layer_table_name,
		array(
			'layer_id'        => 'layer' . ( $milliseconds + 3 ),
			'date_created'    => $time,
			'type'            => 'image',
			'content'         => $layer1content,
			'inner_classList' => 'horz',
			'stationary'      => false,
			'static'          => false,
			'slide_id'        => 'slide' . $milliseconds,
		)
	);

	//Default layer 2
	$wpdb->insert(
		$layer_table_name,
		array(
			'layer_id'        => 'layer' . ( $milliseconds + 4 ),
			'date_created'    => $time,
			'type'            => 'text',
			'content'         => 'Slide 2',
			'inner_classList' => 'text-middle',
			'stationary'      => false,
			'static'          => false,
			'slide_id'        => 'slide' . ( $milliseconds + 1 ),
		)
	);

	//Default layer 3
	$wpdb->insert(
		$layer_table_name,
		array(
			'layer_id'        => 'layer' . ( $milliseconds + 5 ),
			'date_created'    => $time,
			'type'            => 'text',
			'content'         => 'Slide 3',
			'inner_classList' => 'text-middle',
			'stationary'      => false,
			'static'          => false,
			'slide_id'        => 'slide' . ( $milliseconds + 2 ),
		)
	);

	$wpdb->insert(
		$style_table_name,
		array(
			'style_id'      => 'css' . ( $milliseconds ),
			'date_created'  => $time,
			'media_query'   => 'mobile',
			'top'           => '50%',
			'left'          => '50%',
			'px_width'      => '310px',
			'perc_width'    => '',
			'max_width'     => '',
			'min_width'     => '',
			'px_height'     => '310px',
			'perc_height'   => '',
			'max_height'    => '',
			'min_height'    => '',
			'transform'     => 'translate(-50%, -50%)',
			'border'        => '',
			'border_radius' => '',
			'background'    => '',
			'opacity'       => '1',
			'text_align'    => '',
			'font_family'   => '',
			'font_weight'   => '',
			'font_size'     => '',
			'font_style'    => '',
			'color'         => '',
			'z_index'       => '',
			'layer_id'      => 'layer' . ( $milliseconds + 3 ),
		)
	);

	$wpdb->insert(
		$style_table_name,
		array(
			'style_id'      => 'css' . ( $milliseconds + 1 ),
			'date_created'  => $time,
			'media_query'   => 'mobile',
			'top'           => '50%',
			'left'          => '50%',
			'px_width'      => '',
			'perc_width'    => '50%',
			'max_width'     => '',
			'min_width'     => '',
			'px_height'     => '',
			'perc_height'   => '50%',
			'max_height'    => '',
			'min_height'    => '',
			'transform'     => 'translate(-50%, -50%)',
			'border'        => '',
			'border_radius' => '',
			'background'    => '',
			'opacity'       => '1',
			'text_align'    => 'center',
			'font_family'   => 'sans-serif',
			'font_weight'   => 'regular',
			'font_size'     => '1.5em',
			'font_style'    => '',
			'color'         => 'white',
			'z_index'       => '',
			'layer_id'      => 'layer' . ( $milliseconds + 4 ),
		)
	);

	$wpdb->insert(
		$style_table_name,
		array(
			'style_id'      => 'css' . ( $milliseconds + 2 ),
			'date_created'  => $time,
			'media_query'   => 'mobile',
			'top'           => '50%',
			'left'          => '50%',
			'px_width'      => '',
			'perc_width'    => '50%',
			'max_width'     => '',
			'min_width'     => '',
			'px_height'     => '',
			'perc_height'   => '50%',
			'max_height'    => '',
			'min_height'    => '',
			'transform'     => 'translate(-50%, -50%)',
			'border'        => '',
			'border_radius' => '',
			'background'    => '',
			'opacity'       => '1',
			'text_align'    => 'center',
			'font_family'   => 'sans-serif',
			'font_weight'   => 'regular',
			'font_size'     => '1.5em',
			'font_style'    => '',
			'color'         => 'white',
			'z_index'       => '',
			'layer_id'      => 'layer' . ( $milliseconds + 5 ),
		)
	);
}

function slider_update_db_check() {
	global $as_db_version;
	if ( get_site_option( 'as_db_version' ) != $as_db_version ) {
		as_install();
	}
}

add_action( 'plugins_loaded', 'slider_update_db_check' );