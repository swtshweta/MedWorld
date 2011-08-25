<?php
    // Load jQuery
    if ( !is_admin() ) {
        wp_deregister_script('jquery');
        wp_register_script('jquery', ("http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"), false);
        wp_enqueue_script('jquery');
    }

    // Clean up the <head>
    function removeHeadLinks() {
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wp_generator');
        remove_action('wp_head', 'index_rel_link');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'feed_links_extra', 3);
        remove_action('wp_head', 'start_post_rel_link', 10, 0);
        remove_action('wp_head', 'parent_post_rel_link', 10, 0);
        remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
    }
    add_action('init', 'removeHeadLinks');
	wp_deregister_script('l10n');
	
    // remove version info from head and feeds
    function complete_version_removal() {
        return '';
    }
    add_filter('the_generator', 'complete_version_removal');

    // custom excerpt.
    function improved_trim_excerpt($text) {
        global $post;
        if ( '' == $text ) {
            $text = get_the_content('');
			$text = apply_filters('the_content', $text);
			$text = str_replace(']]>', ']]&gt;', $text);
			if (stristr($text,"<style")) { //get rid of CSS.
				$text1 = explode("<style", $text);
				$text2 = explode("</style>", $text);
				$text = $text1[0] . $text2[1]; //this might work
			}
            $text = strip_tags($text, '<strong>');
            //ALLOWED tags (will be rendered) - could add more
            //They count against the word count below, though
			$excerpt_length = 55; //default excerpt is 55 words
			$words = explode(' ', $text, $excerpt_length + 1);
			
			if (count($words)> $excerpt_length) {
				array_pop($words);
				array_push($words, '...'); //indicates "read more..."
				$text = implode(' ', $words);
			}
		}
		return $text;
	}
    remove_filter('get_the_excerpt', 'wp_trim_excerpt');
    add_filter('get_the_excerpt', 'improved_trim_excerpt');

    //Support for Featured Images for posts or pages
    add_theme_support( 'post-thumbnails' );
	
    //Support for WP3 menus - create menus in the admin interface, then add them to widget areas in
    //the theme (like, say, the Nav widget area). Menus are not baked into this theme.
    add_theme_support( 'menus');
	
	// Registering Menus For Theme
 
add_action( 'init', 'register_my_menus' );
 
function register_my_menus() {
	register_nav_menus(
		array(
			'header-menu' => __( 'Header Top Navigation' ),
			'sidebar-menu' => __( 'Left Sidebar Navigation' ),
			'footer-menu' => __( 'Footer Navigation' )
		)
	);
}

    // add custom content after each post
    function add_post_content($content) {
        if(!is_feed() && !is_home()) {
            //$content .= '<p>This article is copyright &copy; '.date('Y').'&nbsp;'.bloginfo('name').'</p>';
            $content .= '';
        }
        return $content;
    }
    add_filter('the_content', 'add_post_content');

    //enable shortcodes in widgets
    if (!is_admin()) {
        add_filter('widget_text', 'do_shortcode', 11);
    }

	// sidebars / widget areas: I have one in the header, nav, sidebar, and footer
    register_sidebar(array(
        'name' => 'Sidebar Widgets',
        'id'   => 'sidebar-widgets',
        'description'   => 'These are widgets for the sidebar.',
        //'before_widget' => '<div id="%1$s" class="widget %2$s">',
        //'after_widget'  => '</div>',
        'before_widget' => '',
        'after_widget' => '',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>'
    ));

    register_sidebar(array(
        'name' => 'Nav Widget Area',
        'id'   => 'nav-widget-area',
        'description'   => 'These are widgets for the Navigation area (use a menu!).',
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => ''
    ));

    register_sidebar(array(
        'name' => 'Right Widget Area',
        'id'   => 'right-widget-area',
        'description'   => 'These are widgets for the right sidebar.',
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget'  => '</li>',
        'before_title'  => '<h3>', // use h3's here?
        'after_title'   => '</h3>'
    ));

    register_sidebar(array(
        'name' => 'Footer Widget Area',
        'id'   => 'footer-widget-area',
        'description'   => 'These are widgets for the footer.',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>'
    ));
	
	//for providing reading permission for a subscriber
$subRole = get_role( 'subscriber' );
$subRole->add_cap( 'read_private_pages' );
$subRole->add_cap( 'read_private_posts' );
// $subRole->add_cap( 'edit_private_posts' );


//for adding an exra field into the user profile

