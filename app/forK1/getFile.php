<?php
	//Получить файл заказа
	if (empty($_GET["zakaz"]) || empty($_GET["file"]) || $_GET["key"] != 'd25cca771ce62') exit('error');
	$file = '/home/admin/zak/wow.cards/' . $_GET["zakaz"] . '/' . $_GET["file"];
	if (!file_exists($file)) exit('error');
	if (filesize($file) == 0) exit('error');

	// сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
	// если этого не сделать файл будет читаться в память полностью!
	if (ob_get_level()) ob_end_clean();
	// заставляем браузер показать окно сохранения файла
	header('Content-Description: File Transfer');
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename=' . basename($file));
	header('Content-Transfer-Encoding: binary');
	header('Expires: 0');
	header('Cache-Control: must-revalidate');
	header('Pragma: public');
	header('Content-Length: ' . filesize($file));
	// читаем файл и отправляем его пользователю
	if ($fd = fopen($file, 'rb')){
		while (!feof($fd)) print fread($fd, 1024);
		fclose($fd);
	}else{
		exit('error');
	}
?>