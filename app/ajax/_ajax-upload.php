<?

# Загрузить файл фрагментами (chunk)

error_reporting(E_ERROR);

# Подключить ключевые файлы CMS
include_once(__DIR__ . '/../app_config.php');

$sid = $_POST['sid'];
if ($sid) {
	session_id($sid);
} // Применяем сессию с конкретным ID полученным из POST

//if ($_POST['sid'])
//mail('my_works@mail.ru', 'weeze upload POST', print_r($_POST, true));

if (!isset($_SESSION))
	session_start();

// если идентификатор пользователя отсутствует, назначим ему
if (!isset($_SESSION['BX_USER_IDENT'])) {
	if ($_POST['uident'])
		$_SESSION['BX_USER_IDENT'] = $_POST['uident'];
	else
		$_SESSION['BX_USER_IDENT'] = uniqid();
	
	//  mail('my_works@mail.ru','lost ident',print_r($_POST,true).print_r($_SESSION, true));
}
//mail('my_works@mail.ru','lost ident',print_r($_POST,true));
if (!isset($_SESSION['BX_CLIP_ID'])) {
	$_SESSION['BX_CLIP_ID'] = $_POST['clip_id'];
}

$sesTmp = $_SESSION;

//  require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

if (!$_POST) {
	die ('Нет POST-данных');
}

function getExtension($basename_file) {
	return substr(strrchr($basename_file, '.'), 1);
}

# ФУНКЦИИ: Получить размеры изображения, видео
function getDimensionsImage($path_video) {
	$image_size = getimagesize($path_video);
	$dimensions_image = [
		'width' => $image_size[0],
		'height' => $image_size[1]
	];
	return $dimensions_image;
}
function getDimensionsVideo($path_video) {
	exec('/usr/bin/ffprobe -v error -select_streams v:0 -show_entries stream=width,height -of csv=s=x:p=0 ' . $path_video, $output, $result);
	$array_output = explode('x', $output[0]);
	$dimensions_video = [
		'width' => $array_output[0],
		'height' => $array_output[1],
	];
	return $dimensions_video;
}

$finfo = finfo_open(FILEINFO_MIME_TYPE);
// Каталог в который будет загружаться файл
$upload_dir = PATH_TMP_USERFILES . '/' . $sesTmp['BX_USER_IDENT'];//.'/'.$_POST['clip_id'] . '/'
//создадим корневую папку юзера, если её нет
if (!is_dir($upload_dir)) {
	@mkdir($upload_dir, 0755);
}
//создадим папку проекта, если её нет
if (!is_dir($upload_dir . '/' . $_POST['clip_id'])) {
	@mkdir($upload_dir . '/' . $_POST['clip_id'], 0755);
}
$upload_dir = $upload_dir . '/' . $_POST['clip_id'];
$basename_file = $_POST['name'];
$f = fopen($upload_dir . '/' . $basename_file, "a");
fputs($f, file_get_contents($_FILES['chunk']['tmp_name']));
fclose($f);

// Закончить загрузку файла
// ... или продолжить её
if (filesize($upload_dir . '/' . $basename_file) == $_POST['size']) {
	
	
	$ext = getExtension($basename_file);
	$name_new_file = md5(microtime() . rand(0, 9999));
	$basename_new_file = $name_new_file . '.' . $ext;
	rename(
		$upload_dir . '/' . $basename_file,
		$upload_dir . '/' . $basename_new_file
	);
	//unlink($upload_dir.$basename_file);
	
	$returned_data = array(
		'src' => URL_TMP_USERFILES . '/' . $sesTmp['BX_USER_IDENT'] . '/' . $_POST['clip_id'] . '/' . $basename_new_file,
		'file_id' => $name_new_file
	);
	
	$arFileType = explode('/', finfo_file($finfo, $_SERVER['DOCUMENT_ROOT'] . $returned_data['src']));
	$type = 'audio';
	if ($arFileType[0] == 'image' || $arFileType[0] == 'video') {
		$type = 'media';
	}
	if ($arFileType[0] == 'image') {
		$returned_data += getDimensionsImage($upload_dir . '/' . $basename_new_file);
	}
	elseif ($arFileType[0] == 'video') {
		$returned_data += getDimensionsVideo($upload_dir . '/' . $basename_new_file);
	}
	
	
	$_SESSION['BX_UPLOADED_CLIPS'][$_POST['clip_id']][$type][$name_new_file] = array(
		'src' => $returned_data['src'],
		'file_id' => $returned_data['file_id'],
		'ext' => $ext,
		'name' => $basename_file,
		'type' => ($arFileType[0] == "application") ? "audio" : $arFileType[0],
	);
}
else {
	$returned_data = array(
		'file_id' => md5($basename_file),
		'file_size' => $_POST['size']
	);
}

// TODO: Что значит сей флаг? Судя по контексту, начало загрузки файлов юзера, но какой в этом смысл?
$path_flag_txt = PATH_TMP_USERFILES . '/' . $sesTmp['BX_USER_IDENT'] . '/' . $_POST['clip_id'] . '/flag.txt';
if (!file_exists($path_flag_txt)) {
	@file_put_contents($path_flag_txt, 1);
}

header('Access-Control-Allow-Origin: *');
echo json_encode($returned_data);