add_action( 'show_user_profile', 'my_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'my_show_extra_profile_fields' );


function my_show_extra_profile_fields( $user ) { ?>

	<h3>Extra Profile Information</h3>

	<table class="form-table">

		<tr>
			<th><label for="companyname">Company Name</label></th>

			<td>            
            <?php
				
				$categories = get_terms( 'company', 'orderby=name&hide_empty=0' );
				$count = count($categories);
			
				 if ( $count > 0 ){
  				   echo '<select name="companyname" id="companyname" >';
     				foreach ( $categories as $term ) 
					{
					  if(get_user_meta($user->ID, 'companyname', true) == $term->name){
	         			 echo "<option selected value='$term->name' id='$term->name'>$term->name</option>";
        				}else {
	         			 echo "<option value='$term->name' id='$term->name'>$term->name</option>";
	       				}
                    }
    			  echo "</select>";
 					}
				?>
               
                <br />
				<span class="description">Please select your company name.</span>
			</td>
		</tr>

	</table>
<?php }

//for saving that value in the profile....

add_action( 'personal_options_update', 'my_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'my_save_extra_profile_fields' );

function my_save_extra_profile_fields( $user_id ) {

	if(!is_admin())
    return;

	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;

	update_usermeta( $user_id, 'companyname',( isset($_POST['companyname']) ? $_POST['companyname'] : '') );
	//update_option(‘companies’, array('1'=>'Company1','2'=>'Company2','3' => 'Company3', '4' => 'Company4', '5'=> 'Company5'));
  
	
}

//register a taxonomy named Companies

  add_action( 'init', 'create_companies' );
function create_companies() {
 $labels = array(
    'name' => _x( 'Companies', 'taxonomy general name' ),
    'singular_name' => _x( 'Company', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Companies' ),
    'all_items' => __( 'All Companies' ),
    'parent_item' => __( 'Parent Company' ),
    'parent_item_colon' => __( 'Parent Company:' ),
    'edit_item' => __( 'Edit Company' ),
    'update_item' => __( 'Update Company' ),
    'add_new_item' => __( 'Add New Company' ),
    'new_item_name' => __( 'New Company Name' ),
  );
  
  register_taxonomy('company','wi_video',array(
    'hierarchical' => true,
    'labels' => $labels,
	'rewrite'       => array('slug' => 'company' )
	
  ));
}

//function for removing filters added for term description

$filters = array('term_description','category_description','pre_term_description');
foreach ( $filters as $filter ) {
remove_filter($filter, 'wptexturize');
remove_filter($filter, 'convert_chars');
remove_filter($filter, 'wpautop');
remove_filter($filter, 'wp_filter_kses');
remove_filter($filter, 'strip_tags');
} 

//function for adding custom post type ="Videos"

add_action('init','wi_init');
function wi_init() {
  register_post_type('wi_video',array(
    'labels'    => make_post_type_labels('Video','Videos'),
    'public'    => true,
    'show_ui'   => true,
    'rewrite'   => array('slug' => 'videos'),
    'query_var' => 'wi_video',
    'supports'  => array(
      'title',
	  'thumbnail'
    ),
  ));
  
   register_taxonomy('wi_physician_name', 'wi_video', array(
    'hierarchical'  => true, // Like Tags
    'label'        => 'Physician Name',
    'query_var'     => 'wi_physician_name',
    'rewrite'       => array('slug' => 'ptags' ),
    )
  );
  
  register_taxonomy('wi_country', 'wi_video', array(
    'hierarchical'  => false, // Like Tags
    'label'        => 'Country',
    'query_var'     => 'wi_country',
    'rewrite'       => array('slug' => 'ctags' ),
    )
  );
  
  register_taxonomy('wi_speciality', 'wi_video', array(
    'hierarchical'  => false, // Like Tags
    'label'        =>'Speciality',
    'query_var'     => 'wi_speciality',
    'rewrite'       => array('slug' => 'stags' ),
    )
  );
  
  register_taxonomy('wi_medical', 'wi_video', array(
    'hierarchical'  => false, // Like Tags
    'label'        => 'Medical Product Company',
    'query_var'     => 'wi_medical',
    'rewrite'       => array('slug' => 'mtags' ),
    )
  );
  
  register_taxonomy('wi_hospital', 'wi_video', array(
    'hierarchical'  => false, // Like Tags
    'label'        => 'Hospital/Medical Teaching Institutions',
    'query_var'     => 'wi_hospital',
    'rewrite'       => array('slug' => 'htags' ),
    )
  );
  //register_taxonomy_for_object_type( 'category', 'wi_video');
  
  flush_rewrite_rules(false);
}


function make_post_type_labels($singular,$plural=false,$args=array()) {
  if ($plural===false)
    $plural = $singular . 's';
  elseif ($plural===true)
    $plural = $singular;
  $defaults = array(
    'name'               =>_x($plural,'post type general name'),
    'singular_name'      =>_x($singular,'post type singular name'),
    'add_new'            =>_x('Add New',$singular),
    'add_new_item'       =>__("Add New $singular"),
    'edit_item'          =>__("Edit $singular"),
    'new_item'           =>__("New $singular"),
    'view_item'          =>__("View $singular"),
    'search_items'       =>__("Search $plural"),
    'not_found'          =>__("No $plural Found"),
    'not_found_in_trash' =>__("No $plural Found in Trash"),
    'parent_item_colon'  =>'',
  );
  return wp_parse_args($args,$defaults);
}

/* Define the custom box */

// WP 3.0+
 add_action( 'add_meta_boxes', 'myplugin_add_custom_box' );

/* Do something with the data entered */
add_action( 'save_post', 'myplugin_save_postdata' );

/* Adds a box to the main column on the Post and Page edit screens */
function myplugin_add_custom_box() {
    add_meta_box( 
        'myplugin_sectionid',
        __( 'Video Fields', 'myplugin_textdomain' ),
        'myplugin_inner_custom_box',
        'wi_video' 
    );
    
}

/* Prints the box content */
function myplugin_inner_custom_box($post) {

  // Use nonce for verification
  wp_nonce_field( plugin_basename( __FILE__ ), 'myplugin_noncename' );

  $start = get_post_meta($post->ID, 'date_picker', TRUE);
  $document_one = get_post_meta($post->ID, 'document_one', TRUE);
  $document_two = get_post_meta($post->ID, 'document_two', TRUE);
  $document_three = get_post_meta($post->ID, 'document_three', TRUE);
  $document_four = get_post_meta($post->ID, 'document_four', TRUE);
  $equi_image = get_post_meta($post->ID, 'equi_image', TRUE);

  // The actual fields for data entry
  echo '<label for="myplugin_video_url">';
       _e("Manifest URL: ", 'myplugin_textdomain' );
  echo '</label> ';?>
  <input type="text" id="myplugin_video_url" name="myplugin_video_url" class="newtag form-input-tip" size="100" value="<?php echo get_post_meta($post->ID, 'video_url',true) ?>" />
  <br/>
  
  <label for="myplugin_vitals_url"><?php _e("Vitals URL: ", 'myplugin_textdomain' ); ?></label>
  <input type="text" id="myplugin_vitals_url" name="myplugin_vitals_url" class="newtag form-input-tip" size="100" value="<?php echo get_post_meta($post->ID, 'vitals_url',true) ?>" />
  <br/>
  
  <label for="myplugin_video_desc"><?php _e("Video Description: ", 'myplugin_textdomain' ); ?></label>
  <textarea name="myplugin_video_desc" id="myplugin_video_desc" rows="5" cols="82" ><?php echo get_post_meta($post->ID, 'video_desc',true) ?></textarea>
  <br/>
  
  <label for="myplugin_date_picker"><?php _e("Date: ", 'myplugin_textdomain' ); ?></label>
  <input type='text' value='<?php echo get_post_meta($post->ID, 'date_picker',true) ?>' id="myplugin_date_picker" name="myplugin_date_picker" class="newtag form-input-tip" size="25"  />
  <script type="text/javascript">jQuery(document).ready(function(){jQuery("#myplugin_date_picker").simpleDatepicker();});</script>
  <br/>
  
  <label for="myplugin_equi_title"><?php _e("Equipment Title: ", 'myplugin_textdomain' ); ?></label>
  <input type="text" id="myplugin_equi_title" name="myplugin_equi_title" class="newtag form-input-tip" size="100" value="<?php echo get_post_meta($post->ID, 'myplugin_equi_title',true) ?>" />
  <br/>
  
  <label for="myplugin_equi_desc"><?php _e("Equipment Description: ", 'myplugin_textdomain' ); ?></label>
  <textarea name="myplugin_equi_desc" id="myplugin_equi_desc" rows="5" cols="82" ><?php echo get_post_meta($post->ID, 'myplugin_equi_desc',true) ?></textarea>
  <br/>
  
  <label for="myplugin_equi_image"><?php _e("Equipment Image: ", 'myplugin_textdomain' ); ?></label>
  <input name="equi_image" size="50" type="file" value="<?php echo $equi_image; ?>" />
  <img src="<?php echo $equi_image; ?>" alt="" />
  <br/>
 
	<label>Document 1:</label><input name="document_one" size="50" type="file" value="<?php echo $document_one; ?>" />
 	<img src="<?php echo $document_one; ?>" alt="" />
    <br/>
    
    <label>Document 2:</label><input name="document_two" size="50" type="file" value="<?php echo $document_two; ?>" />
 	<img src="<?php echo $document_two; ?>" alt="" />
    <br/>
    
    <label>Document 3:</label><input name="document_three" size="50" type="file" value="<?php echo $document_three; ?>" />
 	<img src="<?php echo $document_three; ?>" alt="" />
    <br/>
    
    <label>Document 4:</label><input name="document_four" size="50" type="file" value="<?php echo $document_four; ?>" />
 	<img src="<?php echo $document_four; ?>" alt="" />
    <br/>
    
    
    
 
<?php 


}

//adding action hook to the post form field for allowing image to be uploaded
add_action( 'post_edit_form_tag' , 'post_edit_form_tag' );

function post_edit_form_tag( ) {
    echo ' enctype="multipart/form-data"';
}


/* When the post is saved, saves our custom data */
function myplugin_save_postdata( $post_id ) {
  // verify if this is an auto save routine. 
  // If it is our form has not been submitted, so we dont want to do anything
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return;

  // verify this came from the our screen and with proper authorization,
  // because save_post can be triggered at other times

  if ( !wp_verify_nonce( $_POST['myplugin_noncename'], plugin_basename( __FILE__ ) ) )
      return;

  
  // Check permissions
  if ( 'page' == $_POST['post_type'] ) 
  {
    if ( !current_user_can( 'edit_page', $post_id ) )
        return;
  }
  else
  {
    if ( !current_user_can( 'edit_post', $post_id ) )
        return;
  }

  // OK, we're authenticated: we need to find and save the data

  $mydata = $_POST['myplugin_video_url'];
  $mydataurl = $_POST['myplugin_vitals_url'];
  $mydatadesc = $_POST['myplugin_video_desc'];
  $mydatadate = $_POST['myplugin_date_picker'];
  $mydatadoc = $_POST['document_one'];
  $mydatadoc2 = $_POST['document_two'];
  $mydatadoc3 = $_POST['document_three'];
  $mydatadoc4 = $_POST['document_four'];
  $mydataeqtitle = $_POST['myplugin_equi_title'];
  $mydataeqdesc = $_POST['myplugin_equi_desc'];
  $mydataeqimage = $_POST['equi_image'];
 	$post = get_post($post_id);
	if ($post->post_type == 'wi_video') { 
		update_post_meta($post_id, 'video_url', $mydata );
		update_post_meta($post_id, 'vitals_url', $mydataurl );
		update_post_meta($post_id, 'video_desc', $mydatadesc );
		update_post_meta($post_id, 'date_picker', $mydatadate );
		update_post_meta($post_id, 'myplugin_equi_title', $mydataeqtitle);
		update_post_meta($post_id, 'myplugin_equi_desc', $mydataeqdesc);
      
		
		if(!empty($_FILES['document_one']['name'])){ //New upload
 require_once( ABSPATH . 'wp-admin/includes/file.php' );
 $override['action'] = 'editpost';
 
 $uploaded_file = wp_handle_upload($_FILES['document_one'], $override);
 
 $post_id = $post->ID;
 $attachment = array(
 'post_title' => $_FILES['document_one']['name'],
 'post_content' => '',
 'post_type' => 'attachment',
 'post_parent' => $post_id,
 'post_mime_type' => $_FILES['document_one']['type'],
 'guid' => $uploaded_file['url']
 );
 // Save the data
 $id = wp_insert_attachment( $attachment,$_FILES['document_one'][ 'file' ], $post_id );
 wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $_FILES['document_one']['file'] ) );
 
update_post_meta($post->ID, "document_one", $uploaded_file['url']);
 }
 
 if(!empty($_FILES['document_two']['name'])){ //If second document is uploaded 
 require_once( ABSPATH . 'wp-admin/includes/file.php' );
 $override['action'] = 'editpost';
 
 $uploaded_file = wp_handle_upload($_FILES['document_two'], $override);
 
 $post_id = $post->ID;
 $attachment = array(
 'post_title' => $_FILES['document_two']['name'],
 'post_content' => '',
 'post_type' => 'attachment',
 'post_parent' => $post_id,
 'post_mime_type' => $_FILES['document_two']['type'],
 'guid' => $uploaded_file['url']
 );
 // Save the data
 $id = wp_insert_attachment( $attachment,$_FILES['document_two'][ 'file' ], $post_id );
 wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $_FILES['document_two']['file'] ) );
 
update_post_meta($post->ID, "document_two", $uploaded_file['url']);
 }
 
 if(!empty($_FILES['document_three']['name'])){ //If third document is uploaded
 require_once( ABSPATH . 'wp-admin/includes/file.php' );
 $override['action'] = 'editpost';
 
 $uploaded_file = wp_handle_upload($_FILES['document_three'], $override);
 
 $post_id = $post->ID;
 $attachment = array(
 'post_title' => $_FILES['document_three']['name'],
 'post_content' => '',
 'post_type' => 'attachment',
 'post_parent' => $post_id,
 'post_mime_type' => $_FILES['document_three']['type'],
 'guid' => $uploaded_file['url']
 );
 // Save the data
 $id = wp_insert_attachment( $attachment,$_FILES['document_three'][ 'file' ], $post_id );
 wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $_FILES['document_three']['file'] ) );
 
