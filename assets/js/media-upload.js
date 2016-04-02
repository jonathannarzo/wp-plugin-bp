jQuery(document).ready(function($){
	var mediaUploader;
	$(document).on('click', '#upload-button', function(e){
		e.preventDefault();
		if (mediaUploader) {
			mediaUploader.open();
			return;
		}
		mediaUploader = wp.media.frames.file_frame = wp.media({
			title: 'Choose Photos',
			button: {
				text: 'Choose picture'
			},
			multiple: true
		});
		mediaUploader.on('select', function(){
			var images = [];
			var i = 0;
			var image_view = [];
            var selection = mediaUploader.state().get('selection');
            selection.map(function(attachment){
                attachment = attachment.toJSON();
                images.push(attachment.url);
                image_view.push('<img src="'+attachment.url+'" style="width:200px;" />');
            });
            $('#images-selected').val(images.join());
            $('#twbs_photos_to_upload').html(image_view.join(''));
            $('#twbs_count_selection').html('(' + images.length + ' Files selected to upload)');
            if (images.length > 0) {
            	$('#save_selected_photos').prop('disabled', false);
            } else {
            	$('#save_selected_photos').prop('disabled', true);
            }
		});
		mediaUploader.open();
	});
});