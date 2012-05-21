<?php
/*
 Plugin Name: Apptivo Business site Plugin
 Plugin URI: http://www.apptivo.com/apptivo-business-site-wordpress-plug-in/
 Description: Apptivo Business Site plugin provides News , Events , Testimonials, Jobs, Contact Forms and Newsletter sub plugins with <a href="http://www.apptivo.com" target="_blank">Apptivo ERP</a>.
 Version: 1.0.1
 Author: Rajkumar Mohanasundaram (rmohanasundaram@apptivo.com) 
 Author URI: http://www.apptivo.com/
 */
if (!session_id()) session_start();
 if (!defined('AWP_PLUGIN_BASEPATH')) {
 	define('AWP_PLUGIN_BASEPATH',plugin_dir_path(__FILE__));
 	define('AWP_PLUGIN_BASEURL',plugins_url(basename( dirname(__FILE__))));
 	//plugins_url(basename(dirname(__FILE__)))
    /**
     * Require plugin configuration
     */
    require_once dirname(__FILE__) . '/inc/define.php';
    require_once dirname(__FILE__) . '/inc/dummy-config.php';
    
    /**
     * Load plugins
     */
    awp_load_plugins();
    
    /**
     * Load Widgets
     */
    awp_load_widgets();

    /**
     * Run plugin
     */
add_action( 'admin_enqueue_scripts', 'apptivo_business_enqueue' );

function apptivo_business_enqueue($hook) {
	switch ($hook) {
        	case 'apptivo_page_awp_news':
        	case 'apptivo_page_awp_events':
        	case 'apptivo_page_awp_testimonials':
        	case 'apptivo_page_awp_jobs':
        	case 'apptivo_page_awp_contactforms':
        	case 'apptivo_page_awp_newsletter':
        	case 'apptivo_page_awp_cases':	
            	add_filter("mce_buttons", "remove_mce_buttons");
            	add_filter( 'wp_default_editor', create_function('', 'return "tinymce";') );  	           
                break;  
        }
}

 /**
 * Shortcode button in post editor
 **/
add_action( 'init', 'apptivo_businesssite_add_shortcode_button' );

add_filter('widget_text', 'do_shortcode_apptivo');
function do_shortcode_apptivo($content)
{
   global $shortcode_tags;
   if (empty($shortcode_tags) || !is_array($shortcode_tags))
   {
   	return $content;
   }

    $pattern = get_shortcode_regex();    
    return preg_replace_callback( "/$pattern/s", 'do_shortcode_tag', $content );
	
}

function apptivo_businesssite_add_shortcode_button() {
	if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) return;
	if ( get_user_option('rich_editing') == 'true') :
		add_filter('mce_external_plugins', 'apptivo_businesssite_add_shortcode_tinymce_plugin');
		add_filter('mce_buttons', 'apptivo_businesssite_register_shortcode_button');
	endif;
}

function apptivo_businesssite_register_shortcode_button($buttons) {
	array_push($buttons, "|", "apptivo-businesssite_shortcodes_button");
	return $buttons;
}

function apptivo_businesssite_add_shortcode_tinymce_plugin($plugin_array) {
	$plugin_array['ApptivoBusinesssiteShortcodes'] = AWP_PLUGIN_BASEURL . '/assets/js/editor_plugin.js';
	return $plugin_array;
}


function remove_mce_buttons($buttons) {
echo '<script type="text/javascript" language="javascript" >jQuery(document).ready(function(){	 
 jQuery("#editor-toolbar").remove(); 
 jQuery("#quicktags #ed_toolbar").remove(); 
});</script>';
	unset($buttons[17]);
	array_push($buttons,'code');
    return $buttons;

}   
	require_once AWP_PLUGINS_DIR . '/messages.php'; 
    require_once AWP_PLUGINS_DIR . '/AWPMainController.php';
    $awp_maincontroller = & AWP_MainController::instance();
    $awp_maincontroller->run();
}
function apptivo_business_scripts() {
wp_enqueue_script('media-upload');
wp_enqueue_script('thickbox');
}

function apptivo_business_styles() {
wp_enqueue_style('thickbox');
}


if ( is_admin() ) {
	add_action('admin_print_scripts', 'apptivo_business_scripts');
	add_action('admin_print_styles', 'apptivo_business_styles');
	
}

