<?php

/*
Template Name: пока заказ обрабатывается
 */

# Подключить ключевые файлы CMS
include_once(__DIR__ . '/../../../wp-config.php');

wp_enqueue_style('home-css', get_stylesheet_directory_uri() . '/assets/css/home.css', array(), filemtime( __DIR__ . '/assets/css/home.css' ), false);

?>
<?php get_header(); ?>

	<div class="col-12 container-h1">
		<h1>ПРОВЕРЬТЕ ПОЧТУ:
			<br><span class="text-danger">МЫ КОЕ-ТО ОТПРАВИЛИ :)</span></h1>
	</div>

	<div class="col-12 text-center">
		<div class="h2" style="margin: 60px auto;">Пока заказ обрабатывается, отправьте открытку или короткое видео</div>
		
		<div class="d-flex justify-content-between align-items-stretch elementor-image mx-auto" style="max-width: 1050px;"><!-- align-items-end -->

			<div>

				<figure>
					<div class="title title-w-line text-left mb-2">Открытка</div>
					<img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/1.png" alt="">
					<figcaption class="widget-image-caption wp-caption-text">
						<div class="title-w-line d-flex align-items-center"><span class="wow">уровень радости</span>
							<span class="rateit bigstars"
								  data-rateit-max="1"
								  data-rateit-value="<?php echo 4.2/5 ?>"
								  data-rateit-ispreset="true"
								  data-rateit-readonly="true"
								  data-rateit-starwidth="20"
								  data-rateit-starheight="20"
							></span> <span class="rate-numbers text-danger">4,2</span>
						</div>
					</figcaption>
				</figure>
				<div class="my-3 text-center">
					<button class="btn btn-sm bubbly-button btn-primary shadow">Отправить</button>
					<button class="btn btn-sm bubbly-button btn-outline-dark">свой текст</button>
				</div>

			</div>

			<div>

				<figure>
					<div class="title title-w-line text-left mb-2">анимированная открытка</div>
					<img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/2.png" alt="">
					<figcaption class="widget-image-caption wp-caption-text">
						<div class="title-w-line d-flex align-items-center"><span class="wow">уровень радости</span>
							<span class="rateit bigstars"
								  data-rateit-max="1"
								  data-rateit-value="<?php echo 4.6/5 ?>"
								  data-rateit-ispreset="true"
								  data-rateit-readonly="true"
								  data-rateit-starwidth="20"
								  data-rateit-starheight="20"
							></span><span class="rate-numbers text-danger">4,6</span>
						</div>
					</figcaption>
				</figure>
				<div class="my-3 text-center">
					<button class="btn btn-sm bubbly-button btn-primary shadow">Отправить</button>
					<button class="btn btn-sm bubbly-button btn-outline-dark">свой текст</button>
				</div>

			</div>

			<div>

				<figure>
					<div class="title title-w-line text-left mb-2">видео для моб (до 15 сек)</div>
					<!--
					<img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/5.png" alt="">
					-->
					<video width="216" height="384" height="auto" style="display: block;" loop autoplay muted controls>
						<source src="//fromfoto.app/wp-content/themes/fromfoto-app/680557_33_story.mp4" type="video/mp4">
						Your browser does not support the video tag.
					</video>
					<figcaption class="widget-image-caption wp-caption-text">
						<div class="title-w-line d-flex align-items-center"><span class="wow">уровень радости</span>
							<span class="rateit bigstars"
								  data-rateit-max="1"
								  data-rateit-value="<?php echo 5/5 ?>"
								  data-rateit-ispreset="true"
								  data-rateit-readonly="true"
								  data-rateit-starwidth="20"
								  data-rateit-starheight="20"
							></span> <span class="rate-numbers text-danger">5,0</span>
						</div>
					</figcaption>
				</figure>
				<div class="my-3 text-center">
					<button class="btn btn-sm bubbly-button btn-primary shadow">создать видео</button>
				</div>

			</div>

			<!--
			<div style="position: absolute; left: 0; bottom: 0; width: 75%">
				<h2>ВЫ МОЖЕТЕ ДОБАВИТЬ СВОЙ ТЕКСТ ИЛИ ФОТО.</h2><div class="h1 text-danger m-0">ЭТО БЕСПЛАТНО!</div>
			</div>
			-->
			
		</div>
	</div>



