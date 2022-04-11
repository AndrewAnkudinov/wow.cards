<?php

// СОЗДАТЬ ЗАКАЗ

if (!$_POST) {
	die('$_POST array is empty');
}


function RemoveDir($path)
{
	$path = substr($path, 0, -1);
	if (file_exists($path) && is_dir($path)) {
		$dirHandle = opendir($path);
		while (false !== ($file = readdir($dirHandle))) {
			if ($file != '.' && $file != '..') {
				$tmpPath = $path . '/' . $file;
				chmod($tmpPath, 0777);
				if (is_dir($tmpPath)) {
					RemoveDir($tmpPath);
				} else {
					if (!unlink($tmpPath)) echo 'Не удалось удалить файл «' . $path . '»!';
				}
			}
		}
		closedir($dirHandle);
		// удаляем текущую папку
		if (!rmdir($path)) echo 'error', 'Не удалось удалить папку «' . $path . '»!';
	} else {
		echo 'error', 'Папки «' . $path . '» не существует!';
	}
}


//var_dump($_POST);
$sid = $_POST['sid'];
$reSendId = $_POST['order_id'];
if ($reSendId != '') {
	require_once('../../wp-config.php');
	require_once('../../wp-includes/post.php');
	require_once('../../wp-content/plugins/fromfoto/fromfoto.php');
	require_once('../../wp-content/themes/weeze/function.php');
	if (get_post($reSendId)->post_content != $_POST['email']) {
		$my_post = array(
			'ID'           => $reSendId,
			'post_content' => wp_strip_all_tags($_POST['email'])
		);
		wp_update_post($my_post);
		
		$to = $_POST['email'];
		require_once('../../wp-includes/pluggable.php');
		require_once('../../wp-content/plugins/easy-wp-smtp/easy-wp-smtp.php');
		require_once('../../e-mail/send_mail.php');
		send_mail($reSendId, $to, 'wait');
	}
}

session_id($sid); // Применяем сессию с конкретным ID полученным из POST
if (!isset($_SESSION)) session_start();
if (!isset($_SESSION['BX_USER_IDENT'])) {
	if ($_POST['uident'])
		$_SESSION['BX_USER_IDENT'] = $_POST['uident'];
}
define('DESIGN_ID', $_POST['design_id']);
$_SESSION['BX_CLIP_ID'] = DESIGN_ID;
$sessionTmp = $_SESSION;

//Подключить ключевые файлы WP
require_once('../../wp-config.php');
require_once('../../wp-includes/post.php');
require_once('../../wp-content/plugins/fromfoto/fromfoto.php');

//mail('my_works@mail.ru', 'test crop', print_r($_POST, true) . "\r\n" . print_r($_SESSION, true)); //die;
$baseTmpPath = DEFAULT_UPLOAD_TMP_PATH . $sessionTmp['BX_USER_IDENT'] . '/' . DESIGN_ID . '/';
// защита от повторной отправки одного и того же заказа
if (!file_exists($baseTmpPath . 'flag.txt')) { // eсли файла-флага нет, значит уже удалили, когда оформляли заказ
	echo(json_encode(array('order_url' => '/clips/')));
	die();
}
unlink($baseTmpPath . 'flag.txt'); // eсли файл-флаг есть, значит удаляем его и оформляем заказ


# WEEZE

# Записать кол-во заказов в куки
$order_count = wz_order_count($_POST['email']);
wz_order_count_set($order_count + 1);

//Добавить заказ в базу данных
$my_post = array(
	'post_content' => wp_strip_all_tags($_POST['email']),
	'post_type'    => 'product',
	'post_status'  => 'publish'  // pending draft
);

// Insert the post into the database
$order_id = wp_insert_post($my_post);
define('ORDER_ID', $order_id);

$my_post = array(
	'ID'         => ORDER_ID,
	'post_name'  => ORDER_ID,
	'post_title' => ORDER_ID . FF_SUFFIX
);
wp_update_post($my_post);
add_post_meta(ORDER_ID, '_wpcf_belongs_slideshow_id', DESIGN_ID);  // Установить родительский пост

$baseZakazPath = DEFAULT_UPLOAD_ZAKAZ_PATH . ORDER_ID . FF_SUFFIX . '/';  // Weeze
//$baseZakazPath = substr(DEFAULT_UPLOAD_ZAKAZ_PATH, 0, -1) . '_CONFIRM/' . ORDER_ID . FF_SUFFIX . '/';
mkdir($baseZakazPath, 0755);

