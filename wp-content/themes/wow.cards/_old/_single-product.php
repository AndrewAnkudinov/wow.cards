<?php
/*
Template Name: Страница заказа
 */

//add_action( 'wp_head', 'noRobots' ); // Закрыть страницу от индексирования поисковыми роботами


$the_id      = get_the_ID();
$post_status = get_post_status();
$post_meta   = get_post_meta( $the_id ); // Все метаданные записи в виде массива
$post_date   = get_the_date( 'U', $the_id ) - 3 * 60 * 60; // TODO: Timezone

# ШАБЛОН ЗАКАЗА В ПРОЦЕССЕ ВЫПОЛНЕНИЯ
if ( empty( $post_meta['created_slideshow'][0] ) ) {
	
	# Установить заголовок H1
	add_filter('pre_get_document_title', 'change_title', 999, 1 );
	function change_title($title) {
		return 'Great';
	}
	
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
	require_once PATH_APP . '/lib/lib-designs.php';
	$design = get_design_properties(ID_DESIGN);

	?>

	<div class="col-12 d-flex justify-content-center container-h1">
		<div id="chooseDesign">
			<h1><span class="text-danger">превосходно!</span> видео почти готово</h1>
		</div>
	</div>
	
	<div class="col-md-7 col-lg-8">



		<figure class="figure_video" id="videoHomepage">
			<div class="">
				<video width="100%" height="auto" style="display: block;" loop autoplay muted controls>
					<source src="<?php echo $design['video'] ?>" type="video/mp4">
					Your browser does not support the video tag.
				</video>
			</div>
		</figure>
		
	</div>
	<div class="col-md-5 col-lg-4 text-center d-flex align-items-center justify-content-center">
		<div>
			<div class="h2">ваш заказ сейчас обрабатывается!</div>
			<div style="margin: 50px auto 20px;">
				<a class="btn btn-danger bubbly-button" href="<?php /*echo home_url()*/ ?>#">нажми меня!</a>
			</div>
			<div>Нажмите и узнайте, когда<br />будет готово Ваше видео:)</div>
		</div>
	</div>
	
	<!-- Новый скрипт -->
	<script>
		window.addEventListener('DOMContentLoaded', function () {

			// Проверить статус заказа
			var checkOrderStatus = setInterval(function () {
				var xhr = new XMLHttpRequest();
				xhr.open('GET', '<?php echo home_url() ?>/app/ajax/ajax-get-order-state.php?id=<?php echo( $the_id ) ?>');

				xhr.onload = function (ev) {
					if (ev.target.responseText == 'ready') {
						console.log('reload page');
						window.location.reload();
					}
				};
				xhr.send();
			}, 1000 * 20);

		});
	</script>

	<!--<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>-->
	<script src="<?php echo get_stylesheet_directory_uri() ?>/assets/js/page4.js?1.0.6"></script>
<?php

}

# ШАБЛОН ВЫПОЛНЕННОГО ЗАКАЗА
else
{

	wp_enqueue_script('product_ready-js', get_stylesheet_directory_uri() . '/assets/js/product_ready.js', array('custom-js'), '0.0.5', false);

	$download_path_default  = URL_READY_USERFILES . '/' . $the_id . FF_SUFFIX . '_preview.mp4';
	$download_path_audio    = URL_READY_USERFILES . '/' . $the_id . FF_SUFFIX . '_swapaudio.mp4';
	if ( file_exists( ABSPATH . $download_path_audio ) )
		$download_link = home_url() . $download_path_audio;
	else
		$download_link = home_url() . $download_path_default;

?>
	<script src="<?php echo get_stylesheet_directory_uri() ?>/assets/js/audio-wavesurfer.js"></script>
	<div id="main" class="main text-center vertical-align" data-product-id="<?php echo $the_id ?>">

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
				   class="btn btn-outline-light btn-lg bubbly-button" download="weezy_story_<?php echo $the_id ?>.mp4">download
					story</a></p>
				<!--<a href="<?php echo get_stylesheet_directory_uri() ?>/download_mp4.php?id=<?php echo $the_id ?>" class="button black mini arrow marginleft_1"><?php _e( 'Download clip', 'fromfoto' ) ?></a>-->
			</div>

			<!-- Блок замены музыки -->
			<div id="stepAudio" style="display: none;">
				<p>or</p>
				<p class="video-block music-block-show">
					<div class="music-button">
						<?php /*if ($download_link != home_url() . $download_path_audio) {*/ ?>
						<div id ="replace_music" class="music-block-show-button btn btn-outline-light bubbly-button">replace music</div>
						<?php /*} else { ?>
						<div id ="retrieve_music" class="music-block-show-button btn btn-outline-light bubbly-button">Вернуть нашу музыку</div>
						<?php }*/ ?>
					</div>
					<div class="small <?php if (file_exists(ABSPATH . '/audio_list/' . $the_id . FF_SUFFIX . '_swapaudio.mp4')) echo('d-none')?>"><small>it will take 15 seconds</small></div>
				</p>
			</div>
			
			<!-- ЭЛЕМЕНТЫ работы с аудио -->
			<div id="stepAudioEditor" class="audio-container" style="display: none;">
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

<?php } ?>
<?php get_footer(); ?>