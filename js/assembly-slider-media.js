/**
 * WP Media Uploader function
 */
jQuery(document).ready(function ($) {
    var mediaUploader;
    $('.media-select').on('click', function (e) {
        e.preventDefault();
        opt = this.id;

        // If the uploader object has already been created, reopen the dialog
        if (mediaUploader) {
            mediaUploader.open();
            return;
        }
        // Extend the wp.media object
        mediaUploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            }, multiple: false
        });

        // When a file is selected, grab the URL and set it as the text field's value
        mediaUploader.on('select', function () {
            attachment = mediaUploader.state().get('selection').first().toJSON();
            if (opt == 'bg-img-select') {
                $('#bg-img-url').val(attachment.url);
                var bg = 'url(' + attachment.url + ') center no-repeat';
                rgmSlider.slides[editSlideIndex].bg = bg;

                //VISUAL
                deviceEmulator.style.background = bg;
                slides[editSlideIndex].style.background = bg;
                deviceEmulator.style.backgroundSize = 'cover';
                slides[editSlideIndex].style.backgroundSize = 'cover';
            } else {
                $('#content-area').val(attachment.url);
            }
        });
        // Open the uploader dialog
        mediaUploader.open();
    });
});