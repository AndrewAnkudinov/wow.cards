<?

# ЗАГРУЗИТЬ ФАЙЛ ФРАГМЕНТАМИ (CHUNK) В ЗАКАЗ

error_reporting(E_ERROR);

# Подключить ключевые файлы CMS
include_once(__DIR__ . '/../app-config.php');

$sid = $_POST['sid'];
if ($sid) {
	session_id($sid);
} // Применяем сессию с конкретным ID полученным из POST

//if ($_POST['sid'])
//mail('my_works@mail.ru', 'upload POST', print_r($_POST,true));

if (!isset($_SESSION)) {
	session_start();
}

# Ключевые переменные: ID дизайна
define('DESIGN_ID', $_POST['clip_id']);
if (!isset($_SESSION['BX_CLIP_ID'])) {
	$_SESSION['BX_CLIP_ID'] = DESIGN_ID;
}

// Если идентификатор пользователя или макета отсутствует, то создаим его в сессии
if (!isset($_SESSION['BX_USER_IDENT'])) {
	if ($_POST['uident']) {
		$_SESSION['BX_USER_IDENT'] = $_POST['uident'];
	} else {
		$_SESSION['BX_USER_IDENT'] = uniqid();
	}
	
	//  mail('my_works@mail.ru','lost ident',print_r($_POST,true).print_r($_SESSION, true));
}
//mail('my_works@mail.ru','lost ident',print_r($_POST,true));

if (!$_POST) {
	die ('Нет POST-данных');
}

# ФУНКЦИЯ: Получить расширение файла
function getExtension($basename_file)
{
	return substr(strrchr($basename_file, '.'), 1);
}

# ФУНКЦИИ: Получить размеры изображения, видео
function getDimensionsImage($path_video)
{
	$image_size = getimagesize($path_video);
	$dimensions_image = [
		'width'  => $image_size[0],
		'height' => $image_size[1]
	];
	return $dimensions_image;
}

function getDimensionsVideo($path_video)
{
	exec('/usr/bin/ffprobe -v error -select_streams v:0 -show_entries stream=width,height -of csv=s=x:p=0 ' . $path_video, $output, $result);
	$array_output = explode('x', $output[0]);
	$dimensions_video = [
		'width'  => $array_output[0],
		'height' => $array_output[1],
	];
	return $dimensions_video;
}

// Создать корневую папку юзера и дизайна, если её нет
$dir_upload = PATH_TMP_USERFILES . '/' . $_SESSION['BX_USER_IDENT'] . '/' . DESIGN_ID;
if (!is_dir($dir_upload)) {
	@mkdir($dir_upload, 0755, true);
}

// Загрузить файл
$basename_file = $_POST['name'];  // Имя загружаемого файла

// Тип загрузки: false/lib/url (локальный/из библиотеки/с другого сайта)
$upload_type = false;
if (isset($_POST['upload_type']))
	$upload_type = $_POST['upload_type'];


// ЗАГРУЗИТЬ ФАЙЛ ИЗ БИБЛИОТЕКИ
// ЗАГРУЗИТЬ ФАЙЛ ИЗ КОМПЬЮТЕРА ПОЛЬЗОВАТЕЛЯ
if ($upload_type == 'lib') {
	$fileUpload = $_SERVER['DOCUMENT_ROOT'] . parse_url($basename_file, PHP_URL_PATH);
	$basename_file = pathinfo($basename_file, PATHINFO_BASENAME);
} else {
	$fileUpload = $_FILES['chunk']['tmp_name'];
}

$tmp_filename = $dir_upload . '/' . $basename_file; // Временный файл-контейнер для записи загружаемого файла
$f = fopen($tmp_filename, "a");
fputs($f, file_get_contents($fileUpload));
fclose($f);


