<?php
/**
 * Apptivo Contact forms Plugin.
 * @package  apptivo-business-site
 * @author Rajkumar <rmohanasundaram[at]apptivo[dot]com>
 */
require_once AWP_LIB_DIR . '/Plugin.php';
require_once AWP_INC_DIR . '/apptivo_services/labelDetails.php';
require_once AWP_INC_DIR . '/apptivo_services/noteDetails.php';
require_once AWP_INC_DIR . '/apptivo_services/LeadDetails.php';
require_once AWP_ASSETS_DIR.'/captcha/simple-captcha/simple-captcha.php'; 
require_once AWP_LIB_DIR.'/Plugin/AWPServices.php';
/**
 * Class AWP_ContactForms
 */

class AWP_ContactForms extends AWP_Base
{
	var $_plugin_activated = false;
	/**
	 * PHP5 constructor
	 */
	function __construct()
	{
		$settings=array();
		$this->_plugin_activated=false;
		$settings=get_option("awp_plugins");
		if(get_option("awp_plugins")!=="false"){
			if($settings["contactforms"])
			$this->_plugin_activated=true;
		}
	}

	/**
	 * Returns plugin instance
	 *
	 * @return AWP_ContactForms
	 */
	function &instance()
	{
		static $instances = array();

		if (!isset($instances[0])) {
			$class = __CLASS__;
			$instances[0] = & new $class();
		}

		return $instances[0];
	}
	/**
	 * Runs plugin
	 */
	function run()
	{
		if($this->_plugin_activated){
			add_shortcode('apptivocontactform', array(&$this,'showcontactform'));
			add_action( 'contextual_help', array(&$this,'inlinedocument'), 10, 2 );
		}
	}

