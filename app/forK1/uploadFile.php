<?php
	//Выгрузить файла заказа
	error_reporting(E_ALL);
	ini_set('display_errors', 'on');
	if (empty($_POST["folder"]) || empty($_POST["name"]) || $_POST["key"] != 'd25cca771ce62') exit('error1');
	$type = $_POST["type"];
	$file = $_POST["name"];
	$folder = '/home/admin/zak/wow.cards/' . $_POST["folder"] . '/Video';
	if (!is_dir($folder)) mkdir($folder);
	if ($type == 'new'){
		//Начало файла
		if (!move_uploaded_file($_FILES["uploadfile"]["tmp_name"], $folder . '/' . $file . '.tmp')) exit('error3');
	}else if ($type == 'append'){
		//Середина файла
		if (!file_put_contents($folder . '/' . $file . '.tmp', file_get_contents($_FILES["uploadfile"]["tmp_name"]), FILE_APPEND)) exit('error4');
	}else if ($type == 'end'){
		//Конец файла
		if (!rename($folder . '/' . $file . '.tmp', $folder . '/' . $file)) exit('error5');
	}
	echo('loaded');
?>