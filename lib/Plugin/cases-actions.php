<?php

//Apptivo Business Site Cases -- do shortcode
function apptivo_business_cases()
{
	$cases_fields_properties  = get_option('absp_cases_form_fields');
	
	if(!empty($cases_fields_properties)) :
	$process_cases = process_cases_form($cases_fields_properties);
	$valuepresent = FALSE;
	
   if($process_cases) :	
   if( $process_cases == 'E_IP')
	{  
		$error_message = awp_messagelist('IP_banned');
		apply_filters('absp_cases_ip_banned',$error_message);
		$captcha_error =  '<div class="iperror error">'.$error_message.'</div>';
		$valuepresent = TRUE;
		echo $captcha_error;		
	}else if( $process_cases == 'CE001')
	{  
		$error_message = awp_messagelist('caseform-captcha_error');
		apply_filters('absp_cases_captcha_error',$error_message);
		$captcha_error =  '<div class="captcha_error error">'.$error_message.'</div>';
		$valuepresent = TRUE;
		echo $captcha_error;		
	}else if( $process_cases == 'E_100')
	{
		$error_message = awp_messagelist('casesform-e100');
		apply_filters('absp_cases_e_100',$error_message);
		$e100_error =  '<div class="error">'.$error_message.'</div>';
		$valuepresent = TRUE;
		echo $e100_error;
	}else if( $process_cases->return->statusCode != 1000 )
	{
		$error_message = awp_messagelist('casesform-e100');
		apply_filters('absp_cases_e_100',$error_message);
		$e100_error =  '<div class="invalid keys error">'.$error_message.'</div>';
		$valuepresent = TRUE;
		echo $e100_error;
	}
	endif;
	$cases_properties = $cases_fields_properties['properties'];
	
	$suc_message = '';
	if($process_cases->return->statusCode == 1000):
	 if($cases_properties['confirm_msg_page'] == 'other') :
	 $page_id = $cases_properties['confirm_msg_pageid'];
	 $page = get_permalink($page_id);
	 wp_safe_redirect($page);
	 die();
	 else:
	 $suc_message = $process_cases->return->successMessage;
	 apply_filters('absp_cases_create_message',$suc_message);
	 $message =  '<div class="message">'.$suc_message.'</div>';
	 echo $message;
	 endif;
	endif;
	
	validate_load_script();
	$form_fields=$cases_fields_properties['fields']; //Cases Fields
	usort($form_fields, "awp_sort_by_order");
	
	$cases_fields = array();
	       
	        //Captcha code Genertion
			$possible_letters = '23456789bcdfghjkmnpqrstvwxyz';
			$characters_on_image = 6;
			$code = '';
			$i = 0;
			while ($i < $characters_on_image) { 
			$code .= substr($possible_letters, mt_rand(0, strlen($possible_letters)-1), 1);
			$i++;
			}
			$_SESSION['apptivo_business_cases_captcha'] = $code;
    $cases_fields_properties['properties']['captchaimagepath'] = AWP_PLUGIN_BASEURL.'/assets/captcha/captcha_code_file.php?captcha_code='.$code;
    $form_properties=$cases_fields_properties['properties'];//Case Properties      
  foreach( $form_fields as $FormFields) :
	if($FormFields['fieldid'] == 'status')
	{
		$FormFields['options'] = 'NEW';
	}else if($FormFields['fieldid'] == 'type')
	{
		//Don't change
		$FormFields['options'] = 'Product Questions
		Technical Issues
		Product Purchases
		Partnership Opportunities
		Feature Request
		Feedback
		Report a problem
		Other';                            
	}else if($FormFields['fieldid'] == 'priority')
	{
		//Don't change
		$FormFields['options'] = 'High
		Low
		Medium'; 
	}
	$push_fields = true;
	if( $FormFields['type'] == 'select'  || $FormFields['type'] == 'radio' || $FormFields['type'] == 'checkbox' ) :
	 if(trim($FormFields['options']) == '' ):
	 $push_fields = FALSE;
	 endif;
	endif;
	
	if($push_fields):
	array_push($cases_fields,$FormFields);
	endif;
	endforeach;

	$template_type = $form_properties['tmpltype'];
	$template_layout = $form_properties['layout'];
	if($template_type=="awp_plugin_template") : 
		$templatefile = AWP_CASES_TEMPLATEPATH."/".$template_layout; // Plugin templates	
	else :
		$templatefile=TEMPLATEPATH."/cases/".$template_layout; // theme templates
	endif;
	
	ob_start(); 
	//Cusom Css
	if( trim($form_properties['css']) != '') :
	 echo '<style type="text/css">'.trim($form_properties['css']).'</style>';
	endif;
		//Include Template
		include $templatefile;
	$content = ob_get_clean();
	return $content; 
	else:
	echo awp_messagelist('casesform-display-page');
	endif;
	
}
//validate_load_script
function validate_load_script()
{ 
	//wp_enqueue_style('style_absp_cases', AWP_PLUGIN_BASEURL.'/inc/cases/css/style.css' , false, '1.0.0', 'screen');
	wp_register_script('jquery_validation',AWP_PLUGIN_BASEURL. '/assets/js/validator-min.js',array('jquery'));
	wp_print_scripts('jquery_validation');
	
}
function process_cases_form($cases_fields_properties='') {
	
 if(isset($_POST['apptivo_cases_form'])):  
  $verification = check_blockip();
   if($verification){
   	 return $verification;
   }
    if($cases_fields_properties == '') :
     $cases_fields_properties  = get_option('absp_cases_form_fields'); // Get Form Fields and Properties. 
    endif;
    $customfields = '';
	$formFields = $cases_fields_properties['fields'];
		if(isset($_POST['captcha'])):
		    if(trim($_POST['captcha']) != $_SESSION['apptivo_business_cases_captcha'])
			{
				return 'CE001';
			}
        endif;
	foreach($formFields as $fields):
	   $fieldid=$fields['fieldid'];
	   $pos=strpos($fieldid, "customfield");
		if($pos===false) :
		else:
		 $customfieldVal = $_POST[$fieldid];
		 
		 if( is_array($customfieldVal)) :
		 $customfieldVal = implode(",", $customfieldVal);
		 endif;
		 	 if($customfieldVal != '') :
			 $customfields .= "<br/><b>".$fields['showtext']."</b>:".stripslashes($customfieldVal);
			 endif;
		endif;
	 endforeach;
	$customfields .= "<br/><b>Requested IP</b>:".stripslashes(get_RealIpAddr());
	      
		if(!empty($customfields)){
         $parent1details = nl2br($customfields);
         $noteDetails = notes('Custom Fields',$parent1details,$parent1NoteId);
        }
    $case = array();
    $case['firmid'] = null;
    $case['caseid'] = null;
    $case['firstName'] = stripslashes(trim($_POST['firstname']));
    $case['lastName'] = stripslashes(trim($_POST['lastname']));
    $case['emailId'] = stripslashes(trim($_POST['email']));
    $case['phoneNumber']=stripslashes(trim($_POST['phone']));
    $case['comments'] = null;
    $case['description'] = stripslashes(trim($_POST['description']));
    $case['type'] = stripslashes(trim($_POST['type']));
    $case['status'] = stripslashes(trim($_POST['status']));
    $case['priority'] = stripslashes(trim($_POST['priority']));
    $case['account'] = NULL;
    $case['productName'] = NULL;
    $case['subject'] = stripslashes(trim($_POST['subject']));
    $case['responseString']=null;
    $case['noteDetails']=$noteDetails;
    $case['userIdStr']=null;
    /* create an array for method inputs */
    $params = array (
            "arg0" => APPTIVO_SITE_KEY,  
            "arg1" => APPTIVO_ACCESS_KEY,
            "arg2" => $case,             
    );
    $response = getsoapCall(APPTIVO_BUSINESS_SERVICES,'createCase',$params);
    //Custom success Message.
    $properties = $cases_fields_properties['properties'];
    $suc_message = $properties['confmsg'];
    if($response->return->statusCode == 1000)
    {
    	if(strlen(trim($suc_message)) != 0):
    	$response->return->successMessage = $suc_message;
    	else:
    	$response->return->successMessage = $response->return->responseString;
    	endif;
    }
    return $response;
  else:
    return false;
  endif;
}
//Ajax Cases Form Submit.
add_action('wp_ajax_absp_cases', 'absp_cases');
add_action('wp_ajax_absp_cases', 'absp_cases');
function absp_cases(){	
    $process_cases = process_cases_form();
    if($process_cases) :
    if( $process_cases == 'E_IP')
	{   $error_message = awp_messagelist('IP_banned');
		apply_filters('absp_cases_ip_banned',$error_message);
		$captcha_error =  '<div class="iperror error">'.$error_message.'</div>';
		$valuepresent = TRUE;
		echo $captcha_error;		
	}else if( $process_cases == 'CE001')
	{  
		$error_message = awp_messagelist('caseform-captcha_error');
		apply_filters('absp_cases_captcha_error',$error_message);
		$captcha_error =  '<div class="captcha_error error">'.$error_message.'</div>';
		echo $captcha_error;		
	}else if( $process_cases == 'E_100')
	{
		$error_message = awp_messagelist('casesform-e100');
		apply_filters('absp_cases_e_100',$error_message);
		$e100_error =  '<div class="error">'.$error_message.'</div>';
		echo $e100_error;
	}else if( $process_cases->return->statusCode != 1000 )
	{
		$error_message = awp_messagelist('casesform-e100');
		apply_filters('absp_cases_e_100',$error_message);
		$e100_error =  '<div class="invalid keys error">'.$error_message.'</div>';
		$valuepresent = TRUE;
		echo $e100_error;
	}
	endif;
	
	
	if($process_cases->return->statusCode == 1000):
	 $suc_message = $process_cases->return->successMessage;
	 apply_filters('absp_cases_e_100',$suc_message);
	 $message =  '<div class="message">'.$suc_message.'</div>';
	 echo $message;
	endif;
	
	die();		
}
//Ajax csase form captchs refresh
add_action('wp_ajax_absp_cases_captcha_refresh', 'absp_cases_captcha_refresh');
add_action('wp_ajax_nopriv_absp_cases_captcha_refresh', 'absp_cases_captcha_refresh');
function absp_cases_captcha_refresh()
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
	$_SESSION['apptivo_business_cases_captcha'] = $code;
    $image_src = AWP_PLUGIN_BASEURL.'/assets/captcha/captcha_code_file.php?captcha_code='.$code;
	echo '<img style="border: 1px solid rgb(0, 0, 0);" id="cases_captchaimg" src="'.$image_src.'">';
	exit;
}
?>