<?php
//Default values
define('AWP_DEFAULT_ITEM_SHOW',5);
define('AWP_DEFAULT_MORE_TEXT','More..');

//Disable Plugins
//define('AWP_CONTACTFORM_DISABLE',1);
//define('AWP_NEWSLETTER_DISABLE',1);
//define('AWP_NEWS_DISABLE',1);
//define('AWP_EVENTS_DISABLE',1);
//define('AWP_TESTIMONIALS_DISABLE',1);
//define('AWP_JOBS_DISABLE',1);

/* 
 User updateable define statements ends here..
 Changing define statements below will make plugin to not work properly.
 * */
//Plugin Version
define('AWP_VERSION', '0.5');

//Plugin folders
define('AWP_LIB_DIR', AWP_PLUGIN_BASEPATH . '/lib');
define('AWP_INC_DIR', AWP_PLUGIN_BASEPATH . '/inc');
define('AWP_PLUGINS_DIR', AWP_LIB_DIR . '/Plugin');
define('AWP_WIDGETS_DIR', AWP_LIB_DIR . '/widgets');

//plugin template folder
define('AWP_CONTACTFORM_TEMPLATEPATH',AWP_INC_DIR.'/contact-forms/templates');
define('AWP_NEWSLETTER_TEMPLATEPATH',AWP_INC_DIR.'/newsletter/templates');
define('AWP_NEWS_TEMPLATEPATH',AWP_INC_DIR.'/news/templates');
define('AWP_EVENTS_TEMPLATEPATH',AWP_INC_DIR.'/events/templates');
define('AWP_TESTIMONIALS_TEMPLATEPATH',AWP_INC_DIR.'/testimonials/templates');
define('AWP_JOBSFORM_TEMPLATEPATH',AWP_INC_DIR.'/jobs/templates/jobapplicant');
define('AWP_JOBSEARCHFORM_TEMPLATEPATH',AWP_INC_DIR.'/jobs/templates/jobsearch');
define('AWP_JOBDESCRIPTION_TEMPLATEPATH',AWP_INC_DIR.'/jobs/templates/jobdescription');
define('AWP_JOBLISTS_TEMPLATEPATH',AWP_INC_DIR.'/jobs/templates/joblists');

//Default Template
define('AWP_EVENTS_DEFAULT_TEMPLATE','default-events.php');
define('AWP_NEWS_DEFAULT_TEMPLATE','default-news.php');
define('AWP_TESTIMONIALS_DEFAULT_TEMPLATE','default-testimonials.php');
define('AWP_NEWSLETTER_WIDGET_DEFAULT_TEMPLATE','widget-default-template-usphone.php');
//Apptivo API URL's
//Dont change this unless specified, changing to incorrect values will make plugins to not work properly.
define('APPTIVO_API_URL','https://www.apptivo.com/app/services/');
define('APPTIVO_SITE_SERVICES', APPTIVO_API_URL.'SiteServices?wsdl');
define('APPTIVO_USER_SERVICES', APPTIVO_API_URL.'UserServices?wsdl');
define('APPTIVO_CONTACTUS_SERVICES', APPTIVO_API_URL.'ContactUsServices?wsdl');
define('APPTIVO_HRJOBS_SERVICES', APPTIVO_API_URL.'HrJobServices?wsdl');
define('APPTIVO_DOC_UPLOADURL','http://www.apptivo.com/app/fileuploadservlet');
define('APPTIVO_BUSINESS_SERVICES', APPTIVO_API_URL.'BusinessSiteServices?wsdl');



if(!defined('APPTIVO_SITE_KEY') )
{
	$apptivo_site_key = get_option('apptivo_sitekey');
	$apptivo_accesskey = get_option('apptivo_accesskey');
	if(empty($apptivo_site_key) || strlen(trim($apptivo_site_key)) == 0 ){
		//No site key in DB
	}else{
		define('APPTIVO_SITE_KEY',$apptivo_site_key);
		define('APPTIVO_ACCESS_KEY',$apptivo_accesskey);
	}
}
/**
 * Loads plugins
 *
 * @return void
 */
function awp_load_plugins()
{
	
    //include Plugin Files.
	$plugin_dir = @opendir(AWP_PLUGINS_DIR);
    if ($plugin_dir) {
        while (($entry = @readdir($plugin_dir)) !== false) {
            if (strrchr($entry, '.') === '.php') {
                require_once AWP_PLUGINS_DIR . '/' . $entry;
            }
        }
        @closedir($plugin_dir);
    }
   
    
}
/**
 * Load Widgets
 *
 */
function awp_load_widgets()
{
	

 //include widget files 
   $widget_dir = @opendir(AWP_WIDGETS_DIR);
   
    if ($widget_dir) {
        while (($entry = @readdir($widget_dir)) !== false) {
            if (strrchr($entry, '.') === '.php') {
                require_once AWP_WIDGETS_DIR . '/' . $entry;
            }
        }
        @closedir($widget_dir);
    }
}
/**
 * Recursive strips slahes from the var
 *
 * @param mixed $var
 * @return mixed
 */
