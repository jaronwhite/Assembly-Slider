<?php

class Assembly_Contact_Widget extends WP_Widget {

	public function __construct() {
		$widget_ops = array(
			'classname'   => 'aw_contact',
			'description' => 'Add a contact form to your sidebar.'
		);
		parent::__construct( 'aw_contact', 'Assembly Contact', $widget_ops );
	}

	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$path  = 'C:/wamp64/www/hra/wp-content/plugins/rgm_assembly-slider/assembly-send-form.php';

		echo $args['before_widget'];
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		} ?>

		<form id="aw_contact" action="<?php echo $path; ?>" method="post">
			<input type="text" class="aw_contact-input" name="name" placeholder="Name"/>
			<input type="email" class="aw_contact-input" name="email" placeholder="Email"/>
			<textarea class="aw_contact-textarea" name="message" placeholder="Enter your message."></textarea>
			<input type="submit" id="aw_contact-btn" name="aw_contact-btn" value="Send Message"/>
		</form>

		<?php
		echo $args['after_widget'];
	}

	public function form( $instance ) {
		$defaults = array(
			'title'  => '',
			'emails' => ''
		);

		$title  = $instance['title'];
		$emails = $instance['emails'];
		?>

		<p>
			<label for="<?php echo $this->get_field_name( 'title' ); ?>">Title:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" class="widefat"
			       name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $title; ?>"/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_name( 'emails' ); ?>">Email(s) to send message:</label>
            <textarea id="<?php echo $this->get_field_id( 'emails' ); ?>" class="widefat"
                      name="<?php echo $this->get_field_name( 'emails' ); ?>" value="<?php echo $emails; ?>"></textarea>
		</p>

		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance           = array();
		$instance['title']  = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['emails'] = ( ! empty( $new_instance['emails'] ) ) ? strip_tags( $new_instance['emails'] ) : '';

		return $instance;
	}

}

add_action( 'widgets_init', 'aw_contact_widget_init' );
function aw_contact_widget_init() {
	register_widget( 'Assembly_Contact_Widget' );
}

?>