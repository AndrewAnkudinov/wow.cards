<?php
/*
Template Name: скачать видео
 */

# Подключить ключевые файлы CMS
require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-config.php');

# HTML
get_header();

?>


	<div class="col-12 container-h1">
			<h1>ВАШЕ ВИДЕО <span class="text-danger">ГОТОВО!</span></h1>
	</div>
	
	<div class="col-md-7 col-lg-8">

		<figure class="figure_video" id="videoHomepage">
			<div class="">
				<video width="100%" height="auto" class="rounded" style="display: block;" loop autoplay muted controls>
					<source src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/video_16_9.mp4" type="video/mp4">
					Your browser does not support the video tag.
				</video>
				<div class="d-flex align-items-center justify-content-center" style="position: absolute; width: 100%; height: 100%; top: 0;">
					<img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/play_135x135.png" alt="">
				</div>
			</div>
		</figure>
		
	</div>
	<div class="col-md-5 col-lg-4 text-center d-flex align-items-center justify-content-center">
		<div>
			<div class="h1">Вам нравится?</div>
			<div class="text-big">Вы можете:</div>
			<div style="margin: 60px auto;">
				<a class="btn btn-danger shadow my-2 bubbly-button" href="<?php /*echo home_url()*/ ?>#">Скачать</a>
				<br><a class="btn btn-primary shadow my-2 bubbly-button" href="<?php /*echo home_url()*/ ?>#">Поделиться</a>
				<br><a class="btn btn-outline-dark shadow my-2 bubbly-button" href="<?php /*echo home_url()*/ ?>#">поменять музыку</a>
				<br><a class="btn btn-danger shadow my-2 bubbly-button" href="<?php /*echo home_url()*/ ?>#">создать новое видео</a>
			</div>
		</div>
	</div>

<?php get_footer(); ?>