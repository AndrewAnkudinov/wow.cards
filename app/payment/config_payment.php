<?php

# Настройки Yandex.Кассы

$config_payment = [
	'yandex' => [
		'shopId'        => '585125',
		//'scId'          => '903725',
		'ShopPassword'  => 'test_OJ1pQnxdsIipY9BlsNGcNG9vbCk0N2wfgGA6dJHTPJA'
	],
	'cloudpayments' => [
		'publicId'        => 'pk_0188e68bd02fb6dd1976ec1ea8950',
		'passApi'  => 'e617a42fca6b5ae77bf1662276adfdbd'
	],
	/*
	'tinkoff' => [
		*//*
		# Рабочий терминал
		'terminalkey'   => '1592918010160',
		'secretkey'     => 'g3kh83hm57p4pj73'
		*//*
		# Тестовый терминал
		'terminalkey'   => '1592918010160DEMO',
		'secretkey'     => 'w3a9c9cra2qzhkc0'
	]
	*/
];