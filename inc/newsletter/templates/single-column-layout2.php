<?php
/*
 Template Name:Single Column Layout2
 Template Type: Shortcode
 */
$formfields=array();
$formfields = $newsletterform[fields];

if( $newsletterform[css] != '' )
{
	echo $css='<style type="text/css">'.$newsletterform[css].' .required{color:#000;font-weight:normal;}</style>';
}
else
{
echo '<style type="text/css"> .required{color:#000;font-weight:normal;} </style>';
}

echo $jscript='<script type="text/javascript">
jQuery(document).ready(function(){
 jQuery.validator.addMethod("phoneUS", function(phone_number, element) {
    phone_number = phone_number.replace(/\s+/g, "");
	return this.optional(element) || phone_number.length == 10 &&
		phone_number.match(/[0-9]{10}/);
}, "Please specify a valid phone number");

jQuery("#'.$newsletterform[name].'_newsletter").validate({
    rules: {
        newsletter_phone: { phoneUS: true}
       },
    submitHandler: function(form) {
      form.submit();
    }
});
document.getElementById("success_'.$newsletterform[name].'").scrollIntoView();
});
</script>';
echo '<style type="text/css">
   .awp_newsletter_maindiv_'.$newsletterform[name].' .form_section{
    padding-bottom:10px;
    }
    .awp_newsletter_maindiv_'.$newsletterform[name].' .form_section .form_left_part{
        width:30%;
        float:left;
        line-height:22px;
        }
       
         .awp_newsletter_maindiv_'.$newsletterform[name].' .awp_newsletterform_submit_'.$newsletterform[name].'{
         margin-left: 193px;
        }
        .form_rgt_part textarea {
        width:180px;
        }
      </style>';
if($submitformname==$newsletterform[name] && $successmsg!=""){
	echo '<script type="text/javascript">
	jQuery(document).ready(function(){
	document.getElementById("success_'.$newsletterform[name].'").scrollIntoView();
	});
	</script>';
	echo  '<div id="success_'.$newsletterform[name].'" class="absp_success_msg success_'.$newsletterform[name].'">'.$successmsg."</div>";
}

do_action('apptivo_business_newsletter_'.$newsletterform[name].'_before_form'); //Before Form
echo '<style type="text/css"> .absp_success_msg{color:green;font-weight:bold;padding-bottom:5px;}.absp_error,.error_message{color:red;font-weight:bold;padding-bottom:5px;}</style>';
echo  '<form id="'.$newsletterform[name].'_newsletter" name="'.$newsletterform[name].'_newsletter" action="'.$_SERVER['REQUEST_URI'].'" method="post">';
echo '<input type="hidden" value="'.$newsletterform[name].'" name="awp_newsletterformname" id="awp_newsletterformname">';
echo '<input type="hidden" value="'.$newsletterform[category].'" name="newsletter_category" id="newsletter_category">';
echo '<div class="awp_newsletter_maindiv_'.$newsletterform[name].'">';
foreach($formfields as $field)
{
	$fieldid=$field['fieldid'];
	$showtext=$field['showtext'];
	$validation=$field['validation'];
	$required=$field['required'];
	$fieldtype=$field['fieldtype'];
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

	echo '<div class="form_section">'.
				'<div class="form_left_part">'.
					'<span class="absp_newsletter_label">';
	if($required)
	echo '<span class="absp_newsletter_mandatory">*</span>';

	echo $showtext.'</span>'.
                       '</div>'.
                       '<div class="form_rgt_part">';

	if($fieldtype=="select" || $fieldtype=="radio" || $fieldtype=="checkbox" ){
		if(trim($options)!=""){
			$optionvalues=split(",", $options);
		}
	}
	switch($fieldtype)
	{
		case "text":
			echo '<input type="text" name="'.$fieldid.'" id="'.$fieldid.'" value=""  class="absp_newsletter_input_text'.$validateclass.'">';
			break;
		case "textarea":
			echo   '<textarea  name="'.$fieldid.'" id="'.$fieldid.'" value=""  class="absp_newsletter_textarea'.$validateclass.'" size="50" ></textarea>';
			break;
		case "select":
			echo   '<select  name="'.$fieldid.'" id="'.$fieldid.'" value=""  class="absp_newsletter_select'.$validateclass.'">';
			foreach( $optionvalues as $optionvalue )
			{
				echo   '<option value="'.$optionvalue.'">'.$optionvalue.'</option>';
			}
			echo   '</select>';
			break;
		case "radio":
			$i=0;
			foreach( $optionvalues as $optionvalue )
			{
				if($i>0)
				echo '<br>';
				echo '<label for="'.$fieldid.'">'.$optionvalue.'</label><input type="radio" name="'.$fieldid.'" id="'.$fieldid.'" value="'.$optionvalue.'"  class="absp_newsletter_input_radio '.$validateclass.'">';
			}
			break;
		case "checkbox":
			$i=0;
			foreach( $optionvalues as $optionvalue )
			{
				if($i>0)
				echo '<br>';
				echo '<label for="'.$fieldid.'">'.$optionvalue.'</label><input type="checkbox" name="'.$fieldid.'" id="'.$fieldid.'" value="'.$optionvalue.'"  class="absp_newsletter_input_checkbox '.$validateclass.'">';
				$i++;
			}
			break;
	}
	echo '</div>'.'</div>';
}

echo '<input type="hidden" name="awp_newsletterform_submit"/>';
if($newsletterform[submit_button_type]=="submit" &&($newsletterform[submit_button_val])!=""){
	$button_value = 'value="'.$newsletterform[submit_button_val].'"';
}
else{

	if(strlen(trim($newsletterform[submit_button_val])) == 0)
	{
		$imgSrc = 'http://d5duwnm1arn0s.cloudfront.net/awp-content_1/11162wp10246/files/submit.jpeg';
	}else {

		$imgSrc = $newsletterform[submit_button_val];
	}

	$button_value = 'src="'.$imgSrc.'"';
}

do_action('apptivo_business_newsletter_'.$newsletterform[name].'_before_submit_query');//Before Submit Query

echo  '<input type="'.$newsletterform[submit_button_type].'" class="absp_newsletter_button_submit awp_newsletterform_submit_'.$newsletterform[name].'" '.$button_value.' name="awp_newsletterform_submit_'.$newsletterform[name].'"  id="awp_contactform_submit_'.$newsletterform[name].'" />';
echo '</div>';
echo '</form>';

do_action('apptivo_business_newsletter_'.$newsletterform[name].'_after_form'); //After Form
?>