/**
 * Create auto Pages ( News, Events, Testimonials and Cobtactus ) Nedd to place theme's function.php [ do_action( 'absp_autopages'); ]
 *
 */
function absp_autopages()
{
	/*apptivo Plugin settings*/
    $general_plugins_settings=get_option("awp_plugins");

    if($general_plugins_settings == '' || empty($general_plugins_settings)) {
    	    $plugins = array('contactforms'=>true,'testimonials'=>true,'events'=> true,'news'=>true);
	    	update_option("awp_plugins", $plugins);
	}
	    
	/*Contact Forms*/
     $contact_form_options = get_option('awp_contactforms');
     if($contact_form_options == '' || empty($contact_form_options)) {
	 $config_contactforms = array('0'=>array('name'=>'contact_form',
                                 'properties'=>array('tmpltype'=>'awp_plugin_template','layout'=>'single-column-layout2.php','confirm_msg_page'=>'same','submit_button_type'=>'submit'),
                                 'fields'=>array('0'=>array('fieldid'=>'lastname','showtext'=>'Last Name','required'=>1,'type'=>'text','validation'=>'none','order'=>2),
                                                 '1'=>array('fieldid'=>'email','showtext'=>'Email','required'=>1,'type'=>'text','validation'=>'text','order'=>3))
                                 ));                                 
	 update_option("awp_contactforms", $config_contactforms);

    $contactus_pageid = get_option('awp_contactus_pageid');
	if($contactus_pageid == '' || empty($contactus_pageid)) 
	{
	$page_content = '[apptivocontactform name="contact_form"]';
	 $page_data = array(
        'post_status' => 'publish',
        'post_type' => 'page',
        'post_author' => 1,
        'post_name' => 'contact',
        'post_title' => 'Contact',
        'post_content' => $page_content,
        'post_parent' => '',
        'comment_status' => 'closed'
    );
    $page_id = wp_insert_post($page_data);
    update_option("awp_contactus_pageid", $page_id);   
	}
	
	 }
	
	
	/*Testimonials*/
	 $testimonials_options = get_option('awp_testimonials_settings');
	  if($testimonials_options == '' || empty($testimonials_options)) {
	   $config_testimonials = array('template_type'=>'awp_plugin_template','template_layout'=>'default-testimonials.php','order'=>1,'itemsperpage'=>5);
	   update_option("awp_testimonials_settings", $config_testimonials);
	   
	  $testimonials_pageid = get_option('awp_testimonials_pageid');
	if($testimonials_pageid == '' || empty($testimonials_pageid)) 
	{ 
	 $page_content = '[apptivo_testimonials_fullview]';
	 $page_data = array(
        'post_status' => 'publish',
        'post_type' => 'page',
        'post_author' => 1,
        'post_name' => 'testimonials',
        'post_title' => 'Testimonials',
        'post_content' => $page_content,
        'post_parent' => '',
        'comment_status' => 'closed'
    );
    $page_id = wp_insert_post($page_data);
    update_option("awp_testimonials_pageid", $page_id);      
	}
	
	
	  }
	
	/*News*/
   $news_options = get_option('awp_news_settings');
	  if($news_options == '' || empty($news_options)) {
	   $config_news =array('template_type'=>'awp_plugin_template','template_layout'=>'default-news.php','order'=>1,'itemsperpage'=>5);
	   update_option("awp_news_settings", $config_news);
	   
	  $news_pageid = get_option('awp_news_pageid');
	if($news_pageid == '' || empty($news_pageid)) 
	{ 
	 $page_content = '[apptivo_news_fullview]';
	 $page_data = array(
        'post_status' => 'publish',
        'post_type' => 'page',
        'post_author' => 1,
        'post_name' => 'news',
        'post_title' => 'News',
        'post_content' => $page_content,
        'post_parent' => '',
        'comment_status' => 'closed'
    );
    $page_id = wp_insert_post($page_data);
    update_option("awp_news_pageid", $page_id);      
	}
	
	  }
	
 
	
	/*Events*/	
	$events_options = get_option('awp_events_settings');
	  if($events_options == '' || empty($events_options)) {
	   $config_events =array('template_type'=>'awp_plugin_template','template_layout'=>'default-events.php','order'=>1,'itemsperpage'=>5);
	   update_option("awp_events_settings", $config_events);
	   
	   $events_pageid = get_option('awp_events_pageid');
	if($events_pageid == '' || empty($events_pageid)) 
	{ 
	 $page_content = '[apptivo_events_fullview]';
	 $page_data = array(
        'post_status' => 'publish',
        'post_type' => 'page',
        'post_author' => 1,
        'post_name' => 'events',
        'post_title' => 'Events',
        'post_content' => $page_content,
        'post_parent' => '',
        'comment_status' => 'closed'
    );
    $page_id = wp_insert_post($page_data);
    update_option("awp_events_pageid", $page_id);      
	}	
	
	  }
	
             
} 
add_action( 'absp_autopages', 'absp_autopages', 10, 2 );
//Powered By Apptivo.
add_action('wp_footer','powered_apptivo_status');
function powered_apptivo_status()
{
	$status = get_option('apptivo_poweredby_status');
	if( $status != '' && $status != 'dont_show') :	
	if($status == 'show_homepage') :
        if(is_front_page()) :	
  		  $apptivo_logo = poweredby_apptivo();
  		endif;
  	else:
  		  $apptivo_logo = poweredby_apptivo();
  	endif;	
  	echo '<div class="poweredbyapptivo" style="text-align:center;" >'.$apptivo_logo.'</div>';
  	endif;
}
//Contact Form Submit.
add_action('wp_ajax_apptivo_business_contactus', 'apptivo_business_contactus_lead');
add_action('wp_ajax_nopriv_apptivo_business_contactus', 'apptivo_business_contactus_lead');
function apptivo_business_contactus_lead(){	
    $formname = $_POST['awp_contactformname'];
    $contact_form = &new AWP_ContactForms();
    $contact_formlead = $contact_form->save_contact($formname,true);
    echo $contact_formlead;exit;		
}