update_post_meta($post->ID, "document_three", $uploaded_file['url']);
 }
 
 if(!empty($_FILES['document_four']['name'])){ //If document four is uploaded
 require_once( ABSPATH . 'wp-admin/includes/file.php' );
 $override['action'] = 'editpost';
 
 $uploaded_file = wp_handle_upload($_FILES['document_four'], $override);
 
 $post_id = $post->ID;
 $attachment = array(
 'post_title' => $_FILES['document_four']['name'],
 'post_content' => '',
 'post_type' => 'attachment',
 'post_parent' => $post_id,
 'post_mime_type' => $_FILES['document_four']['type'],
 'guid' => $uploaded_file['url']
 );
 // Save the data
 $id = wp_insert_attachment( $attachment,$_FILES['document_four'][ 'file' ], $post_id );
 wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $_FILES['document_four']['file'] ) );
 
update_post_meta($post->ID, "document_four", $uploaded_file['url']);
 }
 
 if(!empty($_FILES['equi_image']['name'])){ //If equipment image is loaded
 require_once( ABSPATH . 'wp-admin/includes/file.php' );
 $override['action'] = 'editpost';
 
 $uploaded_files = wp_handle_upload($_FILES['equi_image'], $override);
 
 $post_id = $post->ID;
 $attachment = array(
 'post_title' => $_FILES['equi_image']['name'],
 'post_content' => '',
 'post_type' => 'attachment',
 'post_parent' => $post_id,
 'post_mime_type' => $_FILES['equi_image']['type'],
 'guid' => $uploaded_files['url']
 );
 // Save the data
 $id = wp_insert_attachment( $attachment,$_FILES['equi_image'][ 'file' ], $post_id );
 wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $_FILES['equi_image']['file'] ) );
 
