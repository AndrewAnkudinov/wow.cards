<div class="row d-flex justify-content-between align-items-stretch elementor-image mx-auto" style="max-width: 1050px;"><!-- align-items-end -->
<?php

# ШАБЛОН ЧАСТИ: Предложить другие форматы дизайна текущей категории
# Вход: (array) $args['type_design' => ..., 'id_category' => ...]
# Файл подключается через WP-функцию get_template_part()
//var_dump($args);

$name_type_tmp_design = 'postcard';
if ($args['type_design'] !== $name_type_tmp_design) {
	
	# Получить список дизайнов нужной категории
	$designs = design\get_designs($name_type_tmp_design, $args['id_category']);
	echo '<div class="col-lg-7"><div style="margin-bottom: 40px;">' . design\slider_designs($designs) . '</div></div>';
	/*
					?>
				<div>
	
					<figure>
						<div class="title title-w-line text-left mb-2">анимированная открытка</div>
						<img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/2.png" alt="">
						<figcaption class="widget-image-caption wp-caption-text d-none">
							<div class="title-w-line"><span class="wow">уровень радости</span>
								<span class="rateit bigstars"
									  data-rateit-max="1"
									  data-rateit-value="<?php echo 4.6/5 ?>"
									  data-rateit-ispreset="true"
									  data-rateit-readonly="true"
									  data-rateit-starwidth="20"
									  data-rateit-starheight="20"
								></span><span class="rate-numbers text-danger">4,6</span>
							</div>
						</figcaption>
					</figure>
					<div class="my-3 text-center">
						<button class="btn btn-sm bubbly-button btn-primary shadow">Отправить</button>
						<button class="btn btn-sm bubbly-button btn-outline-dark">свой текст</button>
					</div>
	
				</div>
				<?
				*/
}

$name_type_tmp_design = 'slideshow';
if ($args['type_design'] !== $name_type_tmp_design)
{
	
	# Получить список дизайнов нужной категории
	$designs = design\get_designs($name_type_tmp_design, $args['id_category']);
	$class_col = 'col-lg-4';
	if ($args['type_design'] == 'postcard')
		$class_col = 'col-lg-7';
	echo '<div class="' . $class_col . ' d-flex align-items-center"><div class="w-100">' . design\slider_designs($designs) . '</div></div>';
	
	/*
					?>
					<div class="order-sm-2" id="formatSlideshow">
	
						<figure>
							<div class="title title-w-line text-left mb-2">видеопоздравление для TV и PC</div>
							<?php echo design\player_slideshow($args['video']); ?>
							<figcaption class="widget-image-caption wp-caption-text d-none">
								<div class="title-w-line align-items-center d-none"><!--  d-flex --><span class="wow">уровень радости</span>
									<span class="rateit bigstars"
										  data-rateit-max="1"
										  data-rateit-value="<?php echo 5/5 ?>"
										  data-rateit-ispreset="true"
										  data-rateit-readonly="true"
										  data-rateit-starwidth="20"
										  data-rateit-starheight="20"
									></span> <span class="rate-numbers text-danger">5,0</span>
								</div>
							</figcaption>
						</figure>
						<div class="my-sm-3 text-center">
							<a href="https://wow.cards/ru/choose-theme-ru/" class="btn btn-sm bubbly-button btn-primary shadow">создать видео</a>
						</div>
	
					</div>
					<?
				*/
}

$name_type_tmp_design = 'story';
if ($args['type_design'] !== $name_type_tmp_design)
{
	
	# Получить список дизайнов нужной категории
	$designs = design\get_designs($name_type_tmp_design, $args['id_category']);
	echo '<div class="col-lg-4 d-flex align-items-center"><div class="w-100">' . design\slider_designs($designs) . '</div></div>';
	/*
	?>
	<div>

		<figure>
			<div class="title title-w-line text-left mb-2">видео для моб (до 15 сек)</div>
			<?php echo design\player_story($args['video']); ?>
			<figcaption class="widget-image-caption wp-caption-text d-none">
				<div class="title-w-line"><span class="wow">уровень радости</span>
					<span class="rateit bigstars"
						  data-rateit-max="1"
						  data-rateit-value="<?php echo 5/5 ?>"
						  data-rateit-ispreset="true"
						  data-rateit-readonly="true"
						  data-rateit-starwidth="20"
						  data-rateit-starheight="20"
					></span> <span class="rate-numbers text-danger">5,0</span>
				</div>
			</figcaption>
		</figure>
		<div class="my-3 text-center">
			<button class="btn btn-sm bubbly-button btn-primary shadow">создать видео</button>
		</div>

	</div>
<?
	*/
}

?>
</div>
	