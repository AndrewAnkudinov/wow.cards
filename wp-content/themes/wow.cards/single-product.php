<?php
/*
Template Name: Страница заказа
 */

//add_action( 'wp_head', 'noRobots' ); // Закрыть страницу от индексирования поисковыми роботами

define('ID_PRODUCT', get_the_ID());
$post_status  = get_post_status();
$data_product = get_post( ID_PRODUCT );
$post_meta    = get_post_meta( ID_PRODUCT ); // Все метаданные записи в виде массива
$post_date    = get_the_date( 'U', ID_PRODUCT ) - 3 * 60 * 60; // TODO: Timezone
//var_dump($post_meta);

// Получить св-ва дизайна
define('ID_DESIGN', $post_meta['id_design'][0]);
$design = design\get_design(ID_DESIGN);
//require_once PATH_APP . '/lib/Designs.php';
//$design = Designs::get_design(ID_DESIGN);
//var_dump($design);
//echo get_locale();

# ШАБЛОН ЗАКАЗА В ПРОЦЕССЕ ВЫПОЛНЕНИЯ
if ( /*is_user_logged_in() ||*/ empty( $post_meta['created_slideshow'][0] ) )
{

	# Получить категорию дизайна
    //$category = $post_meta['id']


	# Установить заголовок H1
	add_filter('pre_get_document_title', 'change_title', 999, 1 );
	function change_title($title) {
		return 'Great';
	}
	
	wp_enqueue_style( 'home-css', get_stylesheet_directory_uri() . '/assets/css/home.css', array(), filemtime( __DIR__ . '/assets/css/home.css' ), false);
	wp_enqueue_style( 'choose-format-css', get_stylesheet_directory_uri() . '/assets/css/choose-format.css', array(), filemtime( __DIR__ . '/assets/css/choose-format.css' ), false);
	
	get_header();

	
	# Создать слайдеры
	
	# Определить категорию дизайнов
	$terms = get_the_terms(ID_DESIGN, 'category_design');
	//$terms = get_the_terms(get_the_ID(), 'category_' . $name_type_design);
	$id_category = $terms[0]->term_id;
	//$id_category = $wp_query->get_queried_object_id();
	
	# Получить список дизайнов нужных типов и категории
	$designs = design\get_designs($design['type'], $id_category);
	//$designs = Designs::get_designs('slideshow');  // TODO: пока выводятся все слайдшоу
	
	/*
	# Собрать URL первого дизайна
	$slugs_create_order_lang = [
		'ru_RU' => 'create-slideshow-ru',
		'en_US' => 'create-slideshow'
	];
	$url_first_design = home_url() . '/' . $slugs_create_order_lang[ get_locale() ] . '/?theme=' . $designs[ array_key_first($designs) ]['name'];
	*/
	
	$_H['slider_slideshow'] = design\slider_designs($designs);
		
	
	/*
	# Получить данные заказа
	# Вычислить оставшееся время создания видео
	$time_remaining = $post_date + FF_TIME * 60 - time();
	if ( $time_remaining < 0 ) {
		$time_remaining = 0;
	}
	//$time_remaining = 60 * 15;
	$time_remaining_theme = FF_TIME * 60;
	*/

	?>
	<div class="col-12 container-h1">
		<h1>ПРОВЕРЬТЕ ПОЧТУ:
			<br><span class="text-danger">МЫ КОЕ-ТО ОТПРАВИЛИ :)</span></h1>
	</div>

	<div class="col-12 text-center">
		<div class="h2" style="margin: 60px auto;">Пока заказ обрабатывается, отправьте открытку или короткое видео</div>
		<?php
		
		# Вывести шаблон части "Предложить другие форматы дизайна текущей категории"
		get_template_part(
			'/template-parts/part-suggest-formats-current-category',
			'suggest-formats-current-category',
			[
				'type_design' => $design['type'],
				'id_category' => $id_category,
			] );
		
		?>
	</div>

	<div class="col-12 text-center">
		<h2 style="margin: -40px 200px 0;"><span class="text-danger m-0">ЭТО БЕСПЛАТНО</span></h2>
	</div>


	<!-- Новый скрипт -->
	<script>
		window.addEventListener('DOMContentLoaded', function () {

			// Проверить статус заказа
			var countCheckOrderStatus = 0;
			var checkOrderStatus = setInterval(function () {
				var xhr = new XMLHttpRequest();
				xhr.open('GET', '<?php echo site_url() ?>/app/ajax/ajax-get-order-state.php?id=<?php echo( ID_PRODUCT ) ?>');

				xhr.onload = function (ev) {
					if (ev.target.responseText == 'ready') {
						console.log('reload page');
						window.location.reload();
					}
				};
				xhr.send();
				
				// После 100 попытки остановить поиск (пощадить браузер)
				countCheckOrderStatus++;
				if (countCheckOrderStatus > 100)
					clearInterval(refreshIntervalId);
				
			}, 1000 * 20);

		});
	</script>
	
<?php

}

# ШАБЛОН ВЫПОЛНЕННОГО ЗАКАЗА
else {
	
	require_once __DIR__ . '/template-parts/part-product-ready.php';
	
} ?>
<?php get_footer(); ?>