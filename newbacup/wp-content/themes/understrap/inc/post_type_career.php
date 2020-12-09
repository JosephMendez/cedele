<?php
function create_post_type_career(){
	//register custom post
	$labels = array(
		'name' => __('Career', 'inwavethemes'),
		'singular_name' => __('Career', 'inwavethemes'),
		'add_new' => __('Add New Career', 'inwavethemes'),
		'add_new_item' => __('Add New Career', 'inwavethemes'),
		'edit' => __('Edit', 'inwavethemes'),
		'edit_item' => __('Edit Career', 'inwavethemes'),
		'new_item' => __('New Career', 'inwavethemes'),
		'view' => __('View Career', 'inwavethemes'),
		'view_item' => __('View Career', 'inwavethemes'),
		'search_items' => __('Search Career', 'inwavethemes'),
		'not_found' => __('No Career Found', 'inwavethemes'),
		'not_found_in_trash' => __('No Career in trash', 'inwavethemes')
	);
	$args = array(
		'labels' => $labels,
		'public' 			=> true,
		'hierarchical' 		=> false,
		'has_archive'		=> true,
		'supports' 			=> array('title', 'editor', 'thumbnail'),
		'can_export'	 	=> true,
		'rewrite' 			=> array('slug' => 'career', 'with_front' => true),
		'query_var' 		=> false,
		'show_in_nav_menus' => true,
		'taxonomies' => array('career_category')
	);
	register_post_type('career', $args);

	register_taxonomy(
		'career_category',
		'career',
		array(
			'hierarchical' => true,
			'label' => 'Category Career',
			'query_var' => true,
			'show_admin_column' => true,
			'rewrite' => array(
				'slug' => 'career-category',
				'with_front' => false
			)
		)
	);


	$labels_tag = array(
		'name' => _x( 'Career Tags', 'taxonomy general name' ),
		'singular_name' => _x( 'Career Tag', 'taxonomy singular name' ),
		'search_items' => __( 'Search Career Tags' ),
		'popular_items' => __( 'Popular Career Tags' ),
		'all_items' => __( 'All Tags' ),
		'parent_item' => null,
		'parent_item_colon' => null,
		'edit_item' => __( 'Edit Tag' ),
		'update_item' => __( 'Update Tag' ),
		'add_new_item' => __( 'Add New Tag' ),
		'new_item_name' => __( 'New Tag Name' ),
		'separate_items_with_commas' => __( 'Separate tags with commas' ),
		'add_or_remove_items' => __( 'Add or remove tags' ),
		'choose_from_most_used' => __( 'Choose from the most used tags' ),
		'menu_name' => __( 'Tags' ),
	);
	register_taxonomy('career_tag','career',array( // post type name here
		'hierarchical' => false,
		'labels' => $labels_tag,
		'show_ui' => true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var' => true,
		'rewrite' => array( 'slug' => 'career-tag' ),
	));
}
add_action('init', 'create_post_type_career');


function _career_post_create_metabox() {
	add_meta_box(
		'_career_post_metabox', // Metabox ID
		'Addition infomation', // Title to display
		'_career_post_render_metabox', // Function to call that contains the metabox content
		'career', // Post type to display metabox on
		'normal', // Where to put it (normal = main colum, side = sidebar, etc.)
		'default' // Priority relative to other metaboxes
	);
}
add_action( 'add_meta_boxes', '_career_post_create_metabox' );


function _career_post_defaults() {
	return array(
		'store_location' => '',
        'pdf_file' => '',
//        'item_3' => 5,
	);
}

