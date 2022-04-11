<?php

// СОЗДАТЬ ПЛАТЕЖ YOOKASSA

ini_set('display_errors', 1);
error_reporting(E_ERROR);

# Подключить файл конфигурации сайта
# Подключить файл конфигурации магазинов оплаты
require_once( $_SERVER['DOCUMENT_ROOT'] . '/app/app-config.php');
require_once( $_SERVER['DOCUMENT_ROOT'] . '/app/payment/config_payment.php');

require __DIR__ . '/lib/autoload.php'; // Подключить класс для работы с YooKassa
use YooKassa\Client; // Импортировать класс

$client = new Client(); // Создать экземпляр объекта
$client->setAuth($config_payment['yandex']['shopId'], $config_payment['yandex']['ShopPassword']); // Устанавливить данные магазина (можно вбить для тестового магазина).
$idempotenceKey = uniqid('', true); // Создать идентификатор платежа

$payment = $client->createPayment(
	array(
		'amount' => array(
			'value' => 100.0,
			'currency' => 'RUB',
		),
		'confirmation' => array(
			'type' => 'redirect',
			'return_url' => "http" . (!empty($_SERVER['HTTPS'])?"s":""). "://" . $_SERVER['SERVER_NAME'] . '/product/' . $id_product . '?paid',
			//'return_url' => "http" . (!empty($_SERVER['HTTPS'])?"s":""). "://" . $_SERVER['SERVER_NAME'] . URL_APP . '/payment/yandex/yandex_result.php',
			//'return_url' => 'https://www.merchant-website.com/return_url',
		),
		'capture' => true,
		'description' => 'Заказ №' . $id_product,
		'metadata' => ['id_product' => $id_product]
	),
	uniqid('', true)
);
//var_dump($payment->confirmation);
//var_dump($payment);


// после выполнения запроса яндекс-касса не должна возвращать статус canceled. Отсутствие этого статуса означает
// что яндекс-касса вернула confirmation_url - то есть URL на который необходимо перенаправить клиента для оплаты,
// то есть ввода информации о банковской карте.
// Получить переменную $confirmation_url которая или равна false или содержит URL для перенаправления для совершения оплаты клиентом
$confirmation_url = false;
if (isset($payment->status)
	and ($payment->status != "canceled")
	and isset($payment->confirmation->confirmation_url)) {
	$confirmation_url = $payment->confirmation->confirmation_url;
}
if ($confirmation_url) {
	//header($confirmation_url);
	?>
	<a href="<?php echo $confirmation_url ?>">Оплатить</a>
	<?
}

/*
 * http://blog.ivru.net/?id=80
$response = $client->createPayment(
	array(
		"capture"=>true,//после оплаты не требуется подтверждение от владельца интернет-магазина о том что оплата принята магазином. Если установлен в true после успешной оплаты статус будет succeeded, а если не устанавливать этот жлемент массива то waiting_for_capture(так же после успешной оплаты)
		"amount" => array(
			"value" => "1333.33",//Сумма платежа
			"currency" => "RUB",//Валюта
		),
		"payment_method_data" => array(
			"type" => "bank_card",//Способ оплаты - банковская карта
		),
		"confirmation" => array(
			"type" => "redirect",
			"return_url" => "https://domen.ru/local/templates/main/ajax/yapayment.php",//после оплаты клиент будет перенаправлен на этот url
		),
		"description" => "Заказ №72",// Яндекс-касса не обязует Вас делать description уникальным для каждого заказа, тем не менее лучше это сделать
	),
	$idempotenceKey//Уникальный ключ сгенерированный выше
);
*/
