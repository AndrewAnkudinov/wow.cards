<?php

/*
Template Name: Создать заказ
*/

ini_set('display_errors', 1);
error_reporting(E_ERROR);
//error_reporting(E_ALL);

add_action( 'wp_head', 'noRobots' ); // Закрыть страницу от индексирования поисковыми роботами

# Подключить js- и css-файлы
wp_enqueue_style( 'cropper-css', get_stylesheet_directory_uri() . '/assets/css/cropper.css',
	array() );
wp_enqueue_style( 'single-design-css', get_stylesheet_directory_uri() . '/assets/css/single-design.css',
	array('style-css'), filemtime( __DIR__ . '/assets/css/single-design.css' ) );
wp_enqueue_style( 'part-selector-segment-media-css', get_stylesheet_directory_uri() . '/assets/css/part-selector-segment-media.css',
	array('style-css'), filemtime( __DIR__ . '/assets/css/part-selector-segment-media.css' ) );

wp_enqueue_script('sortable-js', get_stylesheet_directory_uri() . '/assets/js/lib/sortable.min.js', array(), '1.0', false);
wp_enqueue_script('polyfill-js', 'https://cdn.polyfill.io/v2/polyfill.min.js', array(), '1.0', false);
wp_enqueue_script('bootstrap-bundle-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.bundle.min.js', array('jquery'), '1.0', false);
wp_enqueue_script('cropper-js', get_stylesheet_directory_uri() . '/assets/js/lib/cropper.js', array(), '1.0', false);
wp_enqueue_script('jquery-cropper-js', get_stylesheet_directory_uri() . '/assets/js/lib/jquery-cropper.min.js', array('cropper-js'), '1.0', false);
//wp_enqueue_script('selector-segment-media-js', get_stylesheet_directory_uri() . '/assets/js/selector-segment-media.js', array('jquery-cropper-js'), filemtime( __DIR__ . '/assets/js/selector-segment-media.js' ), true);
wp_enqueue_script('create-order-crop-js', get_stylesheet_directory_uri() . '/assets/js/create-order-crop.js',
	array('jquery-cropper-js'), filemtime( __DIR__ . '/assets/js/create-order-crop.js' ), true);
wp_enqueue_script('create-order-js', get_stylesheet_directory_uri() . '/assets/js/create-order.js',
	array('create-order-crop-js'), filemtime( __DIR__ . '/assets/js/create-order.js' ), true);
wp_enqueue_script('create-order-selector-segment-media-js', get_stylesheet_directory_uri() . '/assets/js/create-order-selector-segment-media.js',
	array('create-order-js'), filemtime(__DIR__ . '/assets/js/create-order-selector-segment-media.js'), true);

# Установить заголовок H1
add_filter('pre_get_document_title', 'change_title');
function change_title($title) {
	return 'Upload photos to create a video';
}


# ПОЛУЧИТЬ СВ-ВА АКТИВНОГО ДИЗАЙНА

$design_active = design\merge_design_properties($post);  // Соединить св-ва
//var_dump($design_active);
//$design_active = get_design(ID_DESIGN);  // Получить список дизайнов
define('ID_DESIGN', $design_active['ID']);
//define('ID_DESIGN', get_the_ID());
if ( ! isset( $_SESSION['BX_USER_IDENT'] ) ) {
	$_SESSION['BX_USER_IDENT'] = uniqid();
}
setcookie( "ses_var", session_id() );
setcookie( "ses_ident", $_SESSION['BX_USER_IDENT'] );
$_SESSION['BX_CLIP_ID'] = ID_DESIGN;

# Получить настройки подписей дизайна
//$meta_post              = get_post_meta( ID_DESIGN );
//var_dump('<pre>', $meta_post, '</pre>');

# Получить настройки категории сторис
//$term_list = get_the_terms($post->ID, 'design_category');
//$id_category_design = $term_list[0]->term_id;
//var_dump($design_active);

# /ПОЛУЧИТЬ СВ-ВА АКТИВНОГО ДИЗАЙНА

# ВЫБРАТЬ ДИЗАЙН

// Получить список дизайнов
$designs = design\get_designs($design_active['type'], $id_term, 0);
//$designs = Designs::get_designs($design_active['type']);
//var_dump($designs);

# Получить св-ва дизайна
$slugs_create_order_lang = [ # Собрать URL первого дизайна
	'ru_RU' => 'create-slideshow-ru',
	'en_US' => 'create-slideshow'
];
$url_first_design = home_url() . '/' . $slugs_create_order_lang[ get_locale() ] . '/?id_design=' . $designs[ array_key_first($designs) ]['name'];
//$design_active = $designs[ID_DESIGN];

# /ВЫБРАТЬ ДИЗАЙН


# ЗАГРУЗИТЬ СТАРЫЙ ЗАКАЗ

