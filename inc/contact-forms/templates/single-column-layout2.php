<?php
/*
 Template Name:Single Column Layout2
 Template Type: Shortcode
 */
$formfields=array();
$formfields=$contactform['fields'];
$countries = $countrylist;
$css="";
$phone_validation="";
$checkleadType="0";
$checkleadSource = "0";
$checkleadStatus = "0";
$checkleadRank = "0";
$form_outer_width=$contact_width_size;
for($i=0;$i<count($formfields);$i++)
{
	if($formfields[$i]["fieldid"]=="leadType")
	 {
	 	$checkleadType= "1";
	 }
	else if($formfields[$i]["fieldid"]=="leadSource")
	 {
	 	$checkleadSource= "1";
	 }
	else if($formfields[$i]["fieldid"]=="leadStatus")
	 {
	 	$checkleadStatus= "1";
	 }
	else if($formfields[$i]["fieldid"]=="leadRank")
	 {
	 	$checkleadRank= "1";
	 }
}
if( $contactform['css'] != '' )
{
	echo $css='<style type="text/css">'.$contactform['css'].'</style>';
}

    echo $jscript='<script type="text/javascript">
jQuery(document).ready(function(){

var statusName= jQuery("#leadStatus option:selected").text();
      jQuery("#status_name").val(statusName);
      
var typeName= jQuery("#leadType option:selected").text();
      jQuery("#type_name").val(typeName);

var rankName= jQuery("#leadRank option:selected").text();
      jQuery("#rank_name").val(rankName);
      
var statusId=jQuery("#leadStatus option:selected").attr("rel");
      jQuery("#status_id").val(statusId);
var typeId=jQuery("#leadType option:selected").attr("rel");
      jQuery("#type_id").val(typeId);
var rankId=jQuery("#leadRank option:selected").attr("rel");
      jQuery("#rank_id").val(rankId);

jQuery("#leadStatus").change(function(){
      var fieldId= jQuery("option:selected", this).attr("rel");
      var fieldName=jQuery(this).find("option:selected").text();
      jQuery("#status_id").val(fieldId);
      jQuery("#status_name").val(fieldName);
        });
jQuery("#leadType").change(function(){
      var fieldId= jQuery("option:selected", this).attr("rel");
      var fieldName=jQuery(this).find("option:selected").text();
      jQuery("#type_id").val(fieldId);
      jQuery("#type_name").val(fieldName);
        });
jQuery("#leadRank").change(function(){
      var fieldId= jQuery("option:selected", this).attr("rel");
      var fieldName=jQuery(this).find("option:selected").text();
      jQuery("#rank_id").val(fieldId);
      jQuery("#rank_name").val(fieldName);
      });
      
jQuery("#country").change(function(){
		var fieldId=jQuery(this).find("option:selected").attr("value");
		var fieldName=jQuery(this).find("option:selected").text();
		jQuery("#country_id").val(fieldId);
      	jQuery("#country_name").val(fieldName);
});

jQuery("#'.$contactform["name"].'_contactforms").validate({
    submitHandler: function(form) {
      jQuery("button[type=submit], input[type=submit]").attr("disabled",true);
      form.submit();
    }
});
});
</script>';

    
	if(isset($submitformname)){
    if($submitformname==$contactform['name'] && $successmsg!="")
{
    echo $jscript='<script type="text/javascript">
            jQuery(document).ready(function(){
            document.getElementById("success_'.$contactform['name'].'").scrollIntoView();
            });
        </script>';
}
	}

