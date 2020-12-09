jQuery(document).ready(function($) {
	jQuery('body').on('click', '.cdls-upload-div', function(event) {
		var that = this
		event.preventDefault();

		var image = wp.media({ 
			title: 'Upload Image',
			library: {
				type: 'image'
			},
			multiple: false
		}).open()
		.on('select', function(e){
			var uploaded_image = image.state().get('selection').first();
			jQuery(that).find('img').attr('src', uploaded_image.attributes.url);
			jQuery(that).siblings('input[type="hidden"]').val(uploaded_image.attributes.id);
			check_has_image();
		});
	});

	jQuery('body').on('click', '.cdls-button-clear-attachment', function(event) {
		var imageItem = jQuery(this).parents('.config-image');

		jQuery(imageItem).find('img').attr('src', '');
		jQuery(imageItem).find('input[type="hidden"]').val('');
		check_has_image();
	});

	check_has_image();
	function check_has_image() {
		jQuery('.cdls-config-images img').each(function(index, el) {
			var imageSrc = jQuery(this).attr('src');
			var imageItem = jQuery(this).parents('.config-image');

			if (imageSrc) {
				jQuery(imageItem).find('.cdls-button-clear-attachment').show();

				var hasImage = jQuery(imageItem).find('.cdls-upload-div').hasClass('has-image');
				if (!hasImage) {
					jQuery(imageItem).find('.cdls-upload-div').addClass('has-image');
				}
			} else {
				jQuery(imageItem).find('.cdls-button-clear-attachment').hide();
				jQuery(imageItem).find('.cdls-upload-div').removeClass('has-image');
			}
		});
	}
});
