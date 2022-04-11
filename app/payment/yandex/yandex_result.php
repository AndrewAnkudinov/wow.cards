<?php

# YANDEX.КАССА: принять оплату

# Подключить файл конфигурации магазинов оплаты
require_once( $_SERVER['DOCUMENT_ROOT'] . '/app/payment/config_payment.php');

/*
// СОЗДАТЬ ОБЪЕКТ WEBHOOK для отслеживания событий через уведомления по API
// https://yookassa.ru/developers/using-api/webhooks
require __DIR__ . '/lib/autoload.php'; // Подключить класс для работы с YooKassa
use YooKassa\Client;
use YooKassa\Model\NotificationEventType;

$client = new Client();
$client->setAuthToken('<Bearer Token>');

$response = $client->addWebhook([
	"event" => NotificationEventType::PAYMENT_SUCCEEDED,
	"url"   => "https://www.merchant-website.com/notification_url",
]);
// /СОЗДАТЬ ОБЪЕКТ WEBHOOK
*/

require __DIR__ . '/lib/autoload.php'; // Подключить класс для работы с YooKassa

# ОБРАБОТАТЬ УВЕДОМЛЕНИЕ С ПОМОЩЬЮ SDK

// Получите данные из POST-запроса от ЮKassa
$source = file_get_contents('php://input');
$requestBody = json_decode($source, true);

// Создайте объект класса уведомлений в зависимости от события
// NotificationSucceeded, NotificationWaitingForCapture,
// NotificationCanceled,  NotificationRefundSucceeded
use YooKassa\Model\Notification\NotificationSucceeded;
use YooKassa\Model\Notification\NotificationWaitingForCapture;
use YooKassa\Model\NotificationEventType;

try {
	$notification = ($requestBody['event'] === NotificationEventType::PAYMENT_SUCCEEDED)
		? new NotificationSucceeded($requestBody)
		: new NotificationWaitingForCapture($requestBody);
} catch (Exception $e) {
	// Обработка ошибок при неверных данных
}

// Получите объект платежа
$payment = $notification->getObject();

# /ОБРАБОТАТЬ УВЕДОМЛЕНИЕ С ПОМОЩЬЮ SDK


# Отправить лог-письмо об операции
$email_title = 'Данные оплаты wow.cards Yandex.Кассса';
$email_body = '$_GET: ' . print_r( $_GET, true )
	. PHP_EOL
	. PHP_EOL . '$_POST: ' . print_r( $_POST, true )
	. PHP_EOL . '$payment: ' . print_r( $payment, true )
;
mail('my_works@mail.ru', $email_title, $email_body);


# Определить переменные оплаты
$id_product = false;
if (!isset($payment->metadata->id_product))
	die('id product is empty');
$id_product = $payment->metadata->id_product;
if (!$id_product)
	die('id product is empty');
define('ID_PRODUCT', $id_product);

# Подключить функции оплаты
# Обработать платеж в зависимости от его типа
require_once( __DIR__ . '/../functions_payment.php' );
payment\buy_product(ID_PRODUCT);

# Вернуть код успеха Yandex.Кассе
/*
$code = 0; //Временно отключил проверку кода
print '<?xml version="1.0" encoding="UTF-8"?>';
print '<paymentAvisoResponse performedDatetime="' . $_POST['requestDatetime'] . '" code="' . $code . '"'
	. ' invoiceId="' . $_POST['invoiceId'] . '" shopId="' . $config_payment['yandex']['shopId'] . '"/>';
exit();
*/