// Копировать изображение с поворотом и уменьшением размеров
function fix_rename_image($oldname, $newname)
{
	
	$is_fix = false; // Флаг внесенных изменений в файл
	//echo $m = memory_get_usage() . "\n";
	$image = new Imagick($oldname);
	//var_dump($image);
	//echo $m2 = memory_get_usage() . "\n";
	//echo $m2 - $m;
	$rotate = 0;
	$orientation = $image->getImageOrientation();
	switch ($orientation) {
		case imagick::ORIENTATION_BOTTOMRIGHT:
			$rotate = 180; // rotate 180 degrees
			break;
		
		case imagick::ORIENTATION_RIGHTTOP:
			$rotate = 90; // rotate 90 degrees CW
			break;
		
		case imagick::ORIENTATION_LEFTBOTTOM:
			$rotate = -90; // rotate 90 degrees CCW
			break;
	}
	if ($rotate) {
		$is_fix = true;
		$image->rotateimage("#000", $rotate);
		// Now that it's auto-rotated, make sure the EXIF data is correct in case the EXIF gets saved with the image!
		$image->setImageOrientation(imagick::ORIENTATION_TOPLEFT);
	}
	
	//var_dump($image->getImageWidth(), $image->getImageHeight());

	# Уменьшить размер изображения
	$width = $image->getImageWidth();
	$height = $image->getImageHeight();
	if (

		$width > 2000
		|| $height > 1000
/*
		$width > 4800
		|| $height > 2400
*/
	) {
		$is_fix = true;
		$scale = min(4096/$width/6, 2048/$height/4);
		//var_dump($width * $scale, $height * $scale);
		//die;
		$image->scaleImage($width * $scale, $height * $scale);
		//$image->resizeImage($width * $scale, $height * $scale, imagick::FILTER_POINT/*FILTER_CATROM*/, 1);
	}

	if ($is_fix) {
		$image->writeImage($newname);
		$image->clear();
		unlink($oldname);
	}
	else
		rename($oldname, $newname);
}


# Начать/продолжить загрузку файла (загружается порциями)
if (filesize($tmp_filename) != $_POST['size']) {
	
	$returned_data = array(
		'file_id'   => md5($basename_file),
		'file_size' => $_POST['size']
	);
	
} # Закончить загрузку файла
else {
	$name_new_file = md5(microtime() . rand(0, 9999));
	$ext = getExtension($basename_file);
	$basename_new_file = $name_new_file . '.' . $ext;
	//if ($upload_type == 'lib') { // 2021-05-21 Для загрузки файлов из библиотеки
		rename($tmp_filename, $dir_upload . '/' . $basename_new_file);
	//} else {
	//	fix_rename_image($tmp_filename, $dir_upload . '/' . $basename_new_file);
	//}


	//unlink($dir_upload.$basename_file);
	
	$returned_data = array(
		'src'     => URL_TMP_USERFILES . '/' . $_SESSION['BX_USER_IDENT'] . '/' . DESIGN_ID . '/' . $basename_new_file,
		'file_id' => $name_new_file
	);
	if ($upload_type == 'lib') { // 2021-05-21 Для загрузки файлов из библиотеки
		$returned_data['id_restored_file'] = $_POST['id_restored_file'];
	}
	
	$finfo = finfo_open(FILEINFO_MIME_TYPE);
	$arFileType = explode('/', finfo_file($finfo, $_SERVER['DOCUMENT_ROOT'] . $returned_data['src']));
	$type = 'audio';
	if ($arFileType[0] == 'image' || $arFileType[0] == 'video') {
		$type = 'media';
	}
	
	# Добавить в исходящие данные размеры (px) медиа-файла
	if ($arFileType[0] == 'image') {
		$returned_data += getDimensionsImage($dir_upload . '/' . $basename_new_file);
	} elseif ($arFileType[0] == 'video') {
		$returned_data += getDimensionsVideo($dir_upload . '/' . $basename_new_file);
	}
	
	$_SESSION['BX_UPLOADED_CLIPS'][DESIGN_ID][$type][$name_new_file] = array(
		'src'     => $returned_data['src'],
		'file_id' => $returned_data['file_id'],
		'ext'     => getExtension($basename_file),
		'name'    => $basename_file,
		'type'    => ($arFileType[0] == "application") ? "audio" : $arFileType[0],
	);
	
}

// TODO: Что значит сей флаг? Судя по контексту, начало загрузки файлов юзера, но какой в этом смысл?
if (!file_exists($dir_upload . '/flag.txt')) {
	@file_put_contents($dir_upload . '/flag.txt', '1');
}

header('Access-Control-Allow-Origin: *');
echo json_encode($returned_data);