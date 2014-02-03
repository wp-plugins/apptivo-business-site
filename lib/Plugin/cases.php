<?php
/**
 * Apptivo Cases Apps Plugin
 * @package apptivo-business-site
 * @author  RajKumar <rmohanasundaram[at]apptivo[dot]com>
 */
require_once AWP_LIB_DIR . '/Plugin.php';
class AWP_Cases extends AWP_Base
{
function &instance()
{
  static $instances = array();
  if (!isset($instances[0])) {
    $class = __CLASS__;
    $instances[0] = & new $class();
   }        
    return $instances[0];
    
}

function get_case_settings($formname){
		$formExists="";
		$cases_forms=array();
		$caseform=array();
		$formname=trim($formname);
		$cases_forms=get_option('awp_casesforms');
		if($formname=="")
			$formExists="";
		else if(!empty($cases_forms))
			$formExists = awp_recursive_array_search($cases_forms,$formname,'name' );

		if(trim($formExists)!=="" ){
			$caseform=$cases_forms[$formExists];
		}
		return $caseform;
	}   

function __construct()
{
$this->_plugin_activated = false;
$settings=array();
    	$this->_plugin_activated=false;
    	$settings=get_option("awp_plugins");
    	if(get_option("awp_plugins")!=="false"){
    		if($settings["cases"])
    			$this->_plugin_activated=true;
    	}
    	
$this->fields = array(
			array('fieldid' => 'subject','fieldname' => 'Subject','defaulttext' => 'Subject','must_require'=>1,'must'=>1,'showorder' => '1','validation' => 'text','fieldtype' => 'text'),
			array('fieldid' => 'description','fieldname' => 'Description','defaulttext' => 'Description','must_require'=>0,'must'=>0,'showorder' => '2','validation' => 'textarea','fieldtype' => 'textarea'),
			array('fieldid' => 'priority','fieldname' => 'Priority','defaulttext' => 'Priority','must_require'=>1,'must'=>1,'showorder' => '3','validation' => 'text','fieldtype' => 'select'),
			array('fieldid' => 'firstname','fieldname' => 'First Name','defaulttext' => 'First Name','must_require'=>0,'must'=>0,'showorder' => '4','validation' => 'text','fieldtype' => 'text'),
			array('fieldid' => 'lastname','fieldname' => 'Last Name','defaulttext' => 'Last Name','must_require'=>1,'must'=>1,'showorder' => '5','validation' => 'text','fieldtype' => 'text'),
			array('fieldid' => 'email','fieldname' => 'Email','defaulttext' => 'Email','showorder' => '6','must_require'=>1,'must'=>1,'validation' => 'email','fieldtype' => 'text'),
			array('fieldid' => 'phone','fieldname' => 'Telephone Number','defaulttext' => 'Telephone Number','must_require'=>0,'must'=>0,'showorder' => '7','validation' => 'phonenumber','fieldtype' => 'text'),
			array('fieldid' => 'type','fieldname' => 'Type','defaulttext' => 'Type','must_require'=>1,'must'=>1,'showorder' => '8','validation' => 'text','fieldtype' => 'select'),
			array('fieldid' => 'captcha','fieldname' => 'Captcha','defaulttext' => 'Captcha','must_require'=>1,'must'=>0,'showorder' => '9','validation' => 'text','fieldtype' => 'captcha'),
			array('fieldid' => 'customfield1','fieldname' => 'Custom Field 1','defaulttext' => 'Custom Field1','showorder' => '10','validation' => '','fieldtype' => 'select'),
			array('fieldid' => 'customfield2','fieldname' => 'Custom Field 2','defaulttext' => 'Custom Field2','showorder' => '11','validation' => '','fieldtype' => 'select'),
			array('fieldid' => 'customfield3','fieldname' => 'Custom Field 3','defaulttext' => 'Custom Field3','showorder' => '12','validation' => '','fieldtype' => 'select'),
			array('fieldid' => 'customfield4','fieldname' => 'Custom Field 4','defaulttext' => 'Custom Field4','showorder' => '13','validation' => '','fieldtype' => 'radio'),
			array('fieldid' => 'customfield5','fieldname' => 'Custom Field 5','defaulttext' => 'Custom Field5','showorder' => '14','validation' => '','fieldtype' => 'checkbox')          
		);

$this->validations = array(
			array('validationLabel' => 'None','validation' => 'none'),
			array('validationLabel' => 'Email ID','validation' => 'email'),
			array('validationLabel' => 'Number','validation' => 'number')
			);

$this->fieldtypes = array(
			array('fieldtypeLabel' => 'Checkbox','fieldtype' => 'checkbox'),
			array('fieldtypeLabel' => 'Radio Option','fieldtype' => 'radio'),
			array('fieldtypeLabel' => 'Select','fieldtype' => 'select'),
			array('fieldtypeLabel' => 'Textbox','fieldtype' => 'text'),
			array('fieldtypeLabel' => 'Textarea','fieldtype' => 'textarea')
			);

}