$_H['old_order'] = '';
$_H['email'] = '';
$ids_old_orders = design\get_ids_order_cookie();
$id_old_order = false;
//$id_old_order = end($ids_old_orders);
if (isset($_GET['id_old_order'])) // Получить id старого заказа из GET TODO: временно
	$id_old_order = intval($_GET['id_old_order']);

//$id_old_order = 122;
//$id_old_order = 94;
if ($id_old_order)
{
	# Получить email
	$content_post = get_post($id_old_order);
	$_H['email'] = $content_post->post_content;
	
	$old_order = [
		'name_order' => $id_old_order . FF_SUFFIX,
		'files' => []
	];
	$old_order += [
		'path_order' => PATH_BACKUP_USERFILES . '/' . $old_order['name_order']
	];
	$path_order = PATH_TMP_USERFILES . '/' . $_SESSION['BX_USER_IDENT'] . '/' . ID_DESIGN;
	
	/*
	// Копировать старый заказ в папку создаваемого заказа
	var_dump($old_order['path_order']);
	$files = glob($old_order['path_order'] . '/*.*');
	var_dump($files);
	foreach ($files as $file) {
		$extension_file = pathinfo($file, PATHINFO_EXTENSION);
		if ($extension_file != 'txt')
			$file_to_go = $path_order . '/' . md5(microtime() . rand(0, 9999)) . '.' . $extension_file;
		else
			$file_to_go = str_replace($old_order['path_order'], $path_order, $file);
		echo "\r\n" . $file . ' | ' . $file_to_go;
		copy($file, $file_to_go);
		//echo PATH_TMP_USERFILES . '/' . $_SESSION['BX_USER_IDENT'] . '/' . ID_DESIGN;
	}
	*/
	// Получить список файлов старого заказа
	$data_old_order = json_decode(file_get_contents($old_order['path_order'] . '/data.txt'), true);
	
	//var_dump($data_old_order['sources'][1]);
	//if ($id_old_order != $design_active['ID'])
		$data_old_order['sources'] = design\change_sources_design($data_old_order['sources'], $design_active);
	//var_dump($data_old_order['sources'][1]);
	//var_dump($data_old_order);
	foreach ($data_old_order['sources'] as $id_source => $source) {
		$src = URL_BACKUP_USERFILES . '/' . $old_order['name_order'] . '/' . $source['file'];
		$data_file = [
			'id_restored_file' => $id_source,
			'src' => $src,
			'type' => $source['type'],
			'naturalWidth' => $source['naturalWidth'],
			'naturalHeight' => $source['naturalHeight'],
			'size' => filesize($_SERVER['DOCUMENT_ROOT'] . $src),
			'text' => $data_old_order['texts']['text' . ($id_source + 1)]
		];
		if (isset($source['x']))
			$data_file['dataCrop'] = [
				'x' => $source['x'],
				'y' => $source['y'],
				'width' => $source['width'],
				'height' => $source['height'],
				'rotate' => $source['rotate'],
				'scaleX' => $source['scaleX'],
				'scaleY' => $source['scaleY']
			];
		$old_order['files'][$id_source] = $data_file;
	}
	
	//var_dump($old_order);
	$_H['old_order'] = htmlspecialchars(json_encode($old_order));
	
}


# Получить бесплатный дизайн из списка дизайнов
foreach ($designs as $design) {
	if ($design['free']) {
		$design_free = $design;
		break;
	}
}

# Получить список платных дизайнов и переместить активный платный дизайн на первое место
$designs_paid = [];
if (!$design_active['free']) {
	$designs_paid[] = $design_active;
}
foreach ($designs as $design) {
	if (!$design['free'] && $design['ID'] != $design_active['ID']) {
		$designs_paid[] = $design;
	}
}

# HTML
get_header('choose-theme');

//echo "Номер прошлого заказа: " . $id_old_order

