<?php

# ПРИНЯТЬ СИГНАЛ О ГОТОВНОСТИ ПРЕДВАРИТЕЛЬНОГО КЛИПА

if (!isset($_REQUEST['zakaz1'])) {
	die('Не указан ID заказа.');
}

$id_product = (int)$_REQUEST['zakaz1'];
if (!isset($_REQUEST['zakaz1'])) {
	die('ID заказа пуст.');
}

# Подключить ключевые файлы CMS
require_once( __DIR__ . '/../app-config.php' );
require_once( PATH_ROOT . '/wp-config.php');
require_once( PATH_ROOT . '/wp-includes/post.php' );

//если есть каталог с заказо и видео загружено
define('NAME_PRODUCT', $id_product . FF_SUFFIX);
$orderPath = PATH_ORDERFILES . '/' . NAME_PRODUCT . '/';
if (!is_dir($orderPath)) {
	die('Не найдена папка с файлами заказа.');
}

# Скопировать файлы
function copy_file_order($file, $newfile)
{
	if (file_exists($file)) {
		if (!copy($file, $newfile)) {
			die ('Не удалось скопировать' . basename($file));
		}
	} else {
		die;
	}
}
$filePath = $orderPath . '/Video/' . NAME_PRODUCT . '_preview.mp4';
$picPath = $orderPath . '/Video/' . NAME_PRODUCT . '_preview.jpg';
copy_file_order($filePath, PATH_READY_USERFILES . '/' . NAME_PRODUCT . '_preview.mp4');
copy_file_order($picPath, PATH_READY_USERFILES . '/' . NAME_PRODUCT . '_preview.jpg');

// Установить статус заказа "видео создано"
update_post_meta($id_product, 'created_slideshow', time());
	echo 'получено';

# ОТПРАВИТЬ УВЕДОМЛЕНИЕ
function sendPush($json)
{
	if ($json == '') return;
	define('MY_KEY', 'AIzaSyDv70e0r38LZK_RGwzJe72T1pyYWN0HmLI');
	define('TIME_TO_LIVE', 30000);
	$subscribers = json_decode($json, true);
	foreach ($subscribers as $browser => $subscribers_list) {
		foreach ($subscribers_list as $subscriber_id) {
			$result = send_push_message($browser, $subscriber_id);
		}
	}
}

function send_push_message($browser, $subscriber_id)
{
	$ch = curl_init();
	switch ($browser) {
		
		case 'chrome':
			// Уведомление для подписчика
			curl_setopt($ch, CURLOPT_URL, 'https://gcm-http.googleapis.com/gcm/send');
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: key=' . MY_KEY, 'Content-Type: application/json']);
			curl_setopt($ch, CURLOPT_POSTFIELDS,
				json_encode([
					'registration_ids' => [$subscriber_id],
					'data'             => ['message' => 'data'],
					'time_to_live'     => TIME_TO_LIVE,
					'collapse_key'     => 'test'
				])
			);
			break;
		case 'firefox':
			curl_setopt($ch, CURLOPT_URL, 'https://updates.push.services.mozilla.com/push/v5/' . $subscriber_id);
			curl_setopt($ch, CURLOPT_PUT, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_HTTPHEADER, ['TTL: ' . TIME_TO_LIVE]);
			break;
	}
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}


//Проверить наличие токена для push
$push = get_post_meta($id_product, 'wpcf-o_token', true);
sendPush($push);

$post = get_post($id_product);
$to = $post->post_content;
//$mail_template = get_post(214);
//$subject = $mail_template->post_title;
//$body     = sprintf( $mail_template->post_content , $id_product);
//$headers = array('Content-Type: text/plain; charset=UTF-8');  // text/html
//require_once ('../wp-includes/pluggable.php');
//require_once ('../wp-content/plugins/easy-wp-smtp/easy-wp-smtp.php');
//wp_mail( $to, $subject, $body, $headers );
require_once(PATH_APP . '/mail/send-mail.php');
send_mail($id_product, $to, 'ready');