update_post_meta($post->ID, "equi_image", $uploaded_files['url']);
 }
 
  return(esc_attr($mydata));
		return(esc_attr($mydataurl));
		return(esc_attr($mydatadesc));
		return(esc_attr($mydatadate));
		return(esc_attr($mydataeqtitle));
		return(esc_attr($mydataeqdesc));
		return(esc_attr($uploaded_file['url']));
		return(esc_attr($uploaded_files['url']));

	}
	return $post_id;

   //return $mydata;
}


add_action('admin_print_scripts', 'event_javascript');

//function for including datepicker.js  
function event_javascript()
{
  global $post;

  if($post->post_type == 'wi_video')
  {
    wp_enqueue_script('datepicker', WP_CONTENT_URL . '/themes/Anatta-Theme/scripts/datepicker.js', array('jquery'));  
  }
}

//function for includng datepicker.css
add_action('admin_print_styles', 'event_css');

function event_css()
{
  global $post;
  
  if($post->post_type == 'wi_video')
  {
    wp_enqueue_style( 'datepicker', WP_CONTENT_URL . '/themes/Anatta-Theme/css/datepicker.css');
  }
}

//function for getting first post image
function catch_that_image() {
  global $post, $posts;
  $first_img = '';
  ob_start();
  ob_end_clean();
  $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
  $first_img = $matches [1] [0];

  if(empty($first_img)){ //Defines a default image
   // $first_img = "/images/default.jpg";
  }
  return $first_img;
}

