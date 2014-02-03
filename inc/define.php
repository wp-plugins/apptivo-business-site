<?php
/**
 * Apptivo business plugin configuration
 * @package apptivo-business-site
 * @author  RajKumar <rmohanasundaram[at]apptivo[dot]com>
 */
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
//define('AWP_CASES_DISABLE',1);
/*
 User updateable define statements ends here..
 Changing define statements below will make plugin to not work properly.
 * */
// Site Url
define('SITE_URL', site_url());

//Plugin Version
define('AWP_VERSION', '1.1.2.1');

//Plugin folders
define('AWP_LIB_DIR', AWP_PLUGIN_BASEPATH . '/lib');
define('AWP_ASSETS_DIR', AWP_PLUGIN_BASEPATH . '/assets');
define('AWP_INC_DIR', AWP_PLUGIN_BASEPATH . '/inc');
define('AWP_PLUGINS_DIR', AWP_LIB_DIR . '/Plugin');
define('AWP_WIDGETS_DIR', AWP_LIB_DIR . '/widgets');

//plugin template folder
define('AWP_CONTACTFORM_TEMPLATEPATH',AWP_INC_DIR.'/contact-forms/templates');
define('AWP_CASES_TEMPLATEPATH',AWP_INC_DIR.'/cases/templates');
define('AWP_NEWSLETTER_TEMPLATEPATH',AWP_INC_DIR.'/newsletter/templates');
define('AWP_NEWS_TEMPLATEPATH',AWP_INC_DIR.'/news/templates');
define('AWP_EVENTS_TEMPLATEPATH',AWP_INC_DIR.'/events/templates');
define('AWP_TESTIMONIALS_TEMPLATEPATH',AWP_INC_DIR.'/testimonials/templates');
define('AWP_TESTIMONIALS_FORM_TEMPLATEPATH',AWP_INC_DIR.'/testimonials/templates/frontend');
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
define('APPTIVO_API_URL','https://api.apptivo.com/');
define('APPTIVO_BUSINESS_SERVICES', APPTIVO_API_URL.'app/services/v1/BusinessSiteServices?wsdl');
define('APPTIVO_BUSINESS_INDEX', APPTIVO_API_URL.'ts/services/AppJobWebService?wsdl');

define('APPTIVO_LEAD_SOURCE_API',APPTIVO_API_URL.'app/dao/lead');
define('APPTIVO_LEAD_API', APPTIVO_API_URL.'app/dao/leads');
define('APPTIVO_CASES_API', APPTIVO_API_URL. 'app/dao/case');
define('APPTIVO_NOTES_API', APPTIVO_API_URL.'app/dao/note');
define('APPTIVO_TESTIMONIALS_STATUS_API', APPTIVO_API_URL. 'app/dao/testimonial');
define('APPTIVO_TARGETS_API',APPTIVO_API_URL.'app/dao/targets');
define('APPTIVO_SIGNUP_API',APPTIVO_API_URL.'app/dao/signup');

define('APPTIVO_LEAD_OBJECT_ID','4');
define('APPTIVO_CASES_OBJECT_ID','59');

//API Key and Access Keys Settings.

$eCommerce_api_Key = get_option('apptivo_ecommerce_apikey');
$eCommerce_access_key = get_option('apptivo_ecommerce_accesskey');
$business_site_key = get_option('apptivo_sitekey');
if(!empty($eCommerce_api_Key) )
{
	update_option('apptivo_apikey',$eCommerce_api_Key);
	update_option('apptivo_accesskey',$eCommerce_access_key);
}else if(!empty($business_site_key)) {
	update_option('apptivo_apikey',$business_site_key);
	delete_option('apptivo_sitekey');
}

$apptivo_api_key = get_option('apptivo_apikey');
$apptivo_accesskey = get_option('apptivo_accesskey');
define('APPTIVO_BUSINESS_API_KEY',trim($apptivo_api_key));
define('APPTIVO_BUSINESS_ACCESS_KEY',trim($apptivo_accesskey));
/**
 * Loads plugins
 *
 * @return void
 */
