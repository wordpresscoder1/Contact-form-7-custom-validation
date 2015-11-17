<?php
/*
Plugin Name: CONTACT FORM7 CUSTOM VALIDATION
Plugin URI: http://example.com
Description: Easily select a CSS "Ready Class" for your fields within Gravity Forms
Version: 1.0
Author: Aiyaz Khorajia	
Author URI: http://example.com
License: GPL2
*/

function action_cf7cv_save_contact_form( $contact_form ) 
{
 
	$tags = $contact_form->form_scan_shortcode();  
	foreach ($tags as $value) {

		$key = "_cf7cm_".$value['name']."-valid";
		$vals = sanitize_text_field($_POST[$key]);
		update_post_meta($_GET['post'],$key, $value['name']);  

	}  
}
add_action( 'wpcf7_save_contact_form', 'action_cf7cv_save_contact_form', 10, 1 );


function get_meta_values($p_id='', $key = '') {

    global $wpdb;
    if( empty( $key ) )
        return;
		
    $r = $wpdb->get_results( "SELECT pm.meta_value FROM {$wpdb->postmeta} pm WHERE pm.meta_key LIKE '%$key%' AND pm.post_id = $p_id ");

    return $r;
}


function cf7cv_custom_validation_messages( $messages ) {
     
	$p_id = $_GET['post'];		
	if(isset($p_id) && !empty($_GET['post'])){
		$p_val=get_meta_values($p_id, '_cf7cm');
		
		foreach ($p_val as $value) {
		  $key = $value->meta_value;
		  $newmsg = array(
		   'description' => __( "Please Enter $value->meta_value", 'contact-form-7' ),
		   'default' => __( 'Please Required Field.', 'contact-form-7' ));
		   
		   $messages[$key] = $newmsg ;
		}
		
	}
	
    return $messages;
  }
add_filter( 'wpcf7_messages', 'cf7cv_custom_validation_messages' );


  
  
function cf7cv_custom_form_validation($result,$tag) { 
	$type = $tag['type'];
	$name = $tag['name'];

	if($type == 'text*' && $_POST[$name] == ''){   
		$result->invalidate( $name, wpcf7_get_message( $name ) );
	}
	if($type == 'email*' && $_POST[$name] == ''){   
		$result->invalidate( $name, wpcf7_get_message( $name ) );		
	}
	if($type == 'email*' && $_POST[$name] != '') {
      if(substr($_POST[$name], 0, 1) == '.' ||
	  !preg_match('/^([*+!.&#$¦\'\\%\/0-9a-z^_`{}=?~:-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,4})$/i', $_POST[$name])) {  
         $result->invalidate( $name, wpcf7_get_message($name) );
		} 
    }
	if($type == 'textarea*' && $_POST[$name] == ''){   
		$result->invalidate( $name, wpcf7_get_message( $name ) );
	}
	if($type == 'tel*' && $_POST[$name] == ''){   
		$result->invalidate( $name, wpcf7_get_message( $name ) );
	}
	if($type == 'url*' && $_POST[$name] == ''){   
		$result->invalidate( $name, wpcf7_get_message( $name ) );
	}
	if($type == 'checkbox*' && $_POST[$name] == ''){   
		$result->invalidate( $name, wpcf7_get_message( $name ) );
	}
 
 return $result;
} 
//add filter for text field validation
//add_filter('wpcf7_validate_text','cf7cv_custom_form_validation', 10, 2); // text field
add_filter('wpcf7_validate_text*', 'cf7cv_custom_form_validation', 10, 2); // Req. text field  
//add_filter('wpcf7_validate_email','cf7cv_custom_form_validation', 10, 2); // email field
add_filter('wpcf7_validate_email*', 'cf7cv_custom_form_validation', 10, 2); // Req. email field  
//add_filter('wpcf7_validate_textarea','cf7cv_custom_form_validation', 10, 2); // textarea field
add_filter('wpcf7_validate_textarea*', 'cf7cv_custom_form_validation', 10, 2); // Req. textarea field  
//add_filter('wpcf7_validate_tel','cf7cv_custom_form_validation', 10, 2); // telephone field
add_filter('wpcf7_validate_tel*', 'cf7cv_custom_form_validation', 10, 2); // Req. telephone field  
//add_filter('wpcf7_validate_url','cf7cv_custom_form_validation', 10, 2); // URL field
add_filter('wpcf7_validate_url*', 'cf7cv_custom_form_validation', 10, 2); // URL. telephone field  
//add_filter('wpcf7_validate_checkbox','cf7cv_custom_form_validation', 10, 2); // checkbox field
add_filter('wpcf7_validate_checkbox*', 'cf7cv_custom_form_validation', 10, 2); // checkbox. telephone field  



?>