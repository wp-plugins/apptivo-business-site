<?php
/*
 Template Name:Single Column Layout1_US Phone
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
jQuery("#'.$newsletterform[name].'_newsletter").validate({
    rules: {
        '.$newsletterform[name].'_newsletter_phone1: { minlength: 3},
        '.$newsletterform[name].'_newsletter_phone2: { minlength: 3 },
        '.$newsletterform[name].'_newsletter_phone3: { minlength: 4 }
    },
    groups: {
        '.$newsletterform[name].'_newsletter_phone: "'.$newsletterform[name].'_newsletter_phone1 '.$newsletterform[name].'_newsletter_phone2 '.$newsletterform[name].'_newsletter_phone3"
    },
messages: {
'.$newsletterform[name].'_newsletter_phone1: {
 required: "Please Enter Valid Phone Number.",
 minlength: jQuery.format("Please Enter Valid Phone Number")
        },
'.$newsletterform[name].'_newsletter_phone2: {
 required: "Please Enter Valid Phone Number.",
 minlength: jQuery.format("Please Enter Valid Phone Number")
        },
'.$newsletterform[name].'_newsletter_phone3: {
 required: "Please Enter Valid Phone Number.",
 minlength: jQuery.format("Please Enter Valid Phone Number")
        }
        },
   errorPlacement: function(error, element) {
         if (element.attr("name") == "'.$newsletterform[name].'_newsletter_phone1" || element.attr("name") == "'.$newsletterform[name].'_newsletter_phone2" || element.attr("name") == "'.$newsletterform[name].'_newsletter_phone3")
         error.insertAfter("#'.$newsletterform[name].'_newsletter_phone3");
       else
        error.insertAfter(element);
   },
   submitHandler: function(form) {
      form.submit();
    }
});
});
</script>';

if($submitformname==$newsletterform[name] && $successmsg!=""){
	echo '<script type="text/javascript">
	jQuery(document).ready(function(){
	document.getElementById("success_'.$newsletterform[name].'").scrollIntoView();
	});
	</script>';
	echo  '<div id="success_'.$newsletterform[name].'" class="absp_success_msg success_'.$newsletterform[name].'">'.$successmsg."</div>";
}

do_action('apptivo_business_newsletter_'.$newsletterform[name].'_before_form');
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
			if($fieldid != 'newsletter_phone')
			{
				echo '<input type="text" name="'.$fieldid.'" id="'.$fieldid.'" value=""  class="absp_newsletter_input_text'.$validateclass.'">';
			}
			else
			{
				echo '<input maxlength="3" size="3" type="text" name="'.$newsletterform[name].'_'.$fieldid.'1" id="'.$newsletterform[name].'_'.$fieldid.'1" value=""  class="absp_newsletter_input_text'.$validateclass.'">';
				echo '&nbsp;&nbsp;&nbsp;<input maxlength="3" size="3" type="text" name="'.$newsletterform[name].'_'.$fieldid.'2" id="'.$newsletterform[name].'_'.$fieldid.'2" value=""  class="absp_newsletter_input_text'.$validateclass.'">';
				echo '&nbsp;&nbsp;&nbsp;<input maxlength="4" size="4" type="text" name="'.$newsletterform[name].'_'.$fieldid.'3" id="'.$newsletterform[name].'_'.$fieldid.'3" value=""  class="absp_newsletter_input_text'.$validateclass.'">';
			}
			break;
		case "textarea":
			echo  '<textarea  name="'.$fieldid.'" id="'.$fieldid.'" value=""  class="absp_newsletter_textarea'.$validateclass.'" size="50"></textarea>';
			break;
		case "select":
			echo  '<select  name="'.$fieldid.'" id="'.$fieldid.'" value=""  class="absp_newsletter_select'.$validateclass.'">';
			foreach( $optionvalues as $optionvalue )
			{
				echo  '<option value="'.$optionvalue.'">'.$optionvalue.'</option>';
			}
			echo  '</select>';
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

do_action('apptivo_business_newsletter_'.$newsletterform[name].'_before_submit_query');

echo '<input type="'.$newsletterform[submit_button_type].'" class="absp_newsletter_button_submit awp_newsletter_submit_'.$newsletterform[name].'" '.$button_value.' name="awp_newsletter_submit_'.$newsletterform[name].'"  id="awp_newsletter_submit_'.$newsletterform[name].'" />';
echo '</div>';
echo '</form>';
do_action('apptivo_business_newsletter_'.$newsletterform[name].'_after_form');
?>