function awp_load_plugins()
{
	ob_start();
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
	if(is_array($a) && is_array($b))
	{
	return $a["order"] - $b["order"];
	}
	else
	{
		return;
	}
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
	$context = stream_context_create();
	$client = new SoapClient($wsdl, array('stream_context' => $context));
//  $client = new SoapClient($wsdl);
   try {
    	 $response = $client->__soapCall($function, array($params));
    }catch(Exception $e){
        return 'E_100';
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
/**
 *
 * @param <type> $wsdl
 * @param string $publishdate_key
 * @param string $plugincall_key
 * @param <type> $publishdate_function
 * @param <type> $plugincall_function
 * @param <type> $publishdate_params
 * @param <type> $plugincall_params
 * @return <type>
 */
function get_data($wsdl,$publishdate_key,$plugincall_key,$publishdate_function,$plugincall_function,$publishdate_params,$plugincall_params)
    {
     $cache_obj = new AWP_Cache_Util(); //Create Object in AWP_DataCache class
     $response = $cache_obj->get_data($wsdl, $publishdate_key, $plugincall_key, $publishdate_function, $plugincall_function, $publishdate_params, $plugincall_params);
     return $response;
    }
/**
 * Recursive creates directory
 *
 * @param string $path
 * @param integer $mask
 * @param string
 * @return boolean
 */
function awp_mkdir($path, $mask = 0755, $curr_path = '')
{
    $path = awp_realpath($path);
    $path = trim($path, '/');
    $dirs = explode('/', $path);

    foreach ($dirs as $dir) {
        if ($dir == '') {
            return false;
        }

        $curr_path .= ($curr_path == '' ? '' : '/') . $dir;

        if (!@is_dir($curr_path)) {
            if (@mkdir($curr_path, $mask)) {
                @chmod($curr_path, $mask);
            } else {
                return false;
            }
        }
    }

    return true;
}
/**
 * Recursive remove dir
 *
 * @param string $path
 * @param array $exclude
 * @return void
 */
function awp_rmdir($path, $exclude = array(), $remove = true)
{
    $dir = @opendir($path);

    if ($dir) {
        while (($entry = @readdir($dir)) !== false) {
            $full_path = $path . '/' . $entry;

            if ($entry != '.' && $entry != '..' && !in_array($full_path, $exclude)) {
                if (@is_dir($full_path)) {
                    awp_rmdir($full_path, $exclude);
                } else {
                    @unlink($full_path);
                }
            }
        }

        @closedir($dir);

        if ($remove) {
            @rmdir($path);
        }
    }
}
/**
 * Recursive empty dir
 *
 * @param string $path
 * @param array $exclude
 * @return void
 */
function awp_emptydir($path, $exclude = array())
{
    awp_rmdir($path, $exclude, false);
}
/**
 * Returns realpath of given path
 *
 * @param string $path
 */
function awp_realpath($path)
{
    $path = awp_path($path);
    $parts = explode('/', $path);
    $absolutes = array();

    foreach ($parts as $part) {
        if ('.' == $part) {
            continue;
        }
        if ('..' == $part) {
            array_pop($absolutes);
        } else {
            $absolutes[] = $part;
        }
    }

    return implode('/', $absolutes);
}
/**
 * Converts win path to unix
 *
 * @param string $path
 * @return string
 */
function awp_path($path)
{
    $path = preg_replace('~[/\\\]+~', '/', $path);
    $path = rtrim($path, '/');
    return $path;
 }
//Label Field
function awp_labelfield($field='',$class='contactform_field_label',$before='',$after='')
{
	$fieldid=$field['fieldid'];
	$showtext=$field['showtext'];
	return $before.'<label for="'.$fieldid.'" class="'.$class.'">'.$showtext.'</label>'.$after;

}

function awp_jobsearch_textfield ($field='',$class='',$before='',$after='')
{

	$fieldid   = $field['fieldid'];
	$showtext  = $field['showtext'];
	$required  = $field['required'];
	$fieldtype = $field['type'];
	$options   = $field['options'];

	switch( $fieldid )
	{
		case "keywords" :
			$html = '<input type="text" value="" class="absp_jobsearch_input_text '.$class.'"  name="'.$fieldid.'" id="'.$fieldid.'">';
		break;

		case "customfield1" :
			if($fieldtype == 'select') :
			     $html .= '<select class="absp_jobsearch_select '.$class.'" value="" name="'.$fieldid.'" id="'.$fieldid.'"  > ';
                 $html .= '<option selected="selected" value="All" style="">Select  '.$showtext.'</option>';
                 foreach($options as $opt_val)
                   {
                   	$option_arr = explode('::',$opt_val);
                    $html .= '<option value="'.$option_arr[0].'" >'.$option_arr[1].'</option>';
                   }
                 $html .= '</select>';
            endif;
        break;

        case "customfield2" :

			if($fieldtype == 'checkbox') :
			   foreach($options as $opt_val)
                   {   $opt_value = strtoupper(trim($opt_val));
                       $opt_value = str_replace(" ","_",$opt_value);
                       $html .= '<input class="absp_jobsearch_input_checkbox '.$class.'" value="'.$opt_value.'" type="checkbox" name="'.$fieldid.'[]'.'" /> &nbsp;&nbsp;<label>'.$opt_val.'</label><br />';
                    }
            endif;

            if($fieldtype == 'select') :
	            $html .= '<select class="absp_jobsearch_select '.$class.'" value="" name="'.$fieldid.'" id="'.$fieldid.'"  >';
	            $html .= '<option value="" style="">Select  '.$showtext.'</option>';
	            foreach($optionvalues as $opt_val)
	                   {   $opt_value = strtoupper(trim($opt_val));
	                       $opt_value = str_replace(" ","_",$opt_value);
	     				   $html .= '<option value="'.$opt_value.'" style="">'.$opt_val.'</option>';
	                    }
	            $html .='</select>';
            endif;

        break;



	}
	 return $html;
}

/**
 * Enter description here...
 *
 * @param unknown_type $forms
 * @param unknown_type $field
 * @param unknown_type $countries
 * @param Bollean  $value_present
 * @param unknown_type $before
 * @param unknown_type $after
 * @return unknown
 */
function awp_textfield($forms='',$field='',$countries='',$value_present='',$before='',$after='',$placeholder=false, $tabindex='')
{
	$fieldid=$field['fieldid'];
	$showtext=$field['showtext'];
	$validation=$field['validation'];
	$required=$field['required'];
	$fieldtype=$field['type'];
	$options=$field['options'];
	$optionvalues=array();
    $place_text = '';
	if($placeholder)
	{
		$place_text = 'placeholder="'.$showtext.'"';
	}
	    if($fieldtype=="select" || $fieldtype=="radio" || $fieldtype=="checkbox" ){

	       if(trim($fieldid) == 'industry')
				{
				$optionvalues=$options;
				$fieldtype = 'select';
				} else if(trim($options)!=""){
				$optionvalues=split("[\n]",trim($options));//Split the String line by line.
		   }

		}


		  if ($value_present) :
		    $postValue = $_REQUEST[$fieldid];
          else :
           	$postValue="";
           endif;


		//Required Class
		if($required){
			$mandate_property='"mandatory="true"';
			$validateclass=" required";
		}
		else{
			$mandate_property="";
			$validateclass="";
		}

		//Field Validation Class
		switch($validation)
		{
			case "email":
				$validateclass .=" email";
			break;
			case "url":
				$validateclass .=" url";
			break;
			case "number":
				$validateclass .=" number";
			break;
		}

		//Captcha Class
		if($fieldid=='captcha')
         {
         $captcha_class = 'captcha';
         }
         else{
         $captcha_class = '';
         }
    switch($fieldtype)
		{
			case "text":
                            $html = '<input  '.$place_text.' type="text" name="'.$fieldid.'" id="'.$fieldid.'_id" value="'.$postValue.'"  class="absp_contact_input_text'.$validateclass.'" tabindex="'.$tabindex.'">';
			break;
			case "textarea":
				$html  =  '<textarea  '.$place_text.' name="'.$fieldid.'" id="'.$fieldid.'_id"   class="absp_contact_textarea'.$validateclass.'" size="50"  tabindex="'.$tabindex.'">'.$postValue.'</textarea>';
			break;
			case "select":
                if($fieldid == 'country'){
                 $html =  '<select  name="'.$fieldid.'" id="'.$fieldid.'"  class="absp_contact_select'.$validateclass.'"  tabindex="'.$tabindex.'">';
				foreach($countries as $country)
				{
					$country_Code = ((trim($postValue)) == '')?'US':(trim($postValue));
                    if($country_Code == trim($country->countryCode)){
                      $selected='selected="selected"';
                     }
                     else{
                        $selected = "";
                     }
                  $html .=  '<option value="'.$country->countryCode.'" '.$selected.'>'.$country->countryName.'</option>';
				}
				$html .=  '</select>';
                 } else  if($fieldid == 'industry')
	                {
		                if(!empty($optionvalues))
		                {
		                $html .=  '<select  name="'.$fieldid.'" id="'.$fieldid.'" value=""  class="absp_jobapplicant_select'.$validateclass.'"  tabindex="'.$tabindex.'">';
		                	foreach( $optionvalues as $optionvalue )
							{  if(!empty($optionvalue) && strlen(trim($optionvalue)) != 0)
								{
									$options = explode("::",$optionvalue);
									$html .=  '<option value="'.$options[0].'">'.$options[1].'</option>';
								}
							}
						$html .=  '</select>';
		                }else {

		                	 $html .=  '<select  name="'.$fieldid.'" id="'.$fieldid.'" value=""  class="absp_jobapplicant_select'.$validateclass.'"  tabindex="'.$tabindex.'">';
		                	 $html .=  '<option value="0">Default</option>';
							 $html .=  '</select>';
		                }
	                }
               else{
					$html =  '<select  name="'.$fieldid.'" id="'.$fieldid.'" value=""  class="absp_contact_select'.$validateclass.'"  tabindex="'.$tabindex.'">';
					foreach( $optionvalues as $optionvalue )
					{
	                                    if(trim($postValue) == trim($optionvalue)){

	                                       $selected='selected="selected"';
	                                     }
	                                     else{
	                                         $selected='';
	                                     }
						if(!empty($optionvalue) && strlen(trim($optionvalue)) != 0)
							{
						$html .=  '<option value="'.$optionvalue.'" '.$selected.'>'.$optionvalue.'</option>';
							}
					}
					$html .=  '</select>';
                }
			break;
			case "radio":
				$i=0;$opt=0;
				foreach( $optionvalues as $optionvalue )
				{
                                     if(trim($postValue) == trim($optionvalue)){
                                      $selected='checked="checked"';
                                     }
                                     else{
                                         $selected = "";
                                     }
                                    if(!empty($optionvalue) && strlen(trim($optionvalue)) != 0)
						{
					if($i>0)
						$html .='&nbsp;';
					$html .='<label for="'.$fieldid.$opt.'">'.$optionvalue.'</label><input type="radio" name="'.$fieldid.'" id="'.$fieldid.$opt.'" value="'.$optionvalue.'"  class="absp_contact_input_radio '.$validateclass.'" '.$selected.'  tabindex="'.$tabindex.'">';
						}
						$opt++;
				}
			break;
			case "checkbox":
				$i=0;$opt=0;
				foreach( $optionvalues as $optionvalue )
				{
                                     $selected ="";
                                     foreach($postValue as $value){
                                       if(trim($value) == trim($optionvalue)){
                                           $selected='checked="checked"';
                                       }
                                       }
					if(!empty($optionvalue) && strlen(trim($optionvalue)) != 0)
					{
					if($i>0)
					$html .='&nbsp';
					$html.='<label for="'.$fieldid.$opt.'">'.$optionvalue.'</label><input type="checkbox" name="'.$fieldid.'[]" id="'.$fieldid.$opt.'" value="'.$optionvalue.'"  class="absp_contact_input_checkbox '.$validateclass.'"  '.$selected.'  tabindex="'.$tabindex.'">';
					$i++;$opt++;
						}
				}
                        break;
            case "captcha":
                  $html ='<div class="captcha_image"><img src="'.$forms['captchaimagepath'].'" id="captchaimg" style="border:1px solid #000;"/></div>
                          <div class="captcha_input"><input type="text" name="'.$fieldid.'" id="'.$fieldid.'_id" value=""  class="absp_contact_input_text'.$validateclass.'"  tabindex="'.$tabindex.'" /></div>';
            break;

            case "file":
			   $html ='<input type="file" id="file_upload" name="file_upload"  tabindex="'.$tabindex.'" />';
			   $html.= '<input type="hidden" name="uploadfile_docid" id="uploadfile_docid" value="" class="absp_jobapplicant_input_text'.$validateclass.'"  />';
			break;

		}
		return $before.$html.$after;
}

/**
 * SubMit Button Type
 *
 * @param array $contactform
 * @return html field(form submit type)
 */

function awp_submit_type($forms='',$form_submitname='',$class='',$before='',$after='', $tabindex)
{
	if(strlen(trim($form_submitname)) != 0 ) :
	  $html ='<input type="hidden" name="'.$form_submitname.'"/>';
	endif;

      if($forms[submit_button_type] == "submit" ){
      	if(strlen(trim($forms[submit_button_val])) != 0)
      	{
      		$value = $forms[submit_button_val];
      	}else {
      		$value = 'Submit';
      	}
        $button_value = 'value="'.$value.'"';
      }
      else{
      	if(strlen(trim($forms[submit_button_val])) == 0)
      	{
      		$imgSrc = awp_image('submit_button');
      	}else {
      		$imgSrc = $forms[submit_button_val];
      	}
         $button_value = 'src="'.$imgSrc.'"';
      }

      $html .= '<input type="'.$forms[submit_button_type].'" class="'.$class.'" '.$button_value.' name="awp_contactform_submit_'.$forms[name].'"  id="awp_contactform_submit_'.$forms[name].'"  tabindex="'.$tabindex.'"/>';
      return $before.$html.$after;
}


/**
 * Enter description here...
 *
 * @param unknown_type $forms
 * @param unknown_type $field
 * @param unknown_type $countries
 * @param Bollean  $value_present
 * @param unknown_type $before
 * @param unknown_type $after
 * @return unknown
 */
function cases_textfield($forms='',$field='',$countries='',$value_present='',$before='',$after='',$placeholder=false, $tabindex='',$dafaultselect=false,$plugin='',$postValue)
{
	echo $before;
	$fieldid=$field['fieldid'];
	$showtext=$field['showtext'];
	$validation=$field['validation'];
	$required=$field['required'];
	$fieldtype=$field['type'];
	$options=$field['options'];
        $values=$field['value'];
	$optionvalues=array();
        $selectvalues=array();
    $place_text = '';
    if($postValue=="")
    {
    $postValue[$fieldid]="";
    }
	if($placeholder)
	{
		$place_text = 'placeholder="'.$showtext.'"';
	}
	    if($fieldtype=="select" || $fieldtype=="radio" || $fieldtype=="checkbox" ){
	    		$optionvalues=split("[\n]",trim($options));/*Split the String line by line.*/
                        $selectvalues=split("[\n]",trim($values)); /*Split the String line by line. */
		}


		//Required Class
		if($required){
			$mandate_property='"mandatory="true"';
			$validateclass=" required";
		}
		else{
			$mandate_property="";
			$validateclass="";
		}

		//Field Validation Class
		switch($validation)
		{
			case "email":
				$validateclass .=" email";
			break;
			case "url":
				$validateclass .=" url";
			break;
			case "number":
				$validateclass .=" number";
			break;
			case "phonenumber":
				$validateclass .=" phonenumber";
			break;
		}

		//Captcha Class
		if($fieldid=='captcha')
         {
         $captcha_class = 'captcha';
         }
         else{
         $captcha_class = '';
         }
    switch($fieldtype)
		{
			case "text":
               echo '<input  '.$place_text.' type="text" name="'.$fieldid.'" id="'.$fieldid.'_id" value="'.$postValue[$fieldid].'"  class="absp_'.$plugin.'_input_text'.$validateclass.'" tabindex="'.$tabindex.'">';
			break;
			case "textarea":
			   echo '<textarea  '.$place_text.' name="'.$fieldid.'" id="'.$fieldid.'_id"   class="absp_'.$plugin.'_textarea'.$validateclass.'" size="50"  tabindex="'.$tabindex.'">'.$postValue[$fieldid].'</textarea>';
			break;
			case "select":
if(_isCurl())
{				
if($fieldid=="priority" || $fieldid=="type")
{
if($fieldid=="priority")
{
    $js_script="onchange='sendText(".$fieldid.");'";
    $input_text='<input type="hidden" id="priority_name" name="priority_name" value=""/>';
    
}
if($fieldid=="type")
{
    $js_script="";
    $input_text="";
    $js_script="onchange='sendText(".$fieldid.");'";
    $input_text='<input type="hidden" id="type_name" name="type_name" value=""/>';
    
}
					echo '<select '.$js_script.'  name="'.$fieldid.'" id="'.$fieldid.'" value=""  class="absp_'.$plugin.'_select'.$validateclass.'"  tabindex="'.$tabindex.'">';

					if($dafaultselect):
					    $default_select = '<option value="" > -- Select -- </option>';
					    echo apply_filters('apptivo_business_'.$plugin.'_'.$fieldid.'_default_option',$default_select);
					endif;

$j=0;
					foreach( $optionvalues as $optionvalue )
					{
	                                    if(trim($postValue[$fieldid]) == trim($optionvalue)){

	                                       $selected='selected="selected"';
	                                     }
	                                     else{
	                                         $selected='';
	                                     }
						if(!empty($optionvalue) && strlen(trim($optionvalue)) != 0)
							{
                                                    if($js_script!="")
                                                    {
                                                        $attr_name='rel="'.$optionvalue.'"';
                                                    }
                                                    echo  '<option value="'.$selectvalues[$j].'" '.$selected.' '.$attr_name.'>'.$optionvalue.'</option>';
						$j++;
                                                        }

					}
					echo  '</select>';
                                        echo $input_text;
}
else 
{
echo '<select  name="'.$fieldid.'" id="'.$fieldid.'" value=""  class="absp_'.$plugin.'_select'.$validateclass.'"  tabindex="'.$tabindex.'">';
					
					if($dafaultselect):
					    $default_select = '<option value="" > -- Select -- </option>';
					    echo apply_filters('apptivo_business_'.$plugin.'_'.$fieldid.'_default_option',$default_select);
					endif;
					
					
					foreach( $optionvalues as $optionvalue )
					{
	                                    if(trim($postValue) == trim($optionvalue)){
	
	                                       $selected='selected="selected"';
	                                     }
	                                     else{
	                                         $selected='';
	                                     }
						if(!empty($optionvalue) && strlen(trim($optionvalue)) != 0)
							{
						echo  '<option value="'.$optionvalue.'" '.$selected.'>'.$optionvalue.'</option>';
							}
					}
					echo  '</select>';
}
}
else 
{
echo '<select  name="'.$fieldid.'" id="'.$fieldid.'" value=""  class="absp_'.$plugin.'_select'.$validateclass.'"  tabindex="'.$tabindex.'">';
					
					if($dafaultselect):
					    $default_select = '<option value="" > -- Select -- </option>';
					    echo apply_filters('apptivo_business_'.$plugin.'_'.$fieldid.'_default_option',$default_select);
					endif;
					
					
					foreach( $optionvalues as $optionvalue )
					{
	                                    if(trim($postValue) == trim($optionvalue)){
	
	                                       $selected='selected="selected"';
	                                     }
	                                     else{
	                                         $selected='';
	                                     }
						if(!empty($optionvalue) && strlen(trim($optionvalue)) != 0)
							{
						echo  '<option value="'.$optionvalue.'" '.$selected.'>'.$optionvalue.'</option>';
							}
					}
					echo  '</select>';
}

			break;
			case "radio":

				$i=0;$opt=0;
				foreach( $optionvalues as $optionvalue )
				{
                                     if(trim($postValue[$fieldid]) == trim($optionvalue)){
                                      $selected='checked="checked"';
                                     }
                                     else{
                                         $selected = "";
                                     }
                      if(!empty($optionvalue) && strlen(trim($optionvalue)) != 0)
						{
					if($i>0)
						echo '&nbsp;';
					echo '<input type="radio" name="'.$fieldid.'" id="'.$fieldid.$opt.'" value="'.$optionvalue.'"  class="absp_'.$plugin.'_input_radio '.$validateclass.'" '.$selected.'  tabindex="'.$tabindex.'"><label for="'.$fieldid.$opt.'">'.$optionvalue.'</label><br/>';
						}
						$opt++;
				}
			break;
			case "checkbox":
				$i=0;$opt=0;
				foreach( $optionvalues as $optionvalue )
				{
                                     $selected ="";
				if(isset($postValue)!="" && count($postValue) >1)  {
                   foreach($postValue[$fieldid] as $value){
                        if(trim($value) == trim($optionvalue)){
                        $selected='checked="checked"';
                         }
                    }
                   }
					if(!empty($optionvalue) && strlen(trim($optionvalue)) != 0)
					{
					if($i>0)
					echo '&nbsp';
					echo '<input type="checkbox" name="'.$fieldid.'[]" id="'.$fieldid.$opt.'" value="'.$optionvalue.'"  class="absp_'.$plugin.'_input_checkbox '.$validateclass.'"  '.$selected.'  tabindex="'.$tabindex.'"> <label for="'.$fieldid.$opt.'">'.$optionvalue.'</label><br/>';
					$i++;
					}
					$opt++;
				}
                        break;
            case "captcha":
                awp_reCaptcha();
            break;

            case "file":
			   echo '<input type="file" id="file_upload" name="file_upload"  tabindex="'.$tabindex.'" />';
			   echo  '<input type="hidden" name="uploadfile_docid" id="uploadfile_docid" value="" class="absp_'.$plugin.'_input_text'.$validateclass.'"  />';
			break;

		}
		echo $after;
}

function cases_submit_type($forms='',$form_submitname='',$class='',$before='',$after='', $tabindex)
{

      if($forms[submit_button_type] == "submit" ){
      	if(strlen(trim($forms[submit_button_val])) != 0)
      	{
      		$value = $forms[submit_button_val];
      	}else {
      		$value = 'Submit';
      	}
        $button_value = 'value="'.$value.'"';
      }
      else{
      	if(strlen(trim($forms[submit_button_val])) == 0)
      	{
      		$imgSrc = awp_image('submit_button');
      	}else {
      		$imgSrc = $forms[submit_button_val];
      	}
         $button_value = 'src="'.$imgSrc.'"';
      }

      $html .= '<input type="'.$forms[submit_button_type].'" class="'.$class.'" '.$button_value.' name="'.$form_submitname.'"  id="'.$form_submitname.'"   tabindex="'.$tabindex.'"/>';
      return $before.$html.$after;
}


/**
 * Create Label Fields
 *
 * @param unknown_type $showtext
 * @param unknown_type $customtext
 * @param unknown_type $class
 * @param unknown_type $before
 * @param unknown_type $after
 * @return unknown
 */
function awp_create_labelfield($showtext='',$customtext='',$class='',$before='',$after='',$fieldid='')
{
    if($showtext == '' || strlen(trim($showtext)) == 0) :
    	$showtext = $customtext;
    endif;
	return $before.'<label for="'.$fieldid.'" class="'.$class.'">'.$showtext.'</label>'.$after;

}

function awp_create_textfiled($type='',$fieldid='',$class='',$before='',$after='')
{
	switch(strtolower($type))
	{
		case "checkbox" :
		    	$html =  $before.'<input type="checkbox" class="'.$class.'" value="" id="'.$fieldid.'" name="'.$fieldid.'" >'.$after;
	    break;
	}
	return $html;

}

//Mandatory Field.
function awp_mandatoryfield($field='',$before='',$after='',$mandatory_symbol = '*')
{
	$required=$field['required'];
	if($required):
	 return $before.$mandatory_symbol.$after;
	endif;
}
//Powered By Apptivo.
function poweredby_apptivo()
{
$apptivo_logo = '<a target="_blank" href="http://www.apptivo.com/">
<img style="border: medium none;" alt="Apptivo.com is the best free way to run your business. Apptivo.com powers ecommerce websites, provides free CMS, free CRM, free ERP, free Project Management and free Invoicing to small businesses." title="Apptivo.com is the best free way to run your business. Apptivo.com powers ecommerce websites, provides free CMS, free CRM, free ERP, free Project Management and free Invoicing to small businesses." src="http://cdn18.apptivo.com/templates/app/footer/apptivo.png">
</a>';
return $apptivo_logo;
}

/*
 * Apptivo REST API CALL
 */

function _isCurl(){
    return function_exists('curl_version');
}

function getRestAPICall($method, $url, $data = false)
{
    $proxysettings = array();
    $proxysettings = get_option('awp_proxy_settings');
    
    if(!_isCurl()){ echo '<b style="color:red;">CURL disabled in your server. please enable through php.ini</b>';  exit; }

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: application/x-www-form-urlencoded;charset=utf-8"));

    if($method == "POST")
    {
        curl_setopt($ch, CURLOPT_POST, 1);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }
    }
    else{
        if ($data) $url = sprintf("%s?%s", $url, http_build_query($data));
    }
    curl_setopt($ch,CURLOPT_URL, $url);

        if(isset($proxysettings['proxy_enable'])){
        if(isset($proxysettings['proxy_hostname_portno'])){
            curl_setopt($ch, CURLOPT_PROXY, $proxysettings['proxy_hostname_portno']);
        }
        if(isset($proxysettings['proxy_loginuser_pwd'])){
            curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxysettings['proxy_loginuser_pwd']);
        }
    }
    
    curl_setopt($ch, CURLOPT_SSLVERSION, 3);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    $response = curl_exec($ch);
    curl_close($ch);
	$result = json_decode($response);
    return $result;
}

