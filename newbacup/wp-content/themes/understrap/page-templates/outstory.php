<?php
/**
 * Template Name: OurStory
 *
 * @package UnderStrap
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

get_header();
?>
<?php
$banner_ourStory1 = wp_get_attachment_image_src(get_option('sdls_out_story_image1'), 'full');
$banner_ourStory2 = wp_get_attachment_image_src(get_option('sdls_out_story_image2'), 'full');
?>

<?php
$post_cedele = 991;
$post_workspace = 998;
$post_toss = 1003;
$post_chiak = 1008;
$post_slide = 1018;
?>
	<link rel='stylesheet'
		  href="<?php echo get_site_url() . "/wp-content/themes/understrap/css/custom_ourstory.css" ?>"/>
	<style>
		.brands {
			width: 100%;
			background: url("<?php echo $banner_ourStory2[0]; ?>");
		}

		.container_css {
			/*padding-left: 8%;*/
			/*padding-right: 8%;*/
		}
	</style>
	<img class="hero-banner" src="<?php echo $banner_ourStory1[0]; ?>"/>
	<article class="container-fluid">
		<section id="intro">
			<div class="container container_css">
				<div class="d-block d-lg-none">
					<?php $post = get_post($post_slide); ?>
					<a href="<?php echo $post->guid ?>"><h3 class="story-title intro-title mb-3"><?php echo $post->post_title ?></h3>
					</a>
				</div>
				<div class="row">
					<div class="col-12 col-lg-7">
						<div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
							<?php
							if (class_exists('Dynamic_Featured_Image')):
								global $dynamic_featured_image;
								$featured_images = $dynamic_featured_image->get_featured_images(1018);
								if ($featured_images):

									?>
									<div class="carousel-inner">
										<div class="carousel-item active">
											<img class="d-block w-100"
												 src="<?php echo $featured_images[0][full] ?>"
												 alt="First slide">
										</div>

										<div class="carousel-item">
											<img class="d-block w-100"
												 src="<?php echo $featured_images[1][full] ?>"
												 alt="First slide">
										</div>

										<div class="carousel-item">
											<img class="d-block w-100"
												 src="<?php echo $featured_images[2][full] ?>"
												 alt="First slide">
										</div>
										<div class="carousel-item">
											<img class="d-block w-100"
												 src="<?php echo $featured_images[3][full] ?>"
												 alt="First slide">
										</div>
									</div>
								<?php
								endif;
							endif;
							?>
							<a class="carousel-control-prev" href="#carouselExampleControls" role="button"
							   data-slide="prev">
								<span class="carousel-control-prev-icon" aria-hidden="true"></span>
								<span class="sr-only">Previous</span>
							</a>
							<a class="carousel-control-next" href="#carouselExampleControls" role="button"
							   data-slide="next">
								<span class="carousel-control-next-icon" aria-hidden="true"></span>
								<span class="sr-only">Next</span>
							</a>
						</div>
					</div>
					<div class="col-12 col-lg-5">
						<div class="story-text m-0">
							<div class="d-none d-lg-block">
								<a href="<?php echo $post->guid ?>"><h3 class="story-title"><?php echo $post->post_title ?></h3></a>
							</div>
							<p><?php echo $post->post_content ?></p>
						</div>
					</div>
				</div>

			</div>
		</section>
		<section id="brands" class="brands">
			<div class="container container_css">
				<h3 class="story-title">Our Brands</h3>
				<div class="row">

					<div class="col-6 col-lg-3">
						<a href="/" target="_blank">
							<div class="brand-item">
								<img src="<?php echo get_site_url() . '/wp-content/uploads/2020/08/brand-cedele.png' ?>" alt="" class="img-responsive">
                        	</div>
						</a>
					</div>
					<div class="col-6 col-lg-3">
						<a href="http://workspaceespresso.com/" target="_blank">
							<div class="brand-item">
								<img src="<?php echo get_site_url() . '/wp-content/uploads/2020/08/brand-workspace.png' ?>" alt="" class="img-responsive">
							</div>
						</a>
					</div>
					<div class="col-6 col-lg-3">
						<a href="http://www.tossnturnsalad.com/" target="_blank">
							<div class="brand-item">
								<img src="<?php echo get_site_url() . '/wp-content/uploads/2020/08/brand-toss.png' ?>" alt="" class="img-responsive">
							</div>
						</a>
					</div>
					<div class="col-6 col-lg-3">
						<a href="https://sites.google.com/view/chiaksingapore" target="_blank">
							<div class="brand-item">
								<img src="<?php echo get_site_url() . '/wp-content/uploads/2020/08/anh4.png' ?>" alt="" class="img-responsive">
							</div>
						</a>
					</div>
				</div>
			</div>
		</section>
		<section id="cedele">
			<div class="container container_css">
				<div class="row">
					<div class="col-12 col-lg-7">
						<div class="story-text">
							<figure class="story-image-title">
								<img src="<?php echo get_site_url() . '/wp-content/uploads/2020/08/brand-cedele.png' ?>" alt="" class="img-responsive">
							</figure>
							<?php $post_7 = get_post($post_cedele); ?>

							<p>
								<?php echo($post_7->post_content); ?>
							</p>
							<div class="story-button">
								<a href="<?php echo get_permalink( wc_get_page_id('shop') );?>" class="btn btn-lg mt-3 px-4 btn-outline-primary rounded">SHOP NOW</a>
							</div>
						</div>
					</div>
					<div class="col-12 col-lg-5">
						<?php
						if (class_exists('Dynamic_Featured_Image')):
							global $dynamic_featured_image;
							$featured_images = $dynamic_featured_image->get_featured_images($post_cedele);
							if ($featured_images):
								?>
								<div class="story-image-wrapper wrapper-1 wrapper-mobile-1">
									<div>
										<div class="story-image image-1 mb-30">
											<div style="background-image: url(<?php echo $featured_images[0][full] ?>)"></div>
										</div>
										<div class="story-image image-2">
											<div style="background-image: url(<?php echo $featured_images[2][full] ?>)"></div>
										</div>
									</div>
									<div>
										<div class="story-image image-3">
											<div style="background-image: url(<?php echo $featured_images[1][full] ?>)"></div>
										</div>
									</div>
								</div>
							<?php
							endif;
						endif;
						?>
					</div>
				</div>

			</div>
		</section>

		<section id="workspace">
			<div class="container container_css">
				<div class="row">
					<div class="col-12 col-lg-5">
						<?php
						if (class_exists('Dynamic_Featured_Image')):
							global $dynamic_featured_image;
							$featured_images = $dynamic_featured_image->get_featured_images($post_workspace);
							if ($featured_images):
								?>
								<div class="story-image-wrapper wrapper-2 wrapper-mobile-2">
									<div>
										<div class="story-image image-4">
											<div style="background-image: url(<?php echo $featured_images[0][full] ?>)"></div>
										</div>
									</div>
									<div>
										<div class="story-image image-5">
											<div style="background-image: url(<?php echo $featured_images[1][full] ?>)"></div>
										</div>
									</div>
								</div>
								<div class="story-image image-6 mt-30">
									<div style="background-image: url(<?php echo $featured_images[2][full] ?>)"></div>
								</div>
							<?php
							endif;
						endif;
						?>
					</div>
					<div class="col-12 col-lg-7">
						<div class="story-text">
							<figure class="story-image-title">
								<img src="<?php echo get_site_url() . '/wp-content/uploads/2020/08/brand-workspace.png' ?>" alt="" class="img-responsive">
							</figure>
							<?php $post_7 = get_post($post_workspace); ?>
							<p>
								<?php echo($post_7->post_content); ?>
							</p>
							<div class="story-button">
								<a href="http://workspaceespresso.com/" class="btn btn-outline-warning rounded">LEARN MORE</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<section id="toss-turn">
			<div class="container container_css">
				<div class="row">
					<div class="col-12 col-lg-7">
						<div class="story-text">
							<figure class="story-image-title">
								<img src="<?php echo get_site_url() . '/wp-content/uploads/2020/08/brand-toss.png' ?>" alt="" class="">
							</figure>
							<?php $post_7 = get_post($post_toss); ?>
							<p>
								<?php echo($post_7->post_content); ?>
							</p>
							<div class="story-button">
								<a href="http://www.tossnturnsalad.com/" class="btn btn-lg mt-3 px-4 btn-outline-primary rounded">LEARN MORE</a>
							</div>
						</div>
					</div>
					<div class="col-12 col-lg-5">
						<?php
						if (class_exists('Dynamic_Featured_Image')):
							global $dynamic_featured_image;
							$featured_images = $dynamic_featured_image->get_featured_images($post_toss);
							if ($featured_images):
								?>
								<div class="story-image image-7 mb-30">
									<div style="background-image: url(<?php echo $featured_images[0][full] ?>)"></div>
								</div>
								<div class="story-image-wrapper wrapper-1 wrapper-mobile-3">
									<div>
										<div class="story-image image-8">
											<div style="background-image: url(<?php echo $featured_images[1][full] ?>)"></div>
										</div>
									</div>
									<div>
										<div class="story-image image-9">
											<div style="background-image: url(<?php echo $featured_images[2][full] ?>)"></div>
										</div>
									</div>
								</div>
							<?php
							endif;
						endif;
						?>

					</div>
				</div>
			</div>
		</section>
		<section id="chiak">
			<div class="container container_css">
				<div class="row">
					<div class="col-12 col-lg-5">

						<?php
						if (class_exists('Dynamic_Featured_Image')):
							global $dynamic_featured_image;
							$featured_images = $dynamic_featured_image->get_featured_images($post_chiak);
							if ($featured_images):
								?>
								<div class="story-image-wrapper wrapper-2 wrapper-mobile-2">
									<div>
										<div class="story-image image-10">
											<div style="background-image: url(<?php echo $featured_images[0][full] ?>)"></div>
										</div>
									</div>
									<div>
										<div class="story-image image-11">
											<div style="background-image: url(<?php echo $featured_images[1][full] ?>)"></div>
										</div>
									</div>
								</div>
							<?php
							endif;
						endif;
						?>

					</div>
					<div class="col-12 col-lg-7 col-sm content_right_p">
						<div class="story-text">
							<figure class="story-image-title">
								<img src="<?php echo get_site_url() . '/wp-content/uploads/2020/08/chiak.png' ?>" alt="" class="">
							</figure>
							<?php $post_7 = get_post($post_chiak); ?>

							<p class="content_ourstory">
								<?php echo($post_7->post_content); ?>
							</p>
							<div class="story-button">
								<a href="https://sites.google.com/view/chiaksingapore" class="btn btn-lg mt-3 px-4 btn-outline-primary rounded">LEARN MORE</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</article>
<?php
get_footer();
