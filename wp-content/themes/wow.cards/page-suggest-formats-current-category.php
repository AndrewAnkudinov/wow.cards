<?php /* Template Name: Предложить создать медиа-файл другого формата текущей категории */ ?>

<?php get_header(); ?>

	<?php
	
	# Вывести шаблон части "Предложить другие форматы дизайна текущей категории"
	
	# Определить категорию текущего дизайна
	$type_design = '';
	if (isset($_GET['type_design'])) {
		$type_design = $_GET['type_design'];
	}
	if (isset($_GET['id_category'])) {
		$id_category = $_GET['id_category'];
	} else {
		$id_category = design\get_id_default_category_design();
	}
	//$terms = get_the_terms(get_the_id(), 'category_design');
	//$id_category = $terms[0]->term_id;
	
	get_template_part(
		'/template-parts/part-suggest-formats-current-category',
		'suggest-formats-current-category',
		[
			'type_design' => $type_design,
			'id_category' => $id_category,
		]
	);
	
	?>

<?php get_footer(); ?>