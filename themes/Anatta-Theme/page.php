<?php get_header(); ?>
<!--Content area-->
		<div id="content" class="home-page" >
		<?php require_once (TEMPLATEPATH . '/sidebar.php'); //including left sidebar?>
        <?php //require_once (TEMPLATEPATH . '/sidebar-right.php'); //including right sidebar ?>
          <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<!--right column-->
			<div class="right-column">
          
				<h1 class="page-title"><?php the_title(); ?></h1>
				<?php the_content(); ?>
			</div>	
			<!--/right column-->
			<div class="clear"></div>
			<?php endwhile; ?>
		<?php else : ?>
		<article>
			<header>
				<h2>Not Found</h2>
			</header>
		</article>
		<?php endif; ?>

	
<?php get_footer(); ?>
