<?php
/*
Template Name: Страница заказа
 */

//add_action( 'wp_head', 'noRobots' ); // Закрыть страницу от индексирования поисковыми роботами


$id_product  = get_the_ID();
$post_status = get_post_status();
$post_meta   = get_post_meta( $id_product ); // Все метаданные записи в виде массива
$post_date   = get_the_date( 'U', $id_product ) - 3 * 60 * 60; // TODO: Timezone
//var_dump($post_meta);

# ШАБЛОН ЗАКАЗА В ПРОЦЕССЕ ВЫПОЛНЕНИЯ
if ( empty( $post_meta['created_slideshow'][0] ) ) {
	
	# Установить заголовок H1
	add_filter('pre_get_document_title', 'change_title', 999, 1 );
	function change_title($title) {
		return 'Great';
	}
	
	wp_enqueue_style('home-css', get_stylesheet_directory_uri() . '/assets/css/home.css', array(), filemtime( __DIR__ . '/assets/css/home.css' ), false);
	wp_enqueue_style( 'single-product-css', get_stylesheet_directory_uri() . '/assets/css/single-product.css', array('style-css'), filemtime( __DIR__ . '/assets/css/single-product.css' ) );
	
	get_header();
	
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
	
	// Получить св-ва дизайна
	define('ID_DESIGN', $post_meta['id_design'][0]);
	require_once PATH_APP . '/lib/Designs.php';
	$design = Designs::get_design(ID_DESIGN);
	//require_once PATH_APP . '/lib/lib-designs.php';
	//$design = get_design_properties(ID_DESIGN);

	?>

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
				xhr.open('GET', '<?php echo site_url() ?>/app/ajax/ajax-get-order-state.php?id=<?php echo( $id_product ) ?>');

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
	
	wp_enqueue_script('product_ready-js', get_stylesheet_directory_uri() . '/assets/js/product_ready.js', array('custom-js'), '0.0.5', false);
	
	define('NAME_PRODUCT', $id_product . FF_SUFFIX);
	$download_path_default = URL_READY_USERFILES . '/' . NAME_PRODUCT . '_preview.mp4';
	$download_path_audio = URL_READY_USERFILES . '/' . NAME_PRODUCT . '_swapaudio.mp4';
	if (file_exists(ABSPATH . $download_path_audio))
		$download_link = $download_path_audio;
	else
		$download_link = $download_path_default;
	
	// Создать ссылку на оплату
	$show_link = $download_link;
	if ( empty( $post_meta['paid_product'][0] ) ) {
		$show_link = './?payment';
	}
	
	get_header();
	
	// Страница оплаты
	if (isset($_GET['payment'])) {
		require_once __DIR__ . '/template-parts/payment_part.php';
		get_footer();
		exit;
	}

	?>

	<style>

	</style>

	<div class="col-12 container-h1">
		<h1>ВАШЕ ВИДЕО <span class="text-danger">ГОТОВО!</span></h1>
	</div>

	<div class="col-md-7 col-lg-8 d-flex align-content-center flex-wrap">

		<figure class="figure_video mx-auto" id="videoHomepage">
			<div>
				<video height="auto" class="" style="display: block;" loop autoplay muted controls
					poster="<?php echo URL_READY_USERFILES . '/' . NAME_PRODUCT  ?>_preview.jpg">
					<source src="<?php echo $download_link ?>" type="video/mp4">
					Your browser does not support the video tag.
				</video>
				<!--
				<div class="d-flex align-items-center justify-content-center" style="position: absolute; width: 100%; height: 100%; top: 0;">
					<img src="<?php echo URL_READY_USERFILES . '/' . NAME_PRODUCT  ?>_preview.jpg" alt="">
				</div>
				-->
			</div>
		</figure>

	</div>
	<div class="col-md-5 col-lg-4 text-center d-flex align-items-center justify-content-center">
		<div>
			<div class="h1">Вам нравится?</div>
			<div class="text-big">Вы можете:</div>
			<div style="margin: 60px auto;">
				<a class="btn btn-danger shadow my-2 bubbly-button" href="<?php echo $show_link /*echo home_url()*/ ?>">Скачать</a>
				<br><a class="btn btn-primary shadow my-2 bubbly-button" href="<?php /*echo home_url()*/ ?>#">Поделиться</a>
				<br><a class="btn btn-outline-dark shadow my-2 bubbly-button" href="<?php /*echo home_url()*/ ?>#">поменять музыку</a>
				<br><a class="btn btn-danger shadow my-2 bubbly-button" href="<?php /*echo home_url()*/ ?>#">создать новое видео</a>
			</div>
		</div>
	</div>
	
	<?php
	
	if (1 == 1) { ?>

	<script src="<?php echo get_stylesheet_directory_uri() ?>/assets/js/audio-wavesurfer.js"></script>
	<div id="main" class="main text-center vertical-align" data-product-id="<?php echo $id_product ?>">

		<div class="body_content">
			<div id="stepDownload">
				<h3>
					<?php if ($download_link != home_url() . $download_path_audio) { ?>
					story is ready
					<?php } else { ?>
					music track for story was changed
					<?php } ?>
				</h3>

				<p>You can:</p>
				<p><a id="downloadLink" href="<?php echo $download_link ?>"
				   class="btn btn-outline-light btn-lg bubbly-button" download="weezy_story_<?php echo $id_product ?>.mp4">download
					story</a></p>
				<!--<a href="<?php echo get_stylesheet_directory_uri() ?>/download_mp4.php?id=<?php echo $id_product ?>" class="button black mini arrow marginleft_1"><?php _e( 'Download clip', 'fromfoto' ) ?></a>-->
			</div>

			<!-- Блок замены музыки -->
			<div id="stepAudio" style="display: none;">
				<p>or</p>
				<p class="video-block music-block-show">
					<div class="music-button">
						<?php /*if ($download_link != home_url() . $download_path_audio) {*/ ?>
						<div id="replace_music" class="music-block-show-button btn btn-outline-light bubbly-button">replace music</div>
						<?php /*} else { ?>
						<div id ="retrieve_music" class="music-block-show-button btn btn-outline-light bubbly-button">Вернуть нашу музыку</div>
						<?php }*/ ?>
					</div>
					<div class="small <?php if (file_exists(ABSPATH . '/audio_list/' . NAME_PRODUCT . '_swapaudio.mp4')) echo('d-none')?>"><small>it will take 15 seconds</small></div>
				</p>
			</div>
			
			<!-- ЭЛЕМЕНТЫ работы с аудио -->
			<div id="stepAudioEditor" class="audio-container">
				<input class="d-none" type="file" id="loadMusInp" multiple accept="audio/*"/>
				<audio src=""></audio>
				<div class="visualizator-block py-3">
					<div class="visualizator">
						<div class="visualizator-selected-area0">
							<div class="visualizator-selected-area"></div>
						</div>
						<div id="waveform"></div>
					</div>
				</div>
				<div class="py-3"><button class="btn btn-outline-light send-audio bubbly-button" type="button">change music</button></div>
				<div class="py-3"><button class="btn btn-outline-light play-control bubbly-button" type="button"><i class="fas fa-pause fa-3x"></i></button></div>
			</div>

			<div id="stepAudioLoading" class="loading" style="display: none;">music uploading...</div>
			<div id="stepAudioProcessing" class="loading" style="display: none;">processing...</div>
			<!-- /Блок замены музыки -->

			<br />
			<br />

			<div id="stepAfterDownload" class="invisible">add this video in story</div>

		</div>

	</div>

	<!-- СТИЛИ работы с аудио -->
	<style>
		@font-face{
			font-family: afterglow-icon;
			src: url(data:font/truetype;charset=utf-8;base64,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) format("truetype");
			font-weight: 400;
			font-style: normal;
		}
		.audio-container{
			min-width: 100%;
			/*height: 80px;*/
			position: absolute;
			left: 0;
			padding: 0 3%;
			margin-top: 10px;
		}
		.visualizator-block{
			z-index: 0;
		}
		.visualizator{
			position: relative;
			height: 80px;
			overflow: hidden;
			transition: all .35s linear;
		}
		.visualizator-selected-area0{
			position: relative;
			height: 80px;
			overflow: hidden;
			z-index: 3;
		}
		.visualizator-selected-area{
			position: relative;
			height: 80px;
			overflow: hidden;
			z-index: 3;
			background-color: #004085;
		}
		#waveform{
			height: 0;
		}
		/*
		.play-control{
			margin: 40px 0 0;
			padding: 0 10px;
		}
		.play-control:before{
			content: "\e602";
			font-size: 30px;
			margin-left: 5px;
			font-family: afterglow-icon;
			text-shadow: 0 0 0 rgba(255,255,255,1);
		}
		.play-control.playing:before{
			content: "\e60a";
			margin: 0 3px 0 2px;
		}
		.send-audio{
			position: absolute;
			right: 3%;
			bottom: 10px;
		}
		*/
		.body-to-top{
			margin-top: -150px;
		}
		.visualizator canvas{
			opacity: 0.2;
		}
		.visualizator-selected-area canvas{
			opacity: 1;
		}
	</style>

	<!-- СКРИПТЫ работы с аудио -->
	<!--<script src="<?php echo get_stylesheet_directory_uri() ?>/assets/js/product_ready.js?0.0.1"></script>-->

	<?php
	
	}
	
	?>
<?php } ?>
<?php get_footer(); ?>