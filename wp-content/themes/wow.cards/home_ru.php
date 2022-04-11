<?php

include __DIR__ . '/php_libs/mobile_detect.php';

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


# Получить медиа-превью дизайнов для главной страницы

# Создать массив ID дизайнов для главной страницы
$media_designs = [
	'postcard' => false,
	'slideshow' => 351,
	'story' => 360
];
foreach ($media_designs as $type_design => $id_design) {
	$media_designs[$type_design] = design\get_video_design($id_design);
}

//var_dump($media_designs);

//wp_enqueue_script('home-js', get_stylesheet_directory_uri() . '/js/home.js', array('jquery'), filemtime( __DIR__ . '/js/home.js' ), false);
wp_enqueue_style('choose-format-css', get_stylesheet_directory_uri() . '/assets/css/choose-format.css', array(), filemtime( __DIR__ . '/assets/css/choose-format.css' ), false);
wp_enqueue_style('home-css', get_stylesheet_directory_uri() . '/assets/css/home.css', array('style-css'), filemtime( __DIR__ . '/assets/css/home.css' ), false);

$ecsclusive_card = get_field('ecsclusive_card');
$main_video_card = get_field('main_video_card');
$main_story_card = get_field('main_story_card');
$detect = new Mobile_Detect;
?>

<?php get_header(); ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;900&display=swap" rel="stylesheet">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri() ?>/assets/css/new-home.css">
<?php  echo '<div class="header__background"></div>'; ?>
<?php echo '<div class="footer__background"></div>'; ?>
<?php echo '<div class="home_svg">
<picture>
    <source srcset="' . get_stylesheet_directory_uri() . '/assets/img/mob_home.png" media="(max-width: 1561px)">
    <source srcset="' . get_stylesheet_directory_uri() . '/assets/img/home_svg.png">
    <img srcset="' . get_stylesheet_directory_uri() . '/assets/img/home_svg.png" alt="My default image">
</picture></div>'; ?>
<div class="home">
    <div class="home__title"><?= the_field('main_title') ?></div>
    <div class="home__wrapper">
        <div class="home__wrapper-item home__card">
            <div class="home__wrapper-item_name"><?= $ecsclusive_card['card_name']; ?></div>
            <div class="home__wrapper-item_show">
                <img src="<?php echo esc_url( $ecsclusive_card['card_file_pc'] ); ?>" alt="">
            </div>
            <div class="home__wrapper-item_info">
                <a href="<?php echo home_url(); ?>/category_design/main/?type_design=postcard">
                    <p><?php echo $ecsclusive_card['card_text'] ?></p>
                    <div class="btn btn-sm bubbly-button btn-primary shadow"><?php echo $ecsclusive_card['button_text'] ?></div>
                </a>
            </div>
        </div>
        <div class="home__wrapper-item home__pc">
            <div class="home__wrapper-item_name"><?= $main_video_card['card_name'] ?></div>
            <div class="home__wrapper-item_show">
                <?php // echo design\player_slideshow($media_designs['slideshow']) ?>
                <div class="embed-responsive embed-responsive-16by9">
                    <video id="videofab2af7146c2b4a07330e73b87fe7755" loading="lazy" class="embed-responsive-item video-js" preload="none" crossorigin playsinline loop autoplay muted controls
                           data-setup='{"userActions":{"doubleClick":false},"controlBar":{"fullscreenToggle":false}}'>
                        <source src="<?php
                                if ($detect->isMobile()) {
                                    echo $main_video_card['card_file_mob'];
                                } else {
                                    echo $main_video_card['card_file_pc'];
                                }
                                ?>
                        " type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>
            <div class="home__wrapper-item_info">
                <a href="<?php echo home_url(); ?>/category_design/main/?type_design=slideshow">
                    <p><?= $main_video_card['card_text'] ?></p>
                    <div class="btn btn-sm bubbly-button btn-primary shadow"><?= $main_video_card['button_text'] ?></div>
                </a>
            </div>
        </div>
        <div class="home__wrapper-item home__mob">
            <div class="home__wrapper-item_name"><?= $main_story_card['card_name']; ?></div>
            <div class="home__wrapper-item_show">
                <?php // echo design\player_story($media_designs['story']) ?>
                <div class="embed-responsive embed-responsive-9by16">
                    <video id="video83bee46eb485cf639a85f93ff85ebd25" loading="lazy" class="embed-responsive-item video-js" preload="none" crossorigin playsinline loop autoplay muted controls
                           data-setup='{"userActions":{"doubleClick":false},"controlBar":{"fullscreenToggle":false}}'>
                        <source src="<?php
                            if ($detect->isMobile()) {
                                echo $main_story_card['card_file_mob'];
                            } else {
                                echo $main_story_card['card_file_pc'];
                            }
                        ?>" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>
            <div class="home__wrapper-item_info">
                <a href="<?php echo home_url(); ?>/category_design/main/?type_design=story">
                    <p><?= $main_story_card['card_text']; ?></p>
                    <div class="btn btn-sm bubbly-button btn-primary shadow"><?= $main_story_card['button_text']; ?></div>
                </a>
            </div>
        </div>
    </div>
</div>

