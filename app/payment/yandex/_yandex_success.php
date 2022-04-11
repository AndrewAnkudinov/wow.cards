<?php

# YANDEX.КАССА: ОБРАБОТАТЬ УСПЕШНУЮ ОПЛАТУ

# Подключить файл конфигурации магазинов оплаты
require_once( $_SERVER['DOCUMENT_ROOT'] . '/app/payment/config_payment.php');

# Создать сообщение после платежа в зависимости от его типа
require_once( __DIR__ . '/../payment_functions.php' ); # Подключить функции оплаты
$payment_type = $_REQUEST["itemType"];
$message_after_payment = '';
if ($payment_type == 'fast') {
	$message_after_payment = create_message_after_speed_up();
} elseif ($payment_type == 'buy') {
	$message_after_payment =  create_message_after_buy();
} else {
	$message_after_payment = 'ERROR $payment_type of payment success ' . $payment_type;
}


# ВЫВЕСТИ HTML-КОД СООБЩЕНИЯ ПОСЛЕ ОПЛАТЫ

add_action( 'wp_head', 'noRobots' ); // Закрыть страницу от индексирования поисковыми роботами
get_header();
echo $message_after_payment;
get_footer();