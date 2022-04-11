<?php

//var_dump($_POST);

# Сформировать цену заказа
if (!isset($_POST['quantity_frames']))
	die('Не указано кол-во фреймов');
$quantity_frames = intval($_POST['quantity_frames']);
include_once __DIR__ . '/../lib/designs/Designs.php';
$price = Designs::calc_price_product($quantity_frames);
echo Designs::get_part_price_product($price, 1);