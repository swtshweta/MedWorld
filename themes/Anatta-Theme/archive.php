<?php get_header(); ?>
<!--Content area-->
		<div id="content" class="home-page" >
		<?php require_once (TEMPLATEPATH . '/sidebar.php'); //including left sidebar?>
			<!--right column-->
			<div class="search-right-column">
            <?php if (have_posts()) : ?>
			<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
			<?php /* If this is a category archive */ if (is_category()) { ?>
				<h1 class="page-title">Archive for the '<?php single_cat_title(); ?>' Category</h1>
			<?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
				<h1 class="page-title">Posts Tagged '<?php single_tag_title(); ?>'</h1>
			<?php /* If this is a daily archive */ } elseif (is_day()) { ?>
				<h1 class="page-title">Archive for <?php the_time('F jS, Y'); ?></h1>
			<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
				<h1 class="page-title">Archive for <?php the_time('F, Y'); ?></h1>
			<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
				<h1 class="pagetitle">Archive for <?php the_time('Y'); ?></h1>
			<?php /* If this is an author archive */ } elseif (is_author()) { ?>
				<h1 class="pagetitle">Author Archive</h1>
			<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
				<h1 class="pagetitle">Blog Archives</h1>
			<?php } ?>
			<?php while (have_posts()) : the_post(); ?>
		<article <?php post_class() ?> id="post-<?php the_ID(); ?>">
			<header>
				<h2><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
				<p> <!-- edit this meta stuff? -->
					<span>Posted on:</span> <?php the_time('F jS, Y'); ?>
					<span>by</span> <?php the_author(); ?> |
					<?php comments_popup_link('No Comments', '1 Comment', '% Comments', 'comments-link', ''); ?>
				</p>
			</header>
			<section>
				<?php the_excerpt(); ?>
			</section>
			<footer> <!-- post metadata -->
				<p><span>Posted in</span> <?php the_category(', ') ?> | <?php the_tags('<span>Tags:</span> ', ', ', ''); ?> | <?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?></p>
			</footer>
            <hr/>
		</article>
		<?php endwhile; ?>
		<nav class="pagination">
			<div>
				<div><?php next_posts_link('&laquo; Older Entries') ?></div>
				<div><?php previous_posts_link('Newer Entries &raquo;') ?></div>
			</div>
		</nav>
		<?php else : ?>
		<article>
			<header>
				<h2>Not Found</h2>
			</header>
		</article>
		<?php endif; ?>	
				
			</div>	
			<!--/right column-->
			<div class="clear"></div>


<?php get_footer(); ?>
