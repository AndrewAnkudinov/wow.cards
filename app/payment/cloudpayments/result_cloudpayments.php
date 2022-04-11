<?php

# CloudPayments принять оплату

# Отправить лог-письмо об операции
$email_title = 'Данные оплаты wow.cards CloudPayments';
$email_body = '$_POST: ' . print_r( $_POST, true );
mail('my_works@mail.ru', $email_title, $email_body);

# Подключить файл конфигурации магазинов оплаты
require_once( __DIR__ . '/../config_payment.php');

# Определить переменные оплаты
$payment = [];
if (!isset($_POST['Data']))
	die('Payment Data is empty');
$payment['data'] = json_decode($_POST['Data'], true);

# Отправить лог-письмо об операции
$email_title = '$payment[data] оплаты wow.cards CloudPayments';
$email_body = '$payment[data]: ' . print_r( $payment['data'], true );
mail('my_works@mail.ru', $email_title, $email_body);

$id_product = (int) $payment['data']['id_product'];
define('ID_PRODUCT', $id_product);
$type_payment = $payment['data']['type_payment'];

# Подключить функции оплаты
# Обработать платеж в зависимости от его типа
require_once( __DIR__ . '/../functions_payment.php' );
if ($type_payment == 'fast') {
	payment\speed_up_product(ID_PRODUCT);
} elseif ($type_payment == 'buy') {
	payment\buy_product(ID_PRODUCT);
}

# Вернуть код успеха CloudPayments
echo '{"code":0}';