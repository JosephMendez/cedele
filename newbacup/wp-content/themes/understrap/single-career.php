<?php
get_header();


?>

<div class="" style="margin-top: 150px;">
	<?php while (have_posts()) : the_post();
		$postMeta = get_post_meta( get_the_ID(), '_career_post', true );

		var_dump($postMeta);

		?>


	<?php endwhile;?>
</div>

<?php get_footer(); ?>
