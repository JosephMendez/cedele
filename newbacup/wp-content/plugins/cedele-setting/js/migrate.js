jQuery(document).ready(function($) {
	jQuery('body').on('click', '.cdls-input-file-button', function(event) {
		var that = this
		event.preventDefault();

		var upload = wp.media({ 
			title: 'Upload Image',
			multiple: false
		}).open()
		.on('select', function(e){
			var uploaded_file = upload.state().get('selection').first();
			jQuery('.cdls-input-file-hidden').val(uploaded_file.attributes.id);
		});
	});

	jQuery('body').on('click', '.cdls-button-clear-attachment-migrate', function(event) {
		var imageItem = jQuery(this).parents('.config-migrate');

		jQuery(imageItem).find('img').attr('src', '');
		jQuery(imageItem).find('input[type="hidden"]').val('');
		check_has_image();
	});

	check_has_image();
	function check_has_image() {
		jQuery('.cdls-migrate img').each(function(index, el) {
			var imageSrc = jQuery(this).attr('src');
			var imageItem = jQuery(this).parents('.config-migrate');

			if (imageSrc) {
				jQuery(imageItem).find('.cdls-button-clear-attachment-migrate').show();

				var hasImage = jQuery(imageItem).find('.cdls-upload-div-migrate').hasClass('has-image');
				if (!hasImage) {
					jQuery(imageItem).find('.cdls-upload-div-migrate').addClass('has-image');
				}
			} else {
				jQuery(imageItem).find('.cdls-button-clear-attachment-migrate').hide();
				jQuery(imageItem).find('.cdls-upload-div-migrate').removeClass('has-image');
			}
		});
	}

	var limit = 10;

	var current_percent = 0;
	var current_readable = 0;
	var current_start_from = 2;
	var total_rows = 9999999;

	jQuery('body').on('click', '.cdls-import-file', function(event) {
		event.preventDefault();
		/* Act on the event */

		current_percent = 0;
		current_readable = 0;
		current_start_from = 2;
		total_rows = 9999999;
		jQuery(this).hide();
		var typeInput = jQuery(this).attr('data');
		jQuery('#img-load').show();
		send_ajax(typeInput);
	});


	function send_ajax(type) {
		var fd = new FormData();
		var file = jQuery(document).find('input[type="file"]');
		var individual_file = file[0].files[0];
		fd.append("file", individual_file);
		var actionFile = type ? 'send_email_func' : 'upload_file';
		fd.append('action', actionFile);
		jQuery.ajax({
			type: 'POST',
			url: ajax_object.ajax_url,
			data: fd,
			contentType: false,
			processData: false,
			success: function(response){
				alert(response);
				jQuery('.cdls-import-file').show();
				jQuery('#img-load').hide();
			},
			error: function (res) {
				console.log(res);
			}
		});
	}

	function set_process_ui(percent) {
		if (percent > 100) percent = 100;
		jQuery('.cdls-migrate__process').width(`${percent}%`);
	}
});
