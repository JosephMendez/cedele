jQuery(document).ready(function($) {

    // Perform AJAX login on form submit
    $(document).on('change', '#tagCareerFilter, #catCareerFilter', function(e){

    	var formID = '#filerCareer';

		$.ajax({
			type: 'POST',
			dataType: 'html',
			url: ajax_filter_object.ajaxurl,
			beforeSend: function () {
			},
			data: {
				'action': 'filder_career', //calls wp_ajax_nopriv_ajaxlogin
				'career-filter-cat': $(formID + ' #catCareerFilter').val(),
				'career-filter-tag': $(formID + ' #tagCareerFilter').val(),
				'career-security': $(formID + ' #career-security').val(),
			},
			success: function(data){
				$(formID + ' #listCareer').html(data);
				var numberPost = $('#listCareer article').length;
				$('#numberCareer').text(numberPost);

			}
		});

    });

});