	function inlinedocument( $text, $screen){
		$helpcontent = '';
		if( $screen == 'apptivo_page_awp_contactforms')
		{
			$helpcontent ='<p><strong>Activating Contact Form:</strong></p>
    	<ul><li>To activate Contact Form plugin, Check on Enable option for "Contact Forms" under Plugin settings in Apptivo General Settings.</li></ul>
    	<div><strong>Creating New Contact Form</strong>:</div>
    	<div><ul>
    	<li>Enter valid Contact Form name and click on Add New. ( This Form Name will be saved as Lead Source in collected lead in Apptivo Leads App</li></ul><div>
    	<strong>Configuring Contact Form</strong></div>
    	<div><ol>
    	<li>Contact Form can be configured using the section "Contact Form Configuration". ( This section will be shown automatically after first Contact Form is created )</li>
    	<li>Select your Contact Form from the "Contact Form" Drop down.</li><li>Select type of Template (Plugin or Theme) from the "Template Type" dropdown. Current version of plugin supports only Plugin Template.</li>
    	<li>Choose one of the available layouts for the form from "Template Layout" dropdown.</li>
    	<li>Content provided on field "Confirmation Message" will be shown in Site after user submitted this contact form.</li>
    	<li>Custom CSS provides option to override styles of Layout choosen in Step 4. Refer Custom CSS help section for Style details.</li>
    	<li>Option "Submit Button Type" provides option to select Button or Image to be placed for Contact Form submission.</li>
    	<li>If Option "Button" is selected, User can provide text to be displayed in "Submit Button Text" field.</li>
    	<li>If Option "image" is selected, User can provide the url of buttom image to be displayed in "Button Image URL" field.</li>
    	<li>Leads collected through Contact Form can be subscribed to Apptivo Target List. Apptivo Target List for this form can be selected using "Apptivo Target List" drop down. ( Target Lists created in Apptivo Target List app will be listed on this dropdown )</li>
    	<li>On selecting the "Apptivo Target List", Admin can decide whether user should be added to the Target List by default, or end user to choose subscribe using the option "Provide subscribe option to user?"</li><li>Totally 13 inbuilt fields and 5 custom fields can be added into your contact form using Contact Form Fields section.</li>
    	<li>13 inbuilt fields in Contact Form Fields section has options Show flag, Required flag, Display order (in the form) and Display Text to customize.</li><li>Among 13 inbuilt fields Last Name and Email are by default mandatory and it cant excluded from the form.</li><li>Custom fields can be of Field Type Check Box, Radio option, Select, Text box and Text area. Field values can be provided on Option Values.</li></ol>
    	<div><strong>Contact Form Shortcode:</strong></div><div><ol><li>Once Contact Form is created and configured, short code for the particular form will be displayed on "Contact Form Configuration" section.</li>
    	<li>Shortcode for the selected form can be copied from the field "Form Shortcode"</li><li>Paste the short code on the Page or Post.</li></ol><div><strong>Custom CSS:</strong></div><div><ul>
    	<li>Below are the list of CSS Style class names used on Contact Form. Below style class names can be defined in "Custom CSS" field in "Contact Form Configuration" to apply your styles.</li></ul><div>
    	<pre>Label 		:   absp_contact_label<br />mandatory       :   absp_contact_mandatory<br />input text      :   absp_contact_input_text<br />Select          :   absp_contact_select<br />textarea        :   absp_contact_textarea<br />select		:   absp_contact_select<br />input checkbox  :   absp_contact_input_checkbox<br />input radio	:   absp_contact_input_radio<br />submit          :   absp_contact_button_submit</pre></div><strong></strong></div></div><strong></strong></div></div>';
		}
		if($helpcontent == '')
		{
			return $text;
		}else {
			return $helpcontent;
		}
	}

	/**
	 * Contact Form shortcode handler
	 */
	function showcontactform($atts){
		extract(shortcode_atts(array('name'=>  ''), $atts));
		ob_start();
		$formname=trim($name);
		$content="";
		$successmsg="";
		if(isset($_POST['awp_contactformname'])){
		$submitformname=$_POST['awp_contactformname'];
		}
		$value_present = false;
		if(isset($_POST['awp_contactform_submit']) && $submitformname==$formname )
		{
           if(isset($_POST["simple_captcha"]) )
                {
                	if(isset($_POST['awp_simple_captcha_challenge'])){
                	$captcha_instance = new AWPSimpleCaptcha();
                    $response = $captcha_instance->check($_POST['awp_simple_captcha_challenge'], $_POST["simple_captcha"]);
                    $captcha_instance->remove( $_POST['awp_simple_captcha_challenge'] );
                    
                    if($response!="1")
			        {
                        $value_present = true;
	                	$captch_error = awp_messagelist("recaptcha_error");
                        }
                        else
                        {
                        	
                            $successmsg=$this->save_contact($submitformname);
                        }
                	}else{
                		$captcha_instance = new ReallySimpleCaptcha();
                		$captcha_instance->remove( $_POST['awp_simple_captcha_challenge'] );
                		$captch_error = awp_messagelist("recaptcha_error");
                	}
                }
            else if(isset($_POST["recaptcha_challenge_field"]))
                {
                    $response_field =   $_POST["recaptcha_response_field"];
                    $challenge_field=   $_POST["recaptcha_challenge_field"];
                    $option=get_option('apptivo_business_recaptcha_settings');
                    $option=json_decode($option);
                    $private_key    =   $option->recaptcha_privatekey;
                    $response=    captchaValidation($private_key, $challenge_field, $response_field);

                    if($response!="1")
			        {
                        $value_present = true;
	                	$captch_error = awp_messagelist("recaptcha_error");
                        }
                        else
                        {
                            $successmsg=$this->save_contact($submitformname);
                        }
                }
                  else{
                    $successmsg=$this->save_contact($submitformname);
                  }

		}
		$contactform=$this->get_contactform_fields($formname);
		if(strlen(trim($successmsg)) != 0 && $contactform['confmsg_pagemode'] == 'other' ) :
		$location = get_permalink($contactform['confmsg_pageid']);
		$verification = check_blockip();
		if($verification){
			echo awp_messagelist('IP_banned');
			return;
		}
		wp_safe_redirect($location);
		endif;
		if(isset($contactform['fields']))
		{
			foreach($contactform['fields'] as $field){
                            if(is_array($field)){
				if($field['fieldid']=="country")
				{ 
					$countrylist = $this->getAllCountryList();
					break;
				}
                            }
			}
		}
	
		/* Get Contact From Width Size  */
		$contact_width_size=$contactform['contact_width_type'];

		if(!empty($contactform) && !empty($contactform['fields'])){
			//Registering Validation Scripts.
			//$this->loadscripts();
                        add_action('wp_footer', abwpExternalScripts);
			include $contactform['templatefile'];
		}else {
			echo awp_messagelist('contactform-display-page');
		}
		return ob_get_clean();
	}

	/**
	 * Save contact from submitted
	 */
	function save_contact($formname,$ajaxform=false){
		
		if($ajaxform)
		{
			if(isset($_POST['captcha'])){
				if(trim($_POST['captcha']) != $_SESSION['apptivo_business_captcha_code'])
				{
					$captch_error = 'Please enter correct Verification code';
					return $captch_error;
				}
			}
		}

		$contactform=$this->get_contactform_fields($formname);
		if(!empty($contactform)){
			$contactformfields=$contactform['fields'];
			//Process the $_POST here..
			$submittedformvalues=array();
			$submittedformvalues['name']=$contactform['name'];
			if(isset($_POST['subscribe'])){
				$submittedformvalues['targetlist']=$contactform[targetlist];
			}
			else{
				if($contactform['subscribe_option']=='no'){
					$submittedformvalues['targetlist']=$contactform['targetlist'];
				}
			}
			$customfields="";

			foreach($contactformfields as $field)
			{
				$fieldid=  isset($field['fieldid']) ? $field['fieldid'] : '';
				$pos=strpos($fieldid, "customfield");
				if($pos===false){
					if($fieldid=='telephonenumber'){
						if(isset($_POST['telephonenumber1'])){
							$submittedformvalues[$fieldid]= $_POST['telephonenumber1'].$_POST['telephonenumber2'].$_POST['telephonenumber3'];
						}
						else if(isset($_POST['telephonenumber_string']))
						{
							$submittedformvalues[$fieldid]= $_POST['telephonenumber_string'];
						}
						else{

							$submittedformvalues[$fieldid]= $_POST[$fieldid];

						}
					}
					else{
						$submittedformvalues[$fieldid]= stripslashes($_POST[$fieldid]);

					}
				}else{
					if(trim($customfields)!="")
					{
						if(is_array($_POST[$fieldid]))
						{
							$CustomArr = $_POST[$fieldid];
							$customfieldVal= "";
							for($i=0; $i<count($CustomArr); $i++)
							{
								$customfieldVal .= ($i==(count($CustomArr)-1))?$CustomArr[$i]:$CustomArr[$i].", ";
							}

						}else {
							$customfieldVal = $_POST[$fieldid];
						}
						if($customfieldVal != '') :
						$customfields.="<b>".$field['showtext']."</b>:&nbsp;".stripslashes($customfieldVal)."<br/>";
						endif;
					}
					else
					{
						if(is_array($_POST[$fieldid]))
						{
							$CustomArr = $_POST[$fieldid];
							$customfieldVal= "";
							for($i=0; $i<count($CustomArr); $i++)
							{
								$customfieldVal .= ($i==(count($CustomArr)-1))?$CustomArr[$i]:$CustomArr[$i].", ";
							}

						}else {
							$customfieldVal = $_POST[$fieldid];
						}
						if($customfieldVal != '') :
						$customfields .= "<b>".$field['showtext']."</b>:".stripslashes($customfieldVal)."<br/>";
						endif;
					}
				}
			}
				
			$customfields .= "<br/><b>Requested IP</b>:".stripslashes(get_RealIpAddr());

			if(trim($customfields)!="")
			{
				$submittedformvalues["notes"]=$customfields;
			}
			$firstName="";$jobTitle="";$company="";$address1="";$address2="";$city="";$state="";$zipCode="";$bestWayToContact="";$phoneNumber="";$comments="";$targetname="";
			if(isset($submittedformvalues['firstname'])){
                            $firstName = $submittedformvalues['firstname'];
			}
			if(isset($submittedformvalues['lastname'])){
                            $lastName = $submittedformvalues['lastname'];
			}
			if(isset($submittedformvalues['email'])){
                            $emailId = $submittedformvalues['email'];
			}
			if(isset($submittedformvalues['jobtitle'])){
                            $jobTitle = $submittedformvalues['jobtitle'];
			}
			if(isset($submittedformvalues['company'])){
			$company =  $submittedformvalues['company'];
			}
			if(isset($submittedformvalues['address1'])){
                            $address1 = $submittedformvalues['address1'];
			}
			if(isset($submittedformvalues['address2'])){
                            $address2 = $submittedformvalues['address2'];
			}
			if(isset($submittedformvalues['city'])){
                            $city = $submittedformvalues['city'];
			}
			if(isset($submittedformvalues['state'])){
                            $state = $submittedformvalues['state'];
			}
			if(isset($submittedformvalues['zipcode'])){
                            $zipCode = $submittedformvalues['zipcode'];
			}
			if(isset($submittedformvalues['simple_captcha'])){
                            $simple_captcha=$submittedformvalues['simple_captcha'];
			}
			if(isset($submittedformvalues['bestway'])){
                            $bestWayToContact = $submittedformvalues['bestway'];
			}
			if(isset($submittedformvalues['country'])){
                            $country = $submittedformvalues['country'];
			}
			if(isset($submittedformvalues['name'])){
                            $leadSource = $submittedformvalues['name'];
			}
			if(isset($submittedformvalues['telephonenumber'])){
                            $phoneNumber = $submittedformvalues['telephonenumber'];
			}
			if(isset($submittedformvalues['comments'])){
                            $comments = $submittedformvalues['comments'];
			}
			if(isset($submittedformvalues['notes'])){
			$noteDetails = $submittedformvalues['notes'];
			}
			$targetlistid="";
			if(isset($submittedformvalues['targetlist'])){
			$targetlistid = $submittedformvalues['targetlist'];
			}
			/* Get Country Id & Country Name*/
			if(isset($_POST['country_id']) && isset($_POST['country_name'])){
				$countryId=$_POST['country_id'];
				$countryName=$_POST['country_name'];
			}else{
				$countryId="176";
				$countryName="United States";
			}
			
			if(!empty($noteDetails)){
				$parent1NoteId="";
				$parent1details = nl2br($noteDetails);
				$awp_services_obj=new AWPAPIServices();
				$noteDetails = $awp_services_obj->notes('Custom Fields',$parent1details,$parent1NoteId);

				$contactformdetails=$this->get_settings($leadSource);
				$formproperties=$contactformdetails['properties'];
				
				if(isset($targetlistid) && $targetlistid!=''){
						$getTargetName=$awp_services_obj->getTargetListcategory();
					if($getTargetName!="")
					{
						foreach($getTargetName as $category){
							if($category->targetListId==$formproperties['targetlist'])
							{
								$targetname=$category->targetListName;
							}
						}
					}
				}
			}

			$contactAccountName="";
			$customerAccountName="";
		 	
			if(!_isCurl())
			{
				$targetname=$targetlistid;
			}
			if(strlen(trim($firstName)) == 0 ) :
			$firstName = null;
			endif;
			if(strlen(trim($lastName))==0 || strlen(trim($emailId))==0 || !filter_var($emailId, FILTER_VALIDATE_EMAIL)){
				echo awp_messagelist('no_redirection');
			}else{

				/* check manually and update the values */

				$form_name = $_POST['awp_contactformname'];
				$contact_formvalues=$this->get_contactform_fields($form_name);
				
				if(isset($_POST['status_name']) || isset($_POST['status_id']) || isset($_POST['rank_id']) || isset($_POST['rank_id']) || isset($_POST['type_name']) || isset($_POST['type_id'])){
				   	$contact_status=$_POST['status_name'];
					 $contact_status_id=$_POST['status_id'];
					$contact_type=$_POST['type_name'];
					 $contact_type_id=$_POST['type_id'];
					$contact_rank=$_POST['rank_name'];
					 $contact_rank_id=$_POST['rank_id'];
				}
				if($contact_status=="" && $contact_status_id==""){
					$contact_status=$contact_formvalues['contact_status'];
					$contact_status_id=$contact_formvalues['contact_status_id'];
		
				}
				if($contact_type=="" && $contact_type_id==""){
					$contact_type=$contact_formvalues['contact_type'];
					$contact_type_id=$contact_formvalues['contact_type_id'];
				}
				if($contact_rank=="" && $contact_rank_id==""){
					$contact_rank=$contact_formvalues['contact_rank'];
					$contact_rank_id=$contact_formvalues['contact_rank_id'];
				}
				/* Get Lead Source,Type,Status */
				 
				 $contact_source=$contact_formvalues['contact_source'];
				 $contact_source_id=$contact_formvalues['contact_source_id'];
				 
				/* Check Wheather leadSource Selected or not */
				if($contact_source=="Select One" || $contact_source=="" && $contact_source_id==0){$leadSource=$leadSource;$leadSourceId=strtoupper($leadSource);}
				else{$leadSource=$contact_source;$leadSourceId=$contact_source_id;}

				$assigneeName		=   trim($contact_formvalues["contact_assignee_name"]);
				$assigneeObjId		=	($contact_formvalues["contact_assignee_type"] == 'team') ? APPTIVO_TEAM_OBJECT_ID : APPTIVO_EMPLOYEE_OBJECT_ID;
				$assigneeObjRefId	=	$contact_formvalues["contact_assignee_type_id"];
				$contactAssociates 	=  	trim($contact_formvalues["contact_associates"]);
				$createAssociates 	= 	trim($contact_formvalues["contact_create_associates"]);
				//echo $assigneeName."-".$assigneeObjId."-".$assigneeObjRefId."-".$contactAssociates."-".$createAssociates;exit;
			
				/* Check wheather Team exist or not */
		 		if($assigneeName=='No Team'){
		 				$assigneeName= $assigneeObjId = $assigneeObjRefId = "";
		 		}
				$associates="";
				if($contactAssociates != "No Need")
				{ 
		 				$associates=$awp_services_obj->awpContactAssociates($emailId, $contactAssociates);
				}
				//echo $associates["leadContactId"]."-".$associates["leadContact"]."-".$associates["leadCustomerId"]."-".$associates["leadCustomer"];exit;
				$customerAccountId = $customerAccountName = "";
				if(count($associates) !="")
				{
					if(isset($associates["leadCustomerId"])){
					$customerAccountId	=	$associates["leadCustomerId"];
					}
					if(isset($associates["leadCustomer"])){
					$customerAccountName=	$associates["leadCustomer"];
					}
				}
				
				if($customerAccountId == "" && $createAssociates == "customer" && $contactAssociates!="No Need")
				{

			    $createCustomerResponse=$awp_services_obj->createCustomer($lastName,$assigneeName,$assigneeObjId,$assigneeObjRefId,$phoneNumber,$emailId);
		 		$customerAccountId=$createCustomerResponse['leadCustomerId'];
		 		$customerAccountName=$createCustomerResponse['leadCustomer'];
				}
				
				$verification = check_blockip();
				if($verification){
					if($verification == 'E_IP') {
						echo awp_messagelist('IP_banned');
					}
					return;
				}
				$leadSource=strtoupper($leadSource);
				if(_isCurl())
				{  
					$saveLeadresponse = $awp_services_obj->saveLeadDetails($firstName , $lastName, $emailId, $jobTitle, $company, $address1, $address2, $city, $state, $zipCode, $bestWayToContact, $countryId,$countryName, $leadSource, $leadSourceId,$phoneNumber, $comments, $noteDetails,$targetname,$customerAccountId,$customerAccountName,$contact_status,$contact_type,$contact_rank,$contact_status_id,$contact_type_id,$contact_rank_id,$assigneeName,$assigneeObjId,$assigneeObjRefId);
                        		$leadId= $saveLeadresponse->lead->leadId;
					if($noteDetails!="" && $leadId!="")
					{
						$noteText=$noteDetails->noteText;
						$saveNotesResponse=$awp_services_obj->saveNotes(APPTIVO_LEAD_OBJECT_ID,$leadId,$noteText);
					}
					if($leadId!="")
					{
						$response='Success';
						if (extension_loaded('soap') && $targetlistid!="") {
							createTargetList($targetlistid, addslashes($firstName), addslashes($lastName), addslashes($emailId), addslashes($phoneNumber), addslashes($comments),$notesLabel);
						}
					}
					else
					{
						$response='E_100';
					}
				}
				else {
					$leads = new AWP_LeadDetails(APPTIVO_BUSINESS_API_KEY,$firstName, $lastName, $emailId, $jobTitle, $company, $address1, $address2, $city, $state, $zipCode, $bestWayToContact, $country, $leadSource, $phoneNumber, $comments, $noteDetails,$targetlistid);
					$params = array (
                                            "arg0" => APPTIVO_BUSINESS_API_KEY,
                                            "arg1" => APPTIVO_BUSINESS_ACCESS_KEY,
                                            "arg2" => $leads
					);
					$response = getsoapCall(APPTIVO_BUSINESS_SERVICES,'createLeadWithLeadSource',$params);
					$response= $response->return->statusMessage;
				}
				 
				$response_msg = $response;
				if($response_msg=='Success' && $response != 'E_100'){
					if(!empty($contactform['confmsg'])){
						$confmsg = $contactform['confmsg'];
					}
					else{
						$confmsg="Your request has been submitted. Thanks for contacting us.";
					}
				}else if($response == 'E_IP') { echo awp_messagelist('IP_banned');}
				else { echo awp_messagelist('contactlead-display-page'); }
			}
		}
		return $confmsg;
	}

	/**
	 * Get contactform and its fields to render in page which is using shortcode
	 */
	function get_contactform_fields($formname){
            
		$formExists="";
		$contact_forms=array();
		$contactform=array();
		$contactformdetails=array();
		$formname=trim($formname);

		$contact_forms=get_option('awp_contactforms');

		if($formname=="")
		$formExists="";
		else if(!empty($contact_forms))
		$formExists = awp_recursive_array_search($contact_forms,$formname,'name' );

		if(trim($formExists)!=="" ){
			$contactform=$contact_forms[$formExists];
			//build contactformdetails array
			$contactformdetails['name']=$contactform['name'];

			//add properties
			$contactformproperties=$contactform['properties'];
			$contactformdetails['tmpltype']=$contactformproperties['tmpltype'];
			$contactformdetails['layout']=$contactformproperties['layout'];
			$contactformdetails['confmsg']= stripslashes($contactformproperties['confmsg']);
			$contactformdetails['confmsg_pagemode']= $contactformproperties['confirm_msg_page'];
			$contactformdetails['confmsg_pageid']= $contactformproperties['confirm_msg_pageid'];
			$contactformdetails['targetlist']=$contactformproperties['targetlist'];
			$contactformdetails['css']=stripslashes($contactformproperties['css']);
			$contactformdetails['subscribe_option']=$contactformproperties['subscribe_option'];
			$contactformdetails['subscribe_to_newsletter_displaytext']=stripslashes($contactformproperties['subscribe_to_newsletter_displaytext']);
			$contactformdetails['submit_button_type']=$contactformproperties['submit_button_type'];
			$contactformdetails['submit_button_val']=$contactformproperties['submit_button_val'];
			$contactformdetails['contact_create_associates']=$contactformproperties['contact_create_associates'];
			$contactformdetails['contact_assignee_type']=$contactformproperties['contact_assignee_type'];
			$contactformdetails['contact_assignee_type_id']=$contactformproperties['contact_assignee_type_id'];
			$contactformdetails['contact_assignee_name']=$contactformproperties['contact_assignee_name'];
			$contactformdetails['contact_status']=$contactformproperties['contact_status'];
			$contactformdetails['contact_type']=$contactformproperties['contact_type'];
			$contactformdetails['contact_source']=$contactformproperties['contact_source'];
			$contactformdetails['contact_associates']=$contactformproperties['contact_associates'];
			$contactformdetails['contact_rank']=$contactformproperties['contact_rank'];
			$contactformdetails['contact_status_id']=$contactformproperties['contact_status_id'];
			$contactformdetails['contact_type_id']=$contactformproperties['contact_type_id'];
			$contactformdetails['contact_source_id']=$contactformproperties['contact_source_id'];
			$contactformdetails['contact_rank_id']=$contactformproperties['contact_rank_id'];
			$contactformdetails['contact_width_type']=$contactformproperties['contact_width_type'];
			$check_browser	=	get_current_browser();
			if(isset($chrome)){
			if($check_browser=="chrome") { $chrome= ".recaptcha_source{width:40%;}"; }
			echo '<style type="text/css"> form input.required{color:#000;}label.error,.absp_contact_mandatory{color:#FF0400;}.absp_success_msg{color:green;font-weight:bold;padding-bottom:5px;}.absp_error{color:red;font-weight:bold;padding-bottom:5px;}'.$chrome.'</style>';
			}
			//include template files
			if($contactformproperties['tmpltype']=="awp_plugin_template") :
			$templatefile=AWP_CONTACTFORM_TEMPLATEPATH."/".$contactformproperties['layout']; // Plugin templates
			else :
			$templatefile=TEMPLATEPATH."/contactforms/".$contactformproperties['layout']; // theme templates
			endif;

			$contactformdetails['templatefile']=$templatefile;

			$possible_letters = '23456789bcdfghjkmnpqrstvwxyz';
			$characters_on_image = 6;
			$code = '';
			$i = 0;
			while ($i < $characters_on_image) {
				$code .= substr($possible_letters, mt_rand(0, strlen($possible_letters)-1), 1);
				$i++;
			}
			$_SESSION['apptivo_business_captcha_code'] = $code;
			$contactformdetails['captchaimagepath'] = AWP_PLUGIN_BASEURL.'/assets/captcha/captcha_code_file.php?captcha_code='.$code;
			//add fields
			$contactformfields=$contactform['fields'];
			if(!empty($contactformfields)){
				usort($contactformfields, "awp_sort_by_order");
				$newcontactformfields=$contactformfields;
				$contactformdetails['fields']=$newcontactformfields;
			}
		}
		return $contactformdetails;
	}


	/**
	 * Get Contact form settings by form name to render in Admin
	 */
	function get_settings($formname){
		$formExists="";
		$contact_forms=array();
		$contactform=array();
		$formname=trim($formname);
		
		$contact_forms=get_option('awp_contactforms');
		if($formname=="")
		$formExists="";
		else if(!empty($contact_forms))
		$formExists = awp_recursive_array_search($contact_forms,$formname,'name' );

		if(trim($formExists)!=="" ){
			$contactform=$contact_forms[$formExists];
		}
		return $contactform;
	}

	/**
	 * Return master fields lists supported by Apptivo Contact Form
	 */
	function get_master_fields()
	{
		$fields = array(
		array('fieldid' => 'firstname','fieldname' => 'First Name','defaulttext' => 'First Name','showorder' => '1','validation' => 'text','fieldtype' => 'text'),
		array('fieldid' => 'lastname','fieldname' => 'Last Name','defaulttext' => 'Last Name','showorder' => '2','validation' => 'text','fieldtype' => 'text'),
		array('fieldid' => 'leadStatus','fieldname' => 'Lead Status','defaulttext' => 'Lead Status','showorder' => '3','validation' => 'text','fieldtype' => 'select'),
		array('fieldid' => 'leadType','fieldname' => 'Lead Type','defaulttext' => 'Lead Type','showorder' => '4','validation' => 'text','fieldtype' => 'select'),
		array('fieldid' => 'leadSource','fieldname' => 'Lead Source','defaulttext' => 'Lead Source','showorder' => '5','validation' => 'text','fieldtype' => 'select'),
		array('fieldid' => 'leadRank','fieldname' => 'Lead Rank','defaulttext' => 'Lead Rank','showorder' => '6','validation' => 'text','fieldtype' => 'select'),
		array('fieldid' => 'email','fieldname' => 'Email','defaulttext' => 'Email','showorder' => '7','validation' => 'email','fieldtype' => 'text'),
		array('fieldid' => 'jobtitle','fieldname' => 'Job Title','defaulttext' => 'Job Title','showorder' => '8','validation' => 'text','fieldtype' => 'text'),
		array('fieldid' => 'company','fieldname' => 'Company','defaulttext' => 'Company','showorder' => '9','validation' => 'text','fieldtype' => 'text'),
		array('fieldid' => 'address1','fieldname' => 'Address1','defaulttext' => 'Address1','showorder' => '10','validation' => 'text','fieldtype' => 'text'),
		array('fieldid' => 'address2','fieldname' => 'Address2','defaulttext' => 'Address2','showorder' => '11','validation' => 'text','fieldtype' => 'text'),
		array('fieldid' => 'city','fieldname' => 'City','defaulttext' => 'City','showorder' => '12','validation' => 'text','fieldtype' => 'text'),
		array('fieldid' => 'state','fieldname' => 'State','defaulttext' => 'State','showorder' => '13','validation' => 'text','fieldtype' => 'text'),
		array('fieldid' => 'zipcode','fieldname' => 'ZipCode','defaulttext' => 'ZipCode','showorder' => '14','validation' => 'text','fieldtype' => 'text'),
		array('fieldid' => 'country','fieldname' => 'Country','defaulttext' => 'Country','showorder' => '15','validation' => 'text','fieldtype' => 'select'),
		array('fieldid' => 'telephonenumber','fieldname' => 'Telephone Number','defaulttext' => 'Telephone Number','showorder' => '16','validation' => '','fieldtype' => 'text'),
		array('fieldid' => 'comments','fieldname' => 'Comments','defaulttext' => 'Comments','showorder' => '17','validation' => 'textarea','fieldtype' => 'textarea'),
		array('fieldid' => 'captcha','fieldname' => 'Captcha','defaulttext' => 'Captcha','showorder' => '18','validation' => 'text','fieldtype' => 'captcha'),
		array('fieldid' => 'customfield1','fieldname' => 'Custom Field 1','defaulttext' => 'Custom Field1','showorder' => '19','validation' => '','fieldtype' => 'select'),
		array('fieldid' => 'customfield2','fieldname' => 'Custom Field 2','defaulttext' => 'Custom Field2','showorder' => '20','validation' => '','fieldtype' => 'select'),
		array('fieldid' => 'customfield3','fieldname' => 'Custom Field 3','defaulttext' => 'Custom Field3','showorder' => '21','validation' => '','fieldtype' => 'select'),
		array('fieldid' => 'customfield4','fieldname' => 'Custom Field 4','defaulttext' => 'Custom Field4','showorder' => '22','validation' => '','fieldtype' => 'radio'),
		array('fieldid' => 'customfield5','fieldname' => 'Custom Field 5','defaulttext' => 'Custom Field5','showorder' => '23','validation' => '','fieldtype' => 'checkbox')

		);
		//For Additional custom fields.
		$addtional_custom = get_option('awp_addtional_custom');
		if(!empty($addtional_custom)):
		$fields = array_merge($fields,$addtional_custom);
		endif;

		return $fields;
	}
	/**
	 * Retrieve list of validations supported by Apptivo Contact Form
	 *
	 */
	function get_master_validations(){
		$validations = array(
		array('validationLabel' => 'None','validation' => 'none'),
		array('validationLabel' => 'Email ID','validation' => 'email'),
		array('validationLabel' => 'Number','validation' => 'number')
		);
		return $validations;
	}
	/**
	 *
	 * * Retrieve list of Field Types supported by Apptivo Contact Form
	 */
	function get_master_fieldtypes(){
		$fieldtypes = array(
		array('fieldtypeLabel' => 'Checkbox','fieldtype' => 'checkbox'),
		array('fieldtypeLabel' => 'Radio Option','fieldtype' => 'radio'),
		array('fieldtypeLabel' => 'Select','fieldtype' => 'select'),
		array('fieldtypeLabel' => 'Textbox','fieldtype' => 'text'),
		array('fieldtypeLabel' => 'Textarea','fieldtype' => 'textarea')
		);
		return $fieldtypes;
	}

	/**
	 * return array of plugin templates available with Template name and template file name
	 */
	function get_plugin_templates()
	{
		$default_headers = array(
		'Template Name' => 'Template Name'
		);
		$templates = array();
		$dir_contact = AWP_CONTACTFORM_TEMPLATEPATH;
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


	/**
	 * It renders UI in Admin page
	 */
	function options(){
		$updatemessage="";
		if($_POST['delformname'])   //Delete Form Name:
		{
			if(strlen(trim($_POST['delformname'])) != 0)
			{
				$formname = $_POST['delformname'];
				$contact_forms=get_option('awp_contactforms');
				$formExists = awp_recursive_array_search($contact_forms,$formname,'name' );
				if(isset($formExists))
				{
					unset($contact_forms[$formExists]);
				}
				$contact_sort_form = array();
				foreach($contact_forms as $contact_forms_tosort )
				{
					array_push($contact_sort_form,$contact_forms_tosort);
				}

				update_option('awp_contactforms', $contact_sort_form);
				$updatemessage= 'Contact Form "'.$formname.'" Deleted Successfully.';
			}
		}

		$contact_forms=array();
		$contactformdetails=array();
		$contact_forms=get_option('awp_contactforms');

		/*
		 * Saving New form
		 */
		if(isset($_POST['newcontactformname']))
		{
			$newcontactformname =   $_POST['newcontactformname'];
			$newcontactformname = preg_replace('/[^\w]/', '', $newcontactformname);
                        
			$newcontactformname=trim($newcontactformname);
			if(_isCurl())
			{
                            if($newcontactformname != '_' && $newcontactformname != '' )
                            {
				$response= CreateContactFormLeads($newcontactformname);
                            }
                            else{
                                $updatemessage= "Form Name should be valid data.";
                            }
			}
                        else 
                            { 
                                $response = "soap"; 
                            }
			if($newcontactformname!='' )
			{
				$contactform=array();
				$contactform=$this->get_settings($newcontactformname);
				if( count($contactform)==0 && $response!="present")
				{
					$newcontactformname_array =array("name"=>$newcontactformname);
					$newcontactform=array($newcontactformname_array);
					if( empty($contact_forms) ){

						update_option('awp_contactforms',$newcontactform);
					}else{
						array_push($contact_forms, $newcontactformname_array);


						update_option('awp_contactforms',$contact_forms);
					}
					$contact_forms=get_option('awp_contactforms');
					$contactform=$this->get_settings($newcontactformname);
					$selectedcontactform=$newcontactformname;
					$updatemessage= "Contact Form created. Please configure settings using the below Configuration section.";
				}else{
					$updatemessage= "<span style='color:#f00;'>Form already exists. To change configuration, please select the form from below configuration section.</span>";
				}
			}else{
				$updatemessage= "Form Name cannot be empty or should valid data.";
			}
		}

		/*
		 * Loading the settings of selected form
		 */
		if(isset($_POST['awp_contactform_select_form']))
		{
			$selectedcontactform =  trim( $_POST['awp_contactform_select_form']);
			if($selectedcontactform!='')
			{
				$contactform=array();
				$contactform=$this->get_settings($selectedcontactform);
				if( empty($contactform))
				{
					//echo "Selected form configuration doestn exist.";
				}else{
					$contactformdetails=$contactform;
				}
			}
		}
		/*
		 * Saving selected form settings
		 */
		if(isset($_POST['awp_contactform_settings'])){
			$templatelayout="";
			
			contactOptions('save');
			$contactConfigDetails	=array("awp_leadSource_selected"=>$_POST['absp_contact_config_leadSource'],"awp_leadType_selected"=>$_POST['absp_contact_config_leadType'],"awp_leadStatus_selected"=>$_POST['absp_contact_config_leadStatus'],"awp_leadRank_selected"=>$_POST['absp_contact_config_leadRank']);
			$newformname=$_POST['awp_contactform_name'];
			
			if($_POST['awp_contactform_templatetype']=="awp_plugin_template")
			$templatelayout=$_POST['awp_contactform_plugintemplatelayout'];
			else
			$templatelayout=$_POST['awp_contactform_themetemplatelayout'];
				
			if($_POST["awp_contact_select_assignee"] == 'team'){
				$assignee_type_id = $_POST['awp_contact_select_assignee_team'];
			}
			else{
				$assignee_type_id = $_POST['awp_contact_select_assignee_employee'];
			}

			$contactformproperties=array(
							'tmpltype' =>$_POST['awp_contactform_templatetype'],
	                        'layout' =>$templatelayout,
	                        'confmsg' => stripslashes($_POST['awp_contactform_confirmationmsg']),
			                'confirm_msg_page' => $_POST['awp_contactform_confirm_msg_page'],
							'confirm_msg_pageid' => $_POST['awp_contactform_confirmmsg_pageid'],
							'targetlist' =>$_POST['awp_contactform_targetlist'],
	                        'css' => stripslashes($_POST['awp_contactform_customcss']),
                            'subscribe_option' => $_POST['subscribe_option'],
			                'subscribe_to_newsletter_displaytext' => stripslashes($_POST['awp_subscribe_to_newsletter']),
                            'submit_button_type' => $_POST['awp_contactform_submit_type'],
                            'submit_button_val' => $_POST['awp_contactform_submit_value'],
			                'contact_associates' => $_POST['awp_contact_associates'],
							'contact_create_associates' => $_POST['awp_contact_createassociate'],
							'contact_assignee_type' => $_POST['awp_contact_select_assignee'],
							'contact_assignee_type_id' => $assignee_type_id,
							'contact_assignee_name' => trim($_POST['select_assignee_name_val']),
		                    'contact_status' => $_POST['awp_leadStatus'],
		                    'contact_type' => $_POST['awp_leadType'],
							'contact_source' => $_POST['awp_leadSource'],
			   				'contact_rank' => $_POST['awp_leadRank'],
			 				'contact_status_id'=>$_POST['absp_contact_config_leadStatus'],
							'contact_type_id'=>$_POST['absp_contact_config_leadType'],
							'contact_source_id'=>$_POST['absp_contact_config_leadSource'],
							'contact_rank_id'=>$_POST['absp_contact_config_leadRank'],
							'contact_width_type'=>$_POST['awp_contact_width_type']
			);

			//New Custom fields
			$stack = array();
			$addtional_custom = array();
			$addtional_order = 20;
			for($i=6;$i<200;$i++)
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
			update_option('awp_addtional_custom',$stack);
			endif;

			//General Contact form fields
			$contactformfields=array();
			foreach( $this->get_master_fields() as $fieldsmasterproperties )
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
				if($fieldid=='lastname' || $fieldid=='email')
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
					$contactformfield=$this->createformfield_array($fieldid,$displaytext,$required,$_POST[$fieldid.'_type'],$_POST[$fieldid.'_validation'],$_POST[$fieldid.'_options'],$displayorder);
					array_push($contactformfields, $contactformfield);
				}
			}
			//usort($contactformfields, "awp_sort_by_order");
			if(!empty($contactformfields)){
				$newcontactformdetails=array('name'=>$newformname,'properties'=>$contactformproperties,'fields'=>$contactformfields,'contact_config'=>$contactConfigDetails);

				$formExists="";
				if(!empty($contact_forms))
				$formExists = awp_recursive_array_search($contact_forms,$newformname,'name' );
				if(trim($formExists)!=="" ){

					unset($contact_forms[$formExists]);
					array_push($contact_forms, $newcontactformdetails);
					sort($contact_forms);
					contactOptions('save');
					update_option('awp_contactforms',$contact_forms);
					$contact_forms=get_option('awp_contactforms');
					$updatemessage= "Contact Form '".$newformname."' settings updated. Use Shortcode '[apptivocontactform name=\"".$newformname."\"]' in your page to use this form.";
				}

			}else{
				$updatemessage="<span style='color:red;'>Select atleast one Form field for Contact Form.</span>";
			}
			$selectedcontactform=$newformname;
		}

		// Now display the settings editing screen
		echo '<div class="wrap">';
		// header
		echo "<h2>" . __( 'Apptivo Contact Forms', 'awp_contactform' ) . "</h2>";
		checkCaptchaOption();
		echo '<div class="contactform_err"></div>';
		//if updatemessage is not empty display the div
		if(trim($updatemessage)!=""){
			?>
<div id="message" class="updated">
	<p>
	<?php echo $updatemessage;?>
	</p>
</div>
	<?php }
	if(!$this->_plugin_activated){
		$disabledForm = 'disabled="disabled"';
		$disabledOption = TRUE;
		echo "Contact Forms is currently <span style='color:red'>disabled</span>. Please enable this in <a href='".SITE_URL."/wp-admin/admin.php?page=awp_general'>Apptivo General Settings</a>.";
	}

	//get the count of total contact forms created
	$contactformscount=0;
	if(!empty($contact_forms)){
		$contactformscount=count($contactformscount);
	}else{
		$contactformscount=0;
	}

	if($contactformscount < 10){
		?>
<form name="awp_contactform_new" id="awp_contactform_new" method="post"
	action="">

	<p>
		<img id="elementToResize"
			src="<?php echo awp_flow_diagram('contactform');?>" alt="contactform"
			title="contactform" />
	</p>
	<p style="margin: 10px;">
		For Complete instructions,see the <a
			href="<?php echo awp_developerguide('contactform');?>"
			target="_blank">Developer's Guide.</a>
	</p>

	<p>
	<?php _e("Contact Form Name", 'apptivo-businesssite' ); ?>
		<span style="color: #f00;">*</span>&nbsp;&nbsp;<input type="text"
			name="newcontactformname" id="newcontactformname" size="20"
			maxlength="50" value=""> <span class="description"><?php _e('This form name will be used as your Lead Source in Apptivo.','apptivo-businesssite'); ?>
		</span>
	</p>
	<p>
		<input <?php echo $disabledForm;?> type="submit" name="Submit"
			class="button-primary" value="<?php esc_attr_e('Add New') ?>" />
	</p>

</form>

	<?php
	}

	if(!empty($contact_forms)){
		$newsletter_categories = $this->getNewsletterCategory();
		$themetemplates = get_awpTemplates(TEMPLATEPATH.'/contactforms','Plugin');
		$plugintemplates=$this->get_plugin_templates();
		arsort($plugintemplates);

		?>
<br>
<hr />
		<?php
		echo "<h2>" . __( 'Contact Form Configuration', 'awp_contactform' ) . "</h2>";
		?>
		<?php
		if(trim($selectedcontactform)==""){
			$selectedcontactform=$contact_forms[0][name];
		}
		$contactformdetails=$this->get_settings($selectedcontactform);
		if(count($contactformdetails)>0){
			$selectedcontactform=$contactformdetails[name];
			$fields=$contactformdetails[fields];
			$formproperties=$contactformdetails[properties];
		}
		?>

<table class="form-table">
	<tbody>
	<?php if(empty($formproperties[tmpltype])) :  //To check contact form settings are save or not.
	echo '<span style="color:#f00;"> Save the below settings to get the Shortcode for contact form.</span>';
	endif; ?>
		<tr valign="top">
			<th valign="top"><label for="awp_contactform_select_form"><?php _e("Contact Form", 'apptivo-businesssite' ); ?>:</label>
			</th>
			<td valign="top">
				<form name="awp_contact_selection_form" method="post" action=""
					style="float: left;">
					<select name="awp_contactform_select_form"
						id="awp_contactform_select_form" onchange="this.form.submit();">
						<?php
						for($i=0; $i<count($contact_forms); $i++)
						{ ?>
						<option value="<?php echo $contact_forms[$i][name]?>"
						<?php if(trim($selectedcontactform)==$contact_forms[$i][name])
						echo "selected='true'";?>>
						<?php echo $contact_forms[$i][name]?>
						</option>
						<?php } ?>
					</select>

				</form> &nbsp;&nbsp;&nbsp;&nbsp; <?php if($this->_plugin_activated)
				{ ?>
				<form name="awp_contact_delete_form" method="post" action=""
					style="float: left; padding-left: 30px;">
					<a
						href="javascript:contact_confirmation('<?php echo $selectedcontactform; ?>')">Delete</a>
					<input type="hidden" name="delformname" id="delformname" />
				</form> <?php } ?>
			</td>

		</tr>
	</tbody>
</table>

				<?php
				if(_isCurl())
				{
					$configDatas	= contactOptions($save=null);
					//$configDatas	= get_option("awp_contact_configdata");
					$configDatas	= json_decode($configDatas);
					if(isset($configDatas->leadAssignee)){
						foreach ($configDatas->leadAssignee as $assigne_key => $assigne_value){
							if($assigne_value->assigneeObjectId == APPTIVO_EMPLOYEE_OBJECT_ID){
								$assignee_employee_list[$assigne_value->assigneeName] = $assigne_value->assigneeObjectRefId;
							}
							else if($assigne_value->assigneeObjectId == APPTIVO_TEAM_OBJECT_ID){
								$assignee_team_list[$assigne_value->assigneeName] = $assigne_value->assigneeObjectRefId;
							}
						}
						$contact_assignee_default_name = $configDatas->leadAssignee[0]->assigneeName;
					}
					else{
						$contact_assignee_default_name = '';
					}
					if(!isset($assignee_employee_list)){
						$assignee_employee_list["No Employee"] = '';
					}
					if(!isset($assignee_team_list)){
						$assignee_team_list["No Team"] = '';
					}
				}
				if(isset($formproperties['contact_assignee_name'])){
					$contact_assignee_name = $formproperties['contact_assignee_name'];
				}
				else{
					$contact_assignee_name = $case_assignee_default_name;
				}

				?>

<form name="awp_contact_settings_form" method="post" action="">
	<table class="form-table">
		<tbody>
		<?php if(!empty($formproperties[tmpltype])) :?>
			<tr valign="top">
				<th valign="top"><label for="contactform_shortcode"><?php _e("Form Shortcode", 'apptivo-businesssite' ); ?>:</label>
					<br> <span class="description"><?php _e('Copy and Paste this shortcode in your page to display this contact form.','apptivo-businesssite'); ?>
				</span>
				</th>
				<td valign="top"><span id="awp_customform_shortcode"
					name="awp_customform_shortcode"> <input style="width: 300px;"
						type="text" id="contactform_shortcode"
						name="contactform_shortcode" readonly="true"
						value='[apptivocontactform name="<?php echo $selectedcontactform?>"]' />
				</span> <span style="margin: 10px;">*Developers Guide - <a
						href="<?php echo awp_developerguide('contactform-shortcode');?>"
						target="_blank">Contact Form Shortcodes.</a> </span>
				</td>
			</tr>
			<?php endif; ?>
			<tr valign="top">
				<th valign="top"><label for="awp_contactform_templatetype"><?php _e("Template Type", 'apptivo-businesssite' ); ?>:</label>
				</th>
				<td valign="top"><input type="hidden" id="awp_contactform_name"
					name="awp_contactform_name"
					value="<?php echo $selectedcontactform;?>"> <select
					name="awp_contactform_templatetype"
					id="awp_contactform_templatetype"
					onchange="change_contact_Template();">
						<option value="awp_plugin_template"
						<?php selected($formproperties[tmpltype],'awp_plugin_template'); ?>>Plugin
							Templates</option>
							<?php if(!empty($themetemplates)) : ?>
						<option value="theme_template"
						<?php selected($formproperties[tmpltype],'theme_template'); ?>>Templates
							from Current Theme</option>
							<?php endif; ?>
				</select> <span style="margin: 10px;">*Developers Guide - <a
						href="<?php echo awp_developerguide('contactform-template');?>"
						target="_blank">Contact Form Templates.</a> </span>
				</td>
			</tr>
			<tr valign="top">
				<th valign="top"><label for="awp_contactform_templatelayout"><?php _e("Template Layout", 'apptivo-businesssite' ); ?>:</label>
					<br> <span class="description">Selecting Theme template which
						doesnt support Contact form structure will wont show the contact
						form in webpage.</span>
				</th>
				<td valign="top"><select name="awp_contactform_plugintemplatelayout"
					id="awp_contactform_plugintemplatelayout"
					<?php if($formproperties['tmpltype'] == 'theme_template' ) echo 'style="display: none;"'; ?>>
					<?php foreach (array_keys( $plugintemplates ) as $template ) { ?>
						<option value="<?php echo $plugintemplates[$template]?>"
						<?php selected($formproperties[layout],$plugintemplates[$template]); ?>>
							<?php echo $template?>
						</option>
						<?php }  ?>
				</select> <select name="awp_contactform_themetemplatelayout"
					id="awp_contactform_themetemplatelayout"
					<?php if($formproperties['tmpltype'] != 'theme_template' ) echo 'style="display: none;"'; ?>>
					<?php foreach (array_keys( $themetemplates ) as $template ) : ?>
						<option value="<?php echo $themetemplates[$template]?>"
						<?php selected($formproperties['layout'],$themetemplates[$template]);?>>
							<?php echo $template?>
						</option>
						<?php endforeach;?>
				</select>
				</td>
			</tr>
			<tr valign="top">
				<th><label for="awp_contact_samepage"><?php _e("Confirmation message page", 'apptivo-businesssite' ); ?>:</label>
				</th>
				<td valign="top"><input type="radio" value="same"
					id="awp_contact_samepage" name="awp_contactform_confirm_msg_page"
					<?php checked('same',$formproperties[confirm_msg_page]); ?>
					checked="checked" /> <label for="awp_contact_samepage">Same Page</label>
					<input type="radio" value="other" id="awp_contact_otherpage"
					name="awp_contactform_confirm_msg_page"
					<?php checked('other',$formproperties[confirm_msg_page]); ?> /> <label
					for="awp_contact_otherpage">Other page</label> <br /> <br /> <select
					id="awp_contactform_confirmmsg_pageid"
					name="awp_contactform_confirmmsg_pageid"
					<?php if($formproperties[confirm_msg_page] != 'other') echo 'style="display:none;"';?>>
					<?php
					$pages = get_pages();
					foreach ($pages as $pagg) {
						?>
						<option value="<?php echo $pagg->ID; ?>"
						<?php selected($pagg->ID, $formproperties[confirm_msg_pageid]); ?>>
							<?php echo $pagg->post_title; ?>
						</option>
						<?php
					}
					?>
				</select>
				</td>
				</td>
			</tr>
			<tr valign="top" id="awp_contactform_confirmationmsg_tr"
			<?php if($formproperties[confirm_msg_page] == 'other') echo 'style="display:none;"';?>>
				<th valign="top"><label for="awp_contactform_confirmationmsg"><?php _e("Confirmation Message", 'apptivo-businesssite' ); ?>:</label>
					<br> <span class="description">This message will shown in your
						website page, once contact form submitted.</span>
				</th>
				<td valign="top">
					<div style="width: 620px;">
					<?php the_editor($formproperties[confmsg],'awp_contactform_confirmationmsg','',FALSE);  ?>
					</div>
				</td>
			</tr>
			<tr valign="top">
				<th><label for="awp_contactform_customcss"><?php _e("Custom CSS", 'apptivo-businesssite' ); ?>:</label>
					<br> <span valign="top" class="description">Style class provided
						here will override template style. Please refer Apptivo plugin
						help section for class name to be used.</span>
				</th>
				<td valign="top"><textarea name="awp_contactform_customcss"
						id="awp_contactform_customcss" size="100" cols="40" rows="10">
						<?php echo $formproperties[css];?>
					</textarea> <span style="margin: 10px;">*Developers Guide - <a
						href="<?php echo awp_developerguide('contactform-customcss');?>"
						target="_blank">Contact Form CSS.</a> </span>
				</td>

			</tr>
			<tr valign="top">
				<th><label id="awp_contactform_submit_type"
					for="awp_contactform_submit_type"><?php _e("Submit Button Type", 'apptivo-businesssite' ); ?>:</label>
					<br> <span valign="top" class="description"></span>
				</th>

				<td valign="top"><input type="radio" value="submit"
					id="awp_cont_btn" name="awp_contactform_submit_type"
					<?php checked('submit',$formproperties[submit_button_type]); ?>
					checked="checked" /> <label for="awp_cont_btn">Button</label> <input
					type="radio" value="image" id="awp_cont_img"
					name="awp_contactform_submit_type"
					<?php checked('image',$formproperties[submit_button_type]); ?> /> <label
					for="awp_cont_img">Image</label>
				</td>
			</tr>
			<tr valign="top">
				<th><label id="awp_contactform_submit_val"><?php _e("Button Text", 'apptivo-businesssite' ); ?>:</label>
					<br> <span valign="top" class="description"></span>
				</th>
				<td valign="top"><input type="text"
					name="awp_contactform_submit_value"
					id="awp_contactform_submit_value"
					value="<?php echo $formproperties[submit_button_val];?>" size="52" />
					<span id="contact_upload_img_button" style="display: none;"> <input
						id="contact_upload_image" type="button" value="Upload Image"
						class="button-primary" /> <br /> <?php _e('Enter an URL or upload an image.','apptivo-businesssite'); ?>
				</span>
				</td>
			</tr>
			<?php if(!empty($newsletter_categories)) { ?>
			<tr valign="top">
				<th valign="top"><label for="awp_contactform_targetlist"><?php _e("Apptivo Target List", 'apptivo-businesssite' ); ?>:</label>
					<br> <span class="description">Select the Apptivo Target List
						category to which this Form submitted has to be subscribed.</span>
				</th>
				<td valign="top"><select id="awp_contactform_targetlist"
					name="awp_contactform_targetlist"
					onchange="contactform_selectCategory('awp_contactform_targetlist');">
						<option value="">None</option>
						<?php if(count($newsletter_categories)=="1" && is_object($newsletter_categories)) { ?>
						<option
							value="<?php echo  $newsletter_categories->targetListId; ?>"
							<?php selected($newsletter_categories->targetListId, $formproperties[targetlist]) ?>>
							<?php echo  $newsletter_categories->targetListName; ?>
						</option>
						<?php } else {?>
						<?php foreach($newsletter_categories as $category){  ?>
						<option value="<?php echo  $category->targetListId; ?>"
						<?php selected($category->targetListId, $formproperties[targetlist]) ?>>
							<?php echo  $category->targetListName; ?>
						</option>
						<?php }  }?>
				</select>
				</td>
			</tr>
			<tr valign="top">
				<th><label for="awp_newsletterform_customcss">Provide subscribe
						option to user?:</label> <br> <span class="description"
					valign="top">if select yes means subscribe option display to user
						else subscribe user automatically.</span>
				</th>
				<td valign="top"><?php
				if(strlen(trim($formproperties[targetlist])) == 0 )
				{
					$disbleAction = 'disabled="disabled"';
				}
				?> <input <?php echo $disbleAction; ?> type="radio"
					name="subscribe_option" id="subscribe_option_yes" value="yes"
					<?php checked('yes', $formproperties[subscribe_option]); ?> /> <label
					for="subscribe_option_yes">Yes</label> <input
					<?php echo $disbleAction; ?> type="radio" name="subscribe_option"
					id="subscribe_option_no" value="no"
					<?php checked('no', $formproperties[subscribe_option]); ?> /> <label
					for="subscribe_option_no">No</label>
				</td>
			</tr>

			<tr valign="top">
				<th><label for="awp_subscribe_to_newsletter"><?php _e('Subscribe to Newsletter   (Display Text)','apptivo-businesssite'); ?>
				</label> <br> <span class="description" valign="top"></span>
				</th>
				<td valign="top"><input <?php echo $disbleAction; ?> type="text"
					size="52"
					value="<?php echo htmlentities($formproperties[subscribe_to_newsletter_displaytext]); ?>"
					id="awp_subscribe_to_newsletter" name="awp_subscribe_to_newsletter">
				</td>
			</tr>

			<?php } ?>
			<?php if(_isCurl())  { ?>
			<tr valign="top">
				<th valign="top"><label for="awp_contact_associates"><?php _e("Associate Lead With:", 'apptivo-businesssite' ); ?>
				</label>
				</th>
				<td valign="top"><?php $associateOption	=	array("No Need","Customer"); ?>
					<select name="awp_contact_associates" id="awp_contact_associates">
					<?php foreach ($associateOption as $associateValues ) { ?>
						<option value="<?php echo $associateValues?>"
						<?php selected($associateValues, $formproperties[contact_associates]); ?>>
							<?php echo $associateValues?>
						</option>
						<?php }  ?>
				</select>
				</td>
			</tr>

			<tr valign="top">
				<th valign="top" style="float: left;"><label
					for="awp_contact_createassociate"><?php _e("Create new customer and associate with Lead:", 'apptivo-businesssite' ); ?>
				</label>
				</th>
				<td valign="top"><?php $createOption	=	array("Do not create"=>"donot","Create New customer"=>"customer"); ?>
					<select name="awp_contact_createassociate"
					id="awp_contact_createassociate">
					<?php foreach ($createOption as $createdKey => $createdValue ) { ?>
						<option value="<?php echo $createdValue?>"
						<?php selected($createdValue, $formproperties[contact_create_associates]); ?>>
							<?php echo $createdKey?>
						</option>
						<?php }  ?>
				</select>
				</td>
			</tr>
			<tr valign="top">
				<th valign="top" style="float: left;"><label
					for="awp_contact_select_assignee"><?php _e("Select Assignee [ Employee/ Team ]:", 'apptivo-businesssite' ); ?>
				</label>
				</th>
				<td valign="top"><?php $createOption	=	array("Employee"=>"employee","Team"=>"team"); ?>
					<select name="awp_contact_select_assignee"
					id="awp_contact_select_assignee">
					<?php foreach ($createOption as $createdKey => $createdValue ) { ?>
						<option value="<?php echo $createdValue?>"
						<?php selected($createdValue, $formproperties[contact_assignee_type]); ?>>
							<?php echo $createdKey?>
						</option>
						<?php }  ?>
				</select> <?php if($formproperties[contact_assignee_type] == 'team') { $show_assignee_employee_style = 'style="display:none"'; } else { $show_assignee_team_style = ' style="display:none"';}?>
					<select <?php echo $show_assignee_employee_style; ?>
					class="awp_contact_select_assignee"
					name="awp_contact_select_assignee_employee"
					id="awp_contact_select_assignee_employee"
					onchange="document.getElementById('select_assignee_name_val').value=this.options[this.selectedIndex].text">
					<?php foreach ($assignee_employee_list as $createdKey => $createdValue ) { ?>
						<option value="<?php echo $createdValue?>"
						<?php selected($createdValue, $formproperties[contact_assignee_type_id]); ?>><?php echo $createdKey?></option>
						<?php }  ?>
				</select> <select <?php echo $show_assignee_team_style; ?>
					class="awp_contact_select_assignee"
					name="awp_contact_select_assignee_team"
					id="awp_contact_select_assignee_team"
					onchange="document.getElementById('select_assignee_name_val').value=this.options[this.selectedIndex].text">
					<?php foreach ($assignee_team_list as $createdKey => $createdValue ) { ?>
						<option value="<?php echo $createdValue?>"
						<?php selected($createdValue, $formproperties[contact_assignee_type_id]); ?>><?php echo $createdKey?></option>
						<?php }  ?>
				</select> <input type="hidden" id="select_assignee_name_val"
					name="select_assignee_name_val"
					value="<?php echo $contact_assignee_name;?>" />
				</td>
			</tr>
			<?php } ?>
			<tr valign="top">
				<th valign="top" style="float: left;"><label
					for="awp_contact_width_type"><?php _e("Form Outer Width :", 'apptivo-businesssite' ); ?>
				</label>
				</th>
				<td valign="top"><?php $createOption	=	array("Full Width (100%)"=>"100%","Half Width (50%)"=>"50%"); ?>
					<select name="awp_contact_width_type"
					id="awp_contact_width_type">
					<?php foreach ($createOption as $createdKey => $createdValue ) { ?>
						<option value="<?php echo $createdValue?>"
						<?php selected($createdValue, $formproperties[contact_width_type]); ?>>
							<?php echo $createdKey?>
						</option>
						<?php }  ?>
				</select>
				</td>
			</tr>
		</tbody>
	</table>

	<br>
	<?php
	echo "<h3>" . __( 'Contact Form Fields', 'awp_contactform' ) . "</h3>";?>


	<div style="margin: 10px;">
		<span class="description">Select and configure list of fields from
			below table to show in your contact form.</span> <span
			style="margin-left: 30px;">*Developers Guide - <a
			href="<?php echo awp_developerguide('contactform-basicconfig');?>"
			target="_blank">Basic Contact Form Config.</a> </span>
	</div>

	<br>
	<table width="900" cellspacing="0" cellpadding="0"
		id="contact_form_fields" name="contact_form_fields"
		style="border-collapse: collapse;">
		<tbody>
			<tr>
				<th></th>
			</tr>
			<tr align="center"
				style="background-color: rgb(223, 223, 223); font-weight: bold;"
				class="widefat">

				<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Field Name','apptivo-businesssite'); ?>
				</td>
				<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Show','apptivo-businesssite'); ?>
				</td>
				<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Require','apptivo-businesssite'); ?>
				</td>
				<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Display Order','apptivo-businesssite'); ?>
				</td>
				<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Display Text','apptivo-businesssite'); ?>
				</td>
				<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Field Type','apptivo-businesssite'); ?>
				</td>
				<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Validation Type','apptivo-businesssite'); ?>
				</td>
				<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Option Values','apptivo-businesssite'); ?>
				</td>
			</tr>
			<tr>
				<th></th>
			</tr>
			<?php $pos = 0;
			$index_key = 0;
			foreach( $this->get_master_fields() as $fieldsmasterproperties )
			{
				$enabled=0;
				$fieldExists=array();
				$fieldid=$fieldsmasterproperties['fieldid'];
				$fieldExistFlag="";
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
						"validation"=>$fields[$fieldExistFlag]['validation'],
						"options"=>$fields[$fieldExistFlag]['options'],
						"order"=>$fields[$fieldExistFlag]['order']);
				}else{
					if($fieldid=='lastname' || $fieldid=='email')
					{
						$enabled =1;
						$required =1;
					}
					else if($fieldid=='captcha'){
						$required =1;
					}

					$fieldData=array("fieldid"=>$fieldid,
						"fieldname"=>$fieldsmasterproperties['fieldname'],
						"show"=>$enabled,
						"required"=>$required,
						"showtext"=>$fieldsmasterproperties['defaulttext'],
						"type"=>"",
						"validation"=>"",
						"options"=>"",
						"order"=>"");
				}
				$pos=strpos($fieldsmasterproperties['fieldid'], "customfield");
				?>
			<tr>
				<td
					style="border: 1px solid rgb(204, 204, 204); padding-left: 10px; width: 150px;"><?php echo $fieldData['fieldname']?>
				</td>
				<td align="center" style="border: 1px solid rgb(204, 204, 204);"><input
				<?php  if($enabled) { ?> checked="checked"
				<?php } if($fieldData['fieldid']=='lastname' || $fieldData['fieldid']=='email' || $fieldData['fieldid']=='leadSource'){?>
					disabled="disabled" <?php } ?> type="checkbox"
					id="<?php echo $fieldData['fieldid']?>_show"
					name="<?php echo $fieldData['fieldid']?>_show" size="30"
					class="custom_fld" rel="<?php echo $fieldData['fieldid']?>"> <?php if($index_key > 18 ) :?>
					<input type="hidden" id="<?php echo $fieldData['fieldid']?>_newest"
					name="<?php echo $fieldData['fieldid']?>_newest" value="" /> <?php endif; $index_key++; ?>
				</td>
				<td align="center" style="border: 1px solid rgb(204, 204, 204);"><input
				<?php
				if(!$enabled) { ?> disabled="disabled"
				<?php }
				else if($fieldData['required'] ) { ?> checked="checked"
				<?php }?> type="checkbox"
				<?php if($fieldData['fieldid']=='lastname' || $fieldData['fieldid']=='email'|| $fieldData['fieldid']=='captcha'){?>
					disabled="disabled" <?php } ?>
					id="<?php echo $fieldData['fieldid']?>_require"
					name="<?php echo $fieldData['fieldid']?>_require" size="30"></td>
				<td align="center" style="border: 1px solid rgb(204, 204, 204);"><input
					type="text" class="num"
					id="<?php echo $fieldData['fieldid']?>_order"
					name="<?php echo $fieldData['fieldid']?>_order"
					value="<?php echo $fieldData['order']; ?>" size="3" maxlength="2"
					<?php if(!$enabled) { ?> disabled="disabled" <?php } ?>></td>
				<td align="center" style="border: 1px solid rgb(204, 204, 204);"><input
				<?php if(!$enabled) { ?> disabled="disabled" <?php } ?> type="text"
					id="<?php echo $fieldData['fieldid']?>_text"
					name="<?php echo $fieldData['fieldid']?>_text"
					value="<?php echo $fieldData['showtext']; ?>"></td>

				<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php
				$name_postfix="type";
				if($pos===false){
					?> 
					<input type="hidden"
					id="<?php echo $fieldData['fieldid']?>_type"
					name="<?php echo $fieldData['fieldid']?>_type"
					<?php if($fieldid=="country" || $fieldid=="leadStatus" || $fieldid=="leadType" || $fieldid=="leadSource" || $fieldid=="leadRank"){
						?> value="select"
						<?php }else if($fieldid=="comments"){ ?> value="textarea"
						<?php }else if($fieldid=="captcha"){ ?> value="captcha"
						<?php }else{?> value="text" <?php }?>> 
						<input
						<?php if(!$enabled) { ?> disabled="disabled" <?php } ?> size="6"
					readonly="readonly" type="text"
					id="<?php echo $fieldData['fieldid']?>_typehiddentext"
					name="<?php echo $fieldData['fieldid']?>_typehiddentext"
					<?php if($fieldid=="country" || $fieldid=="leadStatus" || $fieldid=="leadType" || $fieldid=="leadSource" || $fieldid=="leadRank"){
						?> value="Select"
						<?php }else if($fieldid=="comments"){ ?> value="Textarea"
						<?php }else if($fieldid=="captcha"){ ?> value="Captcha"
						<?php } else{ ?> value="Text box" <?php }?>> <?php
						$name_postfix="type_select";
				}else{

					?> <select name="<?php echo $fieldData['fieldid']?>_type"
					id="<?php echo $fieldData['fieldid']?>_type"
					<?php

					if($pos===false) {?> readonly="readonly"
					<?php }
					if(!$enabled || ($pos===false)) { ?>
					disabled="disabled" <?php } ?>
					onChange="contactform_showoptionstextarea('<?php echo $fieldData['fieldid']?>');">
					<?php foreach( $this->get_master_fieldtypes() as $masterfieldtypes )
					{ ?>

						<option value="<?php echo $masterfieldtypes['fieldtype'];?>"
						<?php if($masterfieldtypes['fieldtype']==$fieldData['type']){?>
							selected="selected" <?php }?>><?php echo $masterfieldtypes[fieldtypeLabel];?></option>
						<?php }?>

				</select> <?php }
				?>
				
				<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php  $pos=strpos($fieldsmasterproperties['fieldid'], "customfield");

				?> <?php if($pos===false){
					?> <?php
					if($fieldid=="telephonenumber")
					{
						?> <select name="<?php echo $fieldData['fieldid']?>_validation"
					id="<?php echo $fieldData['fieldid']?>_validation">
						<option value="string"
						<?php if($fieldData['validation']=="string"){?>
							selected="selected" <?php }?>>Number and String</option>
						<option value="number"
						<?php if($fieldData['validation']=="number"){?>
							selected="selected" <?php }?>>Number</option>

				</select> <?php }
				else{
					?> <input type="hidden"
					id="<?php echo $fieldData['fieldid']?>_validation"
					name="<?php echo $fieldData['fieldid']?>_validation"
					<?php if($fieldid=="email"){
						?> value="email"
						<?php }else if($fieldid!="telephonenumber"){ ?> value="none"
						<?php }?>> <input <?php if(!$enabled) { ?> disabled="disabled"
						<?php } ?> size="6" readonly="readonly" type="text"
					id="<?php echo $fieldData['fieldid']?>_validationhidden"
					name="<?php echo $fieldData['fieldid']?>_validationhidden"
					<?php if($fieldid=="email"){
						?> value="Email Id"
						<?php }else if($fieldid!="telephonenumber"){ ?> value="None"
						<?php }?>> <?php }
				}
				else if($fieldData!="telephonenumber"){
					?> <select name="<?php echo $fieldData['fieldid']?>_validation"
					id="<?php echo $fieldData['fieldid']?>_validation"
					<?php if(!$enabled){ ?> disabled="disabled"
					<?php }
					if($pos===false) {?> readonly="readonly" <?php }?>
					<?php if( ($fieldData['type'] != 'text' && (strtolower($fieldData['validation']) == 'none' || strtolower($fieldData['validation']) == ''))) { ?>
					disabled="disabled" <?php }?>>
					<?php foreach( $this->get_master_validations() as $masterfieldtypes )
					{ ?>
						<option value="<?php echo $masterfieldtypes['validation'];?>"
						<?php if($masterfieldtypes['validation']==$fieldData['validation']){?>
							selected="selected" <?php }?>>
							<?php echo $masterfieldtypes[validationLabel];?>
						</option>
						<?php }?>
				</select> <?php } ?>
				</td>

				<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php
				if($pos===false){
					if($fieldData['fieldname']!="Lead Status" && $fieldData['fieldname']!="Lead Type" && $fieldData['fieldname']!="Lead Source" && $fieldData['fieldname']!="Lead Rank")
					{
						echo "N/A";
						//Not a custom field. Dont show any thing
					}
					else if($fieldData['fieldname']=="Lead Status" || $fieldData['fieldname']=="Lead Type" || $fieldData['fieldname']=="Lead Source" || $fieldData['fieldname']=="Lead Rank")
					{
						$getConfig=get_option('awp_contactforms');
						for($i=0;$i<count($getConfig);$i++)
						{
							if($getConfig[$i]['name'] == $selectedcontactform)
							{
								$selectedConfigdata=$getConfig[$i]['contact_config'];

							}

						}
						if(_isCurl())
						{
							$selectedConfigName="";
							$configType		=	$fieldData['fieldid'];
							$configTypeName	= $configDatas->$configType;
							//echo "<pre>";print_r($configTypeName[0]->opportunityTypeName		);echo "</pre>";	
							$selectedConfig = $selectedConfigdata['awp_'.$configType.'_selected'];
							//echo "<pre>";print_r($configDatas);echo "</pre>";
							echo '<select name="absp_contact_config_'.$configType.'" style="width:100%;" id="'.$configType.'_Id">';
							if($configType=="leadSource" || $configType=="leadType"){
									echo '<option value="0">Select One</option>';
							}
							for($i=0;$i<count($configTypeName);$i++)
							{
								if($configType=="leadType"){
									$configtypeId= $configTypeName[$i]->opportunityTypeId;
									$configName	 = $configTypeName[$i]->opportunityTypeName;
								}
								else{
									$configtypeId= $configTypeName[$i]->lookupCode;
									$configName	 = $configTypeName[$i]->meaning;
								}
								if($selectedConfig == $configtypeId){
									$selectedConfigName	 = $configTypeName[$i]->meaning;
									if($configType=="leadType"){
										$selectedConfigName = $configTypeName[$i]->opportunityTypeName;
									}
								}
								
								echo '<option '.selected($selectedConfig,$configtypeId).' value="'.$configtypeId.'" rel=/"'.$configName.'/">'.$configName.'</option>';
							}
							echo '</select>';
							if($selectedConfigName=="" && $configType!="leadSource"){
									$selectedConfigName=$configTypeName[0]->meaning;
							}
							
							//if($configType=="leadSource"){$selectedConfigName="Select One";}
							echo '<input id="'.$configType.'Id" type="hidden" class="absp_hidden_'.$configType.'" value="'.htmlspecialchars($selectedConfigName).'" name="awp_'.$configType.'"/>';
							
						}
						
					}
				}
				else if(($fieldData['type']=="select")||($fieldData['type']=="radio")||($fieldData['type']=="checkbox")){?>
					<textarea style="width: 190px;" <?php if(!$enabled){ ?>
						disabled="disabled" <?php } ?>
						id="<?php echo $fieldData['fieldid']?>_options"
						name="<?php echo $fieldData['fieldid']?>_options">
						<?php echo $fieldData['options']; ?>
					</textarea> <?php }else {?> <textarea disabled="disabled"
						style="display: none; width: 190px;"
						id="<?php echo $fieldData['fieldid']?>_options"
						name="<?php echo $fieldData['fieldid']?>_options"></textarea> <?php }?>
				</td>
			</tr>
			<?php  } ?>

