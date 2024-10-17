jQuery(function($){
    $('body').on('click', '#custom-button-upload', function(e){
        e.preventDefault();
        obj_uploader = wp.media({
            title: 'Custom image',
            button: {
                text: 'Use this image'
            },
            multiple: false
        }).on('select', function() {
            var attachment = obj_uploader.state().get('selection').first().toJSON();
            $('#category_custom_image').html('');
            $('#category_custom_image').html(
                "<img src=" + attachment.url + " style='width: 100%'>"
            );
            $('#category_custom_image_url').val(attachment.url);
            $("#custom-button-upload").hide();
            $("#custom-button-remove").show();
        })
        .open();
    });

    $(".custom-button-remove").click( function() {
        $('#category_custom_image').html('');
        $('#category_custom_image_url').val('');
        $(this).hide();
        $("#custom-button-upload").show();
    });
});