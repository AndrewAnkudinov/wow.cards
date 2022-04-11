<?php

# ШАБЛОН ВЫПОЛНЕННОГО ЗАКАЗА


// Страница оплаты
if (isset($_GET['payment'])) {
	get_header();
	require_once __DIR__ . '/part-payment.php';
	get_footer();
	exit;
}

# Подключить js- и css-файлы
wp_enqueue_style( 'single-product-css', get_stylesheet_directory_uri() . '/assets/css/single-product.css',
	array('style-css'), filemtime( __DIR__ . '/../assets/css/single-product.css' ) );
wp_enqueue_style( 'part-selector-segment-media-css', get_stylesheet_directory_uri() . '/assets/css/part-selector-segment-media.css',
	array('style-css'), filemtime( __DIR__ . '/../assets/css/part-selector-segment-media.css' ) );
wp_enqueue_script('product_ready-js', get_stylesheet_directory_uri() . '/assets/js/product_ready.js',
	array('custom-js'), filemtime(__DIR__ . '/../assets/js/product_ready.js'), true);

// justwave
/*
wp_enqueue_style('justwave.player-css', get_stylesheet_directory_uri() . '/justwave/justwave.player.css', array(), '1');
wp_enqueue_script('justwave.player-js', get_stylesheet_directory_uri() . '/justwave/justwave.player-min.js', array('jquery'), '1');
*/
wp_enqueue_script('wavesurfer-js', get_stylesheet_directory_uri() . '/assets/js/lib/wavesurfer.js', array('jquery'), '1');


define('NAME_PRODUCT', ID_PRODUCT . FF_SUFFIX);
$download_path_default = URL_READY_USERFILES . '/' . NAME_PRODUCT . '_preview.mp4';
$download_path_audio = URL_READY_USERFILES . '/' . NAME_PRODUCT . '_swapaudio.mp4';
if (file_exists(ABSPATH . $download_path_audio))
	$download_link = $download_path_audio . '?' . filemtime(ABSPATH . $download_path_audio);
else
	$download_link = $download_path_default;
$video_poster_url = URL_READY_USERFILES . '/' . NAME_PRODUCT . '_preview.jpg';


# БИБЛИОТЕКА АУДИОЗАПИСЕЙ
$lib_audio_fold_name       = 'lib/audio/' . $design['type'];
$lib_audio_root_url        = URL_APP . '/' . $lib_audio_fold_name;
$lib_audio_root_path       = PATH_APP . '/' . $lib_audio_fold_name;
$lib_audio = [
	'pop'  => [
		'title' => 'Поп',
		'icon' => get_stylesheet_directory_uri() . '/assets/img/icons/icon-music-pop.png'
	],
	'rock' => [
		'title' => 'Рок',
		'icon' => get_stylesheet_directory_uri() . '/assets/img/icons/icon-music-rock.png'
	],
	'techno'    => [
		'title' => 'Техно',
		'icon' => get_stylesheet_directory_uri() . '/assets/img/icons/icon-music-techno.png'
	],
	'lyrics'     => [
		'title' => 'Лирика',
		'icon' => get_stylesheet_directory_uri() . '/assets/img/icons/icon-music-lyrics.png'
	],
];
//var_dump( $lib_audio_root_path );

# Получить название файла из его имени
function get_title_from_filename($filename) {
	setlocale(LC_ALL, 'ru_RU.utf8');
	return str_replace('_', ' ', pathinfo($filename, PATHINFO_FILENAME));
}

if ( is_dir( $lib_audio_root_path ) ) {
	chdir( $lib_audio_root_path );
	$lib_audio_category_folders = glob( '*' ); //GLOB_MARK adds a slash to directories returned
	foreach ( $lib_audio_category_folders as $category_folder )
	{
		$category_dir = $lib_audio_root_path . '/' . $category_folder;
		if (
			isset( $lib_audio[ $category_folder ] )
			&& is_dir( $category_dir )
		) {
			chdir( $category_dir );
			$lib_current_folder  = glob( '*' ); //GLOB_MARK adds a slash to directories returned
			if ( !empty($lib_current_folder) )
				foreach ($lib_current_folder as $tmp_file) {
					$lib_audio[ $category_folder ]['audios'][]  = [
						'filename' => $tmp_file,
						'title' => get_title_from_filename( $tmp_file )
					];
				}
			
			
		}
		
	}
}

// Создать ссылку на оплату
$show_link = $download_link;
if (!empty($post_meta['price'][0]) && empty($post_meta['paid_product'][0])) {
	$show_link = './?payment';
}

get_header();
//echo design\get_url_product(ID_PRODUCT);