		</tbody>
	</table>
	<?php
	$addtional_custom = get_option('awp_addtional_custom');
	if(empty($addtional_custom))
	{
		$cnt_custom_filed = 6;
	}else {
		$cnt_custom_filed = 6 + count($addtional_custom);
	}
	?>

	<p>
		<a rel="<?php echo $cnt_custom_filed; ?>" href="javascript:void(0);"
			id="addcustom_field" name="addcustom_field">+Add Another Custom Field</a>
	</p>

	<p class="submit">
		<input <?php echo $disabledForm; ?> type="submit"
			name="awp_contactform_settings" id="awp_contactform_settings"
			class="button-primary"
			value="<?php esc_attr_e('Save Configuration') ?>" />
	</p>
</form>

</div>

	<?php
	}
	}

	/**
	 * Add contact form scripts and styles, only when short code is present in page/posts
	 */
	function check_for_shortcode($posts) {
		$found=awp_check_for_shortcode($posts,'[apptivocontactform');
		if ($found){
			// load styles and scripts
			//$this->loadscripts();
			//$this->loadstyles();
                    add_action('wp_footer', abwpExternalScripts);
		}
		return $posts;
	}

	/**
	 * Load the CSS files
	 */

	function loadstyles() {

	}
	/**
	 * Load the JS files
	 */
	function loadscripts() {
            // add_action('wp_footer', abwpExternalScripts);
	}
        
	function getAllCountryList(){
		$awp_services_obj=new AWPAPIServices();
		$countrylist = $awp_services_obj->getAllCountries();
		return $countrylist;
	}


	function getNewsletterCategory(){
		$awp_services_obj=new AWPAPIServices();
		$category = $awp_services_obj->getTargetListcategory();
		return $category;
	}
}

