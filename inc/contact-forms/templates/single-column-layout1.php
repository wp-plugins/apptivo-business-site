<?php
/*
 Template Name:Single Column Layout1
 Template Type: Shortcode
 */
$formfields=array();
$formfields=$contactform[fields];
$countries = $countrylist;
$css="";

if( $contactform[css] != '' )
{
	echo $css='<style type="text/css">'.$contactform[css].'</style>';
}

echo $jscript='<script type="text/javascript">
jQuery(document).ready(function(){
 jQuery.validator.addMethod("phoneUS", function(phone_number, element) {
    phone_number = phone_number.replace(/\s+/g, "");
	return this.optional(element) || phone_number.length == 10 &&
		phone_number.match(/[0-9]{10}/);
}, "Please specify a valid phone number");

jQuery("#'.$contactform[name].'_contactforms").validate({
    rules: {
        telephonenumber: { phoneUS: true}
       }
});
});
</script>';

if($submitformname==$contactform[name] && $successmsg!=""){
	echo  '<div id="success_'.$contactform[name].'" class="success_'.$contactform[name].'">'.$successmsg."</div>";
}
if($captch_error!="" && $submitformname==$contactform[name]){

	echo  '<div id="error'.$contactform[name].'" class="error_'.$contactform[name].'">'.$captch_error."</div>";
}

do_action ('apptivo_business_contact_'.$contactform[name].'_before_form'); //Before submit form

echo  '<form id="'.$contactform[name].'_contactforms" class="awp_contact_form" name="'.$contactform[name].'_contactforms" action="'.$_SERVER['REQUEST_URI'].'" method="post">';
$form_sessionid = uniqid();$_SESSION['nogdog'] = $form_sessionid;
echo '<input type="hidden" value="'.$contactform[name].'" name="awp_contactformname" id="awp_contactformname">';
echo '<div class="awp_contactform_maindiv_'.$contactform[name].'">';
foreach($formfields as $field)
{
	$fieldid=$field['fieldid'];
	if($fieldid=='captcha'){
		$showtext='';
	}
	else{
		$showtext=$field['showtext'];
	}
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

	echo '<div class="form_section">';
	if($showtext!="")
	{
		echo '<div class="form_left_part">'.
					'<span class="absp_contact_label">';
		if($required)
		echo '<span class="absp_contact_mandatory">*</span>';

		echo $showtext.'</span>'.
                       '</div>';
	}
	echo '<div class="form_rgt_part">';

	if($fieldtype=="select" || $fieldtype=="radio" || $fieldtype=="checkbox" ){
		if(trim($options)!=""){

			$option_values = split("[\n]",trim($options));//Split the String line by line.

			$optionvalues = array();
			foreach($option_values as $values) :
			$optionvalues[] = trim($values);
			endforeach;

		}
	}
	if ($value_present)
	$postValue = $_REQUEST[$fieldid];
	else
	$postValue="";
	switch($fieldtype)
	{
		case "text":
			echo '<input type="text" name="'.$fieldid.'" id="'.$fieldid.'" value="'.$postValue.'"  class="absp_contact_input_text'.$validateclass.'">';
			break;
		case "textarea":
			echo  '<textarea  name="'.$fieldid.'" id="'.$fieldid.'" value="'.$postValue.'"  class="absp_contact_textarea'.$validateclass.'" size="50"></textarea>';
			break;
		case "select":
			if($fieldid == 'country'){
				echo  '<select  name="'.$fieldid.'" id="'.$fieldid.'" value=""  class="absp_contact_select'.$validateclass.'">';
				
				do_action ('apptivo_business_contact_'.$contactform[name].'_'.$fieldid.'_default_option');
				
				foreach($countries as $country)
				{
					$country_Code = ((trim($postValue)) == '')?'US':(trim($postValue));
					if($country_Code == trim($country->countryCode)){
						$selected='selected="selected"';
					}
					else{
						$selected = "";
					}
					echo  '<option value="'.$country->countryCode.'" '.$selected.'>'.$country->countryName.'</option>';
				}
				echo  '</select>';
			}
			else{
				echo  '<select  name="'.$fieldid.'" id="'.$fieldid.'" value=""  class="absp_contact_select'.$validateclass.'">';
				
				do_action ('apptivo_business_contact_'.$contactform[name].'_'.$fieldid.'_default_option');
				
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
				if(trim($postValue) == trim($optionvalue)){
					$selected='checked="checked"';
				}
				else{
					$selected = "";
				}
				if(!empty($optionvalue) && strlen(trim($optionvalue)) != 0)
				{
					if($i>0)
						
					echo '<br>';
					echo '<label for="'.$fieldid.$opt.'">'.$optionvalue.'</label><input type="radio" name="'.$fieldid.'" id="'.$fieldid.$opt.'" value="'.$optionvalue.'"  class="absp_contact_input_radio '.$validateclass.'" '.$selected.'>';
					$i++;$opt++;
				}
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
					echo '<br>';
					echo '<label for="'.$fieldid.$opt.'">'.$optionvalue.'</label><input type="checkbox" name="'.$fieldid.'[]" id="'.$fieldid.$opt.'" value="'.$optionvalue.'"  class="absp_contact_input_checkbox '.$validateclass.'" '.$selected.'>';
					$i++;$opt++;
				}
			}
			break;
		case "captcha":
			echo '<div class="captcha_image"><img src="'.$contactform['captchaimagepath'].'" id="captchaimg" style="border:1px solid #000;"/></div>
                                     <div class="captcha_label"><label for="message">*'.$field['showtext'].'</label></div>
                                     <div class="captcha_input"><input type="text" name="'.$fieldid.'" id="'.$fieldid.'_id" value=""  class="absp_contact_input_text'.$validateclass.'"/></div>';
			break;
	}
	echo '</div>'.'</div>';
}
if($contactform[subscribe_option]=='yes') :
$subscribe_to_newsletter = ($contactform['subscribe_to_newsletter_displaytext'] != '')?$contactform['subscribe_to_newsletter_displaytext']:'Subscribe to Newsletter';
echo '<div class="form_section"><div class="form_left_part">'.
					'<span>'.$subscribe_to_newsletter.'<span>
                                 </div>
                        <div class="form_rgt_part">
                        <input type="checkbox" name="subscribe" id="subscribe" />
                        </div></div>';
endif;
echo '<input type="hidden" name="awp_contactform_submit"/>';
if($contactform[submit_button_type]=="submit" &&($contactform[submit_button_val])!=""){
	$button_value = 'value="'.$contactform[submit_button_val].'"';
}
else{
	if(strlen(trim($contactform[submit_button_val])) == 0)
	{
		$imgSrc = awp_image('submit_button');
	}else {
		$imgSrc = $contactform[submit_button_val];
	}
	 
	$button_value = 'src="'.$imgSrc.'"';
}

do_action ('apptivo_business_contact_'.$contactform[name].'_before_submit_query');//Before Submit Query

echo '<input type="'.$contactform[submit_button_type].'" class="absp_contact_button_submit awp_contactform_submit_'.$contactform[name].'" '.$button_value.' name="awp_contactform_submit_'.$contactform[name].'"  id="awp_contactform_submit_'.$contactform[name].'" />';
echo '</div>';
echo'</form>';

do_action ('apptivo_business_contact_'.$contactform[name].'_after_form');//After submit Form
?>