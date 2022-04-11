<?php

// СОЗДАТЬ ЗАКАЗ

if (!$_POST) {
	die('$_POST array is empty');
}
//var_dump($_POST);

# Начать сессию
$sid = $_POST['sid'];
session_id($sid); // Применяем сессию с конкретным ID полученным из POST
if (!isset($_SESSION))
    session_start();

# Подключить ключевые файлы CMS
# Подключить ключевые файлы WP
include_once(__DIR__ . '/../app-config.php');
require_once(PATH_ROOT . '/wp-config.php');
require_once(PATH_ROOT . '/wp-includes/post.php');

# Редактировать уже отправленный заказ
$reSendId = $_POST['id_order'];
if ($reSendId != '')
{
	if (get_post($reSendId)->post_content != $_POST['email']) {
		$my_post = array(
			'ID'           => $reSendId,
			'post_content' => wp_strip_all_tags($_POST['email'])
		);
		wp_update_post($my_post);
		
		$to = $_POST['email'];
		require_once(PATH_APP . '/mail/send-mail.php');
		send_mail($reSendId, $to, 'wait');
	}
	exit;
}
if (!isset($_SESSION['BX_USER_IDENT'])) {
    if ($_POST['uident'])
        $_SESSION['BX_USER_IDENT'] = $_POST['uident'];
}

# Определить ID дизайна
define('ID_INITIAL_DESIGN', intval($_POST['id_initial_design']));  // ID дизайна, на странице которого создавался заказ
$id_design = ID_INITIAL_DESIGN;  // ID дизайна в заказе (юзер может его менять в процессе создания заказа)
if (isset($_POST['id_design']) && intval($_POST['id_design'])) {
	$id_design = intval($_POST['id_design']);
}
define('ID_DESIGN', $id_design);

$_SESSION['BX_CLIP_ID'] = ID_INITIAL_DESIGN;
$sessionTmp = $_SESSION;

//mail('my_works@mail.ru', 'wow.cards create order data', print_r($_POST, true) . "\r\n" . print_r($_SESSION, true)); die;
define('PATH_TMP_PRODUCT', PATH_TMP_USERFILES . '/' . $sessionTmp['BX_USER_IDENT'] . '/' . ID_INITIAL_DESIGN);

// защита от повторной отправки одного и того же заказа
if (!file_exists(PATH_TMP_PRODUCT . '/' . 'flag.txt')) { // eсли файла-флага нет, значит уже удалили, когда оформляли заказ
	echo(json_encode(array('order_url' => '/clips/')));  // TODO: придумать другой выход из ошибки. Скрипт уже возвращяет ID заказа, а не URL
	die();
}
unlink(PATH_TMP_PRODUCT . '/' . 'flag.txt'); // eсли файл-флаг есть, значит удаляем его и оформляем заказ


# WEEZE

// Добавить заказ в базу данных
$my_post = array(
	'post_content' => wp_strip_all_tags($_POST['email']),
	'post_type'    => 'product',
	'post_status'  => 'publish'  // pending draft
);
$id_order = wp_insert_post($my_post);

define('ID_ORDER', $id_order);
define('NAME_ORDER', ID_ORDER . FF_SUFFIX);

$my_post = array(
	'ID'         => ID_ORDER,
	'post_name'  => ID_ORDER,
	'post_title' => NAME_ORDER
);
wp_update_post($my_post);

# Получить свойства дизайна
$design = design\get_design(ID_DESIGN);

# Записать дополнительные данные заказа в мета-данные его WP-записи:
# - родительский пост;
# - цену заказа;
# - язык.
add_post_meta(ID_ORDER, 'id_design', ID_DESIGN);
if (!$design['free']) {
	include_once(PATH_APP . '/lib/designs/Designs.php'); # Подключить класс "Дизайн"
	add_post_meta(ID_ORDER, 'price', Designs::calc_price_product(count($_POST['media_files'])));
}
//add_post_meta(ID_ORDER, 'count_media', count($_POST['media_files']));  // Записать кол-во медиа-файлов
pll_set_post_language(ID_ORDER, $_POST['code_language']);

$path_order = PATH_ORDERFILES . '/' . NAME_ORDER;  // Weeze
//$path_order = substr(PATH_ORDERFILES . '/', 0, -1) . '_CONFIRM/' . ID_ORDER . FF_SUFFIX . '/';
mkdir($path_order, 0755);