/*
 *  Save Contact Status, Contact Type and Contact Priority
 *
 */

function contactOptions($save)
{
	if(_isCurl())
	{
		$lead_status=array();
		$lead_type=array();
		$lead_source=array();
		$lead_rank=array();
		$contactConfigData = getAllContactConfigData();
                if($contactConfigData == ''){
                    echo '<script> 
                            alert("'.AWP_SERVICE_ERROR_MESSAGE.'");
                            window.location.href = "'.str_replace('awp_contactforms', 'awp_general', $_SERVER["REQUEST_URI"]).'";'.
                         '</script>'; 
                }
                
                if(isset($contactConfigData->leadStatuses)){
                    foreach ($contactConfigData->leadStatuses as $leadStatus){
			if($leadStatus->disabled !='Y'){
				array_push($lead_status, $leadStatus);
			}
                    }
                }
		if(isset($contactConfigData->leadTypes)){
                    foreach ($contactConfigData->leadTypes as $leadType){
			if($leadType->disabled !='Y'){
				array_push($lead_type, $leadType);
			}
                    }
		}
                if(isset($contactConfigData->leadSources)){
                    foreach ($contactConfigData->leadSources as $leadSource){
			if($leadSource->disabled !='Y'){
				array_push($lead_source, $leadSource);
			}
                    }    
		}		
		if(isset($contactConfigData->leadRanks)){
                    foreach ($contactConfigData->leadRanks as $leadRank){
			if($leadRank->disabled !='Y'){
				array_push($lead_rank, $leadRank);
			}
                    }
		}
		$lead_assignee  	= $contactConfigData->assigneesList;
		$contact_config		= array("leadStatus"=>$lead_status,"leadType"=>$lead_type,"leadSource"=>$lead_source,'leadAssignee'=>$lead_assignee,'leadRank'=>$lead_rank);
		$contact_configDatas	= json_encode($contact_config);
	}
	if($save=='save'){
	check_option('awp_contact_configdata',$contact_configDatas);
	}
	return $contact_configDatas;
}


