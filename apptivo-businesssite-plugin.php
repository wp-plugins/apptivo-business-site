<?php
/*
 Plugin Name: Apptivo Business site Plugin
 Plugin URI: http://www.apptivo.com/apptivo-business-site-wordpress-plug-in/
 Description: Apptivo Business Site plugin provides News , Events , Testimonials, Jobs, Contact Forms and Newsletter sub plugins with <a href="http://www.apptivo.com" target="_blank">Apptivo ERP</a>.
 Version: 0.7.2
 Author: Rajkumar Mohanasundaram (rmohanasundaram@apptivo.com) 
 Author URI: http://www.apptivo.com/
 */

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
add_action( 'admin_enqueue_scripts', 'my_enqueue' );

function my_enqueue($hook) {
	switch ($hook) {
        	case 'apptivo_page_awp_news':
        	case 'apptivo_page_awp_events':
        	case 'apptivo_page_awp_testimonials':
        	case 'apptivo_page_awp_jobs':
        	case 'apptivo_page_awp_contactforms':
        	case 'apptivo_page_awp_newsletter':
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

    $contact_form_pos = strpos($content, '[apptivocontactform');
    $newsletter_form_pos = strpos($content, '[apptivonewsletterform name=');
    $pattern = get_shortcode_regex();    
     
	if ($contact_form_pos !== false || $newsletter_form_pos !== false) {
	   return preg_replace_callback( "/$pattern/s", 'do_shortcode_tag', $content );
	} else {
	   return $content;
	}
	
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
function my_admin_scripts() {
wp_enqueue_script('media-upload');
wp_enqueue_script('thickbox');
}

function my_admin_styles() {
wp_enqueue_style('thickbox');
}


if ( is_admin() ) {
	if ( $_GET['page'] == 'awp_events' || $_GET['page'] == 'awp_testimonials' || $_GET['page'] == 'awp_contactforms' || $_GET['page'] == 'awp_jobs' || $_GET['page'] == 'awp_newsletter' || $_GET['page'] == 'awp_news' ) {
	add_action('admin_print_scripts', 'my_admin_scripts');
	add_action('admin_print_styles', 'my_admin_styles');
	}
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
?>