echo $stcss = '<style type="text/css">
.awp_contactform_maindiv_'.$contactform['name'].'{width:'.$form_outer_width.' !important;}
.absp_success_msg {color: green;font-weight: bold;padding: 10px 0;}    
.awformmain div,.awformmain label,.awformmain a,.awformmain span,.awformmain input,.awformmain textarea,.awformmain select{-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;}
.awformmain input[type="text"]{min-height:25px}
.awformmain select.required{color:#000}
.awformmain {max-width:600px}
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
.awformmain .doublecolmn .fullsection label{width:100% !important}
.awformmain .doublecolmn .fullsection .formrgt{width:100% !important}
.awformmain .formsection div{margin: 0 0 5px 0;}
.awformmain .formsection label{width:35%;float:left;padding-right:10px;}
.awformmain .singlecolmn .formsection label,.doublecolmn .formsection label{width: 100%;float: left;padding-right: 10px;}
.awformmain .formsection .formrgt {width: 65%;float: left;padding-right: 10px;}
.awformmain .singlecolmn .formsection .formrgt ,.awformmain .doublecolmn .formsection .formrgt{width: 100%;float: left;padding-right: 10px;}
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
@media (max-width: 600px) {
.awformmain .formsection {margin: 0 0 10px 0;}
.awformmain .formsection label{width: 100%;float: left;margin: 0 0 5px 0;padding-bottom:5px}
.awformmain .formsection .formrgt,.awformmain .formsection {width: 100% !important;float: none;}
.awformmain input[type=text],input[type=email],input[type=url],input[type=password],textarea,select {width: 100%;}
.awformmain .formsect label {margin-left: 5px !important;width: 90% !important;}
}
@media (max-width: 480px) {
#recaptcha_widget_div{zoom:0.59;-moz-transform: scale(0.76);}
}
</style>';

/* For Render Lead Status,Lead Type,Lead Rank */
$firstConfig = get_option("awp_contact_configdata");
	$firstConfig	=	json_decode($firstConfig);
	
	$getConfig=get_option('awp_contactforms');
	
	for($i=0;$i<count($getConfig);$i++)
	{
		if($getConfig[$i]['name']==$contactform['name'])
		{
			$formConfig=$getConfig[$i]['contact_config'];
		}
	}
if(isset($submitformname)){
if($submitformname==$contactform['name'] && $successmsg!=""){
	echo  '<div id="success_'.$contactform['name'].'" class="absp_success_msg success_'.$contactform['name'].'">'.$successmsg."</div>";
}
}
if(isset($captch_error)){
if($captch_error!="" && $submitformname==$contactform['name']){
	echo  '<div id="error'.$contactform['name'].'" class="absp_error error_'.$contactform['name'].'">'.$captch_error."</div>";
}
}
do_action ('apptivo_business_contact_'.$contactform['name'].'_before_form'); //Before submit form

echo  '<form id="'.$contactform['name'].'_contactforms" class="abswpcfm awp_contact_form" name="'.$contactform['name'].'_contactforms" action="'.$_SERVER['REQUEST_URI'].'" method="post">';
echo '<input type="hidden" value="'.$contactform['name'].'" name="awp_contactformname" id="awp_contactformname">';
echo '<div class="awformmain awp_contactform_maindiv_'.$contactform['name'].'">';

foreach($formfields as $field)
{
	if ( !is_array($field)) { continue; }
	$fieldid= $field['fieldid']; 
	$showtext=$field['showtext'];
	$validation=$field['validation'];
	$required=$field['required'];
	$fieldtype=$field['type'];
	$options=$field['options'];
        $optionvalues=array();

        if($validation=="string")
        {
            $phone_validation="_string";
        }
        else {
            $phone_validation= "";
        }

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
                case "string":
			$validateclass .=" string";
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
		if(trim($options)!=""){
			$option_values=split("[\n]",trim($options));//Split the String line by line.

			$optionvalues = array();
			foreach($option_values as $values) :
			$optionvalues[] = trim($values);
			endforeach;

		}
	}
	if ($value_present)
	{
		$postValue = $_REQUEST[$fieldid];
	}else {
		$postValue="";
	}
	
	switch($fieldtype)
	{
		case "text":
			echo '<input type="text" name="'.$fieldid.$phone_validation.'" id="'.$fieldid.'_id" value="'.$postValue.'"  class="absp_contact_input_text'.$validateclass.'">';
			break;
				
		case "textarea":
			echo   '<textarea  name="'.$fieldid.'" id="'.$fieldid.'_id" value=""  class="absp_contact_textarea'.$validateclass.'" size="50"></textarea>';
			break;
				
		case "select":
			 
			if($fieldid == 'country'){

				echo   '<select  name="'.$fieldid.'" id="'.$fieldid.'"  class="absp_contact_select'.$validateclass.'">';
				 
				do_action ('apptivo_business_contact_'.$contactform[name].'_'.$fieldid.'_default_option');

				foreach($countries as $country)
				{
					$country_Code = ((trim($postValue)) == '')?'US':(trim($postValue));
					
					$selected = ( $selected == trim($country->countryId))?'selected="selected"':'';
					
					echo   '<option value="'.$country->countryId.'" '.$selected.'>'.$country->countryName.'</option>';
				}
				echo   '</select>';
				echo '<input type="hidden" id="country_id" name="country_id" value="'.$countries[0]->countryId.'"/>';
				echo '<input type="hidden" id="country_name" name="country_name" value="'.$countries[0]->countryName.'"/>';

			} 
			elseif ($fieldid=='leadStatus'){
				$configValues=$formConfig["awp_leadStatus_selected"];
				echo  '<select  name="'.$fieldid.'" id="'.$fieldid.'"  class="absp_contact_select'.$validateclass.'">';
				
				do_action ('apptivo_business_contact_'.$contactform[name].'_'.$fieldid.'_default_option');
				
				foreach ($firstConfig->leadStatus as $leadStatus)
				{
				$selected = ( $configValues == trim($leadStatus->lookupCode ))?'selected="selected"':''; 
				echo '<option value="'.htmlspecialchars($leadStatus->lookupCode).'" '.$selected.' rel="'.htmlspecialchars($leadStatus->lookupCode).'">'.$leadStatus->meaning.'</option>';
				
			    }
				echo '</select>';
				echo '<input type="hidden" id="status_id" name="status_id" value="'.$configValues.'"/>';
				echo '<input type="hidden" id="status_name" name="status_name" value="'.$leadStatus->meaning.'"/>';
			}elseif ($fieldid=='leadType'){
				$configValues=$formConfig["awp_leadType_selected"];
				if(!$required){
					$notrequired="Select One";
				}
				if($configValues=="0"){
					$validateclass=" required";
				}
				echo  '<select  name="'.$fieldid.'" id="'.$fieldid.'"  class="absp_contact_select'.$validateclass.'">';
				if($configValues=="0"){
					echo '<option value="'.$notrequired.'">Select One</option>';
				}
				do_action ('apptivo_business_contact_'.$contactform[name].'_'.$fieldid.'_default_option');
				foreach ($firstConfig->leadType as $leadType)
				{
				$selected = ( $configValues == trim($leadType->opportunityTypeId ))?'selected="selected"':'';
				echo '<option value="'.$leadType->opportunityTypeId.'" '.$selected.' rel="'.$leadType->opportunityTypeId.'">'.$leadType->opportunityTypeName.'</option>';
				}
				echo '</select>';
				echo '<input type="hidden" id="type_id" name="type_id" value="'.$configValues.'"/>';
				echo '<input type="hidden" id="type_name" name="type_name" value="'.$leadType->opportunityTypeName.'"/>';
			}elseif ($fieldid=='leadRank'){
				$configValues=$formConfig["awp_leadRank_selected"];
				echo  '<select  name="'.$fieldid.'" id="'.$fieldid.'"  class="absp_contact_select'.$validateclass.'">';
				
				do_action ('apptivo_business_contact_'.$contactform[name].'_'.$fieldid.'_default_option');
				foreach ($firstConfig->leadRank as $leadRank)
				{
					$selected = ( $configValues == trim($leadRank->lookupCode ))?'selected="selected"':'';
				echo '<option value="'.htmlspecialchars($leadRank->lookupCode).'" '.$selected.' rel="'.htmlspecialchars($leadRank->lookupCode).'">'.$leadRank->meaning.'</option>';
				}
				echo '</select>';
				echo '<input type="hidden" id="rank_id" name="rank_id" value="'.$configValues.'"/>';
				echo '<input type="hidden" id="rank_name" name="rank_name" value="'.$leadRank->meaning.'"/>';
			}
			else {
				 
				echo   '<select  name="'.$fieldid.'" id="'.$fieldid.'" value=""  class="absp_contact_select'.$validateclass.'">';

				do_action ('apptivo_business_contact_'.$contactform[name].'_'.$fieldid.'_default_option');

				foreach( $optionvalues as $optionvalue )
				{
					$selected = (trim($postValue) == trim($optionvalue))?'selected="selected"':'';
					
					if(!empty($optionvalue) && strlen(trim($optionvalue)) != 0)
					{
						echo   '<option value="'.$optionvalue.'" '.$selected.'>'.$optionvalue.'</option>';
					}
				}

				echo   '</select>';

			}

			break;
				
		case "radio":

			$i=0;$opt=0;
			echo '<div class="absp_radioval">';
			foreach( $optionvalues as $optionvalue )
			{
				$selected = (trim($postValue) == trim($optionvalue))?'checked="checked"':'';
				
				if(!empty($optionvalue) && strlen(trim($optionvalue)) != 0)
				{
					echo '<div class="formsect"><input type="radio" name="'.$fieldid.'" id="'.$fieldid.$opt.'" value="'.$optionvalue.'"  class="absp_contact_input_radio '.$validateclass.'" '.$selected.'> <label for="'.$fieldid.$opt.'">'.$optionvalue.'</label></div>';
				}
				$opt++;
			}
			echo '</div>';
			break;
				
		case "checkbox":
			echo '<div class="formsect absp_checkval">';
			$i=0;$opt=0;
			foreach( $optionvalues as $optionvalue )
			{
				$selected ="";
				if( !empty($postValue)){
				foreach($postValue as $value){
					if(trim($value) == trim($optionvalue)){
						$selected='checked="checked"';
					}
				}
				}
				if(!empty($optionvalue) && strlen(trim($optionvalue)) != 0)
				{
					echo '<div class="formsect"><input type="checkbox" name="'.$fieldid.'[]" id="'.$fieldid.$opt.'" value="'.$optionvalue.'"  class="absp_contact_input_checkbox '.$validateclass.'"  '.$selected.'><label for="'.$fieldid.$opt.'">'.$optionvalue.'</label></div>';
					$i++;$opt++;
				}
			}
			echo '</div>';
			break;

		case "captcha":
			awp_captcha($fieldid,$postValue,$validateclass);
			break;
			 
	}
	
	echo '</div>'.'</div>';
}
if($contactform['subscribe_option']=='yes') :
$subscribe_to_newsletter = ($contactform['subscribe_to_newsletter_displaytext'] != '')?$contactform['subscribe_to_newsletter_displaytext']:'Subscribe to Newsletter';
echo '<div class="formsection"><div class="formsect"><input type="checkbox" name="subscribe" id="subscribe" /><label>'.$subscribe_to_newsletter.'</label></div></div>';

endif;


echo '<input type="hidden" name="awp_contactform_submit"/>';
if($contactform['submit_button_type']=="submit" &&($contactform['submit_button_val'])!=""){
	$button_value = 'value="'.$contactform[submit_button_val].'"';
}
else{
	if(strlen(trim($contactform['submit_button_val'])) == 0)
	{
		$imgSrc = awp_image('submit_button');
	}else {

		$imgSrc = $contactform['submit_button_val'];
	}
	 
	$button_value = 'src="'.$imgSrc.'"';
}

do_action ('apptivo_business_contact_'.$contactform['name'].'_before_submit_query');//Before Submit Query

echo '<div class="formsection"><label><span>&nbsp;</span></label>
      <div class="formrgt"><input type="'.$contactform['submit_button_type'].'" class="absp_contact_button_submit awp_contactform_submit_'.$contactform['name'].'" '.$button_value.' name="awp_contactform_submit_'.$contactform['name'].'"  id="awp_contactform_submit_'.$contactform['name'].'" /></div></div>';

echo '</div>';
echo '</form><p>&nbsp;</p>';
do_action ('apptivo_business_contact_'.$contactform['name'].'_after_form');//After submit Form
?>