<!--	<div class="col-12 text-center">-->
<!--		<h2 class="home__title">Открытки, картинки, видеопоздравления <strong>на день рождения</strong> и не только!</h2>-->
<!---->
<!--		<div class="d-flex flex-column flex-sm-row justify-content-between align-items-stretch elementor-image">align-items-end -->
<!---->
<!--			<div class="order-sm-2" id="formatSlideshow">-->
<!--                <div class="title box-title text-center">-->
<!--                    Видепоздравление для важного события (TV и Pc)-->
<!--                   <img class="title__background title__background-pc" src="--><?php ////echo get_stylesheet_directory_uri() ?><!--/assets/img/Title_background_pc.png" alt="">-->
<!--                </div>-->
<!--				<figure>-->
<!---->
<!---->
<!--                    --><?php //echo design\player_slideshow($media_designs['slideshow']) ?>
<!--					-->
<!--					<img src="--><?php //echo get_stylesheet_directory_uri() ?><!--/assets/img/3.png" alt="">-->
<!--					-->
<!--                    -->
<!--					<a href="--><?php //echo home_url(); ?><!--/category_design/">-->
<!--						<video width="100%" height="auto" loop autoplay muted controls>-->
<!--							<source src="--><?php //echo get_stylesheet_directory_uri() ?><!--/assets/img/video_16_9.mp4" type="video/mp4">-->
<!--							Your browser does not support the video tag.-->
<!--						</video>-->
<!--					</a>-->
<!--					-->
<!--					<figcaption>-->
<!--						<div class="my-3 text-center">-->
<!--                            <p>Загрузите от 10 фото - и видео готово!</p>-->
<!--							<a href="--><?php //echo home_url(); ?><!--/category_design/main/?type_design=slideshow"-->
<!--							   class="btn btn-sm bubbly-button btn-primary shadow">создать видео</a>-->
<!--						</div>-->
<!--					</figcaption>-->
<!--				</figure>-->
<!---->
<!---->
<!--			</div>-->
<!---->
<!--			<div class="order-sm-1" id="formatPostcard">-->
<!--                <div class="title box-title text-center">-->
<!--                    Эксклюзивная открытка-->
<!--                  <img class="title__background" src="--><?php ////echo get_stylesheet_directory_uri() ?><!--/assets/img/Title_background.png" alt="">-->
<!--                </div>-->
<!--				<figure>-->
<!---->
<!--					<img src="--><?php //echo get_stylesheet_directory_uri() ?><!--/assets/img/2.png" alt="">-->
<!--					<figcaption>-->
<!--						<div class="my-3 text-center">-->
<!--                            <p>Напишите свой текст и поздравьте за 5 сек</p>-->
<!--							<a href="--><?php //echo home_url(); ?><!--/category_design/main/?type_design=postcard"-->
<!--							   class="btn btn-sm bubbly-button btn-primary shadow">Выбрать открытку</a>-->
<!--							<button class="btn btn-sm bubbly-button btn-outline-dark">свой текст</button>-->
<!--						</div>-->
<!--					</figcaption>-->
<!--				</figure>-->
<!---->
<!---->
<!--			</div>-->
<!---->
<!--			<div class="order-sm-4" id="formatStory">-->
<!--                <div class="title box-title text-center">-->
<!--                    Истории для моб-->
<!--                 <img class="title__background title__background-mob" src="--><?php ////echo get_stylesheet_directory_uri() ?><!--/assets/img/Title_background_mob.png" alt="">-->
<!--                </div>-->
<!--				<figure>-->
<!---->
<!--					--><?php //echo design\player_story($media_designs['story']) ?>
<!---->
<!--					-->
<!--					-->
<!--					-->
<!--					<video loop autoplay muted controls>-->
<!--						<source src="//fromfoto.app/wp-content/themes/fromfoto-app/680557_33_story.mp4" type="video/mp4">-->
<!--						Your browser does not support the video tag.-->
<!--					</video>-->
<!--					-->
<!--				-->
<!--					<figcaption>-->
<!--                        <p>от 1 до 3 фото</p>-->
<!--						<div class="my-3 text-center">-->
<!--							<a href="--><?php //echo home_url(); ?><!--/category_design/main/?type_design=story"-->
<!--							   class="btn btn-sm bubbly-button btn-primary shadow">создать видео</a>-->
<!--						</div>-->
<!--					</figcaption>-->
<!--				</figure>-->
<!--				-->
<!--			</div>-->
<!---->
<!--			-->
<!--			<div style="position: absolute; left: 0; bottom: 0; width: 75%">-->
<!--				<h2>ВЫ МОЖЕТЕ ДОБАВИТЬ СВОЙ ТЕКСТ ИЛИ ФОТО.</h2><div class="h1 text-danger m-0">ЭТО БЕСПЛАТНО!</div>-->
<!--			</div>-->
<!--			-->
<!--			-->
<!--		</div>-->
<!--	</div>-->

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


<?php
/*
		<a id="btnSubHeaderHome" href="#" class="btn bubbly-button btn-danger">Подробнее</a>
*/
?>


<?php
/*
	<div class="col-12" style="margin: 30px auto;">
		<h2 class="text-center">О сайте</h2>
		<p>Каждый подход, как это и водится, имеет свои плюсы и минусы. Где-то можно выиграть в оптимизации, но потерять в живых читателях. Где-то можно приобрести живых читателей, но придется жертвовать SEO-показателями и, возможно, по этой причине отставать от конкурентов.</p>
		<p>Постоянные сомнения, касающиеся оптимальных путей создания текстов для главной, стали вполне привычными спутниками авторов.</p>
		<p>Кто-то постоянно работает под одной и той же схеме, кто-то мечется между SEO и продающими текстами, а кто-то и вовсе не имеет четкого видения. Чтобы хоть как-то определиться с тем, как писать тексты для главных страниц, мы составили эту небольшую заметку. На полноценный научный труд претендовать не собираемся, но кое-какие собственные наблюдения озвучим.</p>
	</div>
*/
?>
<?php /*get_sidebar();*/ ?>

<?php get_footer(); ?>