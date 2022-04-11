<?php
	//Отправка HTML5 письма
	function send_mail($zakazId, $email, $type) {
		if ($type == 'wait') {
			$template = '213.html';
			$title = 'Your video is processed!';
		}
		if ($type == 'ready') {
			$template = '214.html';
			$title = 'Your video is ready!';
		}
		if ($type == 'sorry') {
			$template = '215.html';
			$title = "Sorry, we can't help :(";
		}
		if ($type == 'paid') {
			$template = '217.html';
			$title = 'Your video!';
		}
		if ($type == 'buy') {
			$template = '218.html';
			$title = 'Your slideshow!';
		}
		if ($type == 'look') {
			$template = '214.html';
			$title = 'Your video is ready!';
		}
		if ($type == 'promo') {
			$template = '220.html';
			$title = 'Discount 37% for Your clip!';
		}
		$body = str_replace('|||', $zakazId, file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/e-mail/' . $template));
		$message = "Тестовое сообщение";
		# Подключить ключевые файлы WP
		require_once (__DIR__ . '/../wp-includes/pluggable.php');
		require_once (__DIR__ . '/../wp-content/plugins/easy-wp-smtp/easy-wp-smtp.php');
		
		# wp_mail() with multipart
$header = "MIME-Version: 1.0
Content-Type: multipart/alternative;
	boundary=\"----=_Part_18243133_1346573420.1408991447668\"";

$multipart_message = "
------=_Part_18243133_1346573420.1408991447668
Content-Type: text/plain; charset=UTF-8
Content-Transfer-Encoding: 7bit

$message
------=_Part_18243133_1346573420.1408991447668
Content-Type: text/html; charset=UTF-8

$body
------=_Part_18243133_1346573420.1408991447668--
";
wp_mail( $email, $title, $multipart_message, $header );
	}
?>