//action hook for redirecting the user to different page after logging in
add_action('login_form', 'redirect_after_login');

//function for redirecting the user to different page after logging in
function redirect_after_login() {
	global $redirect_to;
	
	if (!isset($_GET['redirect_to'])) {
	 if(current_user_can('administrator')) {
	  $redirect_to = admin_url(); } 
	  else { $redirect_to = get_option('home')."/client-portal/"; } 
		
	}
}

//function for getting Physician taxonomy values
function the_wi_physician_name($company=false) {
  if (!$company)
    $company = $GLOBALS['post'];
  return get_the_term_list( $company->ID, 'wi_physician_name',null,', ');
}

//function for getting Country taxonomy values
function the_wi_country($company=false) {
  if (!$company)
    $company = $GLOBALS['post'];
  return get_the_term_list( $company->ID, 'wi_country',null,', ');
}

//function for getting Speciality taxonomy values
function the_wi_speciality($company=false) {
  if (!$company)
    $company = $GLOBALS['post'];
  return get_the_term_list( $company->ID, 'wi_speciality',null,', ');
}

//function for getting Medical Product taxonomy values
function the_wi_medical($company=false) {
  if (!$company)
    $company = $GLOBALS['post'];
  return get_the_term_list( $company->ID, 'wi_medical',null,', ');
}