?>
	<?php
	
	# Подключить файл конфигурации магазинов оплаты
	require_once( PATH_APP . '/payment/config_payment.php');
	
	
	?>
	<script src="https://widget.cloudpayments.ru/bundles/cloudpayments"></script>

	<input type="hidden" name="config_page" id="configPage"
		   data-ses="<?php echo session_id() ?>"
		   data-uident="<?php echo $_SESSION['BX_USER_IDENT'] ?>"
		   data-id-initial-design="<?php echo ID_DESIGN ?>"
		   data-id-free-design="<?php echo $design_free['ID'] ?>"
		   data-type-design="<?php echo $design_active['type'] ?>"
		   <?php if ($design_active['type'] == 'slideshow') { ?> data-free-design="<?php echo $design_active['free'] ?>"<?php } ?>
		   data-id-order="false"
		   data-number-files="<?= $design_active['number_files'] ?>"
		   data-min-number-files="<?= $design_active['min_number_files'] ?>"
		   data-max-number-files="<?= $design_active['max_number_files'] ?>"
		   data-old-order="<?php echo $_H['old_order'] ?>"
		   data-code-language="<?php echo pll_current_language() ?>"
		   data-cloudpayments-code-language='<?php echo str_replace('_', '-', get_locale()) ?>'
		   data-cloudpayments-public-id='<?php echo $config_payment['cloudpayments']['publicId'] ?>'
	>
	<input type="hidden" name="config_crop" id="configCrop"
		   data-width-slideshow="<?php echo $design_active['width_slideshow'] ?>"
		   data-height-slideshow="<?php echo $design_active['height_slideshow'] ?>"
		   data-width-horizontal-frame="<?php echo $design_active['width_horizontal_frame'] ?>"
		   data-height-horizontal-frame="<?php echo $design_active['height_horizontal_frame'] ?>"
		   data-width-vertical-frame="<?php echo $design_active['width_vertical_frame'] ?>"
		   data-height-vertical-frame="<?php echo $design_active['height_vertical_frame'] ?>"
	>

	<div class="col-12 text-center">

		<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri() ?>/assets/css/csshake.min.css">
		<style>
			.shake:hover, .shake-trigger:hover .shake, .shake.shake-freeze, .shake.shake-constant {
				animation-duration: 5s;
			}
		</style>
		
		<section id="stepStart" class="step">
			<div class="container-content d-flex flex-column justify-content-center align-items-center">
				<div class="container-h1">
					<h1>ДИЗАЙН ВЫБРАН, ДОБАВЬ
						от <span class="text-danger"><?= $design_active['min_number_files'] ?></span>
						до <span class="text-danger"><?= $design_active['max_number_files'] ?></span>
						ФОТО,
						<br>ЧТОБЫ СОЗДАТЬ ПРЕКРАСНОЕ <span class="text-danger">ВИДЕО :)</span></h1>
				</div>
				<button type="button" style="margin: calc(10vh) 0;"
						class="btn btn-lg btn-danger mx-auto btn-upload-media bubbly-button d-inline">
					добавить фото
				</button><!--<span class="arrow_clockwise shake shake-constant"></span>-->
				
				<div class="d-none"><button id="btnRestore">Загрузить выбранный заказ</button></div>
				
				<?php
				/* СПИСОК СТАРЫХ ЗАКАЗОВ
				<div class="mt-2">
				Ваши заказы: 
				<?php
				
				$ids_old_orders = array_reverse($ids_old_orders);
				foreach ($ids_old_orders as $key => $id_order) {
					$class = 'btn-outline-secondary';
					//echo $id_order, $id_old_order;
					if ($id_order == $id_old_order)
						$class = 'btn-secondary';
					?>
					<a href="./?theme=<?php echo ID_DESIGN ?>&id_old_order=<?php echo $id_order ?>"
					   class="btn <?php echo $class ?> btn-sm" style="min-width: 50px;"><?php echo $id_order ?></a>
				<?php
					if ($key >= 10)
						break;
				}
				?>
				</div>
				*/
				?>
				
			</div>
		</section>

		<section id="stepProcessing" class="step" style="display: none;">
			<div class="container-content d-flex flex-column justify-content-center align-items-center">
				<div class="row">
					<div class="col-12">
						<div class="loading">Загрузка файлов ...</div>
					</div>
				</div>
			</div>
		</section>
			
		<section id="stepUploading" class="step d-flex justify-content-center align-items-center" style="display: none;">
			<div class="row">
				<div class="col-12">

					<div class="d-none">
						<div class="caption" style="pointer-events: none;" id="loadCount2">
							<?php
							if ( $design_active['number_files'] ) {
								echo sprintf(__('but first, upload %s photos/videos or MORE', 'fromfoto'),
									'<span class="h3">' . $design_active['number_files'] . '</span>');
							} elseif ( $design_active['max_number_files'] ) {
								echo sprintf('но сначала закачайте до %s файлов',
									'<span class="h3">' . $design_active['max_number_files'] . '</span>');
							}
							?>
							<br>
						</div>
					</div>
					
					<div class="switcher">
						<div class="uploader">

							<!-- Progress bar 1 -->
							<div class="circularBar mx-auto" data-value='0' id="circularBar" style="display: none;">
								<span class="progress-left">
									<span class="progress-bar border-primary"></span>
								</span>
								<span class="progress-right">
									<span class="progress-bar border-primary"></span>
								</span>
								<div class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
									<div class="h2 font-weight-bold">
										<span id="circularBarPercent" class="d-none">0</span>
										<small class="small d-none">%</small>
									</div>
									<div class="loading">uploading photo ...</div>
								</div>
							</div>
							<!-- /Progress bar 1 -->

							<div class="progress d-none" id="workProgress" style="visibility: hidden">
								<div class="bar">
									<div class="loaded"></div>
									<div><span class="speed">0%</span></div>
								</div>
							</div>

							<div class="progress" id="fotoUploadProgress" style="display: none;">
								<div>Loading...</div>
								<label>
									<span class="speed">0 <?php _e( 'Kb/sec', 'fromfoto' ) ?></span>
									| <span class="time">00:00</span>
									| <!--<span class="procent">0</span>-->
									| <span class="loaded">0</span> / <span class="totalSize">0</span></label>
								<div class="bar">
									<div class="loaded"></div>
									<span class="procent">0</span>
								</div>
							</div>
							
							<!--
							<button type="button" class="btn btn-lg btn-outline-light button_resp bubbly-button btn-upload-media">
								upload photo
							</button><!-- id="loadFotoBtn" -->
							<!--<div class="caption" style="pointer-events: none;" id="percent"><br></div>-->

						</div>

						<input type="file" id="inputUploadMedia" accept="image/*,video/*"
							<?php if ( $design_active['number_files'] > 1 || $design_active['max_number_files'] > 1 )
							echo ' multiple' ?>><!-- ,video/* -->
						<div class="caption d-none" style="pointer-events: none;"
							 id="percent_new"><?php _e( 'Enough photos. We will process the rest later', 'fromfoto' ); ?>
							:)<br><br></div>
						<!--<button type="button" id="next-step" class="btn d-none btn_new"><?php _e( 'Great! Create a slideshow', 'fromfoto' ); ?></button>-->
						<div id="exp1" class="caption" style="pointer-events: none;"></div>

					</div>

				</div>
			</div>
		</section>

		<section id="stepListMedia" class="step" style="display: none;"><!--  style="display: none;" -->

			<div class="h1 my-2">нажмите, чтобы <span class="text-danger">отредактировать</span>
				<br>фото или добавить <span class="text-danger">текст</span></div>
			<form>
				<div id="boxMediaFiles" class="visible-scrollbar">
					
					<!-- Thumbnail -->
					<div class="row">
						<div class="col-md-12">
							<!--<a name="thumbnailAnchor"></a>-->
							<ul class="row pl-0"></ul>
						</div>
					</div>
					
					<?php
					/*
					<!-- Text -->
					<div class="col-12 d-none">
						<ul id="textsList">
							<li>
								<div class="form-group">
									<div><label for="textOrder" class="mt-2">add text</label></div>
									<div class="mb-3 row">
										<div class="col-10">
									<?php foreach ( $design_active['texts_row'] as $k => $row ) { ?>
										<textarea id="textOrder" class="form-control bg-secondary"
												  placeholder="my life" <?php if ( ! empty( $row[0] ) ) { ?> data-req="1"<?php } ?>
												  data-name="<?php _e( 'Text', 'fromfoto' );
												  echo $k + 1 ?>" maxlength="<?php echo $row[2] ?>" rows="1" data-text-default="my life">my life</textarea>
									<?php } ?>
										</div>
										<div class="col-2 text-right" style="padding-left: 0;">
											<button type="button" class="btn btn-sm" id="btnTxtColor">&nbsp;</button>
										</div>
									</div>
								</div>
							</li>
						</ul>
					</div>
					
					<!-- Music -->
					<!--
					<div class="d-none">
						<div class="h3"><?php _e( 'Last point', 'fromfoto' ); ?></div>
						<div class="caption">
							<?php _e( 'You can upload your music when a slideshow is ready', 'fromfoto' ) ?>.
						</div>
					</div>
					<div class="cial" style="display:none;">
						<button type="button" id="loadMusBtn"
								class="btn btn_repl1"><?php _e( 'UPLOAD YOUR MUSIC', 'fromfoto' ); ?></button>
						<input type="file" id="loadMusInp" multiple accept="audio/*">
						<ul class="musList" id="muzContainer"></ul>
						<div class="progress" id="musUploadProgress">
							<label>
								<span class="speed">0 <?php _e( 'Kb/sec', 'fromfoto' ) ?></span> | <span
										class="time">00:00</span> |
								<span class="procent">0</span> | <span class="loaded">0</span> /
								<span class="totalSize">0</span>
							</label>
							<div class="bar">
								<div class="loaded"></div>
							</div>
						</div>
						<button type="button" id="returnMusBtn" class="btn"
								style="display:none"><?php _e( 'Return to our music track', 'fromfoto' ) ?></button>
						<br>
					</div>
					-->
					<!-- Music -->

					<!-- TODO: Email -->
					<!--
					<div class="col-12">
						<div class="form-group mb-3">
							<label for="emailOrder">e-mail (where to send finished story)</label>
							<input name="email" id="emailOrder" type="email" placeholder="my_mail@gmail.com"
								   class="form-control bg-secondary" required />
						</div>
					</div>
					-->
										*/
					?>
					
				</div>

				<!-- Buttons of Order -->
				<div id="buttonsOrder" class="row">
					<div class="col-12 text-center">
						<button type="button" data-show-min="1" data-show-max="<?= $design_active['max_number_files'] - 1 ?>"
								class="btn btn-danger btn-upload-media mx-auto mt-3 mb-1 bubbly-button show-min-max">добавить фото</button>
						<div class="show-min-max" data-show-min="1" data-show-max="<?= $design_active['max_number_files'] - 1 ?>">
							Чем больше <span class="text-danger">фото</span>, тем лучше <span class="text-danger">видео</span>!</div>
						<div id="messCounterMedia" class="my-2 d-none show-min-max" data-show-max="29">не менее <span id="counterMedia">9</span> фото</div>
						<div class="show-min-max" data-show-min="<?= $design_active['max_number_files'] ?>">
							<?php if ($design_active['type'] == 'slideshow') { ?>отлично, загружно<?php } ?>
							<?php echo $design_active['max_number_files'] ?> фото - идеально
						</div>
					</div>
					<div class="col-12 text-center">
						<button id="btnCompleteUpload" type="button" class="btn btn-outline-dark mx-auto my-3 bubbly-button show-min-max"
								data-show-min="<?= $design_active['min_number_files'] ?>">далее</button>
						<div class="loading">Загрузка файлов ...</div>
					</div>
				</div>

			</form>

		</section>

		<!-- Cropper -->
		<section id="stepCrop" class="step" style="display: none;"><!-- Для тестов: style="display: block;" -->
	
			<div class="container-fluid">
				<div class="row">
					<div class="col-sm-9">
		
						<!-- Кнопки обрезки медиа -->
						<div id="buttonsCropper">
							<!--
							<button class="btn btn-sm btn-outline-dark btn-circle"><span class="material-icons">delete</span></button>
							<button class="btn btn-sm btn-outline-dark ">Cut photo <span class="material-icons">content_cut</span></button>
							<button class="btn btn-sm btn-outline-dark btn-circle"><span class="material-icons">rotate_left</span></button>
							-->
							<div class="row">
								<div class="col-6 d-flex justify-content-center">
									<div class="text-center">
										<button type="button" id="btnCrop2" class="btn btn-icon btn-light shadow-sm"><span class="material-icons">done</span></button>
										<div class="d-none d-sm-block">Готово</div>
									</div>
									<div class="text-center mx-3" id="boxRotateLeft">
										<button type="button" id="rotateLeft" class="btn btn-icon btn-light shadow-sm"><span class="material-icons">rotate_left</span></button>
										<div class="d-none d-sm-block">Повернуть</div>
									</div>
								</div>
								<div class="col-6 d-flex justify-content-center">
									<div class="text-center mx-3">
										<button type="button" id="deleteMedia" class="btn btn-icon btn-light shadow-sm"><span class="material-icons">delete_outline</span></button>
										<div class="d-none d-sm-block">Удалить</div>
									</div>
									<div class="text-center">
										<button type="button" id="closeEditor" class="btn btn-icon btn-light shadow-sm"><span class="material-icons">close</span></button>
										<div class="d-none d-sm-block">Закрыть</div>
									</div>
								</div>
							</div>
						</div>
		
					</div>
				</div>
				<div class="row">
					<div class="col-sm-9">

