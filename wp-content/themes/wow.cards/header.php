<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WP_Bootstrap_Starter
 */
	

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="preconnect" href="https://fonts.gstatic.com">
	<!--<link href="https://fonts.googleapis.com/css2?family=MuseoModerno:wght@400;700&display=swap" rel="stylesheet">-->
	<!--<script src="/wp-content/themes/wow.cards/assets/js/lib/confetti.min.js"></script>-->
    <!--<link href="https://fonts.googleapis.com/css2?family=Jua&family=Roboto:wght@500;700&PT+Sans+Caption:wght@400;700&display=swap" rel="stylesheet">-->
	<!--[if IE]><![endif]-->


	
<?php wp_head(); ?>
</head>


<body <?php if ( is_home() ) { body_class(); } else { body_class('page-wo-footer'); } ?>>
<div class="wrapper">
	<!--<h id="page" class="site">-->

    <?php if(!is_page_template( 'blank-page.php' ) && !is_page_template( 'blank-page-with-container.php' )): ?>
			
	<header id="masthead" class="site-header navbar-static-top" role="banner"><!-- d-flex justify-content-center align-items-center -->

<!--		<svg id="headerSvg" width="834" height="604" viewBox="0 0 834 604" fill="none" xmlns="http://www.w3.org/2000/svg">-->
<!--			<path d="M1173.15 139.727C1233.34 50.2575 1179.54 -58.9161 1268.91 -119.249C1306.78 -144.814 1341.08 -133.298 1379.05 -158.713C1534.69 -262.885 1275.44 -582.784 1084.2 -536.925C892.966 -491.066 497.308 -394.626 497.308 -394.626C486.765 -390.849 327.364 -223.616 248.982 -140.472C170.599 -57.3279 171.803 42.2314 234.958 124.273C272.462 172.993 315.235 178.004 371.759 202.197C494.508 254.735 587.295 201.591 709.495 255.396C782.487 287.535 802.833 353.032 881.643 365.27C1023.88 387.355 1092.81 259.157 1173.15 139.727Z" fill="#AD00FF" fill-opacity="0.05"/>-->
<!--			<path d="M1230.14 41.6417C1283.13 -64.7257 1209.71 -181.511 1298.39 -258.226C1335.98 -290.733 1374.79 -281.34 1412.5 -313.689C1567.07 -446.283 1241.63 -778.097 1040.39 -707.18C839.144 -636.263 422.999 -487.794 422.999 -487.794C412.076 -482.484 262.106 -278.872 188.487 -177.73C114.867 -76.5876 129.888 34.8054 209.734 120.243C257.151 170.98 304.265 172.22 368.945 193.54C509.409 239.84 602.796 170.828 742.837 218.602C826.487 247.138 857.592 318.422 944.815 324.072C1102.23 334.269 1159.39 183.627 1230.14 41.6417Z" fill="#AD00FF" fill-opacity="0.05"/>-->
<!--		</svg>-->




        <div class="container-fluid">
			<div class="row align-items-center">
				<div class="col-6 col-sm-3 col-lg-2">
					<a class="navbar-brand" href="<?php echo home_url() ?>"><img id="imgLogo" src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/logo.png" alt=""></a>
				</div>
				<div class="col-6 col-sm-9 col-lg-10 col-xl-10">
                    <div class="header__nav">
<!--					<nav class="navbar navbar-expand-lg navbar-light" style="padding-right: 0;">-->
<!--						<button class="navbar-toggler py-0" type="button"-->
<!--								data-toggle="collapse" data-target="#navbarSupportedContent"-->
<!--								aria-controls="navbarSupportedContent" aria-expanded="false"-->
<!--								aria-label="Toggle navigation">-->
<!--							<span class="navbar-toggler-icon"></span>-->
<!--							<span class="material-icons" style="font-size: 36px; color: #F01E50;">menu</span>-->
<!--						</button>-->
<!---->
<!--						<div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">-->

							<?php
							wp_nav_menu( array( 'theme_location' => 'max_mega_menu_1', 'menu_class'      => 'm-auto') );
							/*wp_nav_menu([
								'theme_location'  => 'Header',
								'menu'            => '',
								'container'       => 'div',
								'container_class' => '',
								'container_id'    => '',
								'menu_class'      => 'navbar-nav m-auto',
								'menu_id'         => 59,
								'echo'            => true,
								'fallback_cb'     => 'wp_page_menu',
								'before'          => '',
								'after'           => '',
								'link_before'     => '',
								'link_after'      => '',
								'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
								'depth'           => 0,
								'walker'          => '',
							]);*/

							?>
                            <?php $translations = pll_the_languages(array('raw'=>1));
                            ?>




<!--                            <ul class="navbar-nav m-auto d-none">-->
<!--								<li class="nav-item active">-->
<!--									<a class="nav-link" href="#">главная</a>-->
<!--								</li>-->
<!--								<li class="nav-item">-->
<!--									<a class="nav-link" href="#">открытки</a>-->
<!--								</li>-->
<!--								<li class="nav-item dropdown">-->
<!--									<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">-->
<!--										видеопоздравления-->
<!--									</a>-->
<!--									<div class="dropdown-menu" aria-labelledby="navbarDropdown">-->
<!--										<a class="dropdown-item" href="#">Action</a>-->
<!--										<a class="dropdown-item" href="#">Another action</a>-->
<!--										<div class="dropdown-divider"></div>-->
<!--										<a class="dropdown-item" href="#">Something else here</a>-->
<!--									</div>-->
<!--								</li>-->
<!--								<li class="nav-item">-->
<!--									<a class="nav-link" href="#">Контакты</a>-->
<!--								</li>-->
<!--							</ul>-->
<!--							--><?//
//							/*
//							*/
//							?>
<!--						</div>-->
<!--					</nav>-->
                    </div>
				</div>
			</div>
		</div><!-- .container-fluid -->
        <picture>
            <source srcset="<?php echo get_stylesheet_directory_uri() ?>/assets/img/Big1.png" media="(max-width: 950px)">
            <source srcset="<?php echo get_stylesheet_directory_uri() ?>/assets/img/Big.png">
            <img id="headerSvg2" src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/Big.png" alt="">
        </picture>
        <picture>
            <source srcset="<?php echo get_stylesheet_directory_uri() ?>/assets/img/small1.png" media="(max-width: 950px)">
            <source srcset="<?php echo get_stylesheet_directory_uri() ?>/assets/img/small.png">
            <img id="headerSvg3" src="<?php echo get_stylesheet_directory_uri() ?>/assets/img/small.png" alt="">
        </picture>


	</header>
    <script>
        $('#mega-menu-max_mega_menu_1').append(`<li class="mega-menu-item mega-menu-item-type-custom mega-menu-item-object-custom mega-align-bottom-left mega-menu-flyout mega-menu-item-467 dropdown header__dropdown">
                                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <img src="<?php echo $translations['ru']['flag'] . ' ' ?>" alt=""><?php echo strtoupper(pll_current_language())?>
                                </a>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <ul>
                                        <?php
        foreach ($translations as $lang) {
            if (pll_current_language() != $lang['slug']) {
                echo '<li><a href="' . $lang['url'] . '"><img src="' . $lang['flag'] . '" alt="">' . strtoupper($lang['slug']) . '</a></li>';
            }
        }
        ?>
                                    </ul>
                                </div>
                            </li>`);
    </script>

	<div id="content" class="site-content d-flex justify-content-center align-items-center">

			<div class="row">
				<?php endif; ?>