//function for getting Hospital taxonomy values
function the_wi_hospital($company=false) {
  if (!$company)
    $company = $GLOBALS['post'];
  return get_the_term_list( $company->ID, 'wi_hospital',null,', ');
}

//function for changing date format for date picker
function format_date($date)
{
  $months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');

  list($month, $day, $year) = explode("/", $date);

  return $months[$month - 1] . " " . $day . ", " . $year;
}


//add extra fields to custom taxonomy company edit form
function extra_tax_fields($tag) {
   //check for existing taxonomy meta for term ID
    $t_id = $tag->term_id;
    $term_meta = get_option( "taxonomy_$t_id");
?>
<tr class="form-field">
<th scope="row" valign="top"><label for="cat_Image_url"><?php _e('Company Image Url'); ?></label></th>
<td>
<input type="text" name="term_meta[img]" id="term_meta[img]" size="3" style="width:60%;" value="<?php echo $term_meta['img'] ? $term_meta['img'] : ''; ?>"><br />
            <span class="description"><?php _e('Image for Term: use full url with http://'); ?></span>
        </td>
</tr>

<?php

}
//function for adding image url to company taxonomy
function extra_tax_fields_add($tag) { 
//check for existing taxonomy meta for term ID
   // $t_id = $tag->term_id;
    //$term_meta = get_option( "taxonomy_$t_id");
?>
	<div class="form-field">
		<label for="image-url"><?php _e('Image URL') ?></label>
		<input name="term_meta[img]" id="term_meta[img]" type="text" value="" size="40" aria-required="true" />
		<p class="description"><?php _e('This image will be the thumbnail shown on the company page.'); ?></p>
	</div>
<?php } 


//add extra fields to custom taxonomy physician edit form
function extra_tax_fields_physician($tag) {
   //check for existing taxonomy meta for term ID
    $t_id = $tag->term_id;
    $term_meta = get_option( "taxonomy_$t_id");
?>
<tr class="form-field">
<th scope="row" valign="top"><label for="cat_Image_url"><?php _e('Physician Image Url'); ?></label></th>
<td>
<input type="text" name="term_meta[img]" id="term_meta[img]" size="3" style="width:60%;" value="<?php echo $term_meta['img'] ? $term_meta['img'] : ''; ?>"><br />
            <span class="description"><?php _e('Image for Physician: use full url with http://'); ?></span>
        </td>
</tr>

<?php

}
//function for adding image url to physician taxonomy
function extra_tax_fields_add_physician($tag) { 
?>
	<div class="form-field">
		<label for="image-url"><?php _e('Image URL') ?></label>
		<input name="term_meta[img]" id="term_meta[img]" type="text" value="" size="40" aria-required="true" />
		<p class="description"><?php _e('This image will be displayed as physician thumbnail.'); ?></p>
	</div>
<?php } 


// save extra taxonomy fields for company/physician taxonomy
function save_extra_taxonomy_fields( $term_id ) {
    if ( isset( $_POST['term_meta'] ) ) {
        $t_id = $term_id;
        $term_meta = get_option( "taxonomy_$t_id");
        $cat_keys = array_keys($_POST['term_meta']);
            foreach ($cat_keys as $key){
            if (isset($_POST['term_meta'][$key])){
                $term_meta[$key] = $_POST['term_meta'][$key];
            }
        }
        //save the option array
        update_option( "taxonomy_$t_id", $term_meta );
    }
}
 