function getContactFirstConfigData($leadType,$leadSource,$leadStatus,$checkleadRank,$contactForm)
{
	$firstConfig	=	get_option("awp_contact_configdata");
	$firstConfig	=	json_decode($firstConfig);
	$getConfig=get_option('awp_contactforms');
	
	for($i=0;$i<count($getConfig);$i++)
	{
		if($getConfig[$i]['name']==$contactForm)
		{
			$formConfig=$getConfig[$i]['contact_config'];
		}
	}
	if($leadType=="0")
	{
		foreach ($firstConfig->leadType as $leadType)
		{
			if($formConfig["awp_leadType_selected"]==$leadType->opportunityTypeId)
			{
				echo '<input type="hidden" name="type_name" value="'.$leadType->opportunityTypeName.'"/>';
				echo '<input type="hidden" id="type" name="type" value="'.$leadType->opportunityTypeId.'"/>';
				break;
			}
			 
		}
	}
	elseif($leadSource=="0")
	{
		foreach ($firstConfig->leadSource as $leadSource)
		{
			if($formConfig["awp_leadSource_selected"]==$leadSource->lookupCode)
			{
				echo '<input type="hidden" name="source_name" value="'.$leadSource->meaning.'"/>';
				echo '<input type="hidden" id="source" name="source" value="'.$leadSource->lookupCode.'"/>';
				break;
			}

		}
	}
	elseif($leadStatus=="0")
	{

		foreach ($firstConfig->$leadStatus as $leadStatus)
		{
			if($formConfig["awp_leadStatus_selected"]==$leadStatus->lookupCode)
			{
				echo '<input type="hidden" name="status_name" value="'.$leadStatus->meaning.'"/>';
				echo '<input type="hidden" id="status" name="status" value="'.$leadStatus->lookupCode.'"/>';
				break;
			}

		}
	}
	elseif($leadRank=="0")
	{

		foreach ($firstConfig->$leadRank as $leadRank)
		{
			if($formConfig["awp_leadRank_selected"]==$leadRank->lookupCode)
			{
				echo '<input type="hidden" name="rank_name" value="'.$leadRank->meaning.'"/>';
				echo '<input type="hidden" id="rank" name="rank" value="'.$leadRank->lookupCode.'"/>';
				break;
			}

		}
	}
}

