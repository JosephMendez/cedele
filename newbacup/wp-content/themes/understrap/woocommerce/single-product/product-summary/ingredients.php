<?php
global $product;
$custom_field = get_post_meta($product->get_id());
$args = array(
	'posts_per_page' => -1,
	'orderby' => 'menu_order',
	'order' => 'ASC',
	'post_type' => 'acf-field',
	'post_parent' => 1034,
);
$posts = get_posts($args);
$args = array('post_type' => 'acf-field', 'post_parent' => 1034);
$acfs = get_posts($args);
?>
<table class="table">
	<?php if ($custom_field['ingredients'][0] != null) { ?>
		<tr>
			<td colspan="<?php echo count($acfs)?>">
				<p class="m-0">
					<strong class="sub_title smaller">Ingredients:</strong> <?php echo $custom_field['ingredients'][0] ?>
				</p>
			</td>
		</tr>
	<?php } ?>
	<tr>
		<?php foreach ($acfs as $row) { ?>
			<?php if (isset($custom_field[$row->post_excerpt][0]) && $custom_field[$row->post_excerpt][0] != null) { ?>
				<td>
					<strong class="sub_title smaller"><?php echo $row->post_title ?>
						:</strong> <?php echo $custom_field[$row->post_excerpt][0] ?>
				</td>
			<?php }
		} ?>
	</tr>
</table>
