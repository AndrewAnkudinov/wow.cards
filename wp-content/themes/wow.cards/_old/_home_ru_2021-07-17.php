<?php

/*
Template Name: Главная страница (ru)
 */

# Подключить ключевые файлы CMS
include_once(__DIR__ . '/../../../wp-config.php');

/*
# Установить заголовок H1
add_filter('pre_get_document_title', 'change_title');
function change_title($title) {
	return 'What do you need?';
}
*/

//wp_enqueue_script('home-js', get_stylesheet_directory_uri() . '/js/home.js', array('jquery'), filemtime( __DIR__ . '/js/home.js' ), false);
wp_enqueue_script('rateit-js', get_stylesheet_directory_uri() . '/assets/js/lib/rateit/jquery.rateit.min.js', array('jquery'), '1.1.2', false);
wp_enqueue_style('rateit-css', get_stylesheet_directory_uri() . '/assets/js/lib/rateit/rateit.css', array(), '1.1.2', false);
wp_enqueue_style('home-css', get_stylesheet_directory_uri() . '/assets/css/home.css', array(), filemtime( __DIR__ . '/assets/css/home.css' ), false);

?>
<?php get_header(); ?>

	<div class="col-12 text-center">
		<div class="h1 d-sm-none">
			<div class="title-w-line"><span style="display: inline-block; vertical-align: middle;">
					Открытки, картинки,
					<br>видеопоздравления</span></div>
			<div><img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/sladosti.png" alt="" id="imgbirthday">
				<!--на день рождения--> и&nbsp;не&nbsp;только</div>
		</div>
		<h1 class="d-none d-sm-block title-w-line"><span style="
			display: inline-block;
			vertical-align: middle;">Открытки, картинки, видеопоздравления
			<br><img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/sladosti.png" alt="" id="imgbirthday">
				<!--на день рождения--> и&nbsp;не&nbsp;только</span></h1>
		<div id="chooseFormat" class="d-flex justify-content-center align-items-center"><div class="h4 m-0">Выберите формат</div></div>

		<div class="d-flex flex-column flex-sm-row justify-content-between align-items-stretch elementor-image"><!-- align-items-end -->

			<div class="order-sm-2">

				<figure>
					<div class="title title-w-line text-left mb-2">видеопоздравление для TV и PC</div>
					<!--
					<img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/3.png" alt="">
					-->
					<a href="https://wow.cards/ru/choose-theme-ru/">
						<video width="100%" height="auto" style="display: block;" loop autoplay muted controls>
							<source src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/video_16_9.mp4" type="video/mp4">
							Your browser does not support the video tag.
						</video>
					</a>
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
				<div class="my-sm-3 text-center">
					<a href="https://wow.cards/ru/choose-theme-ru/" class="btn btn-sm bubbly-button btn-primary shadow">создать видео</a>
				</div>

			</div>
			
			<div class="order-sm-0">

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
				<div class="my-sm-3 text-center">
					<button class="btn btn-sm bubbly-button btn-primary shadow">Отправить</button>
					<button class="btn btn-sm bubbly-button btn-outline-dark">свой текст</button>
				</div>

			</div>

			<div class="order-sm-1">

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
				<div class="my-sm-3 text-center">
					<button class="btn btn-sm bubbly-button btn-primary shadow">Отправить</button>
					<button class="btn btn-sm bubbly-button btn-outline-dark">свой текст</button>
				</div>

			</div>
			

			<div class="order-sm-4">

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
				<div class="my-sm-3 text-center">
					<a href="https://wow.cards/ru/choose-theme-ru/?type=story" class="btn btn-sm bubbly-button btn-primary shadow">создать видео</a>
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
			<div class="my-sm-3 text-center">
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
			<div class="my-sm-3 text-center">
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
			<div class="my-sm-3 text-center">
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
			<div class="my-sm-3 text-center">
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
		<h2 id="subHeaderHome">ВЫ МОЖЕТЕ ДОБАВИТЬ СВОЙ ТЕКСТ ИЛИ ФОТО.
		<span class="text-danger m-0">ЭТО БЕСПЛАТНО</span></h2>
		<a id="btnSubHeaderHome" href="#" class="btn bubbly-button btn-danger">Подробнее</a>
	</div>

	<div class="col-12" style="margin: 30px auto;">
		<h2 class="text-center">О сайте</h2>
		<p>Каждый подход, как это и водится, имеет свои плюсы и минусы. Где-то можно выиграть в оптимизации, но потерять в живых читателях. Где-то можно приобрести живых читателей, но придется жертвовать SEO-показателями и, возможно, по этой причине отставать от конкурентов.</p>
		<p>Постоянные сомнения, касающиеся оптимальных путей создания текстов для главной, стали вполне привычными спутниками авторов.</p>
		<p>Кто-то постоянно работает под одной и той же схеме, кто-то мечется между SEO и продающими текстами, а кто-то и вовсе не имеет четкого видения. Чтобы хоть как-то определиться с тем, как писать тексты для главных страниц, мы составили эту небольшую заметку. На полноценный научный труд претендовать не собираемся, но кое-какие собственные наблюдения озвучим.</p>
	</div>

<?php /*get_sidebar();*/ ?>
<?php get_footer(); ?>