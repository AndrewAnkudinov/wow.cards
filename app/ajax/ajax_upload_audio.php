<?php


# ЗАКАЧАТЬ МУЗЫКУ

# Подключить файл конфигурации приложений сайта
require_once( __DIR__ . '/../app-config.php' );
$data_output = [];

// Проверить входящие данные
if (!isset( $_POST['id_product']) ) {
	$data_output['error'] = 'Файл не загружен. Не указан ID заказа';
}
if (!isset( $_FILES['audio']['tmp_name'] )) {
	$data_output['error'] = 'Файл не загружен. Входящие данные неполные или неверные.';
}

$uploaded_file = $_FILES['audio']['tmp_name'];
$extension_file = pathinfo($_FILES['audio']['name'], PATHINFO_EXTENSION);
$valid_audio_formats = array('mp3', 'ogg', 'flac', 'wav', 'aiff', 'alac'/*, 'aac', 'wma', 'm4a'*/);  // wma, m4a не воспроизводится в плеере Google Chrome, aac - не правильно определяется время
if (!in_array($extension_file, $valid_audio_formats)) {
	$data_output['error'] = 'Файл не загружен. Неразрешенный тип файла.';
}
// /Проверить входящие данные

# Переместить файл
define('ORDER_ID', $_POST['id_product']);
$path_audio_file = PATH_AUDIO_USERFILES . '/' . ORDER_ID . FF_SUFFIX . '.' . $extension_file;
$url_audio_file = URL_AUDIO_USERFILES . '/' . ORDER_ID . FF_SUFFIX . '.' . $extension_file;
if (!move_uploaded_file($uploaded_file, $path_audio_file)) {
	$data_output['error'] = 'Файл не получен!';
}

# ПРОВЕРИТЬ ДЛИТЕЛЬНОСТЬ МУЗЫКИ
if (!isset($data_output['error']))
{
	$data_output['url'] = $url_audio_file . '?' . filemtime($path_audio_file);
	$design_duration = 30;  // Длительность клипа в секундах
	
	# getid3 https://github.com/JamesHeinrich/getID3/
	require_once(__DIR__ . '/../lib/getID3-master/getid3/getid3.php');
	$getID3 = new getID3;
	$ThisFileInfo = $getID3->analyze($path_audio_file);
	//var_dump($ThisFileInfo);
	$data_output['duration'] =
	$audiofile_duration = $ThisFileInfo['playtime_seconds'];
	if (
		$audiofile_duration != false
		&& $audiofile_duration < $design_duration
	) {
		
		# Форматировать необходимую длительность аудио-файла
		$design_duration_formatted = '';
		$design_duration_split = array(
			'minutes' => (int)date("i", $design_duration),
			'seconds' => (int)date("s", $design_duration)
		);
		if ($design_duration_split['minutes'] > 0)
			$design_duration_formatted .= ' ' . $design_duration_split['minutes'] . ' мин.';
		if ($design_duration_split['seconds'] > 0)
			$design_duration_formatted .= ' ' . $design_duration_split['seconds'] . ' сек.';
		
		$data_output['error'] =
			'Предупреждение!'
			. "\n"
			. "\n" . 'Вы загрузили музыку, которая короче клипа.'
			. "\n" . 'Загрузите музыку длительностью не менее' . $design_duration_formatted . ', либо оставьте нашу.';
		
	}
}

header( 'Access-Control-Allow-Origin: *' );
echo json_encode( $data_output );