add_action("admin_footer", "apptivo_business_contact_assignee_validation");

function apptivo_business_contact_assignee_validation() {
	?>
<script type="text/javascript">
    jQuery(document).ready(function(){
    	var selected_associates = jQuery('#awp_contact_associates').val();
    	if(selected_associates=='No Need'){
    		jQuery("#awp_contact_createassociate option[value='donot']").attr("selected","selected");
    		jQuery("#awp_contact_createassociate").attr("disabled", "disabled");
    	}
        jQuery("#awp_contact_associates").change(function(){
        	var selected_associates = jQuery('#awp_contact_associates').val();
        	if(selected_associates=='No Need'){
        		jQuery("#awp_contact_createassociate option[value='donot']").attr("selected","selected");
        		jQuery("#awp_contact_createassociate").attr("disabled", "disabled");
        	}
        	if(selected_associates=='Customer'){
        		jQuery("#awp_contact_createassociate option[value='customer']").attr("selected","selected");
        		jQuery("#awp_contact_createassociate").removeAttr("disabled");
        	}
      });

        jQuery("#leadStatus_Id").change(function(){
        	jQuery("#leadStatusId").val(jQuery("#leadStatus_Id option:selected").text());
        });
        jQuery("#leadSource_Id").change(function(){
        	jQuery("#leadSourceId").val(jQuery("#leadSource_Id option:selected").text());
        });
        jQuery("#leadType_Id").change(function(){
        	jQuery("#leadTypeId").val(jQuery("#leadType_Id option:selected").text());
        });
        jQuery("#leadRank_Id").change(function(){
        	jQuery("#leadRankId").val(jQuery("#leadRank_Id option:selected").text());
        });
        
    	jQuery("#awp_contact_select_assignee").change(function(){
        	
			var selected_atype = jQuery('#awp_contact_select_assignee').val();
			jQuery(".awp_contact_select_assignee").hide();
			jQuery("#awp_contact_select_assignee_"+selected_atype).show();
			if(selected_atype == 'team'){
				jQuery("#select_assignee_name_val").val(jQuery("#awp_contact_select_assignee_team option:selected").text());	
			}
			else{
				jQuery("#select_assignee_name_val").val(jQuery("#awp_contact_select_assignee_employee option:selected").text());		
			}					
		});
    });
    </script>
	<?php
}