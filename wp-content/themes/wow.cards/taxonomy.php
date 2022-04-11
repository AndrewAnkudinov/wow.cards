<?php

/*
Template Name: Выбрать дизайн категории
*/

# ВЫБРАТЬ ДИЗАЙН КАТЕГОРИИ
//ini_set('display_errors', 1);
//error_reporting(E_ALL);

# Установить заголовок H1
add_filter('pre_get_document_title', 'change_title');
function change_title($title) {
	return 'Big Event';
}

# Определить категорию дизайнов
$category_design = get_queried_object();
/*
$name_category_design = 'slideshow';
if (isset($_GET['type_design']))
	$name_category_design = $_GET['type_design'];
$category_design = get_term_by( 'slug', $name_category_design, 'category_design');
*/
//var_dump($category_design);

# Определить тип дизайнов
$name_type_design = 'slideshow';
if (isset($_GET['type_design']))
    $name_type_design = $_GET['type_design'];
$name_type_design = Designs::verify_type($name_type_design);
$type_design = Designs::get_type_design($name_type_design);

// Получить список дизайнов текущей категории
$id_term = false;
if ($name_type_design != 'story')
	$id_term = $category_design->term_id;
if ($name_type_design == 'slideshow' || $name_type_design == 'story')
	$designs = design\get_designs($name_type_design, $id_term);
else
	$designs = Designs::get_designs($name_type_design);

//$designs = new Designs();
//$designs = $designs->get_designs('slideshow');

//require_once PATH_APP . '/lib/lib-designs.php';
//$designs = get_design_properties();
//var_dump($designs);

//$include_categories_id = array( 15, 16 ); // Id рубрик, которые надо отображать

//var_dump($designs);

# HTML
get_header();

# Вывести шаблон "Выбрать тему открыток"
if ($name_type_design == 'postcard') {
	
	get_template_part( '/template-parts/part-choose-theme-postcard', 'choose-theme-postcard', ['id_term' => $id_term] );
    //include_once __DIR__ . '/template-parts/part-choose-theme-postcard.php';
    get_footer();
    exit;
}

# Собрать URL первого дизайна
$url_first_design = home_url() . '/' . $name_type_design . '/' . $designs[0]['post_name'];

?>

	<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri() ?>/assets/css/csshake.min.css">
	<style>
		.shake:hover, .shake-trigger:hover .shake, .shake.shake-freeze, .shake.shake-constant {
			animation-duration: 5s;
		}
		/*
		#carouselChooseDesign {
			margin: 0 -15px;
		}
		*/

        #carouselChooseDesign .figure_video>div {
            max-width: 1000px;
        }

		#carouselChooseDesign.type_design_story {
			max-width: 500px;
		}
			
		.box-link-design {
			margin: 0 50px;
		}

		.btn-control-carousel:hover {
			opacity: .5;
		}
		
		@media (min-width: 576px) {
			.box-link-design {
				margin: 30px 0;
			}
		}

		@media (min-width: 768px) {
			#carouselChooseDesign {
				margin: 0 20px 40px 0;
			}
		}

		.link_design {
			display: none;
		}

/*
		#chooseDesign {
			width: 427px;
			height: 52px;
			background-image: url("<?php echo get_stylesheet_directory_uri() ?>/assets/img/choose_design.png");
			background-size: 100% 100%;
			margin: 30px auto 40px;
		}
		*/
	</style>

	<!--<div class="col-12 d-flex justify-content-center container-h1">
		<div id="chooseDesign">
			<h1><?php the_title(); ?></h1>
		</div>
	</div>-->

	<div class="col-md-7 col-lg-9">

		<h1 class="text-center mb-3">выбери видеодизайн<?php /*the_title();*/ ?></h1>
		
		<?php echo design\slider_designs($designs) ?>
		
		<?php
		/*
		<div id="carouselChooseDesign" class="carousel slide mx-auto type_design_<?php echo $name_type_design ?>" data-ride="carousel">
			<div class="carousel-inner container_video">

				<?php

				$num_design = 0;
				$name_func_get_designs = 'design\player_' . $name_type_design;
				foreach ($designs as $design)
				{
					
					?>
					<div class="carousel-item<?php if ($num_design == 0) { echo ' active'; } ?>"
						 data-url="<?php echo home_url() . '/create-slideshow/?id_design=' . $design['name'] ?>">

						<figure class="figure_video" id="videoHomepage">
							<div>
                                <?php
								
								echo $name_func_get_designs($design['video']);
								
								?>
								<div class="text-center font-italic"><?php echo $design['name'] ?></div>
							</div>
						</figure>

					</div>
				<?php $num_design++; } ?>

			</div>
			<a class="carousel-control-prev d-flex" href="#carouselChooseDesign" role="button" data-slide="prev">
				<!--<span class="carousel-control-prev-icon" aria-hidden="true"></span>-->
				<span class="material-icons md-48">keyboard_arrow_left</span>
				<span class="sr-only">Previous</span>
			</a>
			<a class="carousel-control-next d-flex" href="#carouselChooseDesign" role="button" data-slide="next">
				<!--<span class="carousel-control-next-icon" aria-hidden="true"></span>-->
				<span class="material-icons md-48">keyboard_arrow_right</span>
				<span class="sr-only">Next</span>
			</a>
		</div>
		*/?>

		<div class="d-flex align-items-center justify-content-center mt-3 text-center text-muted">
			Нажми <span class="btn btn-icon bg-light rounded-circle mx-2 btn-control-carousel"
						onclick="$('#carouselChoose<?php echo ucfirst($name_type_design) ?>').carousel('prev');"><span class="material-icons">keyboard_arrow_left</span></span>
			или <span class="btn btn-icon bg-light rounded-circle mx-2 btn-control-carousel"
					  onclick="$('#carouselChoose<?php echo ucfirst($name_type_design) ?>').carousel('next');"><span class="material-icons">keyboard_arrow_right</span></span>
		</div>
		
	</div>

	<div class="col-md-5 col-lg-3 text-center d-flex align-items-center justify-content-center">
		<div style="margin: 60px 0;">

			<div class="h2"><?php echo $type_design['subtitle'] ?></div>
			<img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/<?php echo $type_design['img_subtitle'] ?>" alt="" style="margin: 20px 0">
			<div class="mt-3 text-big"><?php echo $type_design['min_number_files'] ?>-<?php echo $type_design['max_number_files'] ?> фотографий</div>

			<div id="boxLinkDesign">
				<a id="linkDesign" class="btn btn-danger my-1 bubbly-button"
				   href="<?php echo $url_first_design ?>">Далее</a><span class="arrow_clockwise shake shake-constant"></span>
				<!--<a id="linkDesign" class="btn btn-primary" href="<?php echo home_url() ?>/design/test-slideshow/">GO</a>-->
				
			</div>
			<!--<div class="text-muted mt-3">от 10 до 30 фото</div>-->
		</div>
	</div>

<?php get_footer(); ?>