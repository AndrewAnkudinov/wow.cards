<?php

?>
<?php if ( 'comments.php' == basename( $_SERVER['SCRIPT_FILENAME'] ) ) return; ?>
<section id="comments" class="comments">
	<div class="comments-outer-sidebar">
	<?php
	if ( have_comments() ) :
		global $comments_by_type;
		$comments_by_type = separate_comments( $comments );
		if ( ! empty( $comments_by_type['comment'] ) ) :
			?>
			<section id="comments-list" class="comments">
			
				<h2 class="comments-title brnhmbx-font-1">
					<span class="robin-icon-asterisk mr10">*</span>
					<?php comments_number(); ?>
					<span class="robin-icon-asterisk ml10">*</span>
				</h2>
				<div class="robin-sep"></div>

				<?php if ( get_comment_pages_count() > 1 ) : ?>
					<nav id="comments-nav-above" class="comments-navigation" role="navigation">
						<div class="paginated-comments-links"><?php paginate_comments_links(); ?></div>
					</nav>
				<?php endif; ?>
				
				<ul>
					<?php wp_list_comments( 'type=comment&callback=mytheme_comment' ); ?>
				</ul>

				<?php if ( get_comment_pages_count() > 1 ) : ?>
					<nav id="comments-nav-below" class="comments-navigation" role="navigation">
						<div class="paginated-comments-links"><?php paginate_comments_links(); ?></div>
					</nav>
				<?php endif; ?>

			</section>
			<?php
		endif;
		if ( ! empty( $comments_by_type['pings'] ) ) :
			$ping_count = count( $comments_by_type['pings'] );
			?>
			<section id="trackbacks-list" class="comments">
				<h3 class="comments-title"><?php echo '<span class="ping-count">' . $ping_count . '</span> ' . ( $ping_count > 1 ? __( 'Trackbacks', 'blankslate' ) : __( 'Trackback', 'blankslate' ) ); ?></h3>
				<ul>
					<?php wp_list_comments( 'type=pings&callback=blankslate_custom_pings' ); ?>
				</ul>
			</section>
			<?php
		endif;
	endif;
	?>
	</div>
	<?php if ( comments_open() ) { ?>
		<div style="border: dashed 2px #a6afb9;">
			<?php comment_form(); ?>
		</div>
	<?php } ?>
</section>