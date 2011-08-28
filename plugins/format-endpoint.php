<?php
/*
Plugin Name: Format endpoint
Plugin URI: http://www.ashfame.com/
Description: Adds /format/xml/ and /format/json/ at the end of the post/page url to fetch its content
Author: Ashfame
Version: 0.1
Author URI: http://www.ashfame.com/
*/

/**
 * Add rewrite rule and flush the rules on activation
 */
register_activation_hook( __FILE__, 'ashfame_fep_activate' );

function ashfame_fep_activate() {
	ashfame_fep_add_rules();
	flush_rewrite_rules();
}

/**
 * Flush the rewrite rules so as to remove the rewrite rule on deactivation
 */
register_activation_hook( __FILE__, 'ashfame_fep_deactivate' );

function ashfame_fep_deactivate() {
	flush_rewrite_rules();
}

/**
 * Add the endpoint rewrite rules
 */
add_filter( 'init', 'ashfame_fep_add_rules' );

function ashfame_fep_add_rules() {
	add_rewrite_endpoint( 'format', EP_ALL ); // EP_ALL - for all pages
}

/**
 * Handle the custom format display
 */
add_filter( 'template_redirect', 'ashfame_fep_template_redirect' );

function ashfame_fep_template_redirect() {
	global $post;
	
	if ( ! is_singular() )
		return;
	
	$format = get_query_var( 'format' );
	
	switch( $format ) {
		
		case 'json':
			ashfame_fep_display_json();
			exit();			
		
		case 'xml':
			ashfame_fep_display_xml();
			exit();
	}
	
	// Prevent Duplicate content by redirecting invalid endpoints of the post/page back to post, Good for SEO
	
	// if format is not something we are going to handle, redirect back to post
	if ( $format != '' )
		wp_redirect( get_permalink( $post->ID ) );
	
	// if url is something like just /format/ then redirect back
	if ( $format == '' ) {
		$url_pieces = explode( '/', $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
		if ( $url_pieces[ count( $url_pieces ) - 2 ] == 'format' ) // count - 1 is the last element and because of trailing slash we need second last word in the array
			wp_redirect( get_permalink( $post->ID ) );
	}
}

/**
 * Display the post information in JSON format
 */
function ashfame_fep_display_json() {
	global $wpdb, $post;
	
	$post_data = (array) $post;
	$postmeta_data = array();
	$postrel_data = array();
	
	$wpdb->query( " SELECT `meta_key` , `meta_value` FROM $wpdb->postmeta WHERE `post_id` = $post->ID " );
	
    foreach ( $wpdb->last_result as $k => $v ) {
        // remove the meta keys that begin with underscore
        if ( $v->meta_key[0] == '_' )
        	continue;

		$postmeta_data[ $v->meta_key ] = maybe_unserialize( $v->meta_value );
    };
    
	//query for getting postids for related posts from post relationship table
	$wpdb->query( "SELECT  pr.`post2_id` FROM `aNaTTa_post_relationships` pr WHERE  pr.`post1_id` = $post->ID " );
	
	
    foreach ( $wpdb->last_result as $kr => $vr ) {
	$wpdb->query( "SELECT  p.`ID`, p.`post_title`, p.`post_name` FROM `$wpdb->posts` p WHERE  p.`ID` = $vr->post2_id " ); // query for getting post details for the related posts
	foreach($wpdb->last_result as $ks => $vs)
	{
      
		$postrel_data[ $vs->ID ] =  $vs->post_title ;
		$postrel_data[ $vs->post_name ] = get_option('home')."/". $vs->post_name ;
		}
		
    };
	
    $data = array_merge( $post_data, $postmeta_data, $postrel_data );
	
	header('Content-type: application/json');
    echo json_encode( $data )."\n";
	exit();
}

/**
 * Display the post information in XML format
 */
function ashfame_fep_display_xml() {
	global $wpdb, $post;
	
	$post_data = (array) $post;
	$postmeta_data = array();
         
	$wpdb->query( " SELECT `meta_key` , `meta_value` FROM $wpdb->postmeta WHERE `post_id` = $post->ID " );
    
    foreach ( $wpdb->last_result as $k => $v ) {
        // remove the meta keys that begin with underscore
        if ( $v->meta_key[0] == '_' )
        	continue;

		$postmeta_data[ $v->meta_key ] = maybe_unserialize( $v->meta_value );
		
    };
    
    $data = array_merge( $post_data, $postmeta_data );
    //echo "<pre>";print_r($data); echo "</pre>";

	
    $xml = new XmlWriter();
	$xml->openMemory();
	$xml->startDocument( '1.0', 'UTF-8' );
	$xml->startElement( 'root' );

	function xml_write( XMLWriter $xml, $data ) {
		foreach ( $data as $key => $value ) {
		    if ( is_array( $value ) ) {
		        $xml->startElement( $key );
		        xml_write( $xml, $value );
		        $xml->endElement();
		        continue;
		    }
		    $xml->writeElement( $key, $value );
		}
	}
	xml_write( $xml, $data );

	$xml->endElement();
	
	echo $xml->outputMemory( true );
    exit();
}
