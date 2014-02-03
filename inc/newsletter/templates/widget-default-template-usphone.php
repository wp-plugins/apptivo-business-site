<?php
/*
 Template Name: Default Template Us Phone
 Template Type: Widget
 */
echo $before_widget;
if(!empty($newsletterformfields)){
	if ($instance['title']) echo $before_title . apply_filters('widget_title', $instance['title']) . $after_title;
	
	if( $instance[widget_style] != '' )
	{
	 echo $css='<style type="text/css">'.$instance[widget_style].'</style>';
	}
        wp_register_script('jquery_validation',AWP_PLUGIN_BASEURL. '/assets/js/validator-min.js',array('jquery'));
	wp_print_scripts('jquery_validation');
	echo $jscript='<script type="text/javascript">
			jQuery(document).ready(function(){
			jQuery("#'.$newsletterform[name].'_newsletter_widget").validate({
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
			});'
                          ;
if($successmsg!="" && $newsletterform[properties][confmsg]!="")
{
    echo ' document.getElementById("success_'.$newsletterform[name].'").scrollIntoView();
});
</script>';
}
else
{
    echo ' }); </script>';
}
	if($successmsg!=""){
            echo '<div id="awp_focusmsg">';
            echo  '<div id="success_'.$newsletterform[name].'" class="absp_success_msg success_'.$newsletterform[name].'">'.$successmsg."</div>";
            echo '</div>';
	}
	
	do_action('apptivo_business_newsletter_widget_before_form');//Before Newsletter form
	echo '<style type="text/css"> .absp_success_msg{color:green;font-weight:bold;padding-bottom:5px;}.absp_error,.error_message{color:red;font-weight:bold;padding-bottom:5px;}</style>';
	echo '<form id="'.$newsletterform[name].'_newsletter_widget" name="'.$newsletterform[name].'_newsletter_widget" action="'.$_SERVER['REQUEST_URI'].'" method="post">';
	echo '<input type="hidden" value="'.$newsletterform[name].'" name="awp_newsletterwidgetname" id="awp_newsletterwidgetname">';
	echo '<input type="hidden" value="'.$newsletterproperties[category].'" name="newsletter_category" id="newsletter_category">';
	echo '<div class="awp_newsletter_widget_maindiv_'.$newsletterform[name].'">';
	foreach($newsletterformfields as $field)
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
				echo   '<textarea  name="'.$fieldid.'" id="'.$fieldid.'" value=""  class="absp_newsletter_textarea'.$validateclass.' size="50"></textarea>';
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

	echo '</div>';
	echo '<input type="hidden" name="newsletterform_widget" />';
	if($newsletterproperties[submit_button_type]=="submit" &&($newsletterproperties[submit_button_val])!="")
	{
		$button_value = 'value="'.$newsletterproperties[submit_button_val].'"';
	} else {
		if(strlen(trim($newsletterproperties[submit_button_val])) == 0)
		{
			$imgSrc = 'http://d5duwnm1arn0s.cloudfront.net/awp-content_1/11162wp10246/files/submit.jpeg';
		}else {

			$imgSrc = $newsletterproperties[submit_button_val];
		}
		 
		$button_value = 'src="'.$imgSrc.'"';
	}
	
	do_action('apptivo_business_newsletter_widget_before_submit_query'); //Before_Submit_Query
	
	echo '<br /><input type="'.$newsletterproperties[submit_button_type].'"  '.$button_value.' class="absp_newsletter_button_submit" name="'.$newsletterform[name].'_newsletterform_widget_submit"  id="'.$newsletterform[name].'newsletterform_widget_submit" />';
	echo '</form>';

	do_action('apptivo_business_newsletter_widget_after_form');//After Newsletter form
}
echo $after_widget;
wp_reset_query();
?>