# Создать переменные заказа
$post_meta = get_post_meta(DESIGN_ID);
$design_name = get_the_title(DESIGN_ID);
$countToLoad = $post_meta['stories_img_quantity'][0];
//$design_name        = $post_meta['wpcf-maket'][0];
//$countToLoad = $post_meta['wpcf-foto_max'][0];
$arMeta = $post_meta['wpcf-texts'][0];
$texts_row = array();
$lines = explode(PHP_EOL, $arMeta); # Error! Вызывает непонятную ошибку, которая не позволяет странице перезагрузиться.
foreach ($lines as $v) {
	$texts_row[] = explode(',', $v);
}
$texts_count = count($lines);

# Создать контейнер с выходными данными скрипта
$output_data = [
	'resolution' => $_POST['resolution'],
	'maket'      => $design_name
];


if ($order_count == 0)
	@file_put_contents($baseZakazPath . 'maket.txt', $design_name);  // $arFields->post_name
else
	@file_put_contents($baseZakazPath . 'maket_temp.txt', $design_name);

//$form_data = json_decode(stripslashes($_POST['formData']), true);
$form_data = $_POST['formData'];

// Weezy новый: тексты
$arText = array();
foreach ($form_data as $form_data_value) {
	if ($form_data_value['name'] == 'image_captions[]') {
		$arText[] = $form_data_value['value'];
	}
}
//$arText = json_decode(stripslashes($_POST['texts']), true);
$lostFiles = false;
$lostFilesCount = 0;
$i = 1;
$arMedia = json_decode(stripslashes($_POST['media']), true);
$items = json_decode(stripslashes($_POST['items']), true);

$arFiles = scandir($baseTmpPath);
@file_put_contents(
	$baseZakazPath . 'wait_24.txt',
	'test scandir ' . ORDER_ID
	. "\r\n" . '$_POST: ' . print_r($_POST, true)
	. "\r\n" . '$_SESSION: ' . "\r\n" . print_r($_SESSION, true)
	. "\r\n" . '$_POST[texts]: ' . "\r\n" . print_r($_POST['texts'], true)
	//. "\r\n" . '$form_data: ' . "\r\n" . print_r($form_data, true)
	. "\r\n" . '$arText: ' . "\r\n" . print_r($arText, true)
	. "\r\n" . '$arFiles: ' . "\r\n" . print_r($arFiles, true)
	. "\r\n" . '$arMedia: ' . "\r\n" . print_r($arMedia, true)
	. "\r\n" . '$items: ' . "\r\n" . print_r($items, true));

//*** Создание заказа с учётом неправильных файлов ***
usort($arMedia, function ($a, $b) {
	return $a['ind'] - $b['ind'];
});
$curID = 0;
$realCount = 0;
$baseFiles = [];
foreach ($arMedia as $index => $media) {
	if (
		$media['id'] != ''
		&& (int)$media['ind'] > -1
		&& (strrpos($media['type'], 'image') > -1 || strrpos($media['type'], 'video') > -1)
	) {
		foreach ($arFiles as $file) {
			if ($file == '.' || $file == '..') {
				continue;
			}
			$arFileName = explode('.', $file);
			if ($media['id'] == $arFileName[0]) {
				$curID++;
				$fNewName = (int)$curID;
				if ($fNewName <= $countToLoad) {

					if ($arFileName[1] == 'mp3') {
						$fNewName = 'audio';
					} else {
						$realCount++;
						$baseFiles[$realCount] = $fNewName . "." . $arFileName[1];
					}
					rename($baseTmpPath . $file, $baseZakazPath . $fNewName . "." . $arFileName[1]);
				}
			}
		}
	}
}

/* sm123 2020-04-29: в принцепе wait24, country и инфо о браузере пользователя больше нужно
//Дублировать недостающие файлы
@file_put_contents($baseZakazPath . 'no_logo.txt', $baseTmpPath . "\r\n" . $_SERVER['HTTP_USER_AGENT'] . "\r\n" . $_SESSION['BX_CLIP_ID'] . ' === ' . DESIGN_ID . "\r\n" . $out);
*/

