<?php
/**
 * Template Name: Edit Profile
 *
 * Allow users to update their profiles from Frontend.
 *
 */

/* Get user info. */
global $current_user, $wp_roles;
get_currentuserinfo();

/* Load the registration file. */
require_once( ABSPATH . WPINC . '/registration.php' );

/* If profile was saved, update profile. */
if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'update-user' ) {

    /* Update user password. */
    if ( !empty($_POST['pass1'] ) && !empty( $_POST['pass2'] ) ) {
        if ( $_POST['pass1'] == $_POST['pass2'] )
		{ 
            wp_update_user( array( 'ID' => $current_user->id, 'user_pass' => esc_attr( $_POST['pass1'] ) ) );
			$msg = __('Your Password has been updated', 'profile');
			}
        else
            $error = __('The passwords you entered do not match.  Your password was not updated.', 'profile');
    }
	else {
	$error = __('Please enter some value.', 'profile');
	}
   
}
?>
<?php get_header(); ?>

<div id="content" class="home-page" >
<?php require_once (TEMPLATEPATH . '/sidebar.php'); //including left sidebar?>
<!--right column-->
<div class="right-column">
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <div id="post-<?php the_ID(); ?>">
            <div class="entry-content entry">
               <h1 class="page-title">Edit My Profile</h1>
                <?php if ( !is_user_logged_in() ) : ?>
                        <p class="warning">
                            <?php _e('You must be logged in to edit your profile.', 'profile'); ?>
                        </p><!-- .warning -->
                <?php else : ?>
                    <?php if ( $error ) echo '<p class="error">' . $error . '</p>'; ?>
                    <?php if ( !empty($msg) ) echo '<p class="error">' . $msg . '</p>'; ?>
                    <form method="post" id="adduser" action="<?php the_permalink(); ?>">
                       <p class="form-password">
                            <label for="pass1"><?php _e('Current Password: ', 'profile'); ?> </label>
                            <input class="text-input" name="pass" type="password" id="pass" />
                        </p><!-- .form-password -->
                        <p class="form-password">
                            <label for="pass1"><?php _e('New Password: ', 'profile'); ?> </label>
                            <input class="text-input" name="pass1" type="password" id="pass1" />
                        </p><!-- .form-password -->
                        <p class="form-password">
                            <label for="pass2"><?php _e('Repeat Password: ', 'profile'); ?></label>
                            <input class="text-input" name="pass2" type="password" id="pass2" />
                        </p><!-- .form-password -->
                       
                        <p class="form-submit">
                            <?php echo $referer; ?>
                            <input name="updateuser" type="submit" id="updateuser" class="submit button" value="<?php _e('Update', 'profile'); ?>" />
                            <?php wp_nonce_field( 'update-user' ) ?>
                            <input name="action" type="hidden" id="action" value="update-user" />
                        </p><!-- .form-submit -->
                    </form><!-- #adduser -->
                    <?php endif; ?>
                </div><!-- .entry-content -->
            </div><!-- .hentry .post -->
            
            <?php endwhile; ?>
      
        <?php endif; ?>
</div>	
<!--/right column-->
<div class="clear"></div>
<?php get_footer(); ?>
