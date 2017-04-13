jQuery(document).ready(function ($) {
    $('#save-slider').click(function (e) {
        e.preventDefault();
        var data = {
            'action': 'rgm_slider',
            'slider': JSON.stringify(rgmSlider)      // We pass php values differently!
        };
        // We can also pass the url value separately from ajaxurl for front end AJAX implementations
        jQuery.post(ajax_object.ajax_url, data, function (response) {
            alert(response);
        });
    });
});

// $.ajax({
//     // updateHiddenText()
//     url: ajaxurl,
//     type: 'POST',
//     datatype: 'json',
//     data: {
//         action: ''
//     },
//     success: '',
//     error: ''
// });