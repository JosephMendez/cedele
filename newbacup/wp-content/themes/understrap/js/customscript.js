jQuery(function($){
	/*
     * Select/Upload image(s) event
     */
	$('body').on('click', '.browser_pdf_file', function(e){
		e.preventDefault();

		var button = $(this),
			custom_uploader = wp.media({
				title: 'Choose PDF File',
				library : {
					type : ['application/pdf']
				},
				button: {
					text: 'Select this file' // button label text
				},
				multiple: false // for multiple image selection set to true
			}).on('select', function() { // it also has "open" and "close" events
				var attachment = custom_uploader.state().get('selection').first().toJSON();
				$('#choosen_pdf_file').val(attachment.url);
				button.hide();
				$('.remove_pdf_file').show();
			})
				.open();
	});

	/*
     * Remove image event
     */
	$('body').on('click', '.remove_pdf_file', function(){
		$('#choosen_pdf_file').val('');

		$('.browser_pdf_file').show();
		$('.remove_pdf_file').hide();
	});

});
