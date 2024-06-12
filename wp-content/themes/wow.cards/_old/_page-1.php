<?php

/*
Template Name: Укажите почту
*/

# Подключить ключевые файлы CMS
require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-config.php');
	
# HTML
get_header();

?>
	
	<div class="col-12 text-center">

		<div class="container-content d-flex flex-column justify-content-center align-items-center">
			<div class="container-h1">
				<h1>На какой <span class="text-danger">e-mail</span> отправить
					<br>готовое видео?</h1>
			</div>
			<div style="margin: calc(7vh) 0;">
				<form>
					<input type="email" class="form-control form-control-lg mx-auto" style="width: 300px;"
						   placeholder="введите вашу почту">
					<br>
					<button type="button" class="btn btn-danger mx-auto bubbly-button" style="width: 300px;">
						Отправить видео
					</button><!--<span class="arrow_clockwise shake shake-constant"></span>-->
				</form>
			</div>
		</div>

	</div>

<?php get_footer(); ?>