function awp_stripslashes($var)
{
    if (is_string($var)) {
        return stripslashes($var);
    } elseif (is_array($var)) {
        $var = array_map('awp_stripslashes', $var);
    }
    
    return $var;
}
	
/**
 * Sort customformfields by order
 */
function awp_sort_by_order($a, $b) {
	return $a["order"] - $b["order"];
}
/**
 * Search for value using key in multi-dimensional array
 * returns index if value found
 * returns false if no value is found
 */
function awp_recursive_array_search($haystack, $needle, $index = null) 
{ 
    $aIt = new RecursiveArrayIterator($haystack); 
    $it = new RecursiveIteratorIterator($aIt); 
    
    while($it->valid()) 
    {        
        if (((isset($index) AND ($it->key() == $index)) OR (!isset($index))) AND ($it->current() == $needle)) { 
            return $aIt->key(); 
        } 
        
        $it->next(); 
    } 
    
    return false; 
} 

/**
 * Add contact form scripts and styles, only when short code is present in page/posts
 */
function awp_check_for_shortcode($posts,$shortcode) {
    if ( empty($posts) )
        return $posts;
    // false because we have to search through the posts first
    $found = false;
 
    // search through each post
    foreach ($posts as $post) {
        // check the post content for the short code
        if ((stripos($post->post_content, $shortcode))!==false)
        { // we have found a post with the short code
            $found = true;
        }
            // stop the search
            break;
        }
 	return $found;
}

/**
 * Converts value to boolean
 *
 * @param mixed $value
 * @return boolean
 */
function awp_to_boolean($value)
{
    if (is_string($value)) {
        switch (strtolower($value)) {
            case '+':
            case '1':
            case 'y':
            case 'on':
            case 'yes':
            case 'true':
            case 'enabled':
                return true;
            
            case '-':
            case '0':
            case 'n':
            case 'no':
            case 'off':
            case 'false':
            case 'disabled':
                return false;
        }
    }
    
    return (boolean) $value;
}
function awp_date_compare($a, $b)
{
	$t1 = strtotime($a['datetime']);
        $t2 = strtotime($b['datetime']);
	return $t1 - $t2;
}
function awp_creation_date_compare($a, $b)
{
       $t1 = $a->creationDate;
       $t2 = $b->creationDate;
       return $t1 - $t2;
}
function awp_sort_by_sequence($a, $b) {
	return $a->sequenceNumber - $b->sequenceNumber;
}
/**
 * Pagination
 *
 * @param unknown_type $reload
 * @param unknown_type $page
 * @param unknown_type $tpages
 * @return unknown
 */
function awp_paginate($reload, $page, $tpages,$totalitems) {	
	$firstlabel = "<<";
	$prevlabel  = "<";
	$nextlabel  = ">";
	$lastlabel  = ">>";
	$out = '<div class="tablenav top"><div class="tablenav-pages">';
	$out .= '<span class="displaying-num">'.$totalitems.'&nbsp;items</span>';
	// first
	if($page>1) {
		$out.= "<a class=\"first-page\" href=\"" . $reload . "\">" . $firstlabel . "</a>\n";
	}
	else {
		$out.= "<a class=\"first-page disabled\" >" . $firstlabel . "</a>\n";
	}
	
	// previous
	if($page==1) {
		$out.= "<a class=\"first-page disabled\" >" . $prevlabel . "</a>\n";
	}
	elseif($page==2) {
		$out.= "<a class=\"prev-page\" href=\"" . $reload . "\">" . $prevlabel . "</a>\n";
	}
	else {
		$out.= "<a class=\"prev-page\" href=\"" . $reload . "&amp;pageno=" . ($page-1) . "\">" . $prevlabel . "</a>\n";
	}
	
	// current
	//$out.= "<span class=\"current\">Page " . $page . " of " . $tpages . "</span>\n";
	$out .='<span class="paging-input"><input type="text" class="current-page" title="Current page" name="paged" value="'.$page.'" size="1"> of <span class="total-pages">'.$tpages.'&nbsp;&nbsp;</span></span>';
	
	// next
	if($page<$tpages) {
		$out.= "<a class=\"next-page\" href=\"" . $reload . "&amp;pageno=" .($page+1) . "\">" . $nextlabel . "</a>\n";
	}
	else {
		$out.= "<a class=\"last-page disabled\" >" . $nextlabel . "</a>\n";
	}
	
	// last
	if($page<$tpages) {
		$out.= "<a class=\"last-page\" href=\"" . $reload . "&amp;pageno=" . $tpages . "\">" . $lastlabel . "</a>\n";
	}
	else {
		$out.= "<a class=\"last-page disabled\" >" . $lastlabel . "</a>\n";
	}
	
	$out.= "</div></div>";
	
	return $out;
}
/**
 * To Make Soap Call
 *
 * @param unknown_type $wsdl
 * @param unknown_type $function
 * @param unknown_type $params
 * @return unknown
 */
