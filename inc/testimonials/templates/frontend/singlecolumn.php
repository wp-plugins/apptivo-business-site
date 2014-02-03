<?php
/*
 Template Name: Single Column Layout - Testimonials Form
 Template Type: Shortcode
 */


$formfields = array();
$formfields = $testimonialform[fields];
$countries = $countrylist;
$css = "";

if ($testimonialform[css] != '') {
    echo $css = '<style type="text/css">' . $testimonialform[css] . '</style>';
}

echo $jscript = '<script type="text/javascript">
jQuery(document).ready(function(){
 jQuery.validator.addMethod("phoneUS", function(phone_number, element) {
    phone_number = phone_number.replace(/\s+/g, "");
	return this.optional(element) || phone_number.length == 10 &&
		phone_number.match(/[0-9]{10}/);
}, "Please specify a valid phone number");

jQuery("#' . $testimonialform[name] . '_testimonialforms").validate({
    rules: {
        telephonenumber: { phoneUS: true}
       },
    submitHandler: function(form) {
      form.submit();
    }
});
});
</script>';

$status_msg = $_REQUEST['status'];
if ($status_msg == "Success") {
    echo $jscript = '<script type="text/javascript">
            jQuery(document).ready(function(){
                document.getElementById("success_testimonial").scrollIntoView();
            });
        </script>';
}
?>
<?php

echo '<style type="text/css">
form{margin:0;padding:0;}
/*contact*/
.recaptcha_source{margin:0 !important;}
#login-box .form_section{float:left;width:100%;margin-bottom:15px;}
#login-box{float:left;width:100%;}
#login-box .form_left_part {width:100% !important;float:left;}
#login-box .form_rgt_part{width:100% !important;float:left;margin-top:5px;}
#login-box .form_rgt_part input{width:50%;}
#login-box .form_rgt_part textarea{width:50%;}
#login-box .form_rgt_part input#telephonenumber1, #login-box .form_rgt_part input#telephonenumber2, #login-box .form_rgt_part input#telephonenumber3{width:30% !important}
#login-box .form_rgt_part input[type="checkbox"], #login-box .form_rgt_part input[type="radio"]{width:auto !important;float:left;}
#login-box .form_rgt_part select{padding:6px;width:100%;}
#login-box input[type="submit"], #login-box input[type="image"]{margin-left:0px !important;float:right !important;}
.absp_success_msg{color:green;font-weight:bold;padding-bottom:5px;}

@media screen and (max-width:900px){
#login-box .form_left_part {width:100% !important;float:left !important;}
#login-box .form_rgt_part{width:100% !important;float:left !important;margin-top:5px;}
#recaptcha_widget_div{zoom:0.79;-moz-transform: scale(0.76);}
}
@media screen and (max-width:360px){
#login-box .form_rgt_part input#telephonenumber1, #login-box .form_rgt_part input#telephonenumber2, #login-box .form_rgt_part input#telephonenumber3{width:30% !important}
#recaptcha_widget_div{zoom:0.59;-moz-transform: scale(0.56);}
}
</style>';

?>
<?php

if ($submitformname == $testimonialform[name] && $successmsg != "") {
    echo '<div id="success_' . $testimonialform[name] . '" class="absp_success_msg success_' . $testimonialform[name] . '">' . $successmsg . "</div>";
}

do_action('apptivo_business_testimonial_applicant_' . $testimonialform['name'] . '_before_fprm'); //Before submit form

if ($_REQUEST['status']) {
    $status_msg = $_REQUEST['status'];
    if ($status_msg == "Success") {
        echo "<b id='success_testimonial' class='absp_success_msg'>" . $testimonialform[confmsg] . "</b> <br/>";
    } else if ($status_msg == "Please enter correct Verification code") {
        $postValues= $_SESSION['POST_VALUES'];
    	echo "<b id='success_testimonial' class='absp_error'> Please enter correct Verification code. </b> ";
    } else {
        echo "<b id='success_testimonial' class='absp_error'> Error while submitting Testimonials. </b>";
    }
}
echo '<form id="' . $testimonialform[name] . '_testimonialforms" class="awp_testimonial_form" name="' . $testimonialform[name] . '" action="' . SITE_URL . '/?page=awp_testimonials" method="post">';
echo '<input type="hidden" value="' . $testimonialform[name] . '" name="awp_testimonialformname" id="awp_testimonialformname">';
echo '<input type="hidden" value="' . $testimonialId . '" name="testimonialId" id="testimonialId"><input type="hidden" value="' . $testimonialNo . '" name="testimonialNo" id="testimonialNo">';
echo '<input type="hidden" value="' . $testimonialform[name] . '" name="awp_testimonialformname" id="awp_testimonialformname">';
echo "<div class='container'>
<div id='content'>
<div id='login-box' class='login-popup'>";

