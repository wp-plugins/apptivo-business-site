<?php
/*
 Template Name:Default Template
 Template Type: Shortcode
 */
$formfields=array();
$formfields=$hrjobsform[fields];
$countries = $countrylist;
$css="";

if( $hrjobsform[css] != '' )
{
echo $css='<style type="text/css">'.$hrjobsform[css].'</style>';
}

echo $jscript='<script type="text/javascript">
jQuery(document).ready(function(){
 jQuery.validator.addMethod("phoneUS", function(phone_number, element) {
    phone_number = phone_number.replace(/\s+/g, "");
	return this.optional(element) || phone_number.length == 10 &&
		phone_number.match(/[0-9]{10}/);
}, "Please specify a valid phone number");

jQuery("#'.$hrjobsform[name].'_hrjobsforms").validate({
    rules: {
        telephonenumber: { phoneUS: true}
       },
    submitHandler: function(form) {
      form.submit();
    }
});
});
</script>';
if($submitformname==$hrjobsform[name] && $successmsg!="")
{
    echo $jscript='<script type="text/javascript">
            jQuery(document).ready(function(){
                document.getElementById("success_'.$hrjobsform[name].'").scrollIntoView();
            });
        </script>';
}

echo $stcss = '<style type="text/css">
.awp_contactform_maindiv_'.$contactform['name'].'{width:'.$form_outer_width.' !important;}
.absp_success_msg {color: green;font-weight: bold;padding: 10px 0;}   
.absp_error,.error_message{color:red;font-weight:bold;margin-bottom: 15px; width: 100%; }
.awformmain{max-width: 600px;}
.awformmain .formrgt object{height:40px}
.awformmain div,.awformmain label,.awformmain a,.awformmain span,.awformmain input,.awformmain textarea,.awformmain select{-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;}
.awformmain input[type="text"]{min-height:25px}
.awformmain select.required{color:#000}
 span.absp_contact_mandatory{color:red}
.awformmain .captcha .formrgt{float:left !important}
.awformmain label.error{color:red;width: 100% !important;}
.awformmain span.absp_mandatory{color: #F00; padding-left:5px}
.awformmain .formouter{float:left;width:50%}
.awformmain .formsection {overflow: hidden;padding: 1px;margin: 0 0 10px 0;}
.awdblclm .formsection,.awformmain .doublecolmn .formsection{width:50%;float:left;padding-right: 10px;}
.awdblclm .fullsection,.awformmain .formsection.fullsection{width:100% !important}
.awformmain .fullsection label{width:12.5% !important}
.awformmain .fullsection .formrgt{width:87.5% !important}
.awformmain .fullsecsub .formrgt,.awformmain .fullsecsub .formrgt input{float:right;margin-right: 10px;}
.awformmain .doublecolmn .fullsection label{width:100% !important}
.awformmain .doublecolmn .fullsection .formrgt{width:100% !important}
.awformmain .formsection div{margin: 0 0 5px 0;}
.awformmain .formsection label{width:35%;float:left;padding-right:10px;}
.awformmain .awsinglecolmn .formsection label,.doublecolmn .formsection label{width: 100%;float: left;padding-right: 10px;}
.formsection .formrgt {width: 65%;float: left;padding-right: 10px;}
.awsinglecolmn .formsection .formrgt ,.awformmain .doublecolmn .formsection .formrgt{width: 100%;float: left;padding-right: 10px;}
.awformmain .formsection label {padding:5px 0;}
.awformmain .formrgt div.formsect{width:100%;float:left}
.awformmain .formsect label {margin-left:5px;width:75% !important;font-weight:normal !important;padding-top:0px !important}
.awformmain .fullsection label {width:90% !important;}
.awformmain .formsect input{margin-top:2px}
.awformmain .fltrgt{float:right}
.awformmain input{margin:0px;float:left}
.awformmain input[type=text],input[type=email],input[type=url],input[type=password],textarea {border: 1px solid;width:100%;  margin: 0;}
.awformmain select {width:100%;min-height: 27px;margin:0;color:#000;}
.awformmain .threefield{width:33.3%;float:left}
.awformmain .pd0_10{padding:0 10px}
#recaptcha_widget_div{zoom:0.59;-moz-transform: scale(0.80);}
@media (max-width: 768px) {
.awformmain .formsection {margin: 0 0 10px 0;}
.awformmain .formsection label{width: 100%;float: left;margin: 0 0 5px 0;padding-bottom:5px}
.awformmain .formsection .formrgt,.awformmain .formsection {width: 100% !important;float: none;}
.awformmain input[type=text],input[type=email],input[type=url],input[type=password],textarea,select {width: 100%;}
.awformmain .formsect label {margin-left: 5px !important;width: 90% !important;}
}
@media (max-width: 480px) {
#recaptcha_widget_div{zoom:0.59;-moz-transform: scale(0.56);}
}
</style>';

if($submitformname==$hrjobsform[name] && $successmsg!=""){
	echo  '<div id="success_'.$hrjobsform[name].'" class="absp_success_msg success_'.$hrjobsform[name].'">'.$successmsg."</div>";
}

do_action ('apptivo_business_job_applicant_'.$hrjobsform['name'].'_before_form'); //Before submit form
if($hrjobsform['confmsg_pagemode']=="same"){
echo  '<form id="'.$hrjobsform[name].'_hrjobsforms" class="awp_hrjobs_form abswpjfm" name="'.$hrjobsform[name].'_hrjobsforms" action="'.$_SERVER['REQUEST_URI'].'" method="post">';
}
elseif($hrjobsform['confmsg_pagemode']=="other")
{
    $page_redirect  =    $hrjobsform['confmsg_pageid'];
    $post = get_post( $page_redirect);
    $page_action    =   $post->post_name;
    echo  '<form id="'.$hrjobsform[name].'_hrjobsforms" class="awp_hrjobs_form abswpjfm" name="'.$hrjobsform[name].'_hrjobsforms" action="'.$_SERVER['REQUEST_URI'].'" method="post">';
}
echo  '<input type="hidden" value="'.$jobId.'" name="jobId" id="jobId"><input type="hidden" value="'.$jobNo.'" name="jobNo" id="jobNo">';
echo '<input type="hidden" value="'.$hrjobsform[name].'" name="awp_jobsformname" id="awp_jobsformname">';
echo '<div class="awformmain awp_jobsform_maindiv_'.$hrjobsform[name].'">';

if(trim($jobId) == '')
{
	if( count($allJobs) >= 1)
	{
		echo  '<div class="formsection"><label><span>Job</span></label>
	<div class="formrgt">';
		echo   '<select class="awp_select joblists" value="" id="jobidwithnumber" name="jobidwithnumber">
             <option value="0">Select</option>';
		foreach( $allJobs as $jobslists )
		{
			if( strlen(trim($jobslists->jobTitle)) < 20)
			{
				$jobTitle = $jobslists->jobTitle;
			}else { $jobTitle = substr($jobslists->jobTitle,0,20).'...'; }
			echo  '<option value="'.$jobslists->id.'::'.$jobslists->jobNumber.'">'.$jobTitle.'</option>';

		}
			
		echo  '</select>';
		echo  '</div></div>';
	}
}
foreach($formfields as $field)
{
	$fieldid=$field['fieldid'];
	$showtext=$field['showtext'];
	$validation=$field['validation'];
	$required=$field['required'];
	$fieldtype=$field['type'];
	$options=$field['options'];
	$optionvalues=array();

	if($required){
		$mandate_property='"mandatory="true"';
		$validateclass=" required";
	}
	else{
		$mandate_property="";
		$validateclass="";
	}

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
        
        if($fieldid=='captcha')
	{
		$captcha_class = 'captcha';
	}
	else{
		$captcha_class = '';
	}
	echo '<div class="formsection '.$captcha_class.'"><label><span>';
	if($required)
	echo '<span class="absp_contact_mandatory">*</span>';

	echo $showtext.'</span></label><div class="formrgt">';
        
	if($fieldtype=="select" || $fieldtype=="radio" || $fieldtype=="checkbox" ){
		if(trim($fieldid) == 'industry')
		{
			$optionvalues=$options;
			$fieldtype = 'select';
		} else if(trim($options)!=""){
				
			$option_values=split("[\n]",trim($options));//Split the String line by line.

			$optionvalues = array();
			foreach($option_values as $values) :
			$optionvalues[] = trim($values);
			endforeach;
				
		}
	}
	switch($fieldtype)
	{
		case "text":
			echo '<input type="text" name="'.$fieldid.'" id="'.$fieldid.'" value=""  class="absp_jobapplicant_input_text'.$validateclass.'">';
			break;
		case "textarea":
			echo  '<textarea  name="'.$fieldid.'" id="'.$fieldid.'" value=""  class="absp_jobapplicant_textarea'.$validateclass.' size="50"></textarea>';
			break;
			
		case "select":
			if($fieldid == 'country')
			{
				echo  '<select  name="'.$fieldid.'" id="'.$fieldid.'" value=""  class="awp_select'.$validateclass.'">';
				
				do_action ('apptivo_business_job_applicant_'.$hrjobsform['name'].'_'.$fieldid.'_default_option');
				
				foreach($countries as $country)
				{
					echo  '<option value="'.$country->countryCode.'">'.$country->countryName.'</option>';
				}
				echo  '</select>';
			}
			else  if($fieldid == 'industry')
			{
				if(!empty($optionvalues))
				{
					echo  '<select  name="'.$fieldid.'" id="'.$fieldid.'" value=""  class="absp_jobapplicant_select'.$validateclass.'">';
					
					do_action ('apptivo_business_job_applicant_'.$hrjobsform['name'].'_'.$fieldid.'_default_option');
					
					foreach( $optionvalues as $optionvalue )
					{  if(!empty($optionvalue) && strlen(trim($optionvalue)) != 0)
					{
						$options = explode("::",$optionvalue);
						echo  '<option value="'.$options[0].'">'.$options[1].'</option>';
					}
					}
					echo  '</select>';
				}else {
					 
					echo  '<select  name="'.$fieldid.'" id="'.$fieldid.'" value=""  class="absp_jobapplicant_select'.$validateclass.'">';
					echo  '<option value="0">Default</option>';
					echo  '</select>';
				}
			} else {
				echo  '<select  name="'.$fieldid.'" id="'.$fieldid.'" value=""  class="absp_jobapplicant_select'.$validateclass.'">';
				
				do_action ('apptivo_business_job_applicant_'.$hrjobsform['name'].'_'.$fieldid.'_default_option');
				
				foreach( $optionvalues as $optionvalue )
				{  if(!empty($optionvalue) && strlen(trim($optionvalue)) != 0)
				{
					echo  '<option value="'.$optionvalue.'">'.$optionvalue.'</option>';
				}
				}
				echo  '</select>';
			}
			break;
			
		case "file":

			echo '<input type="file" id="file_upload" name="file_upload" />';
			echo  '<input type="hidden" name="uploadfile_docid" id="uploadfile_docid" value="" class="absp_jobapplicant_input_text'.$validateclass.'"  />';

			break;
			
		case "radio":
			$opt=0;
			foreach( $optionvalues as $optionvalue )
			{
				if(!empty($optionvalue) && strlen(trim($optionvalue)) != 0)
				{
						
					echo '<div class="formsect"><input type="radio" name="'.$fieldid.'" id="'.$fieldid.$opt.'" value="'.$optionvalue.'"  class="absp_jobapplicant_input_radio '.$validateclass.'"/>&nbsp&nbsp<label class="awp_custom_lbl" for="'.$fieldid.$opt.'">'.$optionvalue.'</label> </div>';
				}
				$opt++;
			}
			break;
			
		case "checkbox":

			$opt=0;
			foreach( $optionvalues as $optionvalue )
			{
				if(!empty($optionvalue) && strlen(trim($optionvalue)) != 0)
				{
					echo '<div class="formsect"><input type="checkbox" name="'.$fieldid.'[]" id="'.$fieldid.$opt.'" value="'.$optionvalue.'"  class="absp_jobapplicant_input_checkbox '.$validateclass.'"/>&nbsp&nbsp<label class="awp_custom_lbl" for="'.$fieldid.$opt.'">'.trim($optionvalue).'</label></div>';
				}
				$opt++;
			}
			break;
	}
	echo '</div>'.'</div>';
}
 
echo '<div class="formsection"><label class="jobapplicant_form_left emtydv">&nbsp;</label> <div class="jobscnt_rgstr_line"  style="display:none">'.
				'<div class="formrgt jobscnt_submit">';
echo '<input type="hidden" name="awp_hrjobsform_submit"/>';
if($hrjobsform[submit_button_type]=="submit" &&($hrjobsform[submit_button_val])!=""){
	$button_value = 'value="'.$hrjobsform[submit_button_val].'"';
}
else{
	if($hrjobsform[submit_button_val] == '' || empty($hrjobsform[submit_button_val])) :
	$hrjobsform[submit_button_val] = awp_image('submit_button');
	endif;
	$button_value = 'src="'.$hrjobsform[submit_button_val].'"';
}

do_action ('apptivo_business_job_applicant_'.$hrjobsform['name'].'_before_submit_query');//Before Submit Query
echo '</div></div>';
echo '<div class="formrgt"><input type="'.$hrjobsform[submit_button_type].'" class="absp_jobapplicant_button_submit awp_hrjobsform_submit_'.$hrjobsform[name].'" '.$button_value.' name="awp_jobsform_submit" id="awp_jobsform_submit" /></div></div>';



echo '</div>';

echo '</form>';

do_action ('apptivo_business_job_applicant_'.$hrjobsform['name'].'_after_form');//After submit Form
?>