add_action( 'company_add_form_fields', 'extra_tax_fields_add', 10, 2); //action hook for showing url field for company while adding new company

add_action( 'company_edit_form_fields', 'extra_tax_fields', 10, 2); //action hook for showing url field for company while editing new company

add_action( 'edited_company', 'save_extra_taxonomy_fields', 10, 2); //action hook for saving value for url while editing company taxonomy 

add_action( 'created_company', 'save_extra_taxonomy_fields', 10, 2); //action hook for saving value for url while adding new company taxonomy

add_action( 'wi_physician_name_add_form_fields', 'extra_tax_fields_add_physician', 10, 2); //action hook for showing url field for physician while adding new physician

add_action( 'wi_physician_name_edit_form_fields', 'extra_tax_fields_physician', 10, 2); //action hook for showing url field for physician while editing new physician

add_action( 'edited_wi_physician_name', 'save_extra_taxonomy_fields', 10, 2); //action hook for saving value for url while editing  physician taxonomy

add_action( 'created_wi_physician_name', 'save_extra_taxonomy_fields', 10, 2); //action hook for saving value for url while adding new physician taxonomy

//function for adding login /logout link

function add_login_logout_link()
{
 
   $redirect = get_option('home')."/client-portal/";  
     if(is_user_logged_in())
  {
    $newitems = '<ul><li><a title="Logout" href="'. wp_logout_url($redirect) .'">Logout</a></li></ul>';
  }
  else
  {
    //$newitems = $items;
    $newitems = '<ul><li><a title="Login" href="'. wp_login_url($redirect) .'">Login</a></li></ul>';
  }
 
  return $newitems;
}


 
 /*This is the function that displays the custom login screen.*/
function custom_admin_branding_login() 
   { 
    $com_logo = explode('com=',$_SERVER['REQUEST_URI']);
	//print_r($com_logo);
	if($com_logo[1] == 'mc')
	{
	$login_logo = get_option('home')."/medworldproject/images/monsoon.jpg";
	}
	else if($com_logo[1] == 'st')
	{
	$login_logo = get_option('home')."/medworldproject/wp-content/themes/Anatta-Theme/images/stryker.jpg";
	}
	else
	{
	$login_logo = get_option('home')."/medworldproject/images/med_logo.png";
	}
	$lost_password_color ="#FFFFFF";
	$lost_password_hover_color = "#FFFFFF";
echo '
<style>
/* Diplays the custom graphics for the login screen*/


#loginform {
    background: none repeat scroll 0 0 #314960;
}

.login h1 a { 
	background: url(' . $login_logo . ') center top no-repeat;
	 height: 52px;
    padding: 0 0 5px 10px;
    width: 278px;
}

.login #nav a {
	color:'.$lost_password_color.' !important;
}
	
.login #nav a:hover {
	color:'.$lost_password_hover_color.' !important;
}

</style>
	';
}

add_action('login_head', 'custom_admin_branding_login');

//function for changing url
function fb_login_headerurl() {
 $url = bloginfo('url');
 echo $url;
   }
 add_filter( 'login_headerurl', 'fb_login_headerurl' );
 
 
 //function for displaying categories in a drop down and adding onchange event to that
 function altrugon_dropdown_categories($args = '') {
	if ( is_array($args) )
		$r = &$args;
		
	else
		parse_str($args, $r);
		

	$defaults = array('show_option_all' => '', 'show_option_none' => '', 'orderby' => 'ID',
		'order' => 'ASC', 'show_last_update' => 0, 'show_count' => 0,
		'hide_empty' => 1, 'child_of' => 0, 'exclude' => '', 'echo' => 1,
		'selected' => 0, 'hierarchical' => 0, 'name' => 'cat',
		'class' => 'postform', 'form' => 0, 'onchange' => 0);
	$defaults['selected'] = ( is_category() ) ? get_query_var('cat') : 0;
	$r = array_merge($defaults, $r);
	$r['include_last_update_time'] = $r['show_last_update'];
	extract($r);

	$categories = get_categories($r);

	$output = '';
	if ( ! empty($categories) ) {
		// this line is causing problems with blogs using Apache Rewrite rules
		if ($r['form'] == 1){
			//$output .= '<form action="'.$PHP_SELF.'" method="get">';
			$output .= '<form action="'.get_bloginfo("url").'/index.php" method="get">';
		}

		
			//$output .= "<select name='$name' id='$id' class='$class'  onchange='filterby_page(this.value);'>\n";
			
			$output .= "<select name='$name' id='$id' class='$class'  >\n";
		

		if ( $show_option_all ) {
			$show_option_all = apply_filters('list_cats', $show_option_all);
			$output .= "\t<option value='0'>$show_option_all</option>\n";
		}

		if ( $show_option_none) {
			$show_option_none = apply_filters('list_cats', $show_option_none);
			$output .= "\t<option value='-1'>$show_option_none</option>\n";
		}

		if ( $hierarchical )
			$depth = 0;  // Walk the full depth.
		else
			$depth = -1; // Flat.

		$output .= walk_category_dropdown_tree($categories, $depth, $r);
		$output .= "</select>\n";
		if ($r['form'] == 1){
			$output .= "</form>\n";
		}
	}

	$output = apply_filters('wp_dropdown_cats', $output);

	if ( $echo )
		echo $output;

	return $output;
}

