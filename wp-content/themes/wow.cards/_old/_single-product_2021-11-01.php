<?php
/*
Template Name: Страница заказа
 */

//add_action( 'wp_head', 'noRobots' ); // Закрыть страницу от индексирования поисковыми роботами

define('ID_PRODUCT', get_the_ID());
$post_status  = get_post_status();
$data_product = get_post( ID_PRODUCT );
$post_meta    = get_post_meta( ID_PRODUCT ); // Все метаданные записи в виде массива
$post_date    = get_the_date( 'U', ID_PRODUCT ) - 3 * 60 * 60; // TODO: Timezone
//var_dump($post_meta);

// Получить св-ва дизайна
define('ID_DESIGN', $post_meta['id_design'][0]);
$design = design\get_design(ID_DESIGN);
//require_once PATH_APP . '/lib/Designs.php';
//$design = Designs::get_design(ID_DESIGN);
//var_dump($design);
//echo get_locale();

# ШАБЛОН ЗАКАЗА В ПРОЦЕССЕ ВЫПОЛНЕНИЯ
if ( is_user_logged_in() || empty( $post_meta['created_slideshow'][0] ) )
{

	# Получить категорию дизайна
    //$category = $post_meta['id']


	# Установить заголовок H1
	add_filter('pre_get_document_title', 'change_title', 999, 1 );
	function change_title($title) {
		return 'Great';
	}
	
	wp_enqueue_style( 'home-css', get_stylesheet_directory_uri() . '/assets/css/home.css', array(), filemtime( __DIR__ . '/assets/css/home.css' ), false);
	wp_enqueue_style( 'choose-format-css', get_stylesheet_directory_uri() . '/assets/css/choose-format.css', array(), filemtime( __DIR__ . '/assets/css/choose-format.css' ), false);
	
	get_header();
	
	
	# Создать слайдеры
	
	# Определить категорию дизайнов
	$terms = get_the_terms(ID_DESIGN, 'category_design');
	//$terms = get_the_terms(get_the_ID(), 'category_' . $name_type_design);
	$id_term = $terms[0]->term_id;
	//$id_term = $wp_query->get_queried_object_id();
	
	# Получить список дизайнов нужных типов и категории
	$designs = design\get_designs($design['type'], $id_term);
	//$designs = Designs::get_designs('slideshow');  // TODO: пока выводятся все слайдшоу
	
	# Собрать URL первого дизайна
	$slugs_create_order_lang = [
		'ru_RU' => 'create-slideshow-ru',
		'en_US' => 'create-slideshow'
	];
	$url_first_design = home_url() . '/' . $slugs_create_order_lang[ get_locale() ] . '/?theme=' . $designs[ array_key_first($designs) ]['name'];
	
	$_H['slider_slideshow'] = design\slider_designs($designs);
		
	
	/*
	# Получить данные заказа
	# Вычислить оставшееся время создания видео
	$time_remaining = $post_date + FF_TIME * 60 - time();
	if ( $time_remaining < 0 ) {
		$time_remaining = 0;
	}
	//$time_remaining = 60 * 15;
	$time_remaining_theme = FF_TIME * 60;
	*/

	?>
	<div class="col-12 container-h1">
		<h1>ПРОВЕРЬТЕ ПОЧТУ:
			<br><span class="text-danger">МЫ КОЕ-ТО ОТПРАВИЛИ :)</span></h1>
	</div>

	<div class="col-12 text-center">
		<div class="h2" style="margin: 60px auto;">Пока заказ обрабатывается, отправьте открытку или короткое видео</div>

		<div class="row d-flex justify-content-between align-items-stretch elementor-image mx-auto" style="max-width: 1050px;"><!-- align-items-end -->

			<?php

            $name_type_tmp_design = 'postcard';
            if ($design['type'] !== $name_type_tmp_design) {

                # Получить список дизайнов нужной категории
                $designs = design\get_designs($name_type_tmp_design, $id_term);
                echo '<div class="col-lg-7">' . design\slider_designs($designs) . '</div>';
/*
                ?>
			<div>

				<figure>
					<div class="title title-w-line text-left mb-2">анимированная открытка</div>
					<img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/2.png" alt="">
					<figcaption class="widget-image-caption wp-caption-text d-none">
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
			<?
			*/
			}

			$name_type_tmp_design = 'slideshow';
			if ($design['type'] !== $name_type_tmp_design)
			{

                # Получить список дизайнов нужной категории
                $designs = design\get_designs($name_type_tmp_design, $id_term);
                echo '<div class="col-lg-4">' . design\slider_designs($designs) . '</div>';

/*
				?>
				<div class="order-sm-2" id="formatSlideshow">

					<figure>
						<div class="title title-w-line text-left mb-2">видеопоздравление для TV и PC</div>
                        <?php echo design\player_slideshow($design['video']); ?>
						<figcaption class="widget-image-caption wp-caption-text d-none">
							<div class="title-w-line align-items-center d-none"><!--  d-flex --><span class="wow">уровень радости</span>
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
				<?
			*/
			}

            $name_type_tmp_design = 'story';
			if ($design['type'] !== $name_type_tmp_design)
			{

                # Получить список дизайнов нужной категории
                $designs = design\get_designs($name_type_tmp_design, $id_term);
                echo '<div class="col-lg-4">' . design\slider_designs($designs) . '</div>';
                /*
				?>
				<div>

					<figure>
						<div class="title title-w-line text-left mb-2">видео для моб (до 15 сек)</div>
						<?php echo design\player_story($design['video']); ?>
						<figcaption class="widget-image-caption wp-caption-text d-none">
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
			<?
                */
                }

                ?>


			<!--
			<div style="position: absolute; left: 0; bottom: 0; width: 75%">
				<h2>ВЫ МОЖЕТЕ ДОБАВИТЬ СВОЙ ТЕКСТ ИЛИ ФОТО.</h2><div class="h1 text-danger m-0">ЭТО БЕСПЛАТНО!</div>
			</div>
			-->

		</div>
	</div>

	<div class="col-12 text-center">
		<h2 style="margin: -40px 200px 0;"><span class="text-danger m-0">ЭТО БЕСПЛАТНО</span></h2>
	</div>


	<!-- Новый скрипт -->
	<script>
		window.addEventListener('DOMContentLoaded', function () {

			// Проверить статус заказа
			var countCheckOrderStatus = 0;
			var checkOrderStatus = setInterval(function () {
				var xhr = new XMLHttpRequest();
				xhr.open('GET', '<?php echo site_url() ?>/app/ajax/ajax-get-order-state.php?id=<?php echo( ID_PRODUCT ) ?>');

				xhr.onload = function (ev) {
					if (ev.target.responseText == 'ready') {
						console.log('reload page');
						window.location.reload();
					}
				};
				xhr.send();
				
				// После 100 попытки остановить поиск (пощадить браузер)
				countCheckOrderStatus++;
				if (countCheckOrderStatus > 100)
					clearInterval(refreshIntervalId);
				
			}, 1000 * 20);

		});
	</script>
	
<?php

}

# ШАБЛОН ВЫПОЛНЕННОГО ЗАКАЗА
else {
	
	require_once __DIR__ . '/template-parts/part-product-ready.php';
	
} ?>
<?php get_footer(); ?>