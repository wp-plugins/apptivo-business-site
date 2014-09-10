<?php
/*
 Template Name:Single Column Layout1 with US Phone#
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
echo '<style type="text/css">
		.abswpcfm input, .abswpcfm textarea, .abswpcfm select {width:95%}
        .abswpcfm input[type="checkbox"], .abswpcfm input[type="radio"]{width:auto; float:left; margin-top:5px;margin-right: 5px;}
        .abswpcfm input[type="button"], .abswpcfm input[type="reset"], .abswpcfm input[type="submit"], .abswpcfm input[type="image"] {width:auto;margin-top: 15px}
        .abswpcfm input[type="image"] {border:none}
    	@media screen and (max-width:900px){
		.awp_contactform_maindiv_'.$contactform[name].' .form_left_part {width:100%  ;float:left  ;}
		.awp_contactform_maindiv_'.$contactform[name].' .form_rgt_part{width:100%  ;float:left  ;margin-top:5px;}
		#recaptcha_widget_div{zoom:0.79;-moz-transform: scale(0.76);}
		}
		@media screen and (max-width:360px){
		.awp_contactform_maindiv_'.$contactform[name].' .form_rgt_part input#telephonenumber1, .awp_contactform_maindiv_'.$contactform[name].' .form_rgt_part input#telephonenumber2, .awp_contactform_maindiv_'.$contactform[name].' .form_rgt_part input#telephonenumber3{width:24%;}
		#recaptcha_widget_div{zoom:0.59;-moz-transform: scale(0.56);}
		}
      </style>';
foreach($formfields as $fscript){
    
    echo $jscript='<script type="text/javascript">
jQuery(document).ready(function(){
 jQuery.validator.addMethod("phoneUS", function(phone_number, element) {
    phone_number = phone_number.replace(/\s+/g, "");
	return this.optional(element) || phone_number.length == 10 &&
		phone_number.match(/[0-9]{10}/);
}, "Please specify a valid phone number");

jQuery("#'.$contactform[name].'_contactforms").validate({
    rules: {
        telephonenumber: {phoneUS: true}
       },
    submitHandler: function(form) {
      form.submit();
    }
});
});
</script>';
    }
           if($submitformname==$contactform[name] && $successmsg!="")
{
    echo $jscript='<script type="text/javascript">
            jQuery(document).ready(function(){
            document.getElementById("success_'.$contactform[name].'").scrollIntoView();
            });
        </script>';
}

if($submitformname==$contactform[name] && $successmsg!=""){
	echo  '<div id="success_'.$contactform[name].'" class="absp_success_msg success_'.$contactform[name].'">'.$successmsg."</div>";
}
if($captch_error!="" && $submitformname==$contactform[name]){

	echo  '<div id="error'.$contactform[name].'" class="absp_error error_'.$contactform[name].'">'.$captch_error."</div>";
}

do_action ('apptivo_business_contact_'.$contactform[name].'_before_form'); //Before submit form

echo  '<form id="'.$contactform[name].'_contactforms" class="abswpcfm awp_contact_form" name="'.$contactform[name].'_contactforms" action="'.$_SERVER['REQUEST_URI'].'" method="post">';
echo '<input type="hidden" value="'.$contactform[name].'" name="awp_contactformname" id="awp_contactformname">';
echo '<div class="awp_contactform_maindiv_'.$contactform[name].'">';
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
			if($fieldid != 'telephonenumber')
			{
				echo '<input type="text" name="'.$fieldid.'" id="'.$fieldid.'" value=""  class="absp_contact_input_text'.$validateclass.'">';
			}
			else
			{
				echo '<input maxlength="3" size="3" type="text" name="'.$fieldid.'1" id="'.$fieldid.'1" value=""  class="absp_contact_input_text'.$validateclass.'">';
				echo '&nbsp;&nbsp;&nbsp;<input maxlength="3" size="3" type="text" name="'.$fieldid.'2" id="'.$fieldid.'2" value=""  class="absp_contact_input_text'.$validateclass.'">';
				echo '&nbsp;&nbsp;&nbsp;<input maxlength="4" size="4" type="text" name="'.$fieldid.'3" id="'.$fieldid.'3" value=""  class="absp_contact_input_text'.$validateclass.'">';
			}
			break;
			 
		case "textarea":
			echo  '<textarea  name="'.$fieldid.'" id="'.$fieldid.'" value=""  class="absp_contact_textarea'.$validateclass.'" size="50"></textarea>';
			break;
				
		case "select":
			if($fieldid == 'country'){

				echo  '<select  name="'.$fieldid.'" id="'.$fieldid.'" value=""  class="absp_contact_select'.$validateclass.'">';

				do_action ('apptivo_business_contact_'.$contactform[name].'_'.$fieldid.'_default_option');

				foreach($countries as $country)
				{
					$country_Code = ((trim($postValue)) == '')?'US':(trim($postValue));
					
					$selected = ($country_Code == trim($country->countryCode))?'selected="selected"':'';
					
					echo  '<option value="'.$country->countryCode.'" '.$selected.'>'.$country->countryName.'</option>';
				}
				echo  '</select>';

			}else{
				 
				echo  '<select  name="'.$fieldid.'" id="'.$fieldid.'" value=""  class="absp_contact_select'.$validateclass.'">';

				do_action ('apptivo_business_contact_'.$contactform[name].'_'.$fieldid.'_default_option');

				foreach( $optionvalues as $optionvalue )
				{
					$selected = (trim($postValue) == trim($optionvalue))?'selected="selected"':''; 
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
				$selected = (trim($postValue) == trim($optionvalue))?'checked="checked"':'';
				
				if(!empty($optionvalue) && strlen(trim($optionvalue)) != 0)
				{
					if($i>0)
					echo '&nbsp;';
					echo '<input type="radio" name="'.$fieldid.'" id="'.$fieldid.$opt.'" value="'.$optionvalue.'"  class="absp_contact_input_radio '.$validateclass.'" '.$selected.'> <label for="'.$fieldid.$opt.'">'.$optionvalue.'</label><br/>';
				}
				$opt++;
			}
			
			break;
				
		case "checkbox":
			$i=0;$opt=0;
			foreach( $optionvalues as $optionvalue )
			{
				$selected ="";
				if(!empty($postValue)) {
				foreach($postValue as $value){
					if(trim($value) == trim($optionvalue)){
						$selected='checked="checked"';
					}
				} }
				if(!empty($optionvalue) && strlen(trim($optionvalue)) != 0)
				{
					if($i>0)
					echo '&nbsp;';
					echo '<input type="checkbox" name="'.$fieldid.'[]" id="'.$fieldid.$opt.'" value="'.$optionvalue.'"  class="absp_contact_input_checkbox '.$validateclass.'"  '.$selected.'><label for="'.$fieldid.$opt.'">'.$optionvalue.'</label><br/>';
					$i++;$opt++;
				}
			}
			break;
			
		case "captcha":
            awp_captcha($fieldid,$postValue,$validateclass);
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
echo '</form>';
 
do_action ('apptivo_business_contact_'.$contactform[name].'_after_form');//After submit Form
?>