//checking for whether the current user is admin or not
if ( !current_user_can( 'administrator' ) )
{

// disable the admin bar
show_admin_bar(false);
}

//Search a Specific Post Type
function SearchFilter($query) {
  if ($query->is_search) {
    // Insert the specific post type you want to search
    $query->set('post_type', 'wi_video');
  }
  return $query;
}
 
// This filter will jump into the loop and arrange our results before they're returned
add_filter('pre_get_posts','SearchFilter');

// Enqueue our script to be added into the page first
add_action( 'init', 'ajax_scripts' );

function ajax_scripts() {
	
	wp_enqueue_script( 'ajax_function', get_bloginfo('template_directory') .'/scripts/ajax.js', array( 'jquery' ) );
	
	// Now we can attach some variables which we want to send along with this script. These values can be picked up in the JavaScript code.
	
	$protocol = isset( $_SERVER['HTTPS'] ) ? 'https://' : 'http://';
	
	$ajax_data = array(
		'ajax_url' => admin_url( 'admin-ajax.php', $protocol ),
		'additional_data' => 'value'
	);
	
	wp_localize_script( 'ajax_function', 'ajax_data', $ajax_data );
	
}

// Serve AJAX calls only for logged in users
add_action( 'wp_ajax_portal_listing', 'portal_listing_handler' );

function portal_listing_handler() {
	// Output the content here, put your existing code here
global $current_user;
get_currentuserinfo();	
	
$physician = $_REQUEST['physician'];
$country = $_REQUEST['country'];
$speciality = $_REQUEST['speciality'];
$medical = $_REQUEST['medical'];
$hospital = $_REQUEST['hospital'];
$paged = $_REQUEST['page'];

if($physician == '0') //getting query string for physician
{
	$physician_name = '';
}
else
{
	$termp = get_term( $physician, 'wi_physician_name' );
	$physician_name = '&wi_physician_name='.$termp->name;
}

if($country == '0') //getting query string for country
{
	$country_name = '';
}
else
{
	$termc = get_term( $country, 'wi_country' );
	$country_name = '&wi_country='.$termc->name;
}

if($speciality == '0') //getting query string for speciality
{
	$speciality_name = '' ;
}
else
{
	$term = get_term( $speciality, 'wi_speciality' );
	$speciality_name = '&wi_speciality='.$term->name ;
}

if($medical == '0') //getting query string for medical
{
	$medical_name = '';
}
else
{
	$termm = get_term( $medical, 'wi_medical' );
	$medical_name = '&wi_medical='.$termm->name;
}
 
if($hospital == '0') //getting query string for hospital
{
	$hospital_name = '';
}
else
{
	$termh = get_term( $hospital, 'wi_hospital' );
	$hospital_name = '&wi_hospital='.$termh->name;
}

?>
<?php 
//echo 'post_type=wi_video&company="'.$current_user->companyname.'"'.$physician_name.'"'.$country_name.'"'.$speciality_name.'"'.$medical_name.'"'.$hospital_name.'"&paged='.$paged;
 //$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
query_posts('post_type=wi_video&company="'.$current_user->companyname.'"'.$physician_name.'"'.$country_name.'"'.$speciality_name.'"'.$medical_name.'"'.$hospital_name.'"&paged='.$paged);
if (have_posts()) : while (have_posts()) : the_post(); 
global $post;
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
 <?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); } //pagination function?>
<?php else: echo "No result"; endif; wp_reset_query(); 


	
	// Die immediately
	exit();
	}?>