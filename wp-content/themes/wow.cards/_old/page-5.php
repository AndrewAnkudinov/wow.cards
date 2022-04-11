<?php

/*
Template Name: Укажите почту
*/

# Подключить ключевые файлы CMS
require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-config.php');
	
# HTML
get_header();

?>
<div class="col-12">
	
	<!-- Button trigger modal -->
	<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
		Launch demo modal
	</button>

	<!-- Modal -->
	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content text-center rounded-lg">
				<div class="modal-header">
					<button type="button" class="btn btn-icon btn-light rounded-circle close shadow" data-dismiss="modal" aria-label="Close">
						<span class="material-icons">close</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="h1 my-0">Что - то пошло не так!</div>
					<div class="h2 my-3">я - просто окошко :)</div>
					<button type="button" class="btn btn-danger shadow mx-auto my-3 bubbly-button" data-dismiss="modal">
						Понятно
					</button>
				</div>
			</div>
		</div>
	</div>
	
</div>
<?php get_footer(); ?>