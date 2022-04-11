<?php

# YANDEX.КАССА: ПРОВЕРИТЬ ПЛАТЕЖ (код одинаков для всех сайтов с данным shopId)

# Подключить файл конфигурации магазинов оплаты
require_once( $_SERVER['DOCUMENT_ROOT'] . '/app/payment/config_payment.php');
/*
# Определить магазин, на который поступает оплата
$shop_name = get_shop_name();
if ($shop_name != 'ff')
{
	$shop_home_path = $_SERVER['DOCUMENT_ROOT'] . $config_payment['shops_subfolder_name'][ $shop_name ];
	require_once( $shop_home_path . '/app/payment/yandex/check.php' );
	exit();
}
*/
$code = 0; //Временно отключил проверку кода

print '<?xml version="1.0" encoding="UTF-8"?>';
print '<checkOrderResponse performedDatetime="' . $_POST['requestDatetime'] . '" code="' . $code . '"'
	. ' invoiceId="' . $_POST['invoiceId'] . '" shopId="' . $config_payment['yandex']['shopId'] . '"/>';

// Записать выходные данные в файл. TODO: Зачем? В этом не видно смысла
$fp = fopen('check.txt', 'w');
fwrite($fp, '<checkOrderResponse performedDatetime="' . $_POST['requestDatetime'] . '" code="' . $code . '"'
	. ' invoiceId="' . $_POST['invoiceId'] . '" shopId="' . $config_payment['yandex']['shopId'] . '"'
	. ' orderNumber="' . $_POST['orderNumber'] . '" itemType="' . $_POST['itemType'] . '"'
	. ' orderSumAmount="' . $_POST['orderSumAmount'] . '"/>');
fclose($fp);