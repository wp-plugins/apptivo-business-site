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
	$submitformname=$_POST['awp_contactformname'];
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
	            wp_safe_redirect($location);
	    endif;
		if(!empty($contactform['fields']))
		{
                foreach($contactform['fields'] as $field){
                    if(isset($field['fieldid'])=="country")
                    {
                       $countrylist = $this->getAllCountryList();
                       break;
                    }
                }
		}


		if(!empty($contactform) && !empty($contactform['fields'])){
			    //Registering Validation Scripts.
	            $this->loadscripts();
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
			$submittedformvalues['name']=$contactform[name];
                        if(isset($_POST['subscribe'])){
                           $submittedformvalues['targetlist']=$contactform[targetlist];
                        }
                        else{
                           if($contactform[subscribe_option]=='no'){
                             $submittedformvalues['targetlist']=$contactform[targetlist];
                            }
                        }
                        $customfields="";

			foreach($contactformfields as $field)
			{
				$fieldid=$field['fieldid'];
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
						$customfields.="<br/><b>".$field['showtext']."</b>:&nbsp;".stripslashes($customfieldVal);
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

						$customfields .= "<br/><b>".$field['showtext']."</b>:".stripslashes($customfieldVal);
					}
				}
			}
			$customfields .= "<br/><b>Requested IP</b>:".stripslashes(get_RealIpAddr());

                     	if(trim($customfields)!="")
                     	{
				         $submittedformvalues["notes"]=$customfields;
                     	}
                        $firstName = $submittedformvalues['firstname'];
                        $lastName = $submittedformvalues['lastname'];
                        $emailId = $submittedformvalues['email'];
                        $jobTitle = $submittedformvalues['jobtitle'];
                        $company =  $submittedformvalues['company'];
                        $address1 = $submittedformvalues['address1'];
                        $address2 = $submittedformvalues['address2'];
                        $city = $submittedformvalues['city'];
                        $state = $submittedformvalues['state'];
                        $zipCode = $submittedformvalues['zipcode'];
                        $simple_captcha=$submittedformvalues['simple_captcha'];
                        $bestWayToContact = $submittedformvalues['bestway'];
                        $country = $submittedformvalues['country'];
                        $leadSource = $submittedformvalues['name'];
                        $phoneNumber = $submittedformvalues['telephonenumber'];
                        $comments = $submittedformvalues['comments'];
                        $noteDetails = $submittedformvalues['notes'];
                        $targetlistid = $submittedformvalues['targetlist'];
                        if(!empty($noteDetails)){
                        $parent1details = nl2br($noteDetails);
                        $noteDetails = notes('Custom Fields',$parent1details,$parent1NoteId);
                         if (extension_loaded('soap')) {
                        $contactformdetails=$this->get_settings($leadSource);
						$formproperties=$contactformdetails[properties];
						$getTargetName=getTargetListcategory();
						if($getTargetName!="")
						{
						foreach($getTargetName as $category){
                                     if($category->targetListId==$formproperties[targetlist])
                                     {
                                 		$targetname=$category->targetListName;     	
                                     }
                                 } 
						}
						}
                        }
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
                        $response = saveLeadDetails($firstName , $lastName, $emailId, $jobTitle, $company, $address1, $address2, $city, $state, $zipCode, $bestWayToContact, $country, $leadSource, $phoneNumber, $comments, $noteDetails,$targetname);
                        $response_msg = $response;
                        if($response_msg=='Success' && $response != 'E_100'){
                            if(!empty($contactform[confmsg])){
                            $confmsg = $contactform[confmsg];
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
            $contactformdetails['subscribe_to_newsletter_displaytext']=$contactformproperties['subscribe_to_newsletter_displaytext'];
            $contactformdetails['submit_button_type']=$contactformproperties['submit_button_type'];
            $contactformdetails['submit_button_val']=$contactformproperties['submit_button_val'];
			$check_browser	=	get_current_browser();
            if($check_browser=="chrome") { $chrome= ".recaptcha_source{width:40%;}"; }
			echo '<style type="text/css"> form input.required{color:#000;}label.error,.absp_contact_mandatory{color:#FF0400;}.absp_success_msg{color:green;font-weight:bold;padding-bottom:5px;}.absp_error{color:red;font-weight:bold;padding-bottom:5px;}'.$chrome.'</style>';
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
			array('fieldid' => 'email','fieldname' => 'Email','defaulttext' => 'Email','showorder' => '3','validation' => 'email','fieldtype' => 'text'),
			array('fieldid' => 'jobtitle','fieldname' => 'Job Title','defaulttext' => 'Job Title','showorder' => '4','validation' => 'text','fieldtype' => 'text'),
			array('fieldid' => 'company','fieldname' => 'Company','defaulttext' => 'Company','showorder' => '5','validation' => 'text','fieldtype' => 'text'),
			array('fieldid' => 'address1','fieldname' => 'Address1','defaulttext' => 'Address1','showorder' => '6','validation' => 'text','fieldtype' => 'text'),
			array('fieldid' => 'address2','fieldname' => 'Address2','defaulttext' => 'Address2','showorder' => '7','validation' => 'text','fieldtype' => 'text'),
			array('fieldid' => 'city','fieldname' => 'City','defaulttext' => 'City','showorder' => '8','validation' => 'text','fieldtype' => 'text'),
			array('fieldid' => 'state','fieldname' => 'State','defaulttext' => 'State','showorder' => '9','validation' => 'text','fieldtype' => 'text'),
			array('fieldid' => 'zipcode','fieldname' => 'ZipCode','defaulttext' => 'ZipCode','showorder' => '10','validation' => 'text','fieldtype' => 'text'),
			array('fieldid' => 'country','fieldname' => 'Country','defaulttext' => 'Country','showorder' => '11','validation' => 'text','fieldtype' => 'select'),
			array('fieldid' => 'telephonenumber','fieldname' => 'Telephone Number','defaulttext' => 'Telephone Number','showorder' => '12','validation' => '','fieldtype' => 'text'),
			array('fieldid' => 'comments','fieldname' => 'Comments','defaulttext' => 'Comments','showorder' => '13','validation' => 'textarea','fieldtype' => 'textarea'),
                        array('fieldid' => 'captcha','fieldname' => 'Captcha','defaulttext' => 'Captcha','showorder' => '14','validation' => 'text','fieldtype' => 'captcha'),
			array('fieldid' => 'customfield1','fieldname' => 'Custom Field 1','defaulttext' => 'Custom Field1','showorder' => '15','validation' => '','fieldtype' => 'select'),
			array('fieldid' => 'customfield2','fieldname' => 'Custom Field 2','defaulttext' => 'Custom Field2','showorder' => '16','validation' => '','fieldtype' => 'select'),
			array('fieldid' => 'customfield3','fieldname' => 'Custom Field 3','defaulttext' => 'Custom Field3','showorder' => '17','validation' => '','fieldtype' => 'select'),
			array('fieldid' => 'customfield4','fieldname' => 'Custom Field 4','defaulttext' => 'Custom Field4','showorder' => '18','validation' => '','fieldtype' => 'radio'),
			array('fieldid' => 'customfield5','fieldname' => 'Custom Field 5','defaulttext' => 'Custom Field5','showorder' => '19','validation' => '','fieldtype' => 'checkbox')

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
            $response= CreateContactFormLeads($newcontactformname);
            }
            else { $response = "soap"; }
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
					$updatemessage= "Form Name cannot be empty.";
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
			$newformname=$_POST['awp_contactform_name'];
                        if($_POST['awp_contactform_templatetype']=="awp_plugin_template")
			$templatelayout=$_POST['awp_contactform_plugintemplatelayout'];
			else
			$templatelayout=$_POST['awp_contactform_themetemplatelayout'];

			$contactformproperties=array(
							'tmpltype' =>$_POST['awp_contactform_templatetype'],
	                        'layout' =>$templatelayout,
	                        'confmsg' => stripslashes($_POST['awp_contactform_confirmationmsg']),
			                'confirm_msg_page' => $_POST['awp_contactform_confirm_msg_page'],
							'confirm_msg_pageid' => $_POST['awp_contactform_confirmmsg_pageid'],
							'targetlist' =>$_POST['awp_contactform_targetlist'],
	                        'css' => stripslashes($_POST['awp_contactform_customcss']),
                            'subscribe_option' => $_POST['subscribe_option'],
			                'subscribe_to_newsletter_displaytext' => $_POST['awp_subscribe_to_newsletter'],
                            'submit_button_type' => $_POST['awp_contactform_submit_type'],
                            'submit_button_val' => $_POST['awp_contactform_submit_value']);

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
				$newcontactformdetails=array('name'=>$newformname,'properties'=>$contactformproperties,'fields'=>$contactformfields);

				$formExists="";
				if(!empty($contact_forms))
					$formExists = awp_recursive_array_search($contact_forms,$newformname,'name' );
				if(trim($formExists)!=="" ){

					unset($contact_forms[$formExists]);
					array_push($contact_forms, $newcontactformdetails);
					sort($contact_forms);

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
	<form name="awp_contactform_new" id="awp_contactform_new" method="post" action="" >

	   <p>
	   <img id="elementToResize" src="<?php echo awp_flow_diagram('contactform');?>" alt="contactform" title="contactform"  />
	   </p>
	    <p style="margin:10px;">
		For Complete instructions,see the <a href="<?php echo awp_developerguide('contactform');?>" target="_blank">Developer's Guide.</a>
		</p>

		<p>
		<?php _e("Contact Form Name", 'apptivo-businesssite' ); ?>
			<span style="color:#f00;">*</span>&nbsp;&nbsp;<input type="text" name="newcontactformname" id="newcontactformname" size="20" maxlength="50" value="" >
			<span class="description"><?php _e('This form name will be used as your Lead Source in Apptivo.','apptivo-businesssite'); ?></span>
			</p>
			<p>
			<input <?php echo $disabledForm;?> type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Add New') ?>" />
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
					<form name="awp_contact_selection_form" method="post" action="" style="float:left;" >
					<select name="awp_contactform_select_form" id="awp_contactform_select_form" onchange="this.form.submit();">
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

					</form>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<?php if($this->_plugin_activated)
					{ ?>
					<form name="awp_contact_delete_form" method="post" action="" style="float:left;padding-left:30px;">
					<a  href="javascript:contact_confirmation('<?php echo $selectedcontactform; ?>')" >Delete</a>
					<input type="hidden" name="delformname" id="delformname"  />
					</form>
					<?php } ?>
					</td>

				</tr>
			</tbody>
		</table>

	<form name="awp_contact_settings_form" method="post" action="">
		<table class="form-table">
			<tbody>
			<?php if(!empty($formproperties[tmpltype])) :?>
				<tr valign="top">
					<th valign="top"><label for="contactform_shortcode"><?php _e("Form Shortcode", 'apptivo-businesssite' ); ?>:</label>
					<br><span class="description"><?php _e('Copy and Paste this shortcode in your page to display this contact form.','apptivo-businesssite'); ?></span>
					</th>
					<td valign="top"><span id="awp_customform_shortcode" name="awp_customform_shortcode">
					<input style="width:300px;" type="text" id="contactform_shortcode" name="contactform_shortcode"  readonly="true" value='[apptivocontactform name="<?php echo $selectedcontactform?>"]' />
					</span>
					<span style="margin:10px;">*Developers Guide - <a href="<?php echo awp_developerguide('contactform-shortcode');?>" target="_blank">Contact Form Shortcodes.</a></span>
					</td>
				</tr>
				<?php endif; ?>
				<tr valign="top">
					<th valign="top"><label for="awp_contactform_templatetype"><?php _e("Template Type", 'apptivo-businesssite' ); ?>:</label>
					</th>
					<td valign="top">
					<input type="hidden" id="awp_contactform_name" name="awp_contactform_name" value="<?php echo $selectedcontactform;?>">

						<select name="awp_contactform_templatetype" id="awp_contactform_templatetype" onchange="change_contact_Template();">
							<option value="awp_plugin_template"  <?php selected($formproperties[tmpltype],'awp_plugin_template'); ?> >Plugin Templates</option>
							<?php if(!empty($themetemplates)) : ?>
							<option value="theme_template"  <?php selected($formproperties[tmpltype],'theme_template'); ?> >Templates from Current Theme</option>
							<?php endif; ?>
						</select>

					<span style="margin:10px;">*Developers Guide - <a href="<?php echo awp_developerguide('contactform-template');?>" target="_blank">Contact Form Templates.</a></span>
					</td>
				</tr>
				<tr valign="top">
					<th valign="top"><label for="awp_contactform_templatelayout"><?php _e("Template Layout", 'apptivo-businesssite' ); ?>:</label>
					<br><span class="description">Selecting Theme template which doesnt support Contact form structure will wont show the contact form in webpage.</span>
					</th>
					<td valign="top">

					<select name="awp_contactform_plugintemplatelayout" id="awp_contactform_plugintemplatelayout" <?php if($formproperties['tmpltype'] == 'theme_template' ) echo 'style="display: none;"'; ?> >
						<?php foreach (array_keys( $plugintemplates ) as $template ) { ?>
							<option value="<?php echo $plugintemplates[$template]?>" <?php selected($formproperties[layout],$plugintemplates[$template]); ?> >
							<?php echo $template?>
							</option>
							<?php }  ?>
					</select>

					<select name="awp_contactform_themetemplatelayout" id="awp_contactform_themetemplatelayout" <?php if($formproperties['tmpltype'] != 'theme_template' ) echo 'style="display: none;"'; ?> >
						<?php foreach (array_keys( $themetemplates ) as $template ) : ?>
							<option value="<?php echo $themetemplates[$template]?>" <?php selected($formproperties['layout'],$themetemplates[$template]);?> >
							<?php echo $template?>
							</option>
							<?php endforeach;?>
					</select>
					</td>
				</tr>
				<tr valign="top">
					<th><label for="awp_contact_samepage"><?php _e("Confirmation message page", 'apptivo-businesssite' ); ?>:</label>
					</th>
					<td valign="top">
                          <input type="radio" value="same" id="awp_contact_samepage" name="awp_contactform_confirm_msg_page" <?php checked('same',$formproperties[confirm_msg_page]); ?> checked="checked" />
                          <label for="awp_contact_samepage">Same Page</label>
                          <input type="radio" value="other" id="awp_contact_otherpage" name="awp_contactform_confirm_msg_page" <?php checked('other',$formproperties[confirm_msg_page]); ?>/>
                          <label for="awp_contact_otherpage">Other page</label>
                          <br />
                           <br />
                           <select id="awp_contactform_confirmmsg_pageid" name="awp_contactform_confirmmsg_pageid" <?php if($formproperties[confirm_msg_page] != 'other') echo 'style="display:none;"';?> >
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
				<tr valign="top" id="awp_contactform_confirmationmsg_tr" <?php if($formproperties[confirm_msg_page] == 'other') echo 'style="display:none;"';?> >
					<th valign="top"><label for="awp_contactform_confirmationmsg"><?php _e("Confirmation Message", 'apptivo-businesssite' ); ?>:</label>
					<br><span class="description">This message will shown in your website page, once contact form submitted.</span>
					</th>
					<td valign="top">
					<div style="width:620px;">
					<?php the_editor($formproperties[confmsg],'awp_contactform_confirmationmsg','',FALSE);  ?>
					</div>
					</td>
				</tr>
				<tr valign="top">
					<th><label for="awp_contactform_customcss"><?php _e("Custom CSS", 'apptivo-businesssite' ); ?>:</label>
					<br><span valign="top" class="description">Style class provided here will override template style. Please refer Apptivo plugin help section for class name to be used.</span>
					</th>
					<td valign="top"><textarea name="awp_contactform_customcss"
							id="awp_contactform_customcss" size="100" cols="40" rows="10"><?php echo $formproperties[css];?></textarea>
							<span style="margin:10px;">*Developers Guide - <a href="<?php echo awp_developerguide('contactform-customcss');?>" target="_blank">Contact Form CSS.</a></span>
					</td>

				</tr>
                    <tr valign="top">
					<th><label id="awp_contactform_submit_type" for="awp_contactform_submit_type"><?php _e("Submit Button Type", 'apptivo-businesssite' ); ?>:</label>
					<br><span valign="top" class="description"></span>
					</th>

                    <td valign="top">
                      <input type="radio" value="submit" id="awp_cont_btn" name="awp_contactform_submit_type" <?php checked('submit',$formproperties[submit_button_type]); ?> checked="checked" />
                      <label for="awp_cont_btn">Button</label>
                      <input type="radio" value="image" id="awp_cont_img" name="awp_contactform_submit_type"<?php checked('image',$formproperties[submit_button_type]); ?>/>
                      <label for="awp_cont_img">Image</label>
					</td>
				</tr>
                <tr valign="top">
					<th><label id="awp_contactform_submit_val" ><?php _e("Button Text", 'apptivo-businesssite' ); ?>:</label>
					<br><span valign="top" class="description"></span>
					</th>
                    <td valign="top"><input type="text" name="awp_contactform_submit_value" id="awp_contactform_submit_value" value="<?php echo $formproperties[submit_button_val];?>" size="52"/>
                    <span id="contact_upload_img_button" style="display:none;">
                    <input id="contact_upload_image" type="button" value="Upload Image" class="button-primary" />
					<br /><?php _e('Enter an URL or upload an image.','apptivo-businesssite'); ?>
					</span>
					</td>
				</tr>
			<?php if(!empty($newsletter_categories)) { ?>
				<tr valign="top">
				<th valign="top"><label for="awp_contactform_targetlist"><?php _e("Apptivo Target List", 'apptivo-businesssite' ); ?>:</label>
					<br><span class="description">Select the Apptivo Target List category to which this Form submitted has to be subscribed.</span>
					</th>
				<td valign="top">
                                 <select id="awp_contactform_targetlist" name="awp_contactform_targetlist" onchange="contactform_selectCategory('awp_contactform_targetlist');" >
                                 <option value="" > None </option>
                                   <?php if(count($newsletter_categories)=="1" && is_object($newsletter_categories)) { ?>
                                   <option value="<?php echo  $newsletter_categories->targetListId; ?>" <?php selected($newsletter_categories->targetListId, $formproperties[targetlist]) ?>><?php echo  $newsletter_categories->targetListName; ?></option>
                                   <?php } else {?>
                                <?php foreach($newsletter_categories as $category){  ?>
                                     <option value="<?php echo  $category->targetListId; ?>" <?php selected($category->targetListId, $formproperties[targetlist]) ?>><?php echo  $category->targetListName; ?></option>
                                   <?php }  }?>
                                 </select>
				</td>
                                </tr>
                                 <tr valign="top">
				<th><label for="awp_newsletterform_customcss">Provide subscribe option to user?:</label>
				<br><span class="description" valign="top">if select yes means subscribe option display to user else subscribe user automatically.</span>
				</th>
				<td valign="top">
				<?php
				if(strlen(trim($formproperties[targetlist])) == 0 )
				{
					$disbleAction = 'disabled="disabled"';
				}
				?>
                 <input <?php echo $disbleAction; ?> type="radio" name="subscribe_option" id="subscribe_option_yes" value="yes" <?php checked('yes', $formproperties[subscribe_option]); ?> />
                 <label for="subscribe_option_yes">Yes</label>
                 <input <?php echo $disbleAction; ?> type="radio" name="subscribe_option" id="subscribe_option_no" value="no" <?php checked('no', $formproperties[subscribe_option]); ?> />
                 <label for="subscribe_option_no">No</label>
				</td>
                                </tr>

                <tr valign="top">
					<th><label for="awp_subscribe_to_newsletter"><?php _e('Subscribe to Newsletter   (Display Text)','apptivo-businesssite'); ?></label>
					<br><span class="description" valign="top"></span>
					</th>
                    <td valign="top"><input <?php echo $disbleAction; ?> type="text" size="52" value="<?php echo $formproperties[subscribe_to_newsletter_displaytext]; ?>" id="awp_subscribe_to_newsletter" name="awp_subscribe_to_newsletter"></td>
				</tr>

                 <?php } ?>

			</tbody>
		</table>

		<br>
		<?php
		echo "<h3>" . __( 'Contact Form Fields', 'awp_contactform' ) . "</h3>";?>


		<div style="margin:10px;">
		<span class="description">Select and configure list of fields from below table to show in your contact form.</span>
		<span style="margin-left:30px;">*Developers Guide - <a href="<?php echo awp_developerguide('contactform-basicconfig');?>" target="_blank">Basic Contact Form Config.</a></span>
		</div>

		<br>
		<table width="900" cellspacing="0" cellpadding="0" id="contact_form_fields" name="contact_form_fields" style="border-collapse: collapse;">
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
					<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Validation Type','apptivo-businesssite'); ?></td>
					<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Option Values','apptivo-businesssite'); ?></td>
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
				<tr >
					<td
						style="border: 1px solid rgb(204, 204, 204); padding-left: 10px;width:150px;"><?php echo $fieldData['fieldname']?>
					</td>
					<td align="center" style="border: 1px solid rgb(204, 204, 204);">
					<input
					<?php  if($enabled) { ?> checked="checked" <?php } if($fieldData['fieldid']=='lastname' || $fieldData['fieldid']=='email'){?> disabled="disabled" <?php } ?> type="checkbox"  id="<?php echo $fieldData['fieldid']?>_show" name="<?php echo $fieldData['fieldid']?>_show" size="30"
					class="custom_fld" rel="<?php echo $fieldData['fieldid']?>" >

					<?php if($index_key > 18 ) :?>
					<input type="hidden" id="<?php echo $fieldData['fieldid']?>_newest" name="<?php echo $fieldData['fieldid']?>_newest" value="" />
					<?php endif; $index_key++; ?>
					</td>
					<td align="center" style="border: 1px solid rgb(204, 204, 204);">
					<input
					<?php
						if(!$enabled) { ?> disabled="disabled" <?php }
					 else if($fieldData['required'] ) { ?>
						checked="checked" <?php }?>type="checkbox"
                                         <?php if($fieldData['fieldid']=='lastname' || $fieldData['fieldid']=='email'|| $fieldData['fieldid']=='captcha'){?> disabled="disabled" <?php } ?>
						id="<?php echo $fieldData['fieldid']?>_require"
						name="<?php echo $fieldData['fieldid']?>_require" size="30"></td>
					<td align="center" style="border: 1px solid rgb(204, 204, 204);">
					<input type="text"
						class="num"
						id="<?php echo $fieldData['fieldid']?>_order"
						name="<?php echo $fieldData['fieldid']?>_order"
						value="<?php echo $fieldData['order']; ?>" size="3"
						maxlength="2" <?php if(!$enabled) { ?> disabled="disabled" <?php } ?>></td>
					<td align="center" style="border: 1px solid rgb(204, 204, 204);"><input
					<?php if(!$enabled) { ?> disabled="disabled" <?php } ?>
						type="text" id="<?php echo $fieldData['fieldid']?>_text"
						name="<?php echo $fieldData['fieldid']?>_text"
						value="<?php echo $fieldData['showtext']; ?>"></td>

					<td align="center" style="border: 1px solid rgb(204, 204, 204);">
					<?php
					$name_postfix="type";
					if($pos===false){
						?>
						<input
						type="hidden"
						id="<?php echo $fieldData['fieldid']?>_type"
						name="<?php echo $fieldData['fieldid']?>_type"
						<?php if($fieldid=="country"){
							?>value="select"
							<?php }else if($fieldid=="comments"){ ?>
							value="textarea"
							<?php }else if($fieldid=="captcha"){ ?>
							value="captcha"
							<?php }else{?>
							value="text"
							<?php }?> >
						<input
						<?php if(!$enabled) { ?> disabled="disabled" <?php } ?> size="6" readonly="readonly"
						type="text" id="<?php echo $fieldData['fieldid']?>_typehiddentext"
						name="<?php echo $fieldData['fieldid']?>_typehiddentext"
						<?php if($fieldid=="country"){
							?>value="Select"
							<?php }else if($fieldid=="comments"){ ?>
							value="Textarea"
							<?php }else if($fieldid=="captcha"){ ?>
							value="Captcha"
							<?php } else{ ?>
							value="Text box"
							<?php }?> ><?php
						$name_postfix="type_select";
					}else{

						?>

					<select name="<?php echo $fieldData['fieldid']?>_type" id="<?php echo $fieldData['fieldid']?>_type"
					<?php

						if($pos===false) {?>readonly="readonly"<?php }
						if(!$enabled || ($pos===false)) { ?> disabled="disabled" <?php } ?>

						onChange="contactform_showoptionstextarea('<?php echo $fieldData['fieldid']?>');"
					>
					<?php foreach( $this->get_master_fieldtypes() as $masterfieldtypes )
				{ ?>

					<option value="<?php echo $masterfieldtypes['fieldtype'];?>"
					<?php if($masterfieldtypes['fieldtype']==$fieldData['type']){?>

					selected="selected"<?php }?>><?php echo $masterfieldtypes[fieldtypeLabel];?></option>
					<?php }?>

					</select>
					<?php }
					?>
					<td align="center" style="border: 1px solid rgb(204, 204, 204);">
					<?php  $pos=strpos($fieldsmasterproperties['fieldid'], "customfield");

                                         ?>

                                        <?php if($pos===false){
                                        ?>
                                            <?php
                                            if($fieldid=="telephonenumber")
                                                {
                                            ?>
                                            <select name="<?php echo $fieldData['fieldid']?>_validation" id="<?php echo $fieldData['fieldid']?>_validation">
										<option value="string" <?php if($fieldData['validation']=="string"){?>selected="selected"<?php }?> >Number and String</option>
										<option value="number" <?php if($fieldData['validation']=="number"){?>selected="selected"<?php }?>>Number</option>

                                            </select>
                                          <?php }
                                          else{
                                          ?>


                                            <input
						type="hidden"
						id="<?php echo $fieldData['fieldid']?>_validation"
						name="<?php echo $fieldData['fieldid']?>_validation"
						<?php if($fieldid=="email"){
							?>value="email"
							<?php }else if($fieldid!="telephonenumber"){ ?>
							value="none"
							<?php }?> >
						<input
						<?php if(!$enabled) { ?> disabled="disabled" <?php } ?> size="6" readonly="readonly"
						type="text" id="<?php echo $fieldData['fieldid']?>_validationhidden"
						name="<?php echo $fieldData['fieldid']?>_validationhidden"
						<?php if($fieldid=="email"){
							?>value="Email Id"
							<?php }else if($fieldid!="telephonenumber"){ ?>
							value="None"
							<?php }?> > <?php }
                                        }
                                        else if($fieldData!="telephonenumber"){
                                            ?>
                                        <select name="<?php echo $fieldData['fieldid']?>_validation" id="<?php echo $fieldData['fieldid']?>_validation"
					<?php if(!$enabled){ ?> disabled="disabled" <?php }
						if($pos===false) {?>readonly="readonly"<?php }?>
						<?php if( ($fieldData['type'] != 'text' && (strtolower($fieldData['validation']) == 'none' || strtolower($fieldData['validation']) == ''))) { ?>disabled="disabled"<?php }?>
					>
					<?php foreach( $this->get_master_validations() as $masterfieldtypes )
                                        { ?>
					<option value="<?php echo $masterfieldtypes['validation'];?>"
					<?php if($masterfieldtypes['validation']==$fieldData['validation']){?>
					selected="selected"<?php }?>><?php echo $masterfieldtypes[validationLabel];?></option>
					<?php }?>
					</select>
                                         <?php } ?>
					</td>

					<td align="center" style="border: 1px solid rgb(204, 204, 204);">
					<?php
					if($pos===false){
						echo "N/A";
						//Not a custom field. Dont show any thing
					}else if(($fieldData['type']=="select")||($fieldData['type']=="radio")||($fieldData['type']=="checkbox")){?>
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
		$addtional_custom = get_option('awp_addtional_custom');
		if(empty($addtional_custom))
		{
			$cnt_custom_filed = 6;
		}else {
			$cnt_custom_filed = 6 + count($addtional_custom);
		}
		?>

		<p> <a rel="<?php echo $cnt_custom_filed; ?>" href="javascript:void(0);" id="addcustom_field" name="addcustom_field"  >+Add Another Custom Field</a> </p>

		<p class="submit">
			<input <?php echo $disabledForm; ?> type="submit" name="awp_contactform_settings" id="awp_contactform_settings" class="button-primary" value="<?php esc_attr_e('Save Configuration') ?>" />
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
	        $this->loadscripts();
	        //$this->loadstyles();
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
            wp_register_script('jquery_validation',AWP_PLUGIN_BASEURL. '/assets/js/validator-min.js',array('jquery'));
	        wp_print_scripts('jquery_validation');

	}
    function getAllCountryList(){
        $countrylist = getAllCountries();
        return $countrylist;
        }


    function getNewsletterCategory(){
    	$category = getTargetListcategory();
    	return $category;
    	}
}

/**
 * To Get All Countries name and country code.
 *
 * @return unknown
 */
function getAllCountries()
{
	if(_isCurl())
	{
	$params = array (
                "a" => "getLocations",
                "apiKey"=> APPTIVO_BUSINESS_API_KEY,
                "accessKey"=> APPTIVO_BUSINESS_ACCESS_KEY
                );
   	$response=getRestAPICall("POST", APPTIVO_SIGNUP_API,$params);
	}
	else 
	{
	$params = array ( 
                "arg0" => APPTIVO_BUSINESS_API_KEY,
	            "arg1" => APPTIVO_BUSINESS_ACCESS_KEY               
                );
    $response = getsoapCall(APPTIVO_BUSINESS_SERVICES,'getAllCountries',$params);      
    $response= $response->return;
    }
   	return $response;
}
/**
 * To Get All Target Lists from Apptivo.
 *
 * @return unknown
 */
function getTargetListcategory()
{
	if(_isCurl())
	{ 
	$params = array (
                "a" => "getTargetList",
                "apiKey"=> APPTIVO_BUSINESS_API_KEY,
                "accessKey"=> APPTIVO_BUSINESS_ACCESS_KEY
                );
   $response=getRestAPICall("POST", APPTIVO_TARGETS_API,$params);
   $response=$response->aaData;
	}
	else {
	$params = array ( 
                "arg0" => APPTIVO_BUSINESS_API_KEY,
                "arg1" => APPTIVO_BUSINESS_ACCESS_KEY
              	    );
    $response = getsoapCall(APPTIVO_BUSINESS_SERVICES,'getAllTargetLists',$params);
    $response=$response->return->targetList;
    }
   return $response;
}
/**
 * To SaveContact Lead details.
 *
 * @param unknown_type $firstName
 * @param unknown_type $lastName
 * @param unknown_type $emailId
 * @param unknown_type $jobTitle
 * @param unknown_type $company
 * @param unknown_type $address1
 * @param unknown_type $address2
 * @param unknown_type $city
 * @param unknown_type $state
 * @param unknown_type $zipCode
 * @param unknown_type $bestWayToContact
 * @param unknown_type $country
 * @param unknown_type $leadSource
 * @param unknown_type $phoneNumber
 * @param unknown_type $comments
 * @param unknown_type $noteDetails
 * @return unknown
 */
function saveLeadDetails($firstName, $lastName, $emailId, $jobTitle, $company, $address1, $address2, $city, $state, $zipCode, $bestWayToContact, $country, $leadSource, $phoneNumber, $comments, $noteDetails,$targetlistid)
{
   $verification = check_blockip();
   if($verification){
   	 return $verification;
   }
    $leadSource=strtoupper($leadSource);
    if(_isCurl())
    {
   $leads='{"firstName":"'.addslashes($firstName).'","lastName":"'.addslashes($lastName).'","jobTitle":"'.addslashes($jobTitle).'","easyWayToContact":"'.$bestWayToContact.'","wayToContact":"'.$bestWayToContact.'","leadStatus":"1","leadStatusMeaning":"New","leadSource":"'.$leadSource.'","leadSourceMeaning":"'.$leadSource.'","assigneeObjectId":8,"description":"'.addslashes($comments).'","leadRank":"1","leadRankMeaning":"High","accountName":"","accountId":null,"companyName":"'.addslashes($company).'","phoneNumbers":[{"phoneNumber":"'.addslashes($phoneNumber).'","phoneType":"Business","phoneTypeCode":"PHONE_BUSINESS","id":"lead_phone_input"}],"emailAddresses":[{"emailAddress":"'.$emailId.'","emailTypeCode":"BUSINESS","emailType":"Business","id":"cont_email_input"}],"addresses":[{"addressAttributeId":"address_section_attr_id","addressTypeCode":"1","addressType":"Billing Address","addressLine1":"'.addslashes($address1).'","addressLine2":"'.addslashes($address2).'","city":"'.addslashes($city).'","stateCode":"","state":"'.addslashes($state).'","zipCode":"'.addslashes($zipCode).'","countryId":176,"countryName":"'.addslashes($country).'"}]}';
   $params = array (
                "a" => "createLead",
                "leadData" => $leads,
                "apiKey"=> APPTIVO_BUSINESS_API_KEY,
                "accessKey"=> APPTIVO_BUSINESS_ACCESS_KEY
                );
   $response=getRestAPICall("POST", APPTIVO_LEAD_API,$params);
  
   $leadId= $response->lead->leadId;
         if($noteDetails!="")
   {
      $noteText=$noteDetails->noteText;
      $leads='{"noteText":"'.$noteText.'"}';
      $param = array (
                "a"         => "save",
                "objectId"  => APPTIVO_LEAD_OBJECT_ID,
                "objRefId"  => "$leadId",
                "noteData"  =>  "$leads",
                "apiKey"=> APPTIVO_BUSINESS_API_KEY,
                "accessKey"=> APPTIVO_BUSINESS_ACCESS_KEY
                );
     $notesResponse= getRestAPICall("POST", APPTIVO_NOTES_API,$param);
     $noteid=$notesResponse->noteId;
  }
   if($leadId!="")
   {
       $response='Success';
       if (extension_loaded('soap') && $targetlistid!="") {
       	createTargetList($targetlistid, addslashes($firstName), addslashes($lastName), addslashes($emailId), addslashes($phoneNumber), addslashes($comments));
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
    return $response;
}
/**
 * Notes Details..
 *
 * @param unknown_type $label
 * @param unknown_type $nodeDetails
 * @param unknown_type $noteId
 * @return unknown
 */
function notes($label,$nodeDetails,$noteId)
{
	$labelDetails = new AWP_labelDetails($labelId = null,$label);
	$notetextDetails=new AWP_noteDetails($labelDetails,$noteId, addslashes($nodeDetails));
	return $notetextDetails;
}