foreach ($arMedia as $index => $media) {
	if ($media['ind'] >= $countToLoad) {
		unset($arMedia[$index]);
	}
}
//если в массиве остались позиции, значит потерялись файлы в заказе, нужно его дополнить.
$arFiles = scandir($baseTmpPath);
if (isset($_POST['audio']) && $_POST['audio'] != '') {
	$arAudio = json_decode(stripslashes($_POST['audio']), true);
	if (!empty($arAudio)) {
		# TODO: по какой-то причине иногда пропадает элемент BX_UPLOADED_CLIPS из сессии, и тогда весь сайт переносится в папку с заказом. Следующая заплатка защищает от этого:
		if (isset($sessionTmp['BX_UPLOADED_CLIPS'][DESIGN_ID]['audio'][$arAudio[0]['id']]['src'])) {
			$src = $sessionTmp['BX_UPLOADED_CLIPS'][DESIGN_ID]['audio'][$arAudio[0]['id']]['src'];
			$ext = $sessionTmp['BX_UPLOADED_CLIPS'][DESIGN_ID]['audio'][$arAudio[0]['id']]['ext'];
			rename($_SERVER['DOCUMENT_ROOT'] . $src, $baseZakazPath . 'audio.' . $ext);
		} # Если не сработала старая схема, то найти файл с именем $_POST['audio'] среди закаченных пользователем
		else {
			if (is_file($baseTmpPath . $arAudio[0]['id'] . '.mp3')) {
				rename($baseTmpPath . $arAudio[0]['id'] . '.mp3', $baseZakazPath . 'audio.mp3');
			}
			//mail('my_works@mail.ru', 'test audio '. ORDER_ID, print_r($_POST,true). "\r\n". print_r($_SESSION, true));
		}
	}
}
foreach ($arText as $i => $text) {
	$index = $i + 1;
	//@file_put_contents($baseZakazPath . 'text' . $index . '.txt', $text, FILE_APPEND);  // Weezy новый
	$output_data['text' . $index] = $text;
	//@file_put_contents($baseZakazPath . 'text' . $index . '.txt', $text['text'], FILE_APPEND);
}

// WEEZE:
// Записать в файл координаты обрезки изображения
// Записать в файл время создания заказа
// Записать в файл цвет текста
if (isset($_POST['canvas_data']))
	$output_data['canvas_data'] = $_POST['canvas_data'];
//@file_put_contents($baseZakazPath . 'canvas_data.txt', $_POST['canvas_data']);

# Сохранить список файлов
$arCheckListFiles = scandir($baseZakazPath);
foreach ($arCheckListFiles as $file) {
	if ($file != '.' && $file != '..') {
		$output_data['check_list'][] = $file;
		//@file_put_contents($baseZakazPath . 'check_list.txt', $file . "\r\n", FILE_APPEND);
	}
}

# Отправить письмо
$to = $_POST['email'];
/*
$mail_template = get_post( 213 );
$subject  = $mail_template->post_title;
$body     = sprintf( $mail_template->post_content , ORDER_ID);
$headers   = array('Content-Type: text/plain; charset=UTF-8');  // text/html
require_once ('../../wp-includes/pluggable.php');
require_once ('../../wp-content/plugins/easy-wp-smtp/easy-wp-smtp.php');
wp_mail( $to, $subject, $body, $headers );
*/
require_once('../../e-mail/send_mail.php');
send_mail(ORDER_ID, $to, 'wait');
//wp_mail( $to, $subject, $body, $headers );

echo(json_encode(array('order_id' => ORDER_ID))); // URL запроса подтверждения


# Сохранить инфо заказа

/* sm123 2020-04-29: в принцепе wait24, country и инфо о браузере пользователя больше нужно
include "../../wp-content/plugins/fromfoto/GEO/SxGeo.php";
$SxGeo = new SxGeo('../../wp-content/plugins/fromfoto/GEO/SxGeoCity.dat');
$ip = $_SERVER['REMOTE_ADDR'];
$countryInfo = $SxGeo->getCityFull($ip);
$countryXML = '<CountryInfo>';
$countryXML .= '<code>' . $countryInfo['country']['iso'] . '</code>';
$countryXML .= '<name>' . $countryInfo['country']['name_ru'] . ', ' . $countryInfo['city']['name_ru'] . '</name>';
$countryXML .= '</CountryInfo>';
@file_put_contents($baseZakazPath . 'country.txt', $countryXML);
$baseCount = count($baseFiles);
@file_put_contents(DEFAULT_UPLOAD_ZAKAZ_PATH . '_info.txt', ORDER_ID . ' --> ' . $baseCount . "\r\n", FILE_APPEND); // . ' - ' . $countryInfo['country']['name_ru'] . '/' . $countryInfo['city']['name_ru']
*/

@file_put_contents($baseZakazPath . 'data.txt', json_encode($output_data));
die;