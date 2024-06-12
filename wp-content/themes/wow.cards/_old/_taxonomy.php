<?php

/*
Template Name: Посадочная страница
*/


# ДИЗАЙНЫ

# Определить тип дизайнов
$name_type_design = 'slideshow';
if (isset($_GET['type']))
	$name_type_design = $_GET['type'];
$name_type_design = Designs::verify_type($name_type_design);
$type_design = Designs::get_type_design($name_type_design);

# Определить категорию дизайнов
$id_taxonomy = $wp_query->get_queried_object_id();

# Получить список дизайнов нужной категории
$name_func_get_designs = 'design\get_' . $name_type_design . 's';
$designs = $name_func_get_designs($id_taxonomy);
//$designs = Designs::get_designs('slideshow');  // TODO: пока выводятся все слайдшоу

# Собрать URL первого дизайна
$slugs_create_order_lang = [
	'ru_RU' => 'create-slideshow-ru',
	'en_US' => 'create-slideshow'
];
$url_first_design = home_url() . '/' . $slugs_create_order_lang[ get_locale() ] . '/?theme=' . $designs[ array_key_first($designs) ]['name'];

# /ДИЗАЙНЫ


# Подготовить HTML-код

$_H['h1'] = single_term_title('', false);
$meta_h1 = get_term_meta($id_taxonomy, 'h1', true);
if ($meta_h1 != '')
    $_H['h1'] = $meta_h1;

$_H['description'] = term_description();
$shortcode_slider = '{SLIDER}';
$pos_shortcode_desc = strpos($_H['description'], $shortcode_slider);
if ($pos_shortcode_desc) {
    $_H['description'] = str_replace($shortcode_slider, design\slider_slideshow($designs, 'type_design_' . $name_type_design), $_H['description']);
}


# HTML

get_header();

?>
    <link rel='stylesheet' id='brnhmbx-fonts-css'  href='https://fonts.googleapis.com/css?family=Alice%3A400%2C400italic%2C700%2C700italic%7CComfortaa%3A300%2C400%2C700&#038;subset=latin%2Clatin-ext' type='text/css' media='all' />
    <link rel='stylesheet' id='brnhmbx-fonts-css'  href='https://fonts.googleapis.com/css?family=Alice%3A400%2C400italic%2C700%2C700italic%7CComfortaa%3A300%2C400%2C700&#038;subset=latin%2Clatin-ext' type='text/css' media='all' />

	<style>

        .container-content {
            max-width: 1000px;
            margin: 0 auto;
        }

        .tax-category_slideshow h1,
        .tax-category_slideshow h2,
        .tax-category_slideshow h3,
        .tax-category_slideshow h4,
        .tax-category_slideshow h5,
        .tax-category_slideshow h6
        {
            font-family: "Comfortaa", sans-serif;
        }

        article.taxonomy {
            background-color: #FCDDE6; font-family: Alice, sans-serif; padding: 2rem 4rem;
        }
        article.taxonomy p {
            font-size: 1.1rem;
        }

		big {
			font-size: 1.15rem;
		}
		#carouselChooseDesign {
			/*width: 900px;*/
            max-width: calc(90vw);
			margin: 60px auto 40px;
		}
        .figure_video>div,
		#carouselChooseDesign video {
			width: 760px;
			max-width: calc(70vw);
		}

        .figure_video video {
            max-height: inherit;
        }

		#boxLinkDesign {
			margin-bottom: 60px;
		}

		#lastArticles {
			margin-top: 60px;
		}

		#share {
			margin: 60px 0;
		}

		#lastArticles article {
			margin-bottom: 20px;
		}

		#comments {
			margin-bottom: 60px;
		}
		#comments h1 {
			margin: 60px 0 40px;
		}
		#comments>div {
			padding: 1rem 2rem;
			background: linear-gradient(0deg, rgba(252, 222, 230, 0.2), rgba(252, 222, 230, 0.2)), #FCF7FF;
			margin-bottom: 20px;
		}
	</style>

	<div class="col-12 text-left">

		<div class="container-content">

            <div id="primary" class="content-area">
                <main id="main" class="site-main" role="main">

                    <article class="taxonomy">

                        <div class="container-h1">
                            <h1><?php echo $_H['h1'] ?></h1>
                        </div>

                        <?php echo $_H['description'] ?>
                        <?php
                        /*

                    <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
                    <?php echo design\slider_slideshow($designs, 'type_design_' . $name_type_design); ?>


                    <div id="boxLinkDesign" class="text-center">
                        <a id="linkDesign" class="btn btn-danger my-1 bubbly-button"
                           href="<?php echo $url_first_design ?>">Создать видео</a>
                    </div>

                    <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>

                    <div id="share" class="d-flex">
                        <div class="align-self-center mr-3">Поделиться в</div>
                        <script src="https://yastatic.net/share2/share.js"></script>
                        <div class="ya-share2 align-self-center" data-curtain data-size="l" data-shape="round" data-services="vkontakte,facebook"></div>
                    </div>
                        */
                        ?>

                    </article>

                    <br>
                    <br>
                    <div class="container-h1 text-left">
                        <h1>Автор</h1>

                        <div class="d-flex">
                            <div class="mr-3">
                                <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/autor.jpg" class="rounded-circle border" alt=""></div>
                            <div class="align-self-center text-danger"><big>Поспелова Анастасия</big></div>
                        </div>
                    </div>

                    <div id="lastArticles" style="margin-left: -60px; border-left: 1px solid #4F0074; padding-left: 60px;">
                        <div class="container-h1 text-left">
                            <h1>Последние статьи</h1>
                        </div>

                        <?php foreach([1, 2] as $v) { ?>
                            <article>
                                <div class="row">
                                    <div class="col-5 col-sm-12 col-md-5">
                                        <img class="rounded shadow w-100" src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/3.png" alt="">
                                    </div>
                                    <div class="col-7 col-sm-12 col-md-7">
                                        <h4><strong>Название статьи</strong></h4>
                                        <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod...</div>
                                        <div class="mt-3">
                                            <a href="#" class="text-danger">Перейти</a>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        <?php } ?>

                    </div>

                    <?php comments_template( __DIR__ . '/template-parts/comments.php' ); ?>

                    <!-- Comments -->
                    <div id="comments" class="container-h1 text-left">

                        <h1>Комментарии</h1>

                        <div class="rounded border">
                            <div class="d-flex mb-2">
                                <div class="mr-3">
                                    <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/user.png" class="rounded-circle" alt=""></div>
                                <div class="align-self-center"><strong>Поспелова Анастасия</strong></div>
                            </div>
                            <div>
                                Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</div>
                        </div>
                        <div class="rounded border">
                            <div class="d-flex mb-2">
                                <div class="mr-3">
                                    <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/user.png" class="rounded-circle" alt=""></div>
                                <div class="align-self-center"><strong>Поспелова Анастасия</strong></div>
                            </div>
                            <div>
                                Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</div>
                        </div>

                        <h1>Написать комментарий</h1>

                        <div class="rounded border">
                            <div class="d-flex mb-2">
                                <div class="mr-3">
                                    <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/user.png" class="rounded-circle" alt=""></div>
                                <div class="align-self-center"><strong>Поспелова Анастасия</strong></div>
                            </div>
                            <textarea style="width: 100%" rows="5">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</textarea>
                        </div>

                        <button class="btn btn-outline-dark">Оставить комментарий</button>

                    </div>
                    <!-- Comments -->


                </main><!-- #main -->
            </div>


		</div>

	</div>
	<div class="col-12" style="/*padding-left: 120px; margin-top: 120px;*/">


	</div>

<?php get_footer(); ?>