<div id="editorMedia">
	
	<!-- Контейнер обрезки медиа -->
	<div id="boxLayersCropper" class="mb-2 mb-sm-0">
		<video id="mediaUser" src="#false"></video>
		<img id="imageCrop" src="#false">
	</div>
	
	<!-- Интерфейс выбора секунды видео -->
	<input class="scroll_range" id="rangeMediaSegment" type="range" min="0" max="10" step="1" value="0" />

	<div class="rounded bg-warning mt-3 p-2">
		<div class="row">
			<div class="col-4 text-left offset-sm-5 col-sm-2 text-sm-center">
				<button class="btn btn-icon btn-light shadow-sm play-button"><i class="fas fa-play"></i></button>
				<!--<div class="play-button"><i class="fas fa-play fa-2x text-white"></i></div>-->
			</div>
			<div class="col-8 text-right col-sm-5 d-flex align-items-center justify-content-end">
				<big><!--<i class="fa fa-volume-down text-white"></i>--><i class="fa fa-volume-down"></i></big>&nbsp;<input
						id="volumeMediaUser" type="range" min="0" max="100" step="1" value="30" />
				<span class="d-none"><span id="currentTimeMediaUser"></span> / <span id="totalTimeMediaUser"></span></span>
			</div>
		</div>
	</div>
	
	<?php
	/*
	<h5>Выбрать секунду начала видео-фрагмента</h5>
	<p>Выбрана секунда:
		<input type="number" id="printFirstSecond" value="0" max="10"
			   style="background: none; border: none; color: #FFF; width: 40px" /></p>

	<input class="scroll_range" id="rangeMediaSegment" type="range" min="0" max="10" step="1" value="0" />

	<div id="waveformContainer" class="mb-2">
		<div id="waveform"></div>
		<div id="highlightAudioSegment"></div>
		<input class="seconds_range" id="barMediaUser" type="range" min="0" max="10" step="1" value="0" />
	</div>
	<div class="mb-2">move to select a part of the video</div>

	<div class="row text-light">
		<div class="col-4 text-left offset-sm-5 col-sm-2 text-sm-center">
			<div class="play-button"><i class="fas fa-play fa-2x text-white"></i></div>
		</div>
		<div class="col-8 text-right col-sm-5">
			<span class="m-2">
				<big><i class="fa fa-volume-down text-white"></i>&nbsp;<input
							id="volumeUserMedia" type="range" min="0" max="100" step="1" value="30" />
					</big>
			</span>
			<span id="currentTimeUserMedia"></span> / <span id="totalTimeUserMedia"></span>
		</div>
	</div>
	*/
	?>

