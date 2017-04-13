<?php

class Assembly_Image_Widget extends WP_Widget {

	public function __construct() {
		$widget_ops = array(
			'classname'   => 'aw_image',
			'description' => 'Display an image in your sidebar.'
		);
		parent::__construct( 'aw_image', 'Assembly Image', $widget_ops );
	}

	public function widget( $args, $instance ) {
		echo $args['before_widget']
		     . '<div class="aw_wrapper">'
		     . '<img id="assembly-image-widget" style="width:100%;max-width: 300px;text-align: center;margin:auto;padding: 0px 40px" src="'
		     . $instance['img_url'] . '" />';

		if ( trim( $instance['caption'] ) != '' ) {
			echo '<p class="aw_caption">'
			     . $instance['caption']
			     . '</p>';
		}
		echo '</div>'
		     . $args['after_widget'];
	}

	public function form( $instance ) {
		$defaults = array(
			'title'   => '',
			'img_url' => '',
			'caption' => ''
		);

		$title   = $instance['title'];
		$img_url = $instance['img_url'];
		$caption = $instance['caption'];
		?>

		<p>
			<label for="<?php echo $this->get_field_name( 'title' ); ?>">Title:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" class="widefat"
			       name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $title; ?>"/>
		</p>
		<p>
			<!--input type="button" id="widget" class="as-choose-img" value="Choose Image" /-->
			<label for="<?php echo $this->get_field_name( 'img_url' ); ?>">Image URL:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'img_url' ); ?>" class="widefat"
			       name="<?php echo $this->get_field_name( 'img_url' ); ?>" value="<?php echo $img_url; ?>"/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_name( 'caption' ); ?>">Caption:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'caption' ); ?>" class="widefat"
			       name="<?php echo $this->get_field_name( 'caption' ); ?>" value="<?php echo $caption; ?>"/>
		</p>
		<div style="text-align: center;padding:5px;">
			<h2>Preview:</h2>
			<div id="aw-img"
			     style="width: 150px;margin: 5px auto 0;background:lightgrey;">
				<img src="<?php echo $img_url; ?>" style="width:100%;"/>
			</div>
			<div><?php echo $caption ?></div>
		</div>

		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance            = array();
		$instance['title']   = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['img_url'] = ( ! empty( $new_instance['img_url'] ) ) ? strip_tags( $new_instance['img_url'] ) : '';
		$instance['caption'] = ( ! empty( $new_instance['caption'] ) ) ? strip_tags( $new_instance['caption'] ) : '';

		return $instance;
	}

}

add_action( 'widgets_init', 'aw_image_widget_init' );
function aw_image_widget_init() {
	register_widget( 'Assembly_Image_Widget' );
}

?>