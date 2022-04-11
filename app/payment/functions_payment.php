<?php

# Функции обработки оплаты

namespace payment;

# Подключить ключевые файлы CMS
require_once( $_SERVER['DOCUMENT_ROOT'] . '/app/app-config.php');
require_once( PATH_ROOT . '/wp-config.php' );
require_once( PATH_ROOT . '/wp-includes/post.php' );

# Получить хэш страницы оплаты
function get_hash_payment_product($data_product) {
	return substr(md5($data_product['id_product'] . $data_product['type_payment'] . $data_product['quantity_frames'] . FF_SUFFIX), 0, 8);
}

# Получить URL страницы оплаты
function build_url_page_payment($data_payment) {
	//var_dump($data_payment);
	$data_url = [];
	foreach (['id_product', 'type_payment', 'quantity_frames'] as $item) {
		if (!isset($data_payment[$item]))
			die ('Недостаточно данных для формирования URL страницы оплаты');
		$data_url[$item] = $data_payment[$item];
	}
	$data_url['hash'] = get_hash_payment_product($data_payment);
	//$data_payment['price'] = calc_price_product($data_payment);
	return URL_APP . '/payment/page_payment.php?' . http_build_query($data_url);
}
/*
function speed_up_product($product_id)
{

	update_post_meta($product_id, 'wpcf-o_fast', time());
	
	# Если папки заказа нет в папке zakaz, а есть в папке zakaz_TEMP (СНГ)
	$destPath = DEFAULT_UPLOAD_ZAKAZ_PATH . $product_id . FF_SUFFIX . '/';
	if (!is_dir($destPath)) {
		$sourcePath = substr(DEFAULT_UPLOAD_ZAKAZ_PATH, 0, -1) . '_TEMP/' . $product_id . FF_SUFFIX . '/';
		if (is_dir($sourcePath)) {
			rename($sourcePath . "/maket_temp.txt", $sourcePath . "/maket.txt"); #переименовать maket_temp
			rename($sourcePath, $destPath);    #Переместить папку
		}
	}
	@file_put_contents($destPath . '/paid.txt', '', FILE_APPEND); # Cоздать пустой текстовый файл - маркер ускорения paid.txt
	
}
*/
function buy_product($product_id) {
	
	# Отправить лог-письмо об операции
	$email_title = '$product_id оплаты wow.cards CloudPayments';
	$email_body = '$product_id: ' . print_r( $product_id, true );
	mail('my_works@mail.ru', $email_title, $email_body);
	
	update_post_meta($product_id, 'paid_product', 1);
	
	# Cоздать пустой текстовый файл - маркер оплаты
	@file_put_contents(PATH_READY_USERFILES . '/' . $product_id . FF_SUFFIX . '.txt', '', FILE_APPEND);
	
	/* Письмо после оплаты заказа
	на английском языке, неактуальное, актуальное отправляется из другого места
	
	$to             = get_post_field('post_content', $product_id);
	$mail_template  = get_post( 216 );
	$subject        = $mail_template->post_title;
	$body           = sprintf( $mail_template->post_content , $product_id);
	$headers        = array('Content-Type: text/plain; charset=UTF-8');  // text/html
	wp_mail( $to, $subject, $body, $headers );
	//mail($to, $subject, $body);
	*/
}

function prepay_product($product_id) {
	
	update_post_meta($product_id, 'paid_product', 1);
	
	# Cоздать пустой текстовый файл - маркер оплаты
	@file_put_contents(PATH_READY_USERFILES . '/' . $product_id . FF_SUFFIX . '.txt', '', FILE_APPEND);
	
	/* Письмо после оплаты заказа
	на английском языке, неактуальное, актуальное отправляется из другого места

	$to             = get_post_field('post_content', $product_id);
	$mail_template  = get_post( 216 );
	$subject        = $mail_template->post_title;
	$body           = sprintf( $mail_template->post_content , $product_id);
	$headers        = array('Content-Type: text/plain; charset=UTF-8');  // text/html
	wp_mail( $to, $subject, $body, $headers );
	//mail($to, $subject, $body);
	*/
}

/*
function create_message_after_speed_up() {
	return '
	<div id="main">
		<div style="height: 100px;position: absolute;top:0;bottom: 50px;left: 0;right: 0;margin: auto;
		text-align: center;font-family: Arial,&quot;Helvetica Neue&quot;,Helvetica,sans-serif;font-size: 20px;
		line-height: 1.7em; width: 700px;">
			<span style="color: #00acec; font-weight: 600; text-transform: uppercase; font-size: 20px;">
			Операция по оплате ускорения проведена успешно!</span>
			<br>
			Если клип еще не готов, информацию о его готовности и страница с его наличием будет выслана Вам по электронной почте.
		</div>
	</div>';
}
*/
function create_message_after_buy() {
	return '
	<div id="main">
		<div style="height: 100px;position: absolute;top:0;bottom: 50px;left: 0;right: 0;margin: auto 
		;text-align: center;font-family: Arial,&quot;Helvetica Neue&quot;,Helvetica,sans-serif;font-size: 20px;
		line-height: 1.7em; width: 700px;">
			<span style="color: #00acec; font-weight: 600; text-transform: uppercase; font-size: 20px;">
			Операция по оплате проведена успешно!</span>
			<br>
			Спасибо за оплату! Время выгрузки вашего клипа займет 10-15 минут.
		</div>
	</div>';
}



/*
function get_data_order_prepay($data_pending) {

    $data_pending
}
*/