<?php get_header(); ?>
<?php
$request_uri = explode('/',($_SERVER['REQUEST_URI']));
if($request_uri[1] == 'videos') //checking for videos custom post type
{
if(is_user_logged_in()) { ?>

<!--Content area-->
		<div id="content" >
         <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<!--portal page-->
			<div id="portal-page">
				
				<div class="account-links"><a href="<?php bloginfo('url'); ?>/client-portal/">Back to Listing</a></div>
				<img src="<?php bloginfo('template_url');?>/images/silverlight-video.gif" alt=""/>
				<div class="clear"></div>
                
                <?php /* ?>
				<!--bottom advertisement-->
				<div class="add-content">
					<!--displaying Ads-->
					<span class ="add-space"><?php if(function_exists('drawAd')) drawAd(array('id' => 2), true);// shortcode for showing ad?></span>
				<?php if(function_exists('drawAd')) drawAd(array('id' => 3), true); // shortcode for showing ad?>
				</div>
			<!--/bottom advertisement-->
			<?php */ ?>
			<div class="clear " ></div>
			<div class="client-portal">
            	<div class="left-column">
                	<div class="item">
                    	<h5>Physician</h5>
                        <?php
						$terms = get_the_terms( $post->ID, 'wi_physician_name');//getting term list wrt physician taxonomy
						foreach ($terms as $taxindex => $taxitem) {
						
						$term_data = get_option("taxonomy_$taxitem->term_id"); //code for getting value of physician image from options table
						//print_r($term_data);
						 ?>
                        <img src="<?php echo $term_data['img'];?>" alt=""/>
                        
                        <div>
                        	<h5><?php echo the_wi_physician_name(); // getting physician value?></h5>
                            
                            <?php echo "<p>".$taxitem->description."</p>"; //getting physician description
  						} // end foreach ?>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            	<!--div class="add"><?php if(function_exists('drawAd')) drawAd(array('id' => 1), true);  //getting ad ?></div-->
                <div class="clear"></div>
                
                <div class="bar">
                	<span class="date"><?php $dateformat = get_post_meta($post->ID, 'date_picker', true); //getting date meta value
					echo format_date($dateformat); ?></span>
                    <span class="country">Country: <small><?php echo the_wi_country(); //getting country taxonomy value ?></small></span>
                    <span class="specialty">Speciality: <small><?php echo the_wi_speciality(); //getting speciality taxonomy value?></small></span>
                    <span class="vendor">Vendor: <small><?php echo the_wi_medical(); //getting medical taxonomy value?></small></span>
                </div>
                
            </div>
				

			  <div class="clear"></div>
			</div>
			<!--/portal page-->
			<?php endwhile; ?>
		<?php else : ?>
		<article>
			<header>
				<h2>Not Found</h2>
			</header>
		</article>
		<?php endif; ?>
<?php } //for user loggeed in
else //if not logged in
{ ?>
<!--Content area-->
		<div id="content" >
        <div id="portal-page">
        Please <a href="<?php bloginfo('url');?>/medworldproject/wp-login.php?redirect_to=http%3A%2F%2Fmedworld.wordpressprojects.com%2Fclient-portal%2F">Login</a> to view the content.
        <div class="clear"></div>
        </div>
        
<?php }

}
else {
?>
<!--Content area-->
		<div id="content" class="home-page" >
		<?php require_once (TEMPLATEPATH . '/sidebar.php'); //including left sidebar?>
			<!--right column-->
			<div class="right-column">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<h1><?php the_title(); ?></h1>
				<p> <!-- edit this meta stuff? -->
					<span>Posted on:</span> <?php the_time('F jS, Y'); ?>
					<span>by</span> <?php the_author(); ?> |
					<?php comments_popup_link('No Comments', '1 Comment', '% Comments', 'comments-link', ''); ?>
				</p>
                <p><?php the_content(); ?></p>
               <p><span>Posted in</span> <?php the_category(', ') ?> | <?php the_tags('<span>Tags:</span> ', ', ', ''); ?> | <?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?></p>
               <?php MRP_show_related_posts(); //for displaying related posts?>
				<?php comments_template(); ?>
                <?php endwhile; ?>
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

<?php } ?>
	
<?php get_footer(); ?>
