<?php
if ( isset( $_POST['aw_contact-btn'] ) ) {
	$to      = '/* This will be variable based on user input */';
	$subject = '/* The subject line determined by user input */';

	$message = 'Name: ' . $_POST['name'] . "\r\n\r\n";
	$message .= 'E-Mail: ' . $_POST['email'] . "\r\n\r\n";
	$message .= 'Message: ' . $_POST['message'];

	//This is the email headers etc.
	$headers = "From: /* User defined from address */ \r\n";

	//Future add HTML content
	$headers .= 'Content-Type: text/plain; charset=utf-8';
	$email = filter_input( INPUT_POST, 'email', FILTER_VALIDATE_EMAIL );
	if ( $email ) {
		$headers .= "\r\nReply-To: $email";
	}
	//$success = mail($to, $subject, $message, $headers, '-finfo@raingaugemedia.com');
}

get_header();
?>

	<div id="sent">
		<?php if ( isset( $success ) && $success ) { ?>
			<h1>Thanks!</h1>
			<p>Your message was sent.</p>
		<?php } else { ?>
			<h1>Uh-Oh!</h1>
			<p>Something went wrong and your message wasn't sent.</p>
		<?php } ?>

	</div>

<?php
get_sidebar();
get_footer();
?>