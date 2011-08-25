<?php get_header(); ?>
<!--Content area-->
		<div id="content" class="home-page" >
		<div id="portal-listing">
  
  <div  id="main_listing_div">
  <div id="entries">
    <?php $search_item = $_GET['s']; ?>
           
			<h1 class="page-title">Search Results</h1>
            <?php 
				global $current_user;
				get_currentuserinfo();
				$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
				$posts=query_posts($query_string . '&company="'.$current_user->companyname.'"&paged='.$paged); ?>
               <?php if (have_posts()) : ?>
			<?php while (have_posts()) : the_post(); ?>   
    <div class="list">
      <h1>
        <?php the_title(); ?>
      </h1>
      <?php if(has_post_thumbnail()) { ?>
      <div class="video-demo"> <a href="<?php the_permalink(); ?>">
        <?php the_post_thumbnail( array(223,140)); //getting featured image?>
        </a> </div>
      <?php } ?>
      <div class="video-content">
        <p><?php echo $desc = get_post_meta($post->ID, 'video_desc', true); //getting video description ?> <big><a href="<?php the_permalink(); ?>">Click here to watch </a></big> </p>
      </div>
      <div class="clear"></div>
      <div class="bar">
       <span class="date">
        <?php $dateformat = get_post_meta($post->ID, 'date_picker', true); //getting date meta value
					echo format_date($dateformat);
					 ?>
        </span>
        <span class="Physician">Physician: <small><?php echo the_wi_physician_name(); // getting physician value?></small></span>
        <span class="country">Country: <small><?php echo the_wi_country(); //getting country taxonomy value ?></small></span>
        <span class="specialty">Speciality: <small><?php echo the_wi_speciality(); //getting speciality taxonomy value?></small></span>
        <span class="Medical">Medical Product Companies: <small><?php echo the_wi_medical(); //getting medical taxonomy value?></small></span>
        </div>
      <div class="clear"></div>
    </div>
    <?php endwhile; ?>
    <div class="clear "></div>
    <?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); } //pagination function?>
    
    <?php endif; wp_reset_query(); ?>
  </div><!--entries-->
  </div><!--main listing div-->
</div>
<!--/portal page-->

	
<?php get_footer(); ?>
