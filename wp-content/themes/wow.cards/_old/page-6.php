<?php
/*
Template Name: скачать видео
 */

# Подключить ключевые файлы CMS
require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-config.php');

# HTML
get_header();

?>

	<style>
		.figure_genre_music {
			text-align: center;
			position: relative;
			width: 154px;
			height: 154px;
			margin: 0 auto;
		}
		
		.image {
			position: absolute;
			top: 50%;
			left: 50%;
			width: 150px;
			height: 150px;
			margin:-75px 0 0 -75px;
			-webkit-animation:spin 40s linear infinite;
			-moz-animation:spin 40s linear infinite;
			animation:spin 40s linear infinite;
			z-index: -1;
		}
		@-moz-keyframes spin { 100% { -moz-transform: rotate(360deg); } }
		@-webkit-keyframes spin { 100% { -webkit-transform: rotate(360deg); } }
		@keyframes spin { 100% { -webkit-transform: rotate(360deg); transform:rotate(360deg); }
	</style>

	<div class="col-12 container-h1">
		<h1 class="container-h1"><span class="text-danger">Нажмите</span>, чтобы прослушать
			<br>музыку для изменения</h1>
	</div>


	<div class="col-12" style="margin: calc(10vh) 0 0;">
		<div class="row text-center">
			<div class="col-sm-3">

				<figure class="figure_genre_music">
					<img class="image" src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/bg_stain-small_150x150.png" alt="">
					<img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/icons/icon-music-pop.png" alt="">
				</figure>
				<div class="h1 mt-1">Поп</div>
				<a class="btn btn-outline-dark shadow my-2 bubbly-button" href="<?php /*echo home_url()*/ ?>#">Заменить</a>
				
			</div>
			<div class="col-sm-3">

				<figure class="figure_genre_music">
					<img class="image" src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/bg_stain-small_150x150.png" alt=""
						style="-moz-transform: rotate(180deg); -webkit-transform: rotate(180deg);"
					>
					<img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/icons/icon-music-rock.png" alt="">
				</figure>
				<div class="h1 mt-1">Рок</div>
				<a class="btn btn-outline-dark shadow my-2 bubbly-button" href="<?php /*echo home_url()*/ ?>#">Заменить</a>
				
			</div>
			<div class="col-sm-3">

				<figure class="figure_genre_music">
					<img class="image" src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/bg_stain-small_150x150.png" alt=""
						 style="-moz-transform: rotate(270deg); -webkit-transform: rotate(270deg);">
					<img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/icons/icon-music-techno.png" alt="">
				</figure>
				<div class="h1 mt-1">Техно</div>
				<a class="btn btn-outline-dark shadow my-2 bubbly-button" href="<?php /*echo home_url()*/ ?>#">Заменить</a>
				
			</div>
			<div class="col-sm-3">

				<figure class="figure_genre_music">
					<img class="image" src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/bg_stain-small_150x150.png" alt=""
						 style="-moz-transform: rotate(90deg); -webkit-transform: rotate(90deg);">
					<img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/icons/icon-music-lyrics.png" alt="">
				</figure>
				<div class="h1 mt-1">Лирика</div>
				<a class="btn btn-outline-dark shadow my-2 bubbly-button" href="<?php /*echo home_url()*/ ?>#">Заменить</a>
				
			</div>
		</div>
	</div>


	<div class="col-12 text-center">
		<div style="margin: 60px auto;">
			<a class="btn btn-danger shadow my-2 bubbly-button" href="<?php /*echo home_url()*/ ?>#">Добавить свою музыку</a>
		</div>
		<div class="h2">Музыка будет заменена через 5 минут
			<br><span class="text-danger">это бесплатно</span></div>
	</div>

<?php get_footer(); ?>