function getsoapCall($wsdl,$function,$params)
{
   $client = new SoapClient($wsdl);
   try {
    	 $response = $client->__soapCall($function, array($params));
    }catch(Exception $e){
    	   return 'E_100'; // Exception echo $e->getMessage();
    }
   return $response;
	
}
/**
 * LoadMemCache Methods.
 *
 * @param unknown_type $wsdl
 * @param unknown_type $key_publishdate
 * @param unknown_type $plugincall_key
 * @param unknown_type $method_publishdate
 * @param unknown_type $plugincall_function
 * @param unknown_type $publishdate_params
 * @param unknown_type $plugincall_params
 * @return unknown
 */
function getAllItemsWithMemcache($wsdl,$publishdate_key,$plugincall_key,$publishdate_function,$plugincall_function,$publishdate_params,$plugincall_params) 
{
          $publishdate_key = APPTIVO_SITE_KEY.$publishdate_key;
          $plugincall_key  = APPTIVO_SITE_KEY.$plugincall_key;
           if(class_exists('Memcache'))
          	{
            $mcache_obj = new AWP_Mcache_Util(); //Create Object in AWP_DataCache clss
		    $mcacheconnect = $mcache_obj->connectmcache();
          	}
          	else {
          	 $mcacheconnect = FALSE;
          	}
          	//To check if the MemCache is connected or not.
		    if( $mcacheconnect )	
		    {   //"connected MemCache.";
		    	$awp_cache_publishdate = $mcache_obj->getdata($publishdate_key);    	
		    	if( empty($awp_cache_publishdate)) //Check the published date key value is set in memcahe or not.
		    	{   $load_news_service = TRUE;
		    	} else {		    				    		
		    		$publish_date = getsoapCall(APPTIVO_SITE_SERVICES,$publishdate_function,$publishdate_params);
		    		$publish_prevDate =   $publish_date->return;  	
		    		if($publish_prevDate == $awp_cache_publishdate)
		    		{   
		    			$response = $mcache_obj->getdata($plugincall_key);    			
		    			$load_news_service = FALSE;   			
		    		}else {
		    			$load_news_service = TRUE;
		    		}
		    	}
		    	if($load_news_service)
		    	{      // "Load Services.";
		    		    $response = getsoapCall($wsdl,$plugincall_function,$plugincall_params);
		    		    $publish_date = getsoapCall(APPTIVO_SITE_SERVICES,$publishdate_function,$publishdate_params);
		    		    $mcache_obj->storedata($plugincall_key,$response);
		    			$mcache_obj->storedata($publishdate_key,$publish_date->return);
		    			$awp_cache_publishdate = $mcache_obj->getdata($publishdate_key);
		    	}
		    }else { 
		    	// "MemCache is not connected.."; 
		        $response = getsoapCall($wsdl,$plugincall_function,$plugincall_params);
		    }
		  return $response;
		  
}

    /**
     * get ApptiovWordPress(awp) Templates
     *
     * @param unknown_type $dir
     * @param unknown_type $type == 'widget' => "Widget Templates" , $type == 'plugin' => "Plugin templates"
     * @return Template Array Lists.
     */
    function get_awpTemplates($dir,$type)
    {  
        $default_headers = array(
		'Template Name' => 'Template Name',
        'Template Type' => 'Template Type'	
	    );
	    $templates = array();	 
		$dir_news = $dir;
		// Open a known directory, and proceed to read its contents
		if (is_dir($dir_news)) {
		    if ($dh = opendir($dir_news)) {
		        while (($file = readdir($dh)) !== false) {
		        	if ( substr( $file, -4 ) == '.php' )
		        	{		        		        	
					$plugin_data = get_file_data( $dir_news."/".$file, $default_headers, '' );
					if(strlen(trim($plugin_data['Template Name'])) != 0 )
					{  
					   if(strtolower(trim($plugin_data['Template Type'])) == 'widget' && strtolower($type) == 'widget' )
					   {
						$templates[$plugin_data['Template Name']] = $file;
					   }else if(strtolower(trim($plugin_data['Template Type'])) == 'shortcode' && strtolower($type) == 'plugin')
					   {
					   	$templates[$plugin_data['Template Name']] = $file;						
					   }else if(strtolower(trim($plugin_data['Template Type'])) == 'inline' && strtolower($type) == 'inline')
                       {
                         $templates[$plugin_data['Template Name']] = $file;	
                       }
                    }
					
		        	}
		        }		        
		        closedir($dh);
		    }
		}
		return $templates; 
    }  

/**
 * Remove html tags from string
 *
 * @param string $str
 * @return string
 */    
function html_remove($str){
	return $str;
    return preg_replace("/<[^>]*>/","",$str);
}
/**
 * Convert  Object to array.
 *
 * @param unknown_type $objectValue
 * @return unknown
 */
function awp_convertObjToArray($objectValue)
{    if(!empty($objectValue)){
	if(is_array($objectValue)) {
		$arrayValue = $objectValue;
	}
	else {
		$arrayValue = array();
		array_push($arrayValue,$objectValue);
	}
	return $arrayValue;
        }
}