</div>

					</div>
					<div class="col-sm-3 d-sm-flex align-content-around flex-wrap">
						<div id="boxTextareaCropping" class="w-100">
							<!-- TODO: here you can write text	for this video or close this part -->
							<textarea name="textFrame" maxlength="40" rows="3" class="m-auto" placeholder="здесь можно написать текст для фото"></textarea>
							<div id="messMaxLength" style="display: none;">извините, больше 40 символов сложно прочитать, мы пробовали)</div>
						</div>
						<div class="text-center w-100">
	
							<button type="button" class="btn btn-danger m-auto bubbly-button d-inline" id="btnCrop">Готово</button><span class="arrow_clockwise shake shake-constant"></span>
	
						</div>
						<div>&nbsp;</div>
					</div>
		
					<?php
					/*
					<div class="col-12">
		
						<div id="sliderLoadedMedia" class="carousel slide" data-ride="carousel" data-interval="false">
							<div class="carousel-inner row w-100 mx-auto" role="listbox">
								<!--
								<div class="carousel-item col-12 col-sm-6 col-md-4 col-lg-3 active">
									<img src="https://azmind.com/demo/bootstrap-carousel-multiple-items/assets/img/backgrounds/1.jpg" class="img-fluid mx-auto d-block" alt="img1">
								</div>
								<div class="carousel-item col-12 col-sm-6 col-md-4 col-lg-3">
									<img src="https://azmind.com/demo/bootstrap-carousel-multiple-items/assets/img/backgrounds/2.jpg" class="img-fluid mx-auto d-block" alt="img2">
								</div>
								-->
							</div>
							<a class="carousel-control-prev" href="#sliderLoadedMedia" role="button" data-slide="prev">
								<span class="material-icons md-48">chevron_left</span>
								<!--
								<span class="carousel-control-prev-icon" aria-hidden="true"></span>
								<span class="sr-only">Previous</span>
								-->
							</a>
							<a class="carousel-control-next" href="#sliderLoadedMedia" role="button" data-slide="next">
								<span class="material-icons md-48">chevron_right</span>
								<!--
								<span class="carousel-control-next-icon" aria-hidden="true"></span>
								<span class="sr-only">Next</span>
								-->
							</a>
						</div>
					</div>
					*/
					?>
				</div>
			</div>
		</section>

		
		<?php
		
		
		?>
		<!-- Подтвердить платный дизайн -->
		<section id="stepChooseDesign" class="step" style="display: none;">
			<!--<div class="container-content d-flex flex-column justify-content-center align-items-center">-->
			<div class="row">
				<div class="col-12 d-flex justify-content-center container-h1">
					<h1><span class="text-danger">ОТЛИЧНО!</span> Вы справились</h1>
				</div>

				<div class="col-md-7 col-lg-8">

					<h1 class="text-center mb-3">Ваш видеодизайн:</h1>
					<?php echo design\slider_designs($designs_paid) ?>
					<?php /*echo design\player_slideshow($design_active['video'])*/ ?>
					
				</div>

				<div id="prepayment" class="col-md-5 col-lg-4 text-center d-flex align-items-center justify-content-center"
					 style="padding-top: 36px;">
					<img id="imgUnicornOnRainbow"
						 src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/unicorns/unicorn_on_rainbow.png" style="max-width:100%;" alt="">
					<img id="imgUnicornOnDonut"
						 src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/unicorns/unicorn_on_donut.png" style="max-width:100%;" alt="">
					<div style="margin: 60px 0;">

						<div class="h4">Предоплата <span id="amount">90</span> р.</div>
						<a class="btn btn-danger my-1 bubbly-button" id="btnPayPrepayment">Оплатить</a>
						<div style="font-size: 80%;">* Предплата составляет 10% и зависит от количества фото и выбранного видеодизайна</div>

						<br>
						<br>
						<div class="h4 text-danger">Хотите попроще, но <span class="text-primary">бесплатно</span>?</div>
						<button id="showStepChooseFreeDesign2" class="btn btn-outline-dark my-2 bubbly-button">Да</button>

					</div>
				</div>
			</div>
			<!--</div>-->
		</section>

		<?php /*if ($design_active['free']) {*/ ?>
		<!-- Подтвердить изначально бесплатный дизайн -->
		<section id="stepChooseFreeDesign" class="step" style="display: none;">
			<!--<div class="container-content d-flex flex-column justify-content-center align-items-center">-->
			<div class="row">
				<div class="col-12 d-flex justify-content-center container-h1">
					<h1><span class="text-danger">ОТЛИЧНО!</span> Вы справились</h1>
				</div>

				<div class="col-md-7 col-lg-8">
					<h1 class="text-center mb-3">Бесплатный видеодизайн</h1>
					<?php echo design\player_slideshow($design_active['video']) ?>
				</div>

				<div class="col-md-5 col-lg-4 text-center d-flex align-items-center justify-content-center"
					 style="padding-top: 36px;">
					<img id="imgUnicornBalloons"
						 src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/unicorns/unicorn_balloons.png" style="max-width:100%;" alt="">
					<img id="imgUnicornHeart"
						 src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/unicorns/unicorn_heart.png" style="max-width:100%;" alt="">
					<div style="margin: 60px 0;">

						<div class="h4">Нажмите, чтобы</div>
						<a class="btn btn-danger my-1 bubbly-button btn-choose-design">создать видео</a>
						<div class="h4 mt-2">бесплатно.</div>
						
						<br>
						<br>
						<div class="h4">Либо</div>
						<button id="showStepChooseDesign" class="btn btn-outline-dark my-1 bubbly-button">выбрать платный</button>
						<div class="h4 mt-2">видеодизайн</div>
						<div style="font-size: 80%;">* рекомендуется для <span class="text-danger">важного события</span></div>

					</div>
				</div>
			</div>
		</section>
			
		<?php /*} else {*/ ?>
		<!-- Подтвердить смену дизайна на бесплатный -->
		<section id="stepChooseFreeDesign2" class="step" style="display: none;"><!--  -->
			<!--<div class="container-content d-flex flex-column justify-content-center align-items-center">-->
			<div class="row">
				<div class="col-12 d-flex justify-content-center container-h1">
					<h1><span class="text-danger">ОТЛИЧНО!</span> Вы справились</h1>
				</div>

				<div class="col-md-7 col-lg-8">
					<h1 class="text-center mb-3">Бесплатный видеодизайн</h1>
					<?php echo design\player_slideshow($design_free['video']) ?>
				</div>

				<div class="col-md-5 col-lg-4 text-center d-flex align-items-center justify-content-center"
					 style="padding-top: 36px;">
					<img id="imgUnicornBalloons"
						 src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/unicorns/unicorn_balloons.png" style="max-width:100%;" alt="">
					<img id="imgUnicornHeart"
						 src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/unicorns/unicorn_heart.png" style="max-width:100%;" alt="">
					<div style="margin: 60px 0;">

						<div class="h4">Нажмите, чтобы</div>
						<a class="btn btn-danger my-1 bubbly-button btn-choose-design">Заменить дизайн</a>
						<div class="h4 mt-2">на бесплатный.</div>


						<br>
						<br>
						<div class="h4">Либо</div>
						<button id="showStepChooseDesign2" class="btn btn-outline-dark my-1 bubbly-button">вернуть платный</button>
						<div class="h4 mt-2">видеодизайн</div>
						<div style="font-size: 80%;">* Рекомендуем для <span class="text-danger">важного события</span></div>

					</div>
				</div>
			</div>
		</section>
		<?php /*}*/ ?>
	
		<?php if (1 == 2) { ?>
		<section id="_stepChooseDesign" class="step" style="display: none;">


			<div class="col-12 d-flex justify-content-center container-h1">
				<h1><span class="text-danger">ОТЛИЧНО!</span> Вы справились</h1>
			</div>
		
			<div class="col-12">
				<div id="productChooseDesign" class="m-auto text-center">
		
					<div class="row">
						<div class="col-md-6">
							
							<?php
							
							# Извлечь бесплатный дизайн из списка дизайнов
                            foreach ($designs as $design) {
                                if ($design['free']) {
									$design_free = $design;
									unset($design);
								}
                            }
							
							?>
							<div class="h1 mb-3">Бесплатный</div>
							<figure class="figure_video rounded">
								<video width="100%" height="auto" class="rounded" style="display: block;" loop autoplay muted controls>
									<source src="<?php echo $design_free['video'] ?>" type="video/mp4">
									Your browser does not support the video tag.
								</video>
							</figure>
		
							<div class="icons text-big">
								<div><img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/icons/icon_time_20.png" alt="">
									Готовое видео через 20ч.</div>
								<div><img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/icons/icon_customize.png" alt="">
									HD-качество</div>
								<div><img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/icons/icon_design.png" alt="">
									1 видеодизайн</div>
							</div>
							<a class="btn btn-outline-dark bubbly-button" href="<?php /*echo home_url()*/ ?>#"
							   id="btnChooseFreeDesign" data-id-free-design="<?php echo $design_free['ID'] ?>">БЕСПЛАТНО</a>
		
						</div>
		
						<div class="col-md-6 border rounded-lg">
		
							<div class="h1 mb-3 text-danger">ВАЖНОЕ событие</div>
							<div id="carouselSelectDesign" class="carousel slide" data-ride="carousel" data-interval="false">
								<div class="carousel-inner container_video">
									
									<?php
									
									
									# Переместить активный дизайн в начало списка дизайнов
									$temp = array(ID_DESIGN => $designs[ID_DESIGN]);
									unset($designs[ID_DESIGN]);
									$designs = $temp + $designs;

									foreach ($designs as $design) { ?>
										<div class="carousel-item<?php if ($design['ID'] == ID_DESIGN) { echo ' active'; } ?>"
											 data-id-design="<?php echo $design['ID'] ?>">
		
											<figure class="figure_video rounded">
												<video width="100%" height="auto" class="rounded" style="display: block;" loop autoplay muted controls>
													<source src="<?php echo $design['video'] ?>" type="video/mp4">
													Your browser does not support the video tag.
												</video>
												<div class="text-center font-italic"><?php echo $design['name'] ?></div>
											</figure>
		
										</div>
										<?php } ?>
		
								</div>
								<a class="carousel-control-prev d-none d-md-flex" href="#carouselSelectDesign" role="button" data-slide="prev">
									<!--<span class="carousel-control-prev-icon" aria-hidden="true"></span>-->
									<span class="material-icons md-48">keyboard_arrow_left</span>
									<span class="sr-only">Previous</span>
								</a>
								<a class="carousel-control-next d-none d-md-flex" href="#carouselSelectDesign" role="button" data-slide="next">
									<!--<span class="carousel-control-next-icon" aria-hidden="true"></span>-->
									<span class="material-icons md-48">keyboard_arrow_right</span>
									<span class="sr-only">Next</span>
								</a>
							</div>
		
							<div class="icons text-big">
								<div><img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/icons/icon_time_04.png" alt="">
									Готовое видео через <strong class="text-danger">4ч</strong>.</div>
								<div><img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/icons/icon_customize.png" alt="">
									<strong class="text-danger">Full HD</strong>-качество</div>
								<div><img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/icons/icon_design.png" alt="">
									Видеодизайн <strong class="text-danger">на выбор</strong>
									(<span class="btn btn-icon bg-warning rounded-circle mx-2 btn-control-carousel shadow"
										   onclick="jQuery('#carouselSelectDesign').carousel('prev');"><span class="material-icons">keyboard_arrow_left</span></span>
									или <span class="btn btn-icon bg-warning rounded-circle mx-2 btn-control-carousel shadow"
											  onclick="jQuery('#carouselSelectDesign').carousel('next');"><span class="material-icons">keyboard_arrow_right</span></span>)
								</div>
							</div>
		
							<a id="btnChooseDesign" class="btn btn-danger bubbly-button" href="<?php /*echo home_url()*/ ?>#">ПРЕДОПЛАТА 150 ₽.</a>
							<div style="font-size: .9rem; margin: 6px auto 34px;">* Предплата составляет 10% и зависит от количества фото
								<br>и выбранного видеодизайна</div>
		
						</div>
		
					</div>
				</div>
			</div>
		
		</section>
		<?php } ?>
		
		<section id="stepEmail" class="step" style="display: none;">
			<div class="container-content d-flex flex-column justify-content-center align-items-center">
				<div class="container-h1">
					<h1>На какой <span class="text-danger">e-mail</span> отправить
						<br>готовое видео?</h1>
				</div>
				<div style="margin: calc(7vh) 0;">
					<form>
						<input type="email" id="emailOrder" class="form-control form-control-lg mx-auto" style="width: 300px;"
							   placeholder="введите вашу почту" value="<?php echo $_H['email'] ?>" required>
						<br>
						<button type="button" id="btnSubmit" class="btn btn-danger mx-auto" style="width: 300px;"><!-- bubbly-button -->
							Отправить видео
						</button><!--<span class="arrow_clockwise shake shake-constant"></span>-->
						<a name="btnSubmit"></a>
					</form>
				</div>
			</div>
		</section>

	</div>

	<div class="modal fade" id="modalProcessing" tabindex="-1" role="dialog" aria-labelledby="modalProcessingLabel" aria-hidden="true">
		<div class="modal-dialog modal-xl" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalProcessingLabel">подождите, фото загружаются ...</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
			</div>
		</div>
	</div>
<?php
/*
<!-- Modal -->
<div class="modal fade" id="modalUserLicense" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">User Agreement</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="userLicense">
				<?php

				# Вставить лицензионное соглашение по ID записи из DB
				$post_id = 12; // example post id
				$post_content = get_post($post_id);
				$content = $post_content->post_content;
				//echo '<h3>' . $post_content->post_title . '</h3>';
				echo $content = apply_filters('the_content', $content);

				?>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- / Modal -->
*/
?>

<!--
	<script src="https://cdn.polyfill.io/v2/polyfill.min.js"></script>
	<script src="<?php echo get_stylesheet_directory_uri() ?>/assets/js/helpers.js"></script>
	<script src="<?php echo get_stylesheet_directory_uri() ?>/assets/js/differentPlayers/create_video_new.js"></script>
	<script src="<?php echo get_stylesheet_directory_uri() ?>/assets/js/common.js"></script>
-->

<?php get_footer(); ?>