<?php
/*
Template Name: Client Portal Listing
*/
?>
<?php get_header(); ?>
<!--Content area-->

<div id="content" >
<!--portal page-->
<?php if(is_user_logged_in()) { 
				global $current_user;
      			get_currentuserinfo();
				 ?>
<div id="portal-listing">
  <h5>Sort Videos By:</h5>
  <div class="sort">
    <input type="hidden" name="current_page" id="current_page" value="1" />
    <!--filterby_page function for getting result for sorting ajax based-->
 
 <?php    
    
$args = array(
	'show_option_all' => 'Physician', // initial selected option for Physician 
	'orderby' => 'name',
	'order' => 'ASC',
	'show_last_update' => 0,
	'show_count' => 0,
	'hide_empty' => 1, // for a particular value of the taxonomy
	'child_of' => 0,
	'exclude' => '',
	'echo' => 1,
	'selected' => false,
	'hierarchical' => 0,
	'name' => 'physician',
	'id' => 'filterbyp',
	'class' => 'postform',
	'depth' => 0,
	'tab_index' => 0,
	'taxonomy' => 'wi_physician_name', // specify your taxonomy name here
	
	'hide_if_empty' => false// for the whole dropdown
	
);

altrugon_dropdown_categories($args); ?>       
    
<?php    
    
$args = array(
	'show_option_all' => 'Country', // initial selected option for Country 
	'orderby' => 'name',
	'order' => 'ASC',
	'show_last_update' => 0,
	'show_count' => 0,
	'hide_empty' => 1, // for a particular value of the taxonomy
	'child_of' => 0,
	'exclude' => '',
	'echo' => 1,
	'selected' => false,
	'hierarchical' => 0,
	'name' => 'country',
	'id' => 'filterbyc',
	'class' => 'postform',
	'depth' => 0,
	'tab_index' => 0,
	'taxonomy' => 'wi_country', // specify your taxonomy name here
	
	'hide_if_empty' => false// for the whole dropdown
	
);

altrugon_dropdown_categories($args); ?>    
    
<?php    
    
$args = array(
	'show_option_all' => 'Speciality', // initial selected option for Specialty 
	'orderby' => 'name',
	'order' => 'ASC',
	'show_last_update' => 0,
	'show_count' => 0,
	'hide_empty' => 1, // for a particular value of the taxonomy
	'child_of' => 0,
	'exclude' => '',
	'echo' => 1,
	'selected' => false,
	'hierarchical' => 0,
	'name' => 'speciality',
	'id' => 'filterbys',
	'class' => 'postform',
	'depth' => 0,
	'tab_index' => 0,
	'taxonomy' => 'wi_speciality', // specify your taxonomy name here
	
	'hide_if_empty' => false// for the whole dropdown
	
);

altrugon_dropdown_categories($args); ?>

<?php    
    
$args = array(
	'show_option_all' => 'Medical Product Companies', // initial selected option for Medical Product Companies 
	'orderby' => 'name',
	'order' => 'ASC',
	'show_last_update' => 0,
	'show_count' => 0,
	'hide_empty' => 1, // for a particular value of the taxonomy
	'child_of' => 0,
	'exclude' => '',
	'echo' => 1,
	'selected' => false,
	'hierarchical' => 0,
	'name' => 'medical',
	'id' => 'filterbym',
	'class' => 'postform',
	'depth' => 0,
	'tab_index' => 0,
	'taxonomy' => 'wi_medical', // specify your taxonomy name here
	
	'hide_if_empty' => false// for the whole dropdown
	
);

altrugon_dropdown_categories($args); ?>

   
 <?php    
    
$args = array(
	'show_option_all' => 'Hospital/Medical Teaching Institutions', // initial selected option for Hospital/Medical Teaching Institutions
	'orderby' => 'name',
	'order' => 'ASC',
	'show_last_update' => 0,
	'show_count' => 0,
	'hide_empty' => 1, // for a particular value of the taxonomy
	'child_of' => 0,
	'exclude' => '',
	'echo' => 1,
	'selected' => false,
	'hierarchical' => 0,
	'name' => 'hospital',
	'id' => 'filterbyh',
	'class' => 'postform',
	'depth' => 0,
	'tab_index' => 0,
	'taxonomy' => 'wi_hospital', // specify your taxonomy name here
	
	'hide_if_empty' => false// for the whole dropdown
	
);

altrugon_dropdown_categories($args); ?>   
   
  </div>
  <div  id="main_listing_div">
  <div id="entries">
    <?php 
				  $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
				 // $offset = (2 * $paged ) - 2;
				 query_posts('post_type=wi_video&company="'.$current_user->companyname.'"&paged='.$paged);
				  if (have_posts()) : while (have_posts()) : the_post(); 
				  ?>
                  
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
	<!-- start pagination-->
	<div class="wp-pagenavi">
    <?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); } //pagination function?>
    </div>
	<!-- end pagination-->	
	<div class="clear"></div>
    
	<?php endif; wp_reset_query(); ?>
  </div><!--entries-->
  </div><!--main listing div-->
</div>
<!--/portal page-->
<?php } else { ?>
<div id="portal-listing">Please <a href="<?php bloginfo('url');?>/medworldproject/wp-login.php?redirect_to=http%3A%2F%2Fmedworld.wordpressprojects.com%2Fclient-portal%2F">Login</a> to view the content</div>
<div class="clear"></div>
<?php } ?>
<?php get_footer(); ?>