foreach ($formfields as $field) {
    $fieldid = $field['fieldid'];
    $showtext = $field['showtext'];
    $validation = $field['validation'];
    $required = $field['required'];
    $fieldtype = $field['type'];
    $options = $field['options'];
    $optionvalues = array();

    if ($required) {
        $mandate_property = '"mandatory="true"';
        $validateclass = " required";
    } else {
        $mandate_property = "";
        $validateclass = "";
    }

    switch ($validation) {
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
    //echo Username or email</span>';
echo '<div class="form_section">';
    echo '<div class="form_left_part">'.
	'<label class="absp_testimonial_label">';
    if($required || $fieldtype=="captcha")
        echo '<span class="absp_testimonial_mandatory">*</span>';

    		echo $showtext.'</label>'.
                       '</div>';
echo '<div class="form_rgt_part">';
    if ($fieldtype == "select" || $fieldtype == "radio" || $fieldtype == "checkbox") {
        if (trim($fieldid) == 'industry') {
            $optionvalues = $options;
            $fieldtype = 'select';
        } else if (trim($options) != "") {

            $option_values = split("[\n]", trim($options)); //Split the String line by line.

            $optionvalues = array();
            foreach ($option_values as $values) :
                $optionvalues[] = trim($values);
            endforeach;
        }
    }
    switch ($fieldtype) {
         case "text":
            echo '<input type="text" name="' . $fieldid . '" id="' . $fieldid . '" value="'.$postValues[$fieldid].'"  class="absp_testimonial_input_text' . $validateclass . '"></div>';
            break;
        case "textarea":
            echo '<textarea  name="' . $fieldid . '" id="' . $fieldid . '" class="absp_testimonial_textarea' . $validateclass . '" size="50">'.$postValues[$fieldid].' </textarea></div>';
            break;
        case "select":
            if ($fieldid == 'country') {
                echo '<select  name="' . $fieldid . '" id="' . $fieldid . '" value=""  class="absp_testimonial_select' . $validateclass . '">';
                foreach ($countries as $country) {
                    echo '<option value="' . $country->countryCode . '">' . $country->countryName . '</option>';
                }
                echo '</select></div>';
            } else if ($fieldid == 'industry') {
                if (!empty($optionvalues)) {
                    echo '<select  name="' . $fieldid . '" id="' . $fieldid . '" value=""  class="absp_testimonial_select' . $validateclass . '">';
                    foreach ($optionvalues as $optionvalue) {
                        if (!empty($optionvalue) && strlen(trim($optionvalue)) != 0) {
                            $options = explode("::", $optionvalue);
                            echo '<option value="' . $options[0] . '">' . $options[1] . '</option>';
                        }
                    }
                    echo '</select></div>';
                } else {
                    echo '<select  name="' . $fieldid . '" id="' . $fieldid . '" value=""  class="absp_testimonial_select' . $validateclass . '">';
                    echo '<option value="0">Default</option>';
                    echo '</select></div>';
                }
            } else {
                echo '<select  name="' . $fieldid . '" id="' . $fieldid . '" value=""  class="absp_testimonial_select' . $validateclass . '">';
                foreach ($optionvalues as $optionvalue) {
                    if (!empty($optionvalue) && strlen(trim($optionvalue)) != 0) {
                        echo '<option value="' . $optionvalue . '">' . $optionvalue . '</option>';
                    }
                }
                echo '</select></div>';
            }
            break;
        case "file":
            echo '<input type="file" id="file_upload" name="file_upload" />';
            echo '<input type="hidden" name="upload" id="uploadfile_docid" value="" class="absp_testimonial_input_text' . $validateclass . '"  /></div>';
            break;
        case "radio":
            $i = 0;
            $opt = 0;
            echo '<div class="absp_radioval">';
            foreach ($optionvalues as $optionvalue) {
                if (!empty($optionvalue) && strlen(trim($optionvalue)) != 0) {

                    echo '<div class="awp_custom_fields"><input type="radio" name="' . $fieldid . '" id="' . $fieldid . $opt . '" value="' . $optionvalue . '"  class="absp_testimonial_input_radio ' . $validateclass . '">&nbsp&nbsp
					<label class="awp_custom_lbl" for="' . $fieldid . $opt . '">' . trim($optionvalue) . '</label> </div>';
                    $i++;
                    $opt++;
                }
            }
            echo '</div>';
            break;
        case "checkbox":
            $i = 0;
            $opt = 0;
            echo '<div class="absp_checkval">';
            foreach ($optionvalues as $optionvalue) {
                if (!empty($optionvalue) && strlen(trim($optionvalue)) != 0) {

                    echo '<div class="awp_custom_fields">
					<input type="checkbox" name="' . $fieldid . '[]" id="' . $fieldid . $opt . '" value="' . $optionvalue . '"  class="absp_testimonial_input_checkbox ' . $validateclass . '">&nbsp&nbsp<label class="awp_custom_lbl" for="' . $fieldid . $opt . '">' . trim($optionvalue) . '</label>
                                            </div></label>';
                    $i++;
                    $opt++;
                }
            }
            echo '</div>';
            break;
        case "captcha":
            awp_reCaptcha();
            echo "</label>";
            break;
    }
    echo '</div>';
}

/* redirection URL */
$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

$_SESSION['request_link'] = $actual_link;



echo '<input type="hidden" name="awp_testimonialform_submit"/>';
if ($testimonialform[submit_button_type] == "submit" && ($testimonialform[submit_button_val]) != "") {
    $button_value = 'value="' . $testimonialform[submit_button_val] . '"';
} else {
    if ($testimonialform[submit_button_val] == '' || empty($testimonialform[submit_button_val])) :
        $testimonialform[submit_button_val] = awp_image('submit_button');
    endif;
    $button_value = 'src="' . $testimonialform[submit_button_val] . '"';
}

do_action('apptivo_business_testimonial_applicant_' . $testimonialform['name'] . '_before_submit_query'); //Before Submit Query

echo '<input type="' . $testimonialform[submit_button_type] . '" class="absp_testimonial_button_submit awp_testimonialform_submit_' . $testimonialform[name] . '" ' . $button_value . ' name="awp_testimonialform_submit" id="awp_testimonialform_submit" />';
echo '</div>
</div>
</div>
</form>';
?>





