<?php
/*
 Plugin Name: Apptivo Business site Plugin
 Plugin URI:  http://www.apptivo.com/apptivo-business-site-wordpress-plug-in/
 Description: Apptivo Business Site plugin provides News , Events , Testimonials, Jobs, Contact Forms and Newsletter sub plugins with <a href="http://www.apptivo.com" target="_blank">Apptivo ERP</a>.
 Version: 0.5
 Author: Rajkumar Mohanasundaram (rmohanasundaram@apptivo.com) 
 Author URI: http://www.apptivo.com/
 */

 if (!defined('AWP_PLUGIN_BASEPATH')) {
 	define('AWP_PLUGIN_BASEPATH',plugin_dir_path(__FILE__));
 	define('AWP_PLUGIN_BASEURL',plugins_url(basename(__DIR__)));
 	//plugins_url(basename(dirname(__FILE__)))
    /**
     * Require plugin configuration
     */
    require_once dirname(__FILE__) . '/inc/define.php';
    
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
?>
