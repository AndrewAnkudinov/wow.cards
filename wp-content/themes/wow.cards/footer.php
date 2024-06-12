<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WP_Bootstrap_Starter
 */

?>


<?php if(!is_page_template( 'blank-page.php' ) && !is_page_template( 'blank-page-with-container.php' )): ?>
	
</div>
<!--		</div> --><!-- .container -->
	</div><!-- #content -->
	<?php get_template_part( 'footer-widget' ); ?>
	
	<footer id="colophon" class="site-footer" role="contentinfo">

<!--		<svg id="footerSvg" width="1127" height="508" viewBox="0 0 1127 508" fill="none" xmlns="http://www.w3.org/2000/svg">-->
<!--			<path d="M22.6981 188.56C-76.0047 252.835 -60.2971 374.416 -179.058 401.214C-229.382 412.57 -261.673 390.036 -312.047 401.214C-518.522 447.035 -363.765 837.751 -141.549 859.201C80.6675 880.651 541.013 923.55 541.013 923.55C553.743 923.55 788.425 819.318 904.174 767.201C1019.92 715.085 1056.81 620.44 1020.68 521.367C999.229 462.533 955.381 443.283 904.174 401.214C792.97 309.855 673.308 328.69 563.178 236.319C497.394 181.144 500.734 112.248 421.096 73.9377C277.368 4.79633 154.453 102.762 22.6981 188.56Z" fill="#FF0000" fill-opacity="0.05"/>-->
<!--			<path d="M35.28 263.707C-54.6073 346.413 -24.2202 481.05 -138.541 523.894C-186.983 542.049 -221.704 520.26 -270.218 538.222C-469.067 611.846 -268.163 1033.28 -45.5214 1033.39C177.121 1033.5 638.161 1032.01 638.161 1032.01C650.766 1030.64 870.415 888.478 978.665 817.569C1086.91 746.66 1111.88 636.561 1064.03 529.362C1035.61 465.702 989.845 448.842 934.01 407.187C812.756 316.728 696.572 350.739 576.257 259.029C504.391 204.249 499.291 126.636 415.764 92.2581C265.018 30.215 155.267 153.306 35.28 263.707Z" fill="#FF0000" fill-opacity="0.05"/>-->
<!--		</svg>-->

	</footer><!-- #colophon -->

<?php endif; ?>

<?php
/*
<video class="plyr" controls crossorigin playsinline autoplay="true" muted poster="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-HD.jpg">
	<source src="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-576p.mp4" type="video/mp4" size="576">
	<source src="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-720p.mp4" type="video/mp4" size="720">
	<source src="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-1080p.mp4" type="video/mp4" size="1080">

	<!-- Caption files -->
	<track kind="captions" label="English" srclang="en" src="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-HD.en.vtt"
		   default>
	<track kind="captions" label="Français" srclang="fr" src="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-HD.fr.vtt">
	<!-- Fallback for browsers that don't support the <video> element -->
	<a href="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-576p.mp4" download>Download</a>
</video>

<script src="https://vjs.zencdn.net/7.14.3/video.min.js"></script>
*/
?>

</div><!-- #page -->
</div><!-- wrapper -->
<?php wp_footer(); ?>




<!--    <script src=https://polyfill.io/v3/polyfill.js?features=Element.prototype.classList%2CElement.prototype.dataset-->
<!--    ></script>-->
<!--   <link href="https://vjs.zencdn.net/7.14.3/video-js.css" rel="stylesheet" />-->
<!--    <style rel="stylesheet" href="https://unpkg.com/plyr@3.6.8/dist/plyr.css"></style>-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons&display=swap" rel="stylesheet">

    <script>
        $('.bubbly-button').click(function () {
            $(this).addClass('animate');
        });

        new PerformanceObserver(entryList => {
            console.log(entryList.getEntries());
        }).observe({ type: "largest-contentful-paint", buffered: true });

    </script>




</body>

</html>