function getCaseValues($option)
{
            $params = array (
                "a"         => "getCaseLookupValues",
                "lookupType"=> $option,
                "apiKey"    => APPTIVO_BUSINESS_API_KEY,
                "accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
                );
$response=getRestAPICall("POST",APPTIVO_CASES_API,$params);
return $response;
}

/* Generate Captcha */
function awp_reCaptcha()
{
     if(get_option ('apptivo_business_recaptcha_mode')=="yes")
{
    $option=get_option('apptivo_business_recaptcha_settings');
    $option=json_decode($option);
if($option->recaptcha_publickey!="" && $option->recaptcha_privatekey!="")
{    
echo " <script type='text/javascript'>
	     var RecaptchaOptions = {
		    theme : '".$option->recaptcha_theme."',
			lang : '".$option->recaptcha_language."',
             };
	</script>
<style type='text/css'>
#recaptcha_image{width: 226px;}
#recaptcha_area{width: 245px !important;}
#recaptcha_widget_div{xzoom:0.75;x-moz-transform: scale(0.72);text-align: center;}
#recaptcha_response_field{width:160px !important;height:32px;font-size:18px !important;}
#recaptcha_image img{width:300px !important;}
#recaptcha_privacy{display: none;}
#recaptcha_widget_div label.error{display: none !important;}
.recaptcha_source{width:40%;}
.captcha_key_error{clear:both;text-align:center;width:58%;margin:0 auto;padding:0px;display:block;}
</style>";
require_once AWP_ASSETS_DIR.'/captcha/recaptchalib.php';
echo "<div class='recaptcha_source'>";
echo recaptcha_get_html($option->recaptcha_publickey);
echo "</div><div class='awp_recaptcha_error'><label for='recaptcha_response_field' generated='true' class='error'></label></div>";
echo "<script type='text/javascript'>
jQuery(document).ready(function($) {
			if (jQuery('.recaptcha_source #recaptcha_response_field').length == '0') { 
					jQuery('.recaptcha_source').html('');
					jQuery('.recaptcha_source').html('<p> Invalid ReCaptcha Keys. </p>');
					jQuery('.recaptcha_source').addClass('captcha_key_error').removeClass('recaptcha_source');
					} 
});</script>";
return;
}
}
else
{
    echo '<div class="captcha_image">Please Enable reCaptcha in Plugin Settings.</div>';
    return;
}
}

