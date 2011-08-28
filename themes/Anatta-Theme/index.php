<?php get_header(); ?>

<!--Content area-->
		<div id="content" class="home-page" >
		<?php require_once (TEMPLATEPATH . '/sidebar.php'); //including left sidebar?>
			<!--right column-->
			<div class="right-column galleryheight">
				<?php //include (ABSPATH . '/wp-content/plugins/featured-content-gallery/gallery.php'); //including Featured content Gallery?>
                <?php dynamic_content_gallery(); //including Featured content Gallery?>
				
			</div>	
			<!--/right column-->
			<div class="clear"></div>



<?php get_footer(); ?>