# СОБРАТЬ ДАННЫЕ ЗАКАЗА

# Получить свойства дизайна

$name_design = $design['name'];
$countToLoad = $design['max_number_files'];

# СОБРАТЬ ТЕКСТОВЫЕ ДАННЫЕ ЗАКАЗА
$data_order = [ // Создать контейнер с выходными данными скрипта
	'domain'     => $_SERVER['HTTP_HOST'],
	'folder'     => NAME_ORDER,
	//'resolution' => $_POST['resolution'],
	'maket'      => $name_design
];

//$resolution = $_POST['resolution']; 2021-07-08


// WEEZE:
// Записать в файл координаты обрезки изображения
// Записать в файл время создания заказа
// Записать в файл цвет текста
if (isset($_POST['media_files'])) {
	$data_order['sources'] = $_POST['media_files'];
	foreach ($data_order['sources'] as $key => $source)
	{
		if (isset($source['resolutionFrame'])) {
			$data_order['sources'][$key]['resolution'] = $source['resolutionFrame'];
			unset($data_order['sources'][$key]['resolutionFrame']);
		}
	}
}

if (isset($_POST['time_order']))
	$data_order['order_time'] = $_POST['time_order'];
if (isset($_POST['color_text']))
	$data_order['text_color'] = $_POST['color_text'];

//@file_put_contents($path_order . '/' . 'maket.txt', $name_design);  // $arFields->post_name
@file_put_contents($path_order . '/' . 'maket_temp.txt', $name_design);

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
$items = json_decode(stripslashes($_POST['items']), true); // TODO: массив items не нужен
/*
// Загрузить файлы на сервер, если они пришли в виде BLOB-объектов
function save_BLOB_file($data, $filename) {
	if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
		$data = substr($data, strpos($data, ',') + 1);
		$type = strtolower($type[1]); // jpg, png, gif
		
		if (!in_array($type, [ 'jpg', 'jpeg', 'gif', 'png' ])) {
			throw new \Exception('invalid image type');
		}
		$data = str_replace( ' ', '+', $data );
		$data = base64_decode($data);
		
		if ($data === false) {
			throw new \Exception('base64_decode failed');
		}
	} else {
		throw new \Exception('did not match data URI with image data');
	}
	$basename_file = $filename . '.' . $type;
	file_put_contents($basename_file, $data);
	return $filename;
}


if (isset($data_order['sources'][0]['src'])) {
	foreach ($data_order['sources'] as $i => $media_file) {
		$name_new_file = md5(microtime() . rand(0, 9999));  // TODO: создавать ID файла в JS, как и у файлов, загружаемых через POST
		$filename = save_BLOB_file($name_new_file, $media_file['src']);
		$arMedia[$i]['id'] = $filename;
	}
}
*/
$arFiles = scandir(PATH_TMP_PRODUCT);
@file_put_contents(
	$path_order . '/' . 'wait_24.txt',
	'test scandir ' . ID_ORDER
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
				if ($fNewName <= $countToLoad)
				{
					if (isset($data_order['sources'][ $curID - 1 ]['crop'])) {
						$data_order['sources'][ $curID - 1 ]+= $data_order['sources'][ $curID - 1 ]['crop'];
						unset($data_order['sources'][ $curID - 1 ]['crop']);
					}
					$data_order['sources'][ $curID - 1 ]['file'] = $fNewName . "." . $arFileName[1];
					
					// Привести значение поворота к диапазону -90...180
					$data_order['sources'][ $curID - 1 ]['rotate'] = ($data_order['sources'][ $curID - 1 ]['rotate'] + 90) % 360 - 90;
					rename(PATH_TMP_PRODUCT . '/' . $file, $path_order . '/' . $fNewName . "." . $arFileName[1]);
					
				}
			}
		}
	}
}

# Пересчитать обрезку фреймов при замене дизайна
if (isset($_POST['id_new_design'])) {
	$data_order['sources'] = design\change_sources_design($data_order['sources'], $design);
}

