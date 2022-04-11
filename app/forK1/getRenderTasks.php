<?php
	$out = [];	//Список задач
	if ($_GET["key"] == 'd25cca771ce62'){
		//1. Получить список задач wow.cards
		$tmpPath = '/home/admin/zak/wow.cards/';
		$arFolders = scandir($tmpPath);
		foreach ($arFolders as $rec){
			if ($rec == '.' || $rec == '..') continue;
			if (!is_dir($tmpPath . $rec)) continue;
			chdir($tmpPath . $rec);
			if (!file_exists('data.txt')) continue;

			//Параметры заказа
			$out[] = file_get_contents('data.txt', true);
		}
	}
	echo json_encode($out);
?>