/* Get Captcha Response */
function captchaValidation($privatekey,$challenge_field,$response_field)
{
require_once(AWP_ASSETS_DIR.'/captcha/recaptchalib.php');
  $resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $challenge_field,
                                $response_field);

  if (!$resp->is_valid) {
    $response= $resp->error;

  }
  else{
      $response =$resp->is_valid;
  }
  return $response;
}

/* Check's Captcha if enabled or disabled  */
function checkCaptchaOption()
{
	 if(get_option ('apptivo_business_recaptcha_mode')!="yes")
{
	echo '<script type="text/javascript">
	jQuery(document).ready(function($) {
	jQuery("#captcha_show").attr("disabled", true).prop("checked", false).attr("title","Please Enable Recaptcha in Plugin settings.");
		});
	</script>';
}
}

/* Create Lead Source */

function CreateContactFormLeads($sourceName)
{

    $params = array (
                "a"=>"checkLookupNameExist",
                "from"=>"Create",
                "lookupName"=>$sourceName,
                "lookupType"=>"LEAD_SOURCE_TYPE",
                "apiKey"=> APPTIVO_BUSINESS_API_KEY,
                "accessKey"=> APPTIVO_BUSINESS_ACCESS_KEY
                );
$respone=getRestAPICall("POST",APPTIVO_LEAD_SOURCE_API,$params);
 $checkLead=$respone->meaning;
 if($checkLead=="yes")
 {
     $leadSource = "present";
     return $leadSource;
 }
if($checkLead=="no" && $checkLead!='')
{
    $details='{"meaning":"'.$sourceName.'","notifyEmailId":"","description":"'.$sourceName.'"}';
    $params = array (
                "a"     =>"createNewLeadByLookupType",
                "details"=>$details,
                "lookupType"=>"LEAD_SOURCE_TYPE",
                "displayInDashboard"=>"N",
                "apiKey"=> APPTIVO_BUSINESS_API_KEY,
                "accessKey"=> APPTIVO_BUSINESS_ACCESS_KEY
                );
    $respone=getRestAPICall("POST",APPTIVO_LEAD_SOURCE_API,$params);
    return $respone;
}
}

