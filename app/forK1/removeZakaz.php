<?php
	//Удалить заказ
	if (empty($_GET["zakaz"]) || $_GET["key"] != 'd25cca771ce62') exit('error');
	$folder = '/home/admin/zak/wow.cards/' . $_GET["zakaz"];
	//Удалить файлы из папки video
	$arFolders = scandir($folder . '/Video');
	foreach ($arFolders as $rec){
		if ($rec == '.' || $rec == '..') continue;
		if (!unlink($folder . '/Video/' . $rec)) exit('error');
	}
	if (!rmdir($folder . '/Video')) exit('error');
	//Удалить файлы заказа
	$arFolders = scandir($folder);
	foreach ($arFolders as $rec){
		if ($rec == '.' || $rec == '..') continue;
		if (!unlink($folder . '/' . $rec)) exit('error');
	}
	if (!rmdir($folder)) exit('error');
	echo('ok');
?>