<?php
/*

	<div class="col-12 d-flex justify-content-between align-items-stretch elementor-image"><!-- align-items-end -->

		<div>

			<figure>
				<div class="title title-w-line text-left mb-2">Открытка</div>
				<img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/1.png" alt="">
				<figcaption class="widget-image-caption wp-caption-text">
					<div class="title-w-line">
						<span class="wow">уровень радости</span>
						<span class="rateit"
							  data-rateit-max="1"
							  data-rateit-value="<?php echo 4.2/5 ?>"
							  data-rateit-ispreset="true"
							  data-rateit-readonly="true"></span> 4,2
					</div>
				</figcaption>
			</figure>
			<div class="my-3 text-center">
				<button class="btn btn-sm bubbly-button btn-primary shadow">Отправить</button>
				<button class="btn btn-sm bubbly-button btn-outline-dark">Заменить</button>
			</div>

		</div>

		<div>

			<figure>
				<div class="title title-w-line text-left mb-2">Открытка с анимацией</div>
				<img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/2.png" alt="">
				<figcaption class="widget-image-caption wp-caption-text">
					<div class="title-w-line">
						<span class="wow">уровень радости</span>
						<span class="rateit"
							  data-rateit-max="1"
							  data-rateit-value="<?php echo 4.6/5 ?>"
							  data-rateit-ispreset="true"
							  data-rateit-readonly="true"></span> 4,6
					</div>
				</figcaption>
			</figure>
			<div class="my-3 text-center">
				<button class="btn btn-sm bubbly-button btn-primary shadow">Отправить</button>
				<button class="btn btn-sm bubbly-button btn-outline-dark">Заменить</button>
			</div>

		</div>

		<div>

			<figure>
				<div class="title title-w-line text-left mb-2">Видеопоздравление</div>
				<!--
				<img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/3.png" alt="">
				-->
				<video width="100%" height="auto" style="display: block;" loop autoplay muted controls>
					<source src="https://www.w3schools.com/html/mov_bbb.mp4" type="video/mp4">
					Your browser does not support the video tag.
				</video>
				<figcaption class="widget-image-caption wp-caption-text">
					<div class="title-w-line">
						<span class="wow">уровень радости</span>
						<span class="rateit"
							  data-rateit-max="1"
							  data-rateit-value="<?php echo 5/5 ?>"
							  data-rateit-ispreset="true"
							  data-rateit-readonly="true"></span> 5,0
					</div>
				</figcaption>
			</figure>
			<div class="my-3 text-center">
				<button class="btn btn-sm bubbly-button btn-primary shadow">Отправить</button>
				<button class="btn btn-sm bubbly-button btn-outline-dark">Заменить</button>
			</div>

		</div>

		<div>

			<figure style="max-width: 260px;">
				<div class="title title-w-line text-left mb-2">Видеопоздравление</div>
				<!--
				<img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/5.png" alt="">
				-->
				<video width="216" height="384" height="auto" style="display: block;" loop autoplay muted controls>
					<source src="//fromfoto.app/wp-content/themes/fromfoto-app/680557_33_story.mp4" type="video/mp4">
					Your browser does not support the video tag.
				</video>
				<figcaption class="widget-image-caption wp-caption-text">
					<div class="title-w-line">
						<span class="wow">уровень радости</span>
						<span class="rateit"
							  data-rateit-max="1"
							  data-rateit-value="<?php echo 5/5 ?>"
							  data-rateit-ispreset="true"
							  data-rateit-readonly="true"></span> 5,0
					</div>
				</figcaption>
			</figure>
			<div class="my-3 text-center">
				<button class="btn btn-sm bubbly-button btn-primary shadow">Отправить</button>
				<button class="btn btn-sm bubbly-button btn-outline-dark">Заменить</button>
			</div>

		</div>

	</div>


	<div class="col-12 text-center elementor-image">
		<div class="row align-items-end">
			<div class="col-sm-3 d-flex justify-content-center">
				<div>
					
					<figure>
						<div class="title title-w-line text-left mb-2">Открытка</div>
						<img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/1.png" alt="">
						<figcaption class="widget-image-caption wp-caption-text">
							<div class="title-w-line">
								<span class="wow">уровень радости</span>
								<span class="rateit"
									  data-rateit-max="1"
									  data-rateit-value="<?php echo 4.2/5 ?>"
									  data-rateit-ispreset="true"
									  data-rateit-readonly="true"></span> 4,2
							</div>
						</figcaption>
					</figure>
					<div class="my-3">
						<button class="btn btn-sm bubbly-button btn-primary shadow">Отправить</button>
						<button class="btn btn-sm bubbly-button btn-outline-dark">Заменить</button>
					</div>
					
				</div>
			</div>
			<div class="col-sm-3 d-flex justify-content-center">
				<div>
					
					<figure>
						<div class="title title-w-line text-left mb-2">Открытка с анимацией</div>
						<img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/2.png" alt="">
						<figcaption class="widget-image-caption wp-caption-text">
							<div class="title-w-line">
								<span class="wow">уровень радости</span>
								<span class="rateit"
									  data-rateit-max="1"
									  data-rateit-value="<?php echo 4.6/5 ?>"
									  data-rateit-ispreset="true"
									  data-rateit-readonly="true"></span> 4,6
							</div>
						</figcaption>
					</figure>
					<div class="my-3">
						<button class="btn btn-sm bubbly-button btn-primary shadow">Отправить</button>
						<button class="btn btn-sm bubbly-button btn-outline-dark">Заменить</button>
					</div>
					
				</div>
			</div>
			<div class="col-sm-3 d-flex justify-content-center">
				<div>
					
					<figure>
						<div class="title title-w-line text-left mb-2">Видеопоздравление</div>
						<img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/3.png" alt="">
						<!--
						<video width="100%" height="auto" style="display: block;" loop autoplay muted controls>
							<source src="https://www.w3schools.com/html/mov_bbb.mp4" type="video/mp4">
							Your browser does not support the video tag.
						</video>
						-->
						<figcaption class="widget-image-caption wp-caption-text">
							<div class="title-w-line">
								<span class="wow">уровень радости</span>
								<span class="rateit"
									  data-rateit-max="1"
									  data-rateit-value="<?php echo 5/5 ?>"
									  data-rateit-ispreset="true"
									  data-rateit-readonly="true"></span> 5,0
							</div>
						</figcaption>
					</figure>
					<div class="my-3">
						<button class="btn btn-sm bubbly-button btn-primary shadow">Отправить</button>
						<button class="btn btn-sm bubbly-button btn-outline-dark">Заменить</button>
					</div>
					
				</div>
			</div>
			<div class="col-sm-3 d-flex justify-content-center">
				<div>
					
					<figure style="max-width: 260px;">
						<div class="title title-w-line text-left mb-2">Видеопоздравление</div>
						<img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/5.png" alt="">
						<!--
						<video width="216" height="384" height="auto" style="display: block;" loop autoplay muted controls>
							<source src="//fromfoto.app/wp-content/themes/fromfoto-app/680557_33_story.mp4" type="video/mp4">
							Your browser does not support the video tag.
						</video>
						-->
						<figcaption class="widget-image-caption wp-caption-text">
							<div class="title-w-line">
								<span class="wow">уровень радости</span>
								<span class="rateit"
									  data-rateit-max="1"
									  data-rateit-value="<?php echo 5/5 ?>"
									  data-rateit-ispreset="true"
									  data-rateit-readonly="true"></span> 5,0
							</div>
						</figcaption>
					</figure>
					<div class="my-3">
						<button class="btn btn-sm bubbly-button btn-primary shadow">Отправить</button>
						<button class="btn btn-sm bubbly-button btn-outline-dark">Заменить</button>
					</div>
					
				</div>
			</div>
		</div>
	</div>
*/
?>

	<div class="col-12 text-center">
		<h2 style="margin: -40px 200px 0;"><span class="text-danger m-0">ЭТО БЕСПЛАТНО</span></h2>
	</div>


<?php /*get_sidebar();*/ ?>
<?php get_footer(); ?>