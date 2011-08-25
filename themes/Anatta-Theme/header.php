<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<!--[if lte IE 7]><html class="ie7" <?php language_attributes(); ?>><![endif]-->
<!--[if (gt IE 7)|!(IE)]><! --><html <?php language_attributes(); ?>><!-- <![endif]-->
<head>
	<meta charset="utf-8" />
    <title><?php wp_title(''); ?></title>
 <?php wp_enqueue_script('jquery'); ?>

    <?php wp_head(); ?>

	<!-- http://google.com/webmasters -->
    <meta name="google-site-verification" content="" />

    <!-- don't allow IE9 to render the site in compatibility mode. Dude. -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/style.css" />
	<!--[if lt IE 9]>
		<link rel="stylesheet" media="all" href="<?php bloginfo('template_directory'); ?>/css/ie.css"/>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	
	<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?>: Feed" href="<?php bloginfo('rss2_url'); ?>" />
	<link rel="shortcut icon" href="<?php bloginfo('url');?>/anatta.jpg" type="image/vnd.microsoft.icon"/>
<link rel="icon" href="<?php bloginfo('url');?>/anatta.jpg" type="image/x-ico"/>
	<?php if (is_search()) { ?>
	   <meta name="robots" content="noindex, nofollow" /> 
	<?php } ?>
	<?php //if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
    <?php // for google analytics code
if ( function_exists( 'yoast_analytics' ) ) {
  yoast_analytics();
}
?>
<script type="text/javascript">
var templateURL= '<?php bloginfo('template_url');?>';
</script>

    <script type="text/javascript">
   //function for changing classname for navigation
function hidedivider(id){
  		document.getElementById(id).className="divider-hide";
  	}
  	  	function showdivider(id){
  		document.getElementById(id).className="show-hide";
  	}

 jQuery(document).ready(function(){	
	// ajax pagination
	jQuery('.wp-pagenavi a').live('click', function(){ // if not using wp-page-numbers, change this to correct ID
		var link = jQuery(this).attr('href');
					// #main is the ID of the outer div wrapping your posts
		jQuery('#main_listing_div').html('<div class="loading"><h2>Loading...</h2></div>');
					// #entries is the ID of the inner div wrapping your posts
		jQuery('#main_listing_div').load(link+' #entries')	;
	});	
}); // end ready function

</script>


</head>

<body >
	<!--wrapper-->
	<div id="wrapper">
		<!--Header-->
		<div id="header">
			<a id="logo" href="<?php bloginfo('url'); ?>">Med World Live</a>
			<big>Changing the world’s <i>View</i> of medicine<br/>See One. Do One. Teach One.</big>
			
            <?php if(is_user_logged_in()) { //if the user is logged in
				global $current_user;
      			get_currentuserinfo(); //getting current logged in user's detail
			$request_uri = explode('/',($_SERVER['REQUEST_URI']));
			if($request_uri[1] == 'videos' || $request_uri[1] == 'client-portal' ) //checking for videos custom post type
			{ 
				require_once(TEMPLATEPATH.'/searchform.php');//including search form 
			} } ?>
		</div>
        <?php 
			  if(is_user_logged_in()) { //if the user is logged in
			
			if($request_uri[1] == 'videos' || $request_uri[1] == 'client-portal' ) //checking for videos custom post type
			{ 
			$usercname =  $current_user->companyname;
			$terms = get_terms( 'company');//getting term list wrt physician taxonomy
			foreach ($terms as $taxindex => $taxitem) {
			
				$term_data = get_option("taxonomy_$taxitem->term_id"); //code for getting value of physician image from options table
				if($taxitem->name == $usercname){
				 ?>
				<div class="padding_logo"><img src="<?php echo $term_data['img'];?>" alt=""/></div>
				
				<?php
				}} // end foreach 
 
		}
		else { //if request uri is not equal to videos or client-portal?>
		<div class="menu">
        <?php wp_nav_menu( array('menu' => 'Header Top Navigation', 'container' => '','container_class' => '', 'container_id' => '','menu_class'      => '', 'items_wrap'      => '<ul id="navigation">%3$s</ul>' )); ?>
        </div>
		<?php }
		
		}
		else { 	//if the user is not logged in
		 ?>
		<!--Mian-navigation-->
        <div class="menu">
        <?php wp_nav_menu( array('menu' => 'Header Top Navigation', 'container' => '','container_class' => '', 'container_id' => '','menu_class'      => '', 'items_wrap'      => '<ul id="navigation">%3$s</ul>' )); ?>
        </div>
        <?php } ?>
        
		<!--/Mian navigation-->      
  		<div class="logins"><?php echo add_login_logout_link(); ?>
       
        </div>
         <?php if (is_user_logged_in()) { ?>
              <div class="eprofile"><a href="<?php bloginfo('url'); ?>/edit-profile/">Edit My Profile</a></div>
        <?php } ?>
		<div class="clear"></div>