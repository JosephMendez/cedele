<?php
$postMeta = get_post_meta( get_the_ID(), '_career_post', true );
?>

<article id="post-<?php echo esc_attr(get_the_ID()); ?>" <?php post_class(); ?>>
	<div class="post-item">
		<div class="post-content">
			<div class="post-content-detail">
				<div class="post-location"><?php echo $postMeta['store_location']; ?></div>
				<h3 class="post-title"><?php the_title(); ?></h3>

			</div>
			<div class="post-pdf">
				<a href="<?php echo $postMeta['pdf_file']; ?>">Full Description</a>
			</div>
		</div>
	</div>
</article>