function _career_post_render_metabox() {

	// Variables
	global $post; // Get the current post data
	$saved = get_post_meta( $post->ID, '_career_post', true ); // Get the saved values
	$defaults = _career_post_defaults(); // Get the default values
	$details = wp_parse_args( $saved, $defaults ); // Merge the two in case any fields don't exist in the saved data

	?>

	<fieldset>
		<style>
			.tableMetaBox{
				width: 100%;
			}
			.tableMetaBox td{
				padding: 5px;
			}
			.tableMetaBox td.tdLabel{
				width: 100px;
			}
			.tableMetaBox input.txt_text{
				width: 100%;
				max-width: 300px;
			}
			.browser_pdf_file, .remove_pdf_file{
				height: 30px;
				margin-top: 10px;
				background: #0abd0a;
				border: none;
				padding: 0 15px;
				color: #fff;
				border-radius: 4px;
				outline: none;
				cursor: pointer;
			}
			.remove_pdf_file{
				background: #d80c0c;
			}

		</style>

		<table class="tableMetaBox">
			<tbody>
			<tr>
				<td class="tdLabel">
					<label for="_career_post_custom_metabox_store_location">
						<?php _e( 'Store Location', '_career_post' ); ?>
					</label>
				</td>
				<td>
					<input
						type="text"
						name="_career_post_custom_metabox[store_location]"
						id="_career_post_custom_metabox_store_location"
						class="txt_text"
						value="<?php echo esc_attr($details['store_location']) ? esc_attr($details['store_location']) :''; ?>" />
				</td>
			</tr>
			<tr>
				<td class="tdLabel">
					<label>
						<?php _e( 'PDF File', '_career_post' ); ?>
					</label>
				</td>
				<td>
					<input id="choosen_pdf_file" type="text" class="txt_text"
						   name="_career_post_custom_metabox[pdf_file]"
						   value="<?php echo esc_attr($details['pdf_file']) ? esc_attr($details['pdf_file']) :''; ?>" />
					<br />

					<?php $style = 'style="display:none;"' ?>
					<button class="browser_pdf_file" type="button"
						<?php echo $details['pdf_file'] ? $style :''; ?>>Choose PDF File</button>
					<button class="remove_pdf_file" type="button"
						<?php echo !$details['pdf_file'] ? $style :''; ?>>Remove PDF File</button>
				</td>
			</tr>
			</tbody>
		</table>

	</fieldset>

	<?php
	wp_nonce_field( '_career_post_form_metabox_nonce', '_career_post_form_metabox_process' );

}


function _career_post_save_metabox( $post_id, $post ) {

	// Verify that our security field exists. If not, bail.
	if ( !isset( $_POST['_career_post_form_metabox_process'] ) ) return;

	// Verify data came from edit/dashboard screen
	if ( !wp_verify_nonce( $_POST['_career_post_form_metabox_process'], '_career_post_form_metabox_nonce' ) ) {
		return $post->ID;
	}

	// Verify user has permission to edit post
	if ( !current_user_can( 'edit_post', $post->ID )) {
		return $post->ID;
	}

	// Check that our custom fields are being passed along
	// This is the `name` value array. We can grab all
	// of the fields and their values at once.
	if ( !isset( $_POST['_career_post_custom_metabox'] ) ) {
		return $post->ID;
	}

	/**
	 * Sanitize all data
	 * This keeps malicious code out of our database.
	 */

	// Set up an empty array
	$sanitized = array();

	// Loop through each of our fields
	foreach ( $_POST['_career_post_custom_metabox'] as $key => $detail ) {
		// Sanitize the data and push it to our new array
		// `wp_filter_post_kses` strips our dangerous server values
		// and allows through anything you can include a post.
		$sanitized[$key] = wp_filter_post_kses( $detail );
	}
	// Save our submissions to the database
	update_post_meta( $post->ID, '_career_post', $sanitized );
}
add_action( 'save_post', '_career_post_save_metabox', 1, 2 );





function upload_pdf_script() {
	if ( ! did_action( 'wp_enqueue_media' ) ) {
		wp_enqueue_media();
	}
	wp_enqueue_script( 'myuploadscript', get_stylesheet_directory_uri() . '/js/customscript.js', array('jquery'), null, false );
}
add_action( 'admin_enqueue_scripts', 'upload_pdf_script' );


?>