function checkSoapextension($currentOption) {
if (!extension_loaded('soap')) {
echo '<div class="awp_updated" id="errormessage">
<p style="color:#f00;font-weight:bold;text-align:center;"> SOAP extension required to run Apptivo Business Site CRM  plugin- ' . $currentOption . '. </p>
</div>
<style type="text/css">
.awp_updated{background-color: #FFFFE0;border-color: #E6DB55;border-radius: 5px 5px 5px 5px;border-style: solid;border-width: 1px;line-height: 0.9em;}
</style>'
    ;
    echo '<script type="text/javascript">
	jQuery(document).ready(function($) {
	jQuery(".awp_updated").fadeOut(10000);
		});
	</script>';
    }
}

/**
 * Create TargetLists.
 *
 * @param unknown_type $category
 * @param unknown_type $fname
 * @param unknown_type $lname
 * @param unknown_type $email
 * @param unknown_type $userId
 * @return unknown
 */
function createTargetList($category,$fname,$lname,$email,$phoneNumber,$comments,$userId=null)
{
   
   $target_category = target_lists_category(trim($category));
   if(trim($category) != $target_category )
   {
   	return $target_category;
   }
   
   $verification = check_blockip();
   if($verification){
   	 return $verification;
   }
	$appParam = new AWP_appParam('PHONE',$phoneNumber);	
	$params = array ( 
                "arg0" => APPTIVO_BUSINESS_API_KEY,
                "arg1" => APPTIVO_BUSINESS_ACCESS_KEY,
	            "arg2" => $category,
	            "arg3" => $fname,
	            "arg4" => $lname,
	            "arg5" => $email,
	            "arg6" => $comments,
	            "arg7" => $userId,
		        "arg8" => $appParam
                 );
    $response = getsoapCall(APPTIVO_BUSINESS_SERVICES,'createTargetWithCommunicationDetailsAndAddToTargetList',$params);
    return $response;
}

/*
 * 
 * To get Current Browser
 */

function get_current_browser()
{
if(isset($_SERVER['HTTP_USER_AGENT'])){
    $agent = $_SERVER['HTTP_USER_AGENT'];
}
if(strlen(strstr($agent,"Firefox")) > 0 ){ 
	$browser = 'firefox';
}
else if(strlen(strstr($agent,"Chrome")) > 0 ){ 

    $browser = 'chrome';

}
else
{
   $browser = 'other';
}
return $browser;
}