 /**
     * Runs plugin
     */
function run()
{
  if($this->_plugin_activated){
	    add_shortcode('apptivocases',array(&$this,'apptivo_business_casesnew'));
  }
}

//validate_load_script

function validate_load_script()
{
	wp_register_script('jquery_validation',AWP_PLUGIN_BASEURL. '/assets/js/validator-min.js',array('jquery'));
	wp_print_scripts('jquery_validation');

}


function get_cases_form_fields($formname)
{
            $caseform=array();
            $cases_forms=array();
            $formexist="";
            $casesformdetails=array();
            $formname=trim($formname);

            $cases_forms=get_option("awp_casesforms");
            $formexists=awp_recursive_array_search($cases_forms,$formname,'name');

            //echo 'formname==='.$formname.'form exists==='.$formexists;
            //echo 'cases forms====='.$cases_forms;
            
            if(trim($formexists)!="")
            {
                $caseform=$cases_forms[$formexists];

                $formFields = $caseform['fields'];
		
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
                return $caseform;
            }

}



function apptivo_business_casesnew($atts)
{
	$cases_fields_properties  = get_option('awp_casesforms');

	
        extract(shortcode_atts(array('name'=>  ''), $atts));
	ob_start();
	$formname=trim($name);
        
        $case_form=$this->get_cases_form_fields($formname);
        $submitformname=$_POST['awp_caseformname'];
        
        $success_message="";
        if(isset ($_POST["awp_casesforms_submit"])  && $submitformname==$formname )
        {
                
                $customfields = '';
                $formFields = $case_form['fields'];
		if(isset($_POST["recaptcha_challenge_field"]))
                {
                    $captcha_error="";
                    $response_field =   $_POST["recaptcha_response_field"];
                    $challenge_field=   $_POST["recaptcha_challenge_field"];
                    $option=get_option('apptivo_business_recaptcha_settings');
                    $option=json_decode($option);
                    $private_key    =   $option->recaptcha_privatekey;
                    $response=    captchaValidation($private_key, $challenge_field, $response_field);
                    if($response!="1")
					{
                        $value_present = true;
                        $captcha_error = awp_messagelist("recaptcha_error");
                        $success_message = awp_messagelist("recaptcha_error");
                    }
                        else
                        {
                            $captcha_error="";
                            $success_message="";
                        }
                }
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
        if(empty ($captcha_error))
        {
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
    $case['type_name'] = stripslashes(trim($_POST['type_name']));
    $case['status'] = stripslashes(trim($_POST['awp_cases_status']));
    $case['priority'] = stripslashes(trim($_POST['priority']));
    $case['priority_name']=stripslashes(trim($_POST['priority_name']));
    $case['account'] = NULL;
    $case['productName'] = NULL;
    $case['subject'] = stripslashes(trim($_POST['subject']));
    $case['responseString']=null;
    $case['noteDetails']=$noteDetails;
    $case['userIdStr']=null;
    /* create an array for method inputs */
$caseStatus= $case['status'];
$caseStatusId= stripslashes(trim($_POST['awp_cases_values']));
$caseType  = $case['type_name'];
$caseTypeId  = $case['type'];
$casePriority= $case['priority_name'];
$casePriorityId= $case['priority'];
$emailId=$case['emailId'];
$caseSummary= $case['subject'];
$caseDescription= $case['description'];
$caseNumber	=	'Auto generated number';
$caseData   ='{"caseNumber":"'.stripslashes(trim($caseNumber)).'","caseStatus":"'.$caseStatus.'","caseStatusId":"'.$caseStatusId.'","":"","caseType":"'.$caseType.'","caseTypeId":"'.$caseTypeId.'","casePriority":"'.$casePriority.'","casePriorityId":"'.$casePriorityId.'","assignedObjectRefName":"'.$emailId.'","caseSummary":"'.addslashes($caseSummary).'","description":"'.addslashes($caseDescription).'","followUpDate":null,"followUpDescription":null,"caseItem":"","caseItemId":null,"caseProject":"","caseProjectId":null,"dateResolved":"","createdByName":"","lastUpdatedByName":"","creationDate":"","lastUpdateDate":"","caseCustomer":"","caseCustomerId":null,"caseContact":"","caseContactId":null,"caseEmail":"'.$emailId.'","addresses":[]}';
$params = array (
                "a" => "createCase",
                "caseData"  => $caseData,
                "fromObjectId"=>	"null",
                "fromObjectRefId" =>	"null",
                "apiKey" => APPTIVO_BUSINESS_API_KEY,
                "accessKey" => APPTIVO_BUSINESS_ACCESS_KEY
                );
$verification = check_blockip();
		   if($verification){
		   	  $success_message= awp_messagelist('IP_banned');
		   	  
		   }
		   else{                  
	if(!_isCurl()) 
	{
	$params = array (
            "arg0" => APPTIVO_BUSINESS_API_KEY,  
            "arg1" => APPTIVO_BUSINESS_ACCESS_KEY,
            "arg2" => $case,             
    );
    $response = getsoapCall(APPTIVO_BUSINESS_SERVICES,'createCase',$params);
    //Custom success Message.
    $properties = $case_form['properties'];
    $success_message = $properties['confmsg'];
   }	
	else{	   	
    $response=getRestAPICall("POST", APPTIVO_CASES_API,$params);
    $caseId	= $response->csCase->caseId;
               if($noteDetails!="")
   {
      $noteText=$noteDetails->noteText;
      $caseNotes='{"noteText":"'.$noteText.'"}';
      $param = array (
                "a"         => "save",
                "objectId"  => APPTIVO_CASES_OBJECT_ID,
                "objRefId"  => "$caseId",
                "noteData"  =>  "$caseNotes",
                "apiKey"=> APPTIVO_BUSINESS_API_KEY,
                "accessKey"=> APPTIVO_BUSINESS_ACCESS_KEY
                );
     $notesResponse= getRestAPICall("POST", APPTIVO_NOTES_API,$param);
     $noteid=$notesResponse->noteId;
  }
    $properties = $case_form['properties'];
    $success_message = $properties['confmsg'];
	}
    if($success_message=="")
    {
    	$success_message="Case Submitted Successfully";
    }
    if($response->return->statusCode == 1000)
    {
    	if(strlen(trim($success_message)) != 0):
    	$response->return->successMessage = $success_message;
    	else:
    	$response->return->successMessage = $response->return->responseString;
    	endif;
    }
        }
        }
        }

        if(strlen(trim($success_message)) != 0 && $properties['confirm_msg_page'] == 'other' ) :
                $location = get_permalink($properties['confirm_msg_pageid']);
	            wp_safe_redirect($location);
        else :
           // echo '<center>'. trim($success_message).'</center>';
            
	endif;


            
        if ($case_form):
            
        $cases_properties = $case_form['properties'];
        

        $this->validate_load_script();
	$form_fields=$case_form['fields']; //Cases Fields
	usort($form_fields, "awp_sort_by_order");

	$cases_fields = array();
    $form_properties=$case_form['properties'];//Case Properties
    $form_fields[] = array("fieldid"=>"status");
    if(!_isCurl()){
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
    }
    else {
    foreach( $form_fields as $FormFields) :
	if($FormFields['fieldid'] == 'status')
	{
            $case_status  = get_option("awp_cases_status");
            $case_status  = json_decode($case_status);
            foreach ($case_status as $casestatus)
            {
                if($casestatus->meaning=="New")
                {
                $status_type[]= $casestatus->meaning;
                $status_type_value[]=$casestatus->lookupId;
                }
            }
            $formnames =implode("\n", $status_type);
            $formvalues =implode("\n", $status_type_value);
            $FormFields['options'] = $formnames;
            $FormFields['value']=$formvalues;
            
	}else if($FormFields['fieldid'] == 'type')
	{
		//Don't change

            $case_type  = get_option("awp_cases_type");
            $case_type  = json_decode($case_type);
            foreach ($case_type as $casetype)
            {
                $type[]= $casetype->meaning;
                $type_value[]=$casetype->lookupId;
            }
            $formnames =implode("\n", $type);
            $formvalues =implode("\n", $type_value);
            $FormFields['options'] = $formnames;
            $FormFields['value']=$formvalues;
        
	}else if($FormFields['fieldid'] == 'priority')
	{

//Don't change
            $case_priority  = get_option("awp_cases_priority");
            $case_priority  = json_decode($case_priority);
            foreach ($case_priority as $casepriority)
            {
                $type[]= $casepriority->meaning;
                $type_value[]=$casepriority->lookupId;
            }
            $formnames =implode("\n", $type);
            $formvalues =implode("\n", $type_value);
            $FormFields['options'] = $formnames;
            $FormFields['value']=$formvalues;
            unset($type);
            unset($type_value);
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
    }
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

function settings(){
//Theme Templates
$themetemplates = get_awpTemplates(TEMPLATEPATH.'/cases','Plugin');
$plugintemplates=$this->get_plugin_templates();
arsort($plugintemplates);
	
if(isset($_POST['awp_cases_settings'])):
if(_isCurl())
{    
	$case_status  = getCaseValues("CASE_STATUS");
    $case_priority= getCaseValues("CASE_PRIORITY");
    $case_type= getCaseValues("CASE_TYPE");
    $case_status=json_encode($case_status);
    $case_priority=json_encode($case_priority);
    $case_type=json_encode($case_type);
}
    
 if(get_option ('awp_cases_status')=="")
    {
   add_option("awp_cases_status",$case_status);
    }
    else
    {
        update_option("awp_cases_status",$case_status);
    }
  if(get_option ('awp_cases_priority')=="")
    {
   add_option("awp_cases_priority",$case_priority);
    }
    else
    {
        update_option("awp_cases_priority",$case_priority);
    }
    if(get_option ('awp_cases_type')=="")
    {
   add_option("awp_cases_type",$case_type);
    }
    else
    {
        update_option("awp_cases_type",$case_type);
    }
    $newformname=$_POST['awp_cases_name'];
//Cases Form Propertieds.

//template Type& Template Layout
if($_POST['awp_cases_templatetype']=="awp_plugin_template"):
	$templatelayout=$_POST['awp_cases_plugintemplatelayout'];
else:
	$templatelayout=$_POST['awp_cases_themetemplatelayout'];
endif;

$casesform_properties=array( 'tmpltype' =>$_POST['awp_cases_templatetype'],
                             'layout' =>$templatelayout, 
	                         'confmsg' => stripslashes($_POST['awp_cases_confirmationmsg']),
			                 'confirm_msg_page' => $_POST['awp_cases_confirm_msg_page'],
							 'confirm_msg_pageid' => $_POST['awp_cases_confirmmsg_pageid'],
							 'css' => stripslashes($_POST['awp_cases_customcss']),
                             'submit_button_type' => $_POST['awp_cases_submit_type'],
                             'submit_button_val' => $_POST['awp_cases_submit_value'] );

//New Custom fields 
			$stack = array();
			$addtional_custom = array();
			$addtional_order = 15;
			for($i=6;$i<20;$i++)
			{  
				if(isset($_POST['customfield'.$i.'_newest']) )
				{
					$addtional_custom = array('fieldid' => 'customfield'.$i.'','fieldname' => 'Custom Field '.$i.'',
					                     'defaulttext' => 'Custom Field'.$i.'','showorder' => $addtional_order,'validation' => '',
					                     'fieldtype' => 'select');
					$addtional_order++;
					array_push($stack, $addtional_custom);
				}else {
					break;
				}
			}
			
			if(!empty($stack)) :
			 update_option('awp_addtional_custom_cases',$stack);
			endif;
			
       //General Cases form fields

		//For Additional custom fields.
		$addtional_custom = get_option('awp_addtional_custom_cases');
		$master_field = array();
		if(!empty($addtional_custom)):
		$master_field = array_merge($this->fields,$addtional_custom);
		else:
		$master_field = $this->fields;
		endif;
		
		
			$casesformfields=array();
			foreach( $master_field as $fieldsmasterproperties )
			{
				$enabled=0;
				$contactformfield=array();
				$fieldid=$fieldsmasterproperties['fieldid'];
				
                                if(!empty ($_POST[$fieldid.'_order'])){
                                    $displayorder = $_POST[$fieldid.'_order'];
                                }
                                else{
                                    $displayorder = $fieldsmasterproperties['showorder'];
                                }
                                 if(!empty ($_POST[$fieldid.'_text'])){
                                    $displaytext = $_POST[$fieldid.'_text'];
                                }
                                else{
                                    $displaytext = $fieldsmasterproperties['defaulttext'];
                                }
                                 
			                   if($fieldsmasterproperties['must'])
                                {
                                    $enabled = 1;
                                    $required = 1;
                                }
                                else if($fieldid=='captcha')
                                {
                                    $enabled = $_POST[$fieldid.'_show'];
                                    $required = 1;
                                }
                                else
                                {
                                    $enabled = $_POST[$fieldid.'_show'];
                                    $required = $_POST[$fieldid.'_require'];
                                }
				if($enabled){
					$casefield=$this->createformfield_array($fieldid,$displaytext,$required,$_POST[$fieldid.'_type'],$_POST[$fieldid.'_validation'],$_POST[$fieldid.'_options'],$displayorder);
					array_push($casesformfields, $casefield);
				}
			}
			
			if(!empty($casesformfields)){
				$newcaseformdetails=array('name'=>$newformname,'properties'=>$casesform_properties,'fields'=>$casesformfields);
				$cases_forms=array();
                $cases_forms=get_option('awp_casesforms');
				$formExists="";
				if(!empty($cases_forms))
					$formExists = awp_recursive_array_search($cases_forms,$newformname,'name' );
				if(trim($formExists)!=="" ){

					unset($cases_forms[$formExists]);
					array_push($cases_forms, $newcaseformdetails);
					sort($cases_forms);

					update_option('awp_casesforms',$cases_forms);
					$cases_forms=get_option('awp_casesforms');
					$updatemessage= "Cases Form '".$newformname."' settings updated. Use Shortcode '[apptivocases name=\"".$newformname."\"]' in your page to use this form.";
				}

			}else{
				$updatemessage="<span style='color:red;'>Select atleast one Form field for Case Form.</span>";
			}
			$selectedcasesform=$newformname;
endif;


$absp_cases_fields_properties = get_option('awp_casesforms');
if(isset($absp_cases_fields_properties['fields']) && isset($absp_cases_fields_properties['properties']))
{
$fields=$absp_cases_fields_properties['fields']; //Cases Fields
$formproperties=$absp_cases_fields_properties['properties'];//Case Properties
}	
echo '<div class="wrap"><h2>Apptivo Cases Form</h2></div>';
checkCaptchaOption();
?>
<?php 
if(!$this->_plugin_activated)
{
	echo "Cases form is currently <span style='color:red'>disabled</span>. Please enable this in <a href='".SITE_URL."/wp-admin/admin.php?page=awp_general&tab=plugins'>Apptivo General Settings</a>.";
}
echo awp_flow_diagram('cases',1);

//saving the cases form name

$cases_forms=array();
$casesformdetails=array();
add_option('awp_casesforms');
$cases_forms=get_option('awp_casesforms');

if(isset($_POST['newcasesformname']))
		{
            $newcasesformname =   $_POST['newcasesformname'];
            $newcasesformname = preg_replace('/[^\w]/', '', $newcasesformname);
            $newcasesformname=trim($newcasesformname);
            if($newcasesformname!='')
			{
                                
				$casesform=array();
				$casesform=$this->get_case_settings($newcasesformname);
				if( count($casesform)==0 )
				{
					$newcasesformname_array =array("name"=>$newcasesformname);
					$newcasesform=array($newcasesformname_array);
					if( empty($cases_forms) ){

						update_option('awp_casesforms',$newcasesform);
					}else{
						array_push($cases_forms, $newcasesformname_array);


                                                update_option('awp_casesforms',$cases_forms);
					}
					$cases_forms=get_option('awp_casesforms');
					$casesform=$this->get_case_settings($newcasesformname);
					$selectedcasesform=$newcasesformname;
					$updatemessage= "Cases Form created. Please configure settings using the below Configuration section.";
				}else{
					$updatemessage= "<span style='color:#f00;'>Form already exists. To change configuration, please select the form from below configuration section.</span>";
				}
			}else{
					$updatemessage= "Form Name cannot be empty.";
			}
		}

        echo '<div class="wrap">';
		// header
		
		echo '<div class="casesform_err">';
                
                echo'</div>';
		//if updatemessage is not empty display the div
                if(trim($updatemessage)!=""){
		?>
		<div id="message" class="updated">
	        <p>
	        <?php echo $updatemessage;?>
	        </p>
	    </div>
                <?php
                }


                if(isset($_POST['awp_caseform_select_form']))
		{
			$selectedcasesform =  trim( $_POST['awp_caseform_select_form']);
			if($selectedcasesform!='')
			{
				$caseform=array();
				$caseform=$this->get_case_settings($selectedcasesform);
				if( empty($caseform))
				{
					//echo "Selected form configuration doestn exist.";
				}else{
					$caseformdetails=$caseform;
				}
			}
		}

        if($_POST['delformname'])   //Delete Form Name:
		{
			if(strlen(trim($_POST['delformname'])) != 0)
			{
				$formname = $_POST['delformname'];
				$cases_forms=get_option('awp_casesforms');
				$formExists = awp_recursive_array_search($cases_forms,$formname,'name' );
				if(isset($formExists))
				{
					unset($cases_forms[$formExists]);
				}
				$case_sort_form = array();
				foreach($cases_forms as $cases_forms_tosort )
				{
					array_push($case_sort_form,$cases_forms_tosort);
				}

				update_option('awp_casesforms', $case_sort_form);
				$updatemessage= 'Contact Form "'.$formname.'" Deleted Successfully.';
			}
		}

                ?>

	<form name="awp_cases_new_form" id="awp_cases_new_form" method="post" action="" >
         	<p>
		<?php _e("Cases Form Name", 'apptivo-businesssite' ); ?>
			<span style="color:#f00;">*</span>&nbsp;&nbsp;<input type="text" name="newcasesformname" id="newcasesformname" size="20" maxlength="50" value="" >
			<span class="description"><?php _e('This form name will be used as your Lead Source in Apptivo.','apptivo-businesssite'); ?></span>
		</p>
			<p>
			<input <?php echo $disabledForm;?> type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Add New') ?>" />
		</p>

	</form>

<?php if(!empty($cases_forms)) { ?>

        <br>
	<hr />
			<?php
			echo "<h2>" . __( 'Cases Form Configuration', 'awp_casesforms' ) . "</h2>";
			?>

 <?php
			if(trim($selectedcasesform)=="" && $cases_forms!= ""){
				$selectedcasesform=$cases_forms[0][name];
			}
			$caseformdetails=$this->get_case_settings($selectedcasesform);
			if(count($caseformdetails)>0){
				$selectedcasesform=$caseformdetails[name];
				$fields=$caseformdetails[fields];
				$formproperties=$caseformdetails[properties];
			}
			?>

<table class="form-table">
			<tbody>
			<?php if(empty($formproperties[tmpltype])) :  //To check contact form settings are save or not.
			        echo '<span style="color:#f00;"> Save the below settings to get the Shortcode for case form.</span>';
			       endif; ?>
				<tr valign="top">
					<th valign="top"><label for="awp_caseform_select_form"><?php _e("Case Form", 'apptivo-businesssite' ); ?>:</label>
					</th>
					<td valign="top">
					<form name="awp_cases_selection_form" method="post" action="" style="float:left;" >
					<select name="awp_caseform_select_form" id="awp_caseform_select_form" onchange="this.form.submit();">
						<?php
						for($i=0; $i<count($cases_forms); $i++)
						{ ?>
							<option value="<?php echo $cases_forms[$i][name]?>"
							<?php if(trim($selectedcasesform)==$cases_forms[$i][name])
							echo "selected='true'";?>>
							<?php echo $cases_forms[$i][name]?>
							</option>
							<?php } ?>
					</select>

					</form>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<?php if($this->_plugin_activated)
					{ ?>
					<form name="awp_contact_delete_form" method="post" action="" style="float:left;padding-left:30px;">
					<a  href="javascript:contact_confirmation('<?php echo $selectedcasesform; ?>')" >Delete</a>
					<input type="hidden" name="delformname" id="delformname" value="<?php echo $selectedcasesform ?>"  />
					</form>
					<?php } ?>
					</td>

				</tr>
			</tbody>
		</table>

<form name="awp_cases_settings_form" method="post" action="">
<table class="form-table">
<tr valign="top">

<?php if(!empty($formproperties[tmpltype])) :?>
        <tbody>

				<tr valign="top">
					<th valign="top"><label for="cases_shortcode"><?php _e("Form Shortcode", 'apptivo-businesssite' ); ?>:</label>
					<br><span class="description"><?php _e('Copy and Paste this shortcode in your page to display the cases form.','apptivo-businesssite'); ?></span>
					</th>
					<td valign="top"><span id="awp_cases_shortcode" name="awp_cases_shortcode">
					<input style="width:300px;" type="text" id="cases_shortcode" name="cases_shortcode"  readonly="true" value='[apptivocases name="<?php echo $selectedcasesform?>"]' />
					</span>
					</td>
				</tr>
<?php endif; ?>
				
					<th valign="top"><label for="awp_cases_templatetype"><?php _e("Template Type", 'apptivo-businesssite' ); ?>:</label>
					</th>
					<td valign="top">
					<input type="hidden" id="awp_cases_name" name="awp_cases_name" value="<?php echo $selectedcasesform;?>">
					
						<select name="awp_cases_templatetype" id="awp_cases_templatetype" onchange="cases_change_template();">
							<option value="awp_plugin_template"  <?php selected($formproperties[tmpltype],'awp_plugin_template'); ?> >Plugin Templates</option>
							<?php if(!empty($themetemplates)) : ?>
							<option value="theme_template"  <?php selected($formproperties[tmpltype],'theme_template'); ?> >Templates from Current Theme</option>
							<?php endif; ?>
						</select>
					
					</td>
				</tr>
				<tr valign="top">
					<th valign="top"><label for="awp_cases_templatelayout"><?php _e("Template Layout", 'apptivo-businesssite' ); ?>:</label>					
					</th>
					<td valign="top">
					<?php  if( sizeof($plugintemplates) > 0 ) : ?>
					<select name="awp_cases_plugintemplatelayout" id="awp_cases_plugintemplatelayout" <?php if($formproperties['tmpltype'] == 'theme_template' ) echo 'style="display: none;"'; ?> >
						<?php foreach (array_keys( $plugintemplates ) as $template ) { ?>
							<option value="<?php echo $plugintemplates[$template]?>" <?php selected($formproperties[layout],$plugintemplates[$template]); ?> >
							<?php echo $template?>
							</option>
							<?php }  ?>
					</select> 
					<?php else : echo 'No templates available'; endif;?>
					<select name="awp_cases_themetemplatelayout" id="awp_cases_themetemplatelayout" <?php if($formproperties['tmpltype'] != 'theme_template' ) echo 'style="display: none;"'; ?> >
						<?php foreach (array_keys( $themetemplates ) as $template ) : ?>
							<option value="<?php echo $themetemplates[$template]?>" <?php selected($formproperties['layout'],$themetemplates[$template]);?> >
							<?php echo $template?>
							</option>
							<?php endforeach;?>
					</select>
					
					</td>
				</tr>
				<tr valign="top">
					<th><label for="awp_cases_customcss"><?php _e("Confirmation message page", 'apptivo-businesssite' ); ?>:</label>
					</th>
					<td valign="top">
                          <input type="radio" value="same"  id="same_page" name="awp_cases_confirm_msg_page" <?php checked('same',$formproperties[confirm_msg_page]); ?> checked="checked" /><label for="same_page"> Same Page</label>
                          <input type="radio" value="other" id="other_page" name="awp_cases_confirm_msg_page" <?php checked('other',$formproperties[confirm_msg_page]); ?>/> <label for="other_page"> Other page</label>
                          <br />
                           <br />
                           <select id="awp_cases_confirmmsg_pageid" name="awp_cases_confirmmsg_pageid" <?php if($formproperties[confirm_msg_page] != 'other') echo 'style="display:none;"';?> >                      
							 <?php
							  $pages = get_pages(); 
							  foreach ($pages as $pagg) {
							  	?>
							  	<option value="<?php echo $pagg->ID; ?>"  <?php selected($pagg->ID, $formproperties[confirm_msg_pageid]); ?> >
								<?php echo $pagg->post_title; ?>
								</option>
							  	<?php 
							  }
							 ?>	
							 </select>
							 
					</td>
					</td>					
				</tr>
				<tr valign="top" id="awp_cases_confirmationmsg_tr" <?php if($formproperties[confirm_msg_page] == 'other') echo 'style="display:none;"';?> >
					<th valign="top"><label for="awp_cases_confirmationmsg"><?php _e("Confirmation Message", 'apptivo-businesssite' ); ?>:</label>
					<br><span class="description">This message will shown in your website page, once cases form submitted.</span>
					</th>
					<td valign="top">
					<div style="width:620px;">
					<?php the_editor($formproperties[confmsg],'awp_cases_confirmationmsg','',FALSE);  ?>
					</div>
					</td>
				</tr>
				<tr valign="top">
					<th><label for="awp_cases_customcss"><?php _e("Custom CSS", 'apptivo-businesssite' ); ?>:</label>
					<br><span valign="top" class="description">Style class provided here will override template style. Please refer Apptivo plugin help section for class name to be used.</span>
					</th>
					<td valign="top"><textarea name="awp_cases_customcss"
							id="awp_cases_customcss" size="100" cols="40" rows="10"><?php echo $formproperties[css];?></textarea>
					</td>
					
				</tr>
                    <tr valign="top">
					<th><label id="awp_cases_submit_type" for="awp_cases_submit_type"><?php _e("Submit Button Type", 'apptivo-businesssite' ); ?>:</label>
					<br><span valign="top" class="description"></span>
					</th>

                    <td valign="top">
                       <input type="radio" value="submit" id="submit_button" name="awp_cases_submit_type" <?php checked('submit',$formproperties[submit_button_type]); ?> checked="checked" /> <label for="submit_button">Button</label>
                       <input type="radio" value="image" id="submit_image" name="awp_cases_submit_type"<?php checked('image',$formproperties[submit_button_type]); ?>/><label for="submit_image">Image</label>
					</td>
				</tr>
                <tr valign="top">
					<th><label for="awp_cases_submit_val"  id="awp_cases_submit_val" ><?php _e("Button Text", 'apptivo-businesssite' ); ?>:</label>
					<br><span valign="top" class="description"></span>
					</th>
                    <td valign="top"><input type="text" name="awp_cases_submit_value" id="awp_cases_submit_value" value="<?php echo $formproperties[submit_button_val];?>" size="52"/>
                    <span id="upload_img_button" style="display:none;">
                    <input id="cases_upload_image" type="button" value="Upload Image" class="button-primary" />
					<br /><?php _e('Enter an URL or upload an image.','apptivo-businesssite'); ?>
					</span>
					</td>
				</tr>
</table>

<?php
       //For Additional custom fields.
		$addtional_custom = get_option('awp_addtional_custom_cases');
		$master_field = array();
		if(!empty($addtional_custom)):
		$master_field = array_merge($this->fields,$addtional_custom);
		else:
		$master_field = $this->fields;
		endif;	
?>
<table width="900" cellspacing="0" cellpadding="0" id="cases_form_fields" name="cases_form_fields" style="border-collapse: collapse;">
<br /><h3>Cases Form Fields</h3>
<div style="margin: 10px;">Select and configure list of fields from below table to show in your cases form.</div>
			<tbody>
				<tr>
					<th></th>
				</tr>
				<tr align="center"
					style="background-color: rgb(223, 223, 223); font-weight: bold;"
					class="widefat">
	
					<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Field Name','apptivo-businesssite'); ?></td>
					<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Show','apptivo-businesssite'); ?></td>
					<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Require','apptivo-businesssite'); ?></td>
					<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Display Order','apptivo-businesssite'); ?></td>
					<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Display Text','apptivo-businesssite'); ?></td>
					<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Field Type','apptivo-businesssite'); ?></td>
					<td align="center" style="width:100px;border: 1px solid rgb(204, 204, 204);"><?php _e('Validation Type','apptivo-businesssite'); ?></td>
					<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Option Values','apptivo-businesssite'); ?></td>
				</tr>
				<tr>
					<th></th>
				</tr>
				<?php
				
				$pos = 0;
	            $index_key = 0;
				foreach( $master_field as $fieldsmasterproperties )
				{   
					$enabled=0;$required=0;
					$fieldExists=array();
					$fieldid=$fieldsmasterproperties['fieldid'];
					
					 if($fieldsmasterproperties['must']) :
					 $enabled =1;
                     $required =1;
                     endif;
                     
                     if($fieldid == 'captcha') :
                     $required = 1;                      
                     endif;

                    if(!empty($fields))
					{
						$fieldExistFlag= awp_recursive_array_search($fields, $fieldid, 'fieldid');	
					}
					
					if(trim($fieldExistFlag)!=="")
					{
						$enabled=1;
						$fieldData=array("fieldid"=>$fieldid,
										 "fieldname"=>$fieldsmasterproperties['fieldname'],
										 "show"=>$enabled,
										 "required"=>$fields[$fieldExistFlag]['required'],
										 "showtext"=>$fields[$fieldExistFlag]['showtext'],
										 "type"=>$fields[$fieldExistFlag]['type'],
						                 "must_require"=> $fieldsmasterproperties['must_require'],
										 "validation"=>$fields[$fieldExistFlag]['validation'],
										 "options"=>$fields[$fieldExistFlag]['options'],
										 "order"=>$fields[$fieldExistFlag]['order']);
					}else{					
						$fieldData=array("fieldid"=>$fieldid,
										 "fieldname"=>$fieldsmasterproperties['fieldname'],
										 "show"=>$enabled,
										 "required"=>$required,
										 "showtext"=>$fieldsmasterproperties['defaulttext'],
										 "type"=> $fieldsmasterproperties['fieldtype'],
						                 "must_require"=> $fieldsmasterproperties['must_require'],
										 "validation"=>"",
										 "options"=>"",
										 "order"=>"");
				
					}
				 $pos=strpos($fieldsmasterproperties['fieldid'], "customfield");
                                 ?>
				<tr >
				    <!--  Field Name -->
					<td
						style="border: 1px solid rgb(204, 204, 204); padding-left: 10px;width:150px;"><?php echo $fieldData['fieldname']?>
						
						<?php if($index_key > 13 ) : ?>
					<input type="hidden" id="<?php echo $fieldData['fieldid']?>_newest" name="<?php echo $fieldData['fieldid']?>_newest" value="dd" />
					<?php endif; $index_key++; ?>	
					
					</td>
					
						
					 <!--  Field To Show -->
					<td align="center" style="border: 1px solid rgb(204, 204, 204);">
					<input
					<?php  if($enabled) { ?> checked="checked"  <?php }  if($fieldsmasterproperties['must']) { ?>  disabled="disabled" <?php }  ?> type="checkbox"  id="<?php echo $fieldData['fieldid']?>_show" name="<?php echo $fieldData['fieldid']?>_show" size="30"
					onclick="casesform_enablefield('<?php echo $fieldData['fieldid']?>')">
					</td> 
					
					 <!--  Field To Require -->
					<td align="center" style="border: 1px solid rgb(204, 204, 204);">
					<input <?php if($fieldData['required'] ) { ?>checked="checked" <?php }?> <?php if(!$enabled || ($fieldData['must_require'])) { ?> disabled="disabled" <?php } ?> type="checkbox"                                        
						id="<?php echo $fieldData['fieldid']?>_require"
						name="<?php echo $fieldData['fieldid']?>_require" size="30"></td>
						
					 <!--  Display Order -->	
<td align="center" style="border: 1px solid rgb(204, 204, 204);">
					<input type="text" style="text-align:center;" onkeypress="return isNumberKey(event)"  id="<?php echo $fieldData['fieldid']?>_order"
						name="<?php echo $fieldData['fieldid']?>_order"
						value="<?php echo $fieldData['order']; ?>" size="3"
						maxlength="2" <?php if(!$enabled) { ?> disabled="disabled" <?php } ?>></td>
						
					 <!--  Display Text -->		
					<td align="center" style="border: 1px solid rgb(204, 204, 204);"><input
					<?php if(!$enabled) { ?> disabled="disabled" <?php } ?>
						type="text" id="<?php echo $fieldData['fieldid']?>_text"
						name="<?php echo $fieldData['fieldid']?>_text"
						value="<?php echo $fieldData['showtext']; ?>"></td>
					
					 <!--  Field Type -->		
					<td align="center" style="border: 1px solid rgb(204, 204, 204);">
					<?php
					$name_postfix="type";
					if($pos===false){
						?>
						<input 
						type="hidden"
						id="<?php echo $fieldData['fieldid']?>_type"
						name="<?php echo $fieldData['fieldid']?>_type"
						value="<?php echo $fieldData['type']; ?>" >
						<input 
						<?php if(!$enabled) { ?> disabled="disabled" <?php } ?> size="6" readonly="readonly"
						type="text" id="<?php echo $fieldData['fieldid']?>_typehiddentext"
						name="<?php echo $fieldData['fieldid']?>_typehiddentext"
						value="<?php echo $fieldData['type']; ?>" ><?php
						$name_postfix="type_select";	
					}else{
						?>
					<select name="<?php echo $fieldData['fieldid']?>_type" id="<?php echo $fieldData['fieldid']?>_type" 
					<?php
						
						if($pos===false) {?>readonly="readonly"<?php }
						if(!$enabled || ($pos===false)) { ?> disabled="disabled" <?php } ?>
						onChange="casesform_showoptionstextarea('<?php echo $fieldData['fieldid']?>');"
					>
					<?php foreach( $this->fieldtypes as $masterfieldtypes )
				{ ?>
					
					<option value="<?php echo $masterfieldtypes['fieldtype'];?>" 
					<?php if($masterfieldtypes['fieldtype']==$fieldData['type']){?>
					
					selected="selected"<?php }?>><?php echo $masterfieldtypes[fieldtypeLabel];?></option>
					<?php }?>
					
					</select>
					<?php }
					?>
					</td>
					
					<!-- Validation Type -->
					<td align="center" style="width:100px;border: 1px solid rgb(204, 204, 204);">
					<?php  $pos=strpos($fieldsmasterproperties['fieldid'], "customfield");
                                         ?>
                                        <?php if($pos===false){
                                        ?><input
						type="hidden"
						id="<?php echo $fieldData['fieldid']?>_validation"
						name="<?php echo $fieldData['fieldid']?>_validation"
						<?php if($fieldid=="email"){
							?>value="email"
							<?php }else if($fieldid=="phone"){ ?>
							value="phonenumber"
							<?php }else{ ?>
							value="none"
							<?php }?> >
						<input style="width:100px;"
						<?php if(!$enabled) { ?> disabled="disabled" <?php } ?> size="6" readonly="readonly"
						type="text" id="<?php echo $fieldData['fieldid']?>_validationhidden"
						name="<?php echo $fieldData['fieldid']?>_validationhidden"
						<?php if($fieldid=="email"){
							?>value="Email Id"
							<?php }else if($fieldid=="phone"){ ?>
							value="Phone Number"
							<?php }else{ ?>
							value="None"
							<?php }?> > <?php
                                        }
                                        else{
                                        	
                                        ?>
                                        <select name="<?php echo $fieldData['fieldid']?>_validation" id="<?php echo $fieldData['fieldid']?>_validation"
					<?php if(!$enabled ) { ?> disabled="disabled" <?php }
						if( ($fieldData['type'] != 'text' && (strtolower($fieldData['validation']) == 'none' || strtolower($fieldData['validation']) == ''))) {?>disabled="disabled"<?php }?>
					>
					<?php foreach( $this->validations as $masterfieldtypes )
                                        { ?>
					<option value="<?php echo $masterfieldtypes['validation'];?>" 
					<?php if($masterfieldtypes['validation']==$fieldData['validation']){?>
					selected="selected"<?php }?>><?php echo $masterfieldtypes[validationLabel];?></option>
					<?php }?>
					</select>
                                         <?php }  ?>
					</td>
					<!-- Options Values -->
					<td align="center" style="border: 1px solid rgb(204, 204, 204);">
					<?php
					if($pos===false){
						echo "N/A";
						//Not a custom field. Dont show any thing
					}else if( $enabled && ( ($fieldData['type']=="select")||($fieldData['type']=="radio")||($fieldData['type']=="checkbox")) ){?>
					<textarea style="width:190px;"
					<?php if(!$enabled){ ?> disabled="disabled" <?php } ?>
						id="<?php echo $fieldData['fieldid']?>_options"
						name="<?php echo $fieldData['fieldid']?>_options" ><?php echo $fieldData['options']; ?></textarea>
					<?php }else {?>
					<textarea
					disabled="disabled" style="display:none;width:190px;"
						id="<?php echo $fieldData['fieldid']?>_options"
						name="<?php echo $fieldData['fieldid']?>_options"  ></textarea>
					<?php }?>
						</td>
				</tr>
				<?php  } ?>
	
			</tbody>
		</table>
		<?php 
		$addtional_custom = get_option('awp_addtional_custom_cases');
		if(empty($addtional_custom))
		{
			$cnt_custom_filed = 6;
		}else {
			$cnt_custom_filed = 6 + count($addtional_custom);
		}
		?>
		<p style="display:none;"> <a rel="<?php echo $cnt_custom_filed; ?>" href="javascript:void(0);" id="cases_addcustom_field" name="cases_addcustom_field"  >+Add Another Custom Field</a> </p>
		<p class="submit">
			<input <?php if(!$this->_plugin_activated): echo 'disabled="disabled"'; endif; ?>   type="submit" name="awp_cases_settings" id="awp_cases_settings" class="button-primary" value="<?php esc_attr_e('Save Configuration','apptivo business site') ?>" />
		</p>
		</form>
		
<?php 
}
}
//GEt Plugin Templates.
function get_plugin_templates()
	{  
		$default_headers = array(
		'Template Name' => 'Template Name'		
	    );
	    $templates = array();	 
		$dir_contact = AWP_CASES_TEMPLATEPATH;
		// Open a known directory, and proceed to read its contents
		if (is_dir($dir_contact)) {
		    if ($dh = opendir($dir_contact)) {
		        while (($file = readdir($dh)) !== false) {
		        	if ( substr( $file, -4 ) == '.php' )
		        	{		        		        	
					$plugin_data = get_file_data( $dir_contact."/".$file, $default_headers, '' );
					if(strlen(trim($plugin_data['Template Name'])) != 0 )
					{
						$templates[$plugin_data['Template Name']] = $file;						
					}
		        	}
		        }
		        
		        closedir($dh);
		    }
		}
		return $templates;                        
	 
}

/**
 * Create field array
*/

function createformfield_array($fieldid,$showtext,$required,$type,$validation,$options,$displayorder){
        
	    $displayorder = (trim($displayorder)=="")?0:trim($displayorder);
				
		$options = (is_array($options))?$options:stripslashes(str_replace( array('"'), '', strip_tags($options)));

		if( trim($type) != 'text' && trim($type) != 'textarea')
		{
			$pos = strpos(trim($fieldid), 'customfield');
			if( $pos !== false )
			{
				if( !is_array($options) && trim($options) == '')
				{	
					return '';
				}
			}
		}   
		$contactformfield= array(
	            'fieldid'=>$fieldid,
                'showtext' => stripslashes(str_replace( array('"'), '', strip_tags($showtext))),
	            'required' => $required,
				'type' => $type,
				'validation' => $validation,
				'options' => $options,
	   			'order' => $displayorder
		);
		return $contactformfield;
}
	
}