add_action('wp_ajax_apptivo_business_captcha_refresh', 'apptivo_business_captcha_refresh');
add_action('wp_ajax_nopriv_apptivo_business_captcha_refresh', 'apptivo_business_captcha_refresh');

function apptivo_business_captcha_refresh()
{
	if (!session_id()) session_start();
	$possible_letters = '23456789bcdfghjkmnpqrstvwxyz';
	$characters_on_image = 6;
	$code = '';
	$i = 0;
	while ($i < $characters_on_image) { 
	$code .= substr($possible_letters, mt_rand(0, strlen($possible_letters)-1), 1);
	$i++;
	}
	$_SESSION['apptivo_business_captcha_code'] = $code;
    $image_src = AWP_PLUGIN_BASEURL.'/assets/captcha/captcha_code_file.php?captcha_code='.$code;
	echo '<img style="border: 1px solid rgb(0, 0, 0);" id="captchaimg" src="'.$image_src.'">';
	exit;
}
//Newsletter Form Submit.
add_action('wp_ajax_apptivo_business_newsletter', 'apptivo_business_newsletter_target');
add_action('wp_ajax_nopriv_apptivo_business_newsletter', 'apptivo_business_newsletter_target');
function apptivo_business_newsletter_target(){	
    $formname = $_POST['awp_newsletterformname'];
    $newsletter_form = &new AWP_Newsletter();
    $newsletter_subscribe = $newsletter_form->save_newsletter($formname);
    echo $newsletter_subscribe;exit;		
}
//Jobs Doc ID
add_action('wp_ajax_apptivo_business_jobs_docid', 'apptivo_business_jobs_docid');
add_action('wp_ajax_nopriv_apptivo_business_jobs_docid', 'apptivo_business_jobs_docid');
function apptivo_business_jobs_docid()
{
	$key  = $_POST['dockey'];
	$size = $_POST['docsize'];
	$name = $_POST['docname'];
	$type = $_POST['doctype'];	
	$params = array ( "arg0" => APPTIVO_SITE_KEY,"arg1" => null,
	                  "arg2" => $type,"arg3" => $key,"arg4" => $size,
	                  "arg5" => null,"arg6" => null,"arg7"=> null,"arg8"=>$name);
	 $response = getsoapCall(APPTIVO_SITE_SERVICES,'saveDocument',$params);
	 echo $response->return;
	 die();
}
?>