/* sm123 2020-04-29: в принцепе wait24, country и инфо о браузере пользователя больше нужно
//Дублировать недостающие файлы
@file_put_contents($path_order . '/' . 'no_logo.txt', PATH_TMP_PRODUCT . '/' . "\r\n" . $_SERVER['HTTP_USER_AGENT'] . "\r\n" . $_SESSION['BX_CLIP_ID'] . ' === ' . ID_DESIGN . "\r\n" . $out);
*/

foreach ($arMedia as $index => $media) {
	if ($media['ind'] >= $countToLoad) {
		unset($arMedia[$index]);
	}
}
//если в массиве остались позиции, значит потерялись файлы в заказе, нужно его дополнить.
$arFiles = scandir(PATH_TMP_PRODUCT);
if (isset($_POST['audio']) && $_POST['audio'] != '') {
	$arAudio = json_decode(stripslashes($_POST['audio']), true);
	if (!empty($arAudio)) {
		# TODO: по какой-то причине иногда пропадает элемент BX_UPLOADED_CLIPS из сессии, и тогда весь сайт переносится в папку с заказом. Следующая заплатка защищает от этого:
		if (isset($sessionTmp['BX_UPLOADED_CLIPS'][ID_DESIGN]['audio'][$arAudio[0]['id']]['src'])) {
			$src = $sessionTmp['BX_UPLOADED_CLIPS'][ID_DESIGN]['audio'][$arAudio[0]['id']]['src'];
			$ext = $sessionTmp['BX_UPLOADED_CLIPS'][ID_DESIGN]['audio'][$arAudio[0]['id']]['ext'];
			rename($_SERVER['DOCUMENT_ROOT'] . $src, $path_order . '/' . 'audio.' . $ext);
		} # Если не сработала старая схема, то найти файл с именем $_POST['audio'] среди закаченных пользователем
		else {
			if (is_file(PATH_TMP_PRODUCT . '/' . $arAudio[0]['id'] . '.mp3')) {
				rename(PATH_TMP_PRODUCT . '/' . $arAudio[0]['id'] . '.mp3', $path_order . '/' . 'audio.mp3');
			}
			//mail('my_works@mail.ru', 'test audio '. ID_ORDER, print_r($_POST,true). "\r\n". print_r($_SESSION, true));
		}
	}
}
foreach ($arText as $i => $text) {
	$index = $i + 1;
	//@file_put_contents($path_order . '/' . 'text' . $index . '.txt', $text, FILE_APPEND);  // Weezy новый
	$data_order['texts']['text' . $index] = $text;
	//@file_put_contents($path_order . '/' . 'text' . $index . '.txt', $text['text'], FILE_APPEND);
}

# Собрать список файлов
$arCheckListFiles = scandir($path_order);
foreach ($arCheckListFiles as $file) {
	if ($file != '.' && $file != '..') {
		$data_order['check_list'][] = $file;
		//@file_put_contents($path_order . '/' . 'check_list.txt', $file . "\r\n", FILE_APPEND);
	}
}

# Отправить письмо
$to = $_POST['email'];
require_once(PATH_APP . '/mail/send-mail.php');
send_mail(ID_ORDER, $to, 'wait');
//wp_mail( $to, $subject, $body, $headers );


# СОХРАНИТЬ ЗАКАЗ

# Сохранить данные заказа
@file_put_contents($path_order . '/' . 'data.txt', json_encode($data_order));

// СОХРАНИТЬ ЗАКАЗ В КУКИ И В ПАПКУ БЕКАПА ДЛЯ ПЕРЕСОЗДАНИЯ ЗАКАЗОВ
$ids_old_orders = design\get_ids_order_cookie();  // TODO: Удалить старые заказы
$ids_old_orders[] = ID_ORDER;
setcookie(
	'ids_orders',
	serialize($ids_old_orders),
	time() + (10 * 365 * 24 * 60 * 60),
	'/'
);

# Копировать заказ в папку бекапа
$files = glob($path_order . '/*.*');
$path_backup_order = PATH_BACKUP_USERFILES . '/' . NAME_ORDER;
mkdir($path_backup_order, 0755);
//var_dump($files);
foreach ($files as $file) {
	$file_to_go = str_replace($path_order, $path_backup_order, $file);
	//echo "\r\n" . $file . ' | ' . $file_to_go;
	copy($file, $file_to_go);
}

# Вывести выходящие данные
exit(json_encode([
	'id_order' => ID_ORDER,
	'url_order' => design\get_url_product(ID_ORDER)
]));