# Проверить хэш страницы
$hash_product = Designs::get_hash_product(ID_PRODUCT);
$hash_url = !isset($_GET['hash']) ? false : $_GET['hash'];
if ( !current_user_can( 'administrator' )
	&& ( !isset($hash_url) || $hash_product != $hash_url ))
{
	
	?>
	<div class="text-center text-danger">Указан неверный хэш страницы</div>
	<?php
	
	get_footer();
	exit;
}


# HTML

?>
<input type="hidden" id="idProduct" name="id_product" value="<?php echo ID_PRODUCT ?>" />
<input type="hidden" id="hashProduct" name="hash_product" value="<?php echo $hash_product ?>" />

<div class="col-12 text-center">
	
	<section id="stepStart" class="step">
		
		<div class="container-content d-flex flex-column justify-content-center align-items-center">

				<div class="container-h1">
					<h1>ВАШЕ ВИДЕО <span class="text-danger">ГОТОВО!</span></h1>
				</div>
			
				<div class="row w-100">
				
					<div class="col-md-7 col-lg-8 d-flex align-content-center flex-wrap">
	
						<!-- Video Player -->
						<!--<figure class="figure_video">-->
							<?php echo design\player_design($design['post_type'], $download_link); ?>
							<?php
							/*
							<div>
								<video id="playerVideo" height="auto" class="" style="display: block;" controls
									   poster="<?php echo $video_poster_url ?>"><!-- autoplay muted -->
									<source src="<?php echo $download_link ?>" type="video/mp4">
									Your browser does not support the video tag.
								</video>
								<!--
									<div class="d-flex align-items-center justify-content-center" style="position: absolute; width: 100%; height: 100%; top: 0;">
										<img src="<?php echo URL_READY_USERFILES . '/' . NAME_PRODUCT  ?>_preview.jpg" alt="">
									</div>
									-->
							</div>
							<!--<div class="play-button"><i class="fas fa-play fa-3x text-white"></i></div>-->
							*/
							?>
						<!--</figure>-->
	
					</div>
					<div class="col-md-5 col-lg-4 text-center d-flex align-items-center justify-content-center">
						<div>
							<div class="h1">Вам нравится?</div>
							<div class="text-big">Вы можете:</div>
							<div style="margin: 60px auto;">
								<a class="btn btn-danger shadow my-2 bubbly-button"
								   href="<?php echo $show_link ?>">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
										<path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
										<path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
									</svg>
									Скачать</a>
								<br><a class="btn btn-primary shadow my-2 bubbly-button"
									   href="<?php /*echo home_url()*/ ?>#" data-toggle="modal" data-target="#modalShare">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-share" viewBox="0 0 16 16">
										<path d="M13.5 1a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zM11 2.5a2.5 2.5 0 1 1 .603 1.628l-6.718 3.12a2.499 2.499 0 0 1 0 1.504l6.718 3.12a2.5 2.5 0 1 1-.488.876l-6.718-3.12a2.5 2.5 0 1 1 0-3.256l6.718-3.12A2.5 2.5 0 0 1 11 2.5zm-8.5 4a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zm11 5.5a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3z"/>
									</svg> Поделиться</a>

								<?php if ($design['post_type'] == 'slideshow') { ?>
								<br><button class="btn btn-outline-dark shadow my-2 bubbly-button" id="btnStepChangeAudio">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-music-note" viewBox="0 0 16 16">
										<path d="M9 13c0 1.105-1.12 2-2.5 2S4 14.105 4 13s1.12-2 2.5-2 2.5.895 2.5 2z"/>
										<path fill-rule="evenodd" d="M9 3v10H8V3h1z"/>
										<path d="M8 2.82a1 1 0 0 1 .804-.98l3-.6A1 1 0 0 1 13 2.22V4L8 5V2.82z"/>
									</svg>
									поменять музыку</button>
								<?php } ?>
								<br><a class="btn btn-danger shadow my-2 bubbly-button"
									   href="<?php echo home_url() ?>/category_design/main/?type_design=<?php echo $design['type']; ?>">создать новое видео</a>
							</div>
						</div>
					</div>

				</div>
				<?php
				# TODO
				/*
				<!-- Payment -->
				<div class="h2">за <?php echo PRODUCT_PRICE ?> руб.</div>
				<div>цена без скидки 299 руб.</div>
				<div style="margin-top: 30px;">
					<a id="payLink"
					   href="<?php echo home_url() ?>/stories/payment/?item=buy&productId=<?php echo ID_PRODUCT ?>&clips=1&userMail=<? echo $userMail ?>"
					   class="btn btn-danger btn-lg font-weight-bold bubbly-button"
					   target="_blank">Скачать</a>
				</div>
				*/
				?>
				
			</div>
			</div>
		</div>
	</section>
	
	<section id="stepChangeAudio" class="step" style="display: none;">
		
		<!-- ПОМЕНЯТЬ МУЗЫКУ -->
		<style>
			.figure_genre_music {

			}
			
			.icon_genre_music {
				text-align: center;
				position: relative;
				width: 154px;
				height: 154px;
				margin: 0 auto;
			}
			
			.spinning_bg {
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

        <!-- Audio -->
        <button type="button" id="showAudioLib"
                class="d-block d-sm-none text-center btn btn-outline-light btn-lg mx-auto mt-1"
                style="margin-bottom: 30px"
        >Выбрать</button>

		<div class="col-12 container-h1">
			<h1 class="container-h1"><span class="text-danger">Нажмите</span>, чтобы прослушать
				<br>музыку для изменения</h1>
		</div>
		
		
		<div class="col-12" style="margin: calc(10vh) 0 0;" id="audioLib">
			<div class="row text-center">
				<?php
				
				foreach ($lib_audio as $category_name => $category) {
					foreach ($category['audios'] as $k => $audio) {
						
						?>
						<div class="col-sm-3">

							<figure class="figure_genre_music">
								<div class="icon_genre_music">
									<img class="spinning_bg"
										 src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/bg_stain-small_150x150.png"
										 alt="">
									<img src="<?php echo $category['icon'] ?>"
										 alt="">
								</div>
								<audio
										id="audio<?php echo $category_name . $k ?>"
										src="<?php echo $lib_audio_root_url . '/' . $category_name . '/' . $audio['filename'] ?>"
								>
									Your browser does not support the
									<code>audio</code> element.
								</audio>
								<figcaption data-audio-id="audio<?php echo $category_name . $k ?>">
									<div class="h1 mt-1"><?php echo $category['title'] ?></div>
									<a class="btn btn-outline-dark shadow my-2 btn-change-audio bubbly-button"
									   href="<?php /*echo home_url()*/ ?>#">Заменить</a>
									<i class="fas fa-play d-none"></i>
								</figcaption>
							</figure>
						</div>
						<?php
						
					}
				}
				
				?>
			</div>
	
			<div class="col-12 text-center">
	
				<!-- Загрузить свою музыку -->
				<div class="upload_audio">
					<?php
					/*
					$dis1 = '';
					$dis2 = '';
					if ($is_video_with_audio) {
						$dis1 = 'd-none';
					} else {
						$dis2 = 'd-none';
					} ?>
					<div id="uploadAudioBtn" class="btn btn-lg btn-outline-light <?php echo $dis1 ?>">Загрузить свою</div>
					<div id="returnMusBtn" class="btn btn-lg btn-outline-light <?php echo $dis2 ?>">Загрузить свою</div>
					*/?>
	
					<div style="margin: 60px auto;">
						<button class="btn btn-danger shadow my-2 bubbly-button" id="btnUploadAudio">Добавить свою музыку</button>
					</div>
					<div class="h2">Музыка будет заменена через 5 минут
						<br><span class="text-danger">это бесплатно</span></div>
	
					<input class="d-none" type="file" id="inputUploadAudio" multiple accept="audio/*"/>
				</div>
				
				<?php
				/*
				$dis1 = '';
				$dis2 = '';
				if ($is_video_with_audio) {
					$dis1 = 'd-none';
				} else {
					$dis2 = 'd-none';
				} ?>
				<div id="btnUploadAudio" class="btn btn-lg btn-outline-light <?php echo $dis1 ?>">Загрузить свою</div>
				<div id="btnReturnMus" class="btn btn-lg btn-outline-light <?php echo $dis2 ?>">Загрузить свою</div>
				*/?>
				
			</div>

		</div>
		
		<!-- audioLib -->
		<div class="d-none">
			<div class="tab-content" id="audioLibTabContent">
				<?php
				
				reset($lib_audio);
				$first_key = key($lib_audio);
				foreach ($lib_audio as $category_name => $category)
				{
					?>
					<div
							class="tab-pane fade<?php if ($category_name == $first_key) echo ' show active'; ?>"
							id="audioLibCategory<?php echo $category_name ?>"
							role="tabpanel"
							aria-labelledby="audioLibCategory<?php echo $category_name ?>-tab"
					>
						<ul class="list-inline text-left">
							<?php foreach ($category['audios'] as $k => $audio) { ?>
								<li>
									<figure>
										<figcaption data-audio-id="audio<?php echo $category_name . $k ?>">
											<i class="fas fa-play"></i>
											<?php echo $audio['title'] ?>
										</figcaption>
										<audio
												id="audio<?php echo $category_name . $k ?>"
												src="<?php echo $lib_audio_root_url . '/' . $category_name . '/' . $audio['filename'] ?>"
										>
											Your browser does not support the
											<code>audio</code> element.
										</audio>
									</figure>
								</li>
							<?php } ?>
						</ul>
					</div>
				<?php } ?>
			</div>

			<?php
			
			# URL и путь к аудио файлу
			$audio_user = [
				'url' => $data_product->post_excerpt,
				'second'  => 0,
				'is_file' => false
			];
			if ($audio_user['url'])
			{
				$audio_user['path'] = $_SERVER['DOCUMENT_ROOT'] . '/' . parse_url($audio_user['url'], PHP_URL_PATH);
				$audio_user['is_file'] = file_exists($audio_user['path']);
				//$user_audio_title = '';
				if ($audio_user['is_file']) {
					$audio_user['url'].= '?' . filemtime($audio_user['path']);
					//$user_audio_title = get_title_from_filename($audio_user['path']);
				}
				else {
					//$audio_user['url'] = '';
				}
				$audio_user['second'] = $post_meta['second_start_audio'][0];
				if (isset($post_meta['second_start_audio'][0]))
					$audio_user['second'] = $post_meta['second_start_audio'][0];
			}
			//var_dump($audio_user);
			?>
			<ul class="list-inline text-left">
				<li>
					<figure id="figureAudioUser" style="display: <?php echo ($audio_user['is_file'] ? 'block' : 'none'); ?>">
						<figcaption data-audio-id="audioUser">
							<i class="fas fa-play"></i>
							Ваша музыка<?php /*echo $user_audio_title*/ ?>
						</figcaption>
						<audio
								id="audioUser"
							<?php if ($audio_user['is_file']) { echo ' src="' . $audio_user['url'] .'"'; } ?>
								<?php if (is_user_logged_in()) { ?>controls="controls"<?php } ?>
								data-second="<?php echo $audio_user['second'] ?>"
						>
							Your browser does not support the
							<code>audio</code> element.
						</audio>
					</figure>
				</li>
			</ul>

		</div>
		<!-- /audioLib -->

	</section>
	<!-- /ПОМЕНЯТЬ МУЗЫКУ -->

</div>


<!-- Аудио-редактор -->

<!-- Button trigger modal -->
<?php /*if (is_user_logged_in()) { ?>
	<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editorMedia">
		Выбрать секунду начала Вашей аудио-записи
	</button>
<?php }*/ ?>

<!-- Modal: Выбрать обрезок пользовательского аудио -->
<div class="modal fade" id="editorMedia" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="false">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-body" style="margin: inherit;">
				
				<div style="position: relative">
					
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<i class="far fa-times-circle fa-2x"></i>
					</button>
					<div class="clearfix"></div>
					
					<div id="waveformContainer">
						<div id="waveform"></div>
						<div id="highlightAudioSegment"></div>
						<input class="seconds_range" id="barMediaUser" type="range" min="0" max="10" step="1" value="0" />
					</div>
					
					<input class="scroll_range" id="rangeMediaSegment" type="range" min="0" max="10" step="1" value="0" />
					
					<div class="rounded bg-warning mt-3 p-2">
						<div class="row">
							<div class="col-4 text-left offset-sm-5 col-sm-2 text-sm-center">
								<div class="play-button"><i class="fas fa-play fa-2x"></i></div>
								<!--<div class="play-button"><i class="fas fa-play fa-2x text-white"></i></div>-->
							</div>
							<div class="col-8 text-right col-sm-5 d-flex align-items-center justify-content-end">
								<big><!--<i class="fa fa-volume-down text-white"></i>--><i class="fa fa-volume-down"></i></big>&nbsp;<input
										id="volumeMediaUser" type="range" min="0" max="100" step="1" value="30" />
								<span class="d-none"><span id="currentTimeMediaUser"></span> / <span id="totalTimeMediaUser"></span></span>
							</div>
						</div>
					</div>
				
				</div>
			</div>
			<div class="modal-footer">
				<!--
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
				
				-->
				<button id="btnTakeSegmentAudio" type="button" class="btn btn-danger mx-auto" data-dismiss="modal">Взять выделенную часть</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalWait" tabindex="-1" role="dialog" aria-labelledby="modalWaitLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content text-center rounded-lg">
			<div class="modal-body">
				<div class="h1 my-0">Ждите!</div>
				<div class="h2 my-3">Идет создание файла</div>
				<div class="h2 my-3">Осталось секунд: <span id="counterSeconds">40</span></div>

			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalShare" tabindex="-1" role="dialog" aria-labelledby="modalShareLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalShareLabel">Поделиться в соц.сетях</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<script src="https://yastatic.net/share2/share.js"></script>
				<div class="ya-share2" data-curtain data-size="l" data-url="<?php echo $download_link ?>"
					 data-services="vkontakte,facebook,odnoklassniki,twitter,whatsapp,telegram"></div>
			</div>
		</div>
	</div>
</div>
<!-- https://<?php echo $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?> -->