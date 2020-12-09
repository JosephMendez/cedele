jQuery(document).ready(function() {
	// if (jQuery('.blog-template').length) {
	// 	jQuery('.blog-template .select-category-blog').select2();
	//
	// 	// override the select2 open event
	// 	jQuery('.blog-template .select-category-blog').on('select2:open', function () {
	//
	// 	});
	//
	// }

	setTimeout(function () {
		var category = jQuery('.current-cat a').html();
		if (category === undefined) {
			jQuery(".blog-template .select-category-blog option:first-child").prop("selected", "selected");
			jQuery('.select-category-blog .select2-selection__rendered').html('Category');
		} else {
			jQuery('.blog-template .select-category-blog option').each(function() {
				if (jQuery(this).html() === category) {
					jQuery(this).prop("selected", "selected");
					jQuery('.select-category-blog .select2-selection__rendered').html(category);
				}
			});
		}
	},100);

	jQuery('body').on("change", ".blog-template .select-category-blog", function(e) {
		if (jQuery(this).val()) {
			window.location.href = jQuery(this).val();
		}
	});
});
