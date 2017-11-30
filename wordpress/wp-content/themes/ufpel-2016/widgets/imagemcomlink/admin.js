// http://mikejolley.com/2012/12/using-the-new-wordpress-3-5-media-uploader-in-plugins/
// Uploading files
var file_frame;
var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
var set_to_post_id = 10; // Set this

jQuery('.upload_image_button').live('click', function( event ){
    var id = jQuery(this).attr("id");
    event.preventDefault();

    // If the media frame already exists, reopen it.
    if ( file_frame ) {
    // Set the post ID to what we want
    file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
    // Open frame
    file_frame.open();
    return;
    } else {

    // Set the wp.media post id so the uploader grabs the ID we want when initialised
    wp.media.model.settings.post.id = set_to_post_id;

    }

    // Create the media frame.
    file_frame = wp.media.frames.file_frame = wp.media({
        title: jQuery( this ).data( 'uploader_title' ),
    button: {
        text: jQuery( this ).data( 'uploader_button_text' ),
    },
    multiple: false // Set to true to allow multiple files to be selected
    });


    // When an image is selected, run a callback.
    file_frame.on( 'close', function() {
        // We set multiple to false so only get one image from the uploader
        var attachment = file_frame.state().get('selection').first().toJSON();

        jQuery("#imagem_anexo_"+id+" div").html('<img src="'+attachment.sizes.thumbnail.url+'" alt="Imagem anexada" />');
        jQuery("#imagem_anexo_"+id+" input").val(attachment.sizes.thumbnail.url);
        jQuery(".upload_image_button").val("Alterar imagem");
        //console.log(attachment.sizes);
        // Do something with attachment.id and/or attachment.url here
        // Restore the main post ID
        wp.media.model.settings.post.id = wp_media_post_id;
    });



// Finally, open the modal
file_frame.open();
});
// Restore the main ID when the add media button is pressed
jQuery('a').live('click', function( event ){

    //wp.media.model.settings.post.id = wp_media_post_id;
});
