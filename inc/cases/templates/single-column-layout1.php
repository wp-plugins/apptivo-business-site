<?php
/*
 Template Name:Single Column Layout
 Template Type: Shortcode
 */ 
echo $jss ='<script type="text/javascript">
jQuery(document).ready(function()
{
   jQuery.validator.addMethod("phoneUS", function(phone_number, element) {
    return this.optional(element) || phone_number.match(/^\(?(\d{3})\)?[- ]?(\d{3})[- ]?(\d{4})$/);
}, "Please enter a valid phone number");
if (jQuery("#phone_id").is(".required")){
jQuery("#apptivo_business_cases").validate(
{ rules: {
        phone: "phoneUS",
       }
});
}else{
jQuery("#apptivo_business_cases").validate();
}
});
</script>';
?>
<style type="text/css">
div.message {background-color: #DFF2BF;color: #4F8A10;padding: 10px 10px 10px 32px;margin:15px;}
div.error {background-color: #FFD4D4;color: #D8000C;padding: 10px 10px 10px 32px;margin:15px;}
.cases_outfrm{width:440px;display:block;}
.case_main {font-family: arial, helvetica, sans-serif;font-size: 12px;margin-left: 20px;color: #000;width: 100%;display:inline-block;}
.case_main .case_label {float: left;width: 48%;}
.case_main .case_input {float: left;width: 42%;padding-bottom: 10px;}
#intro_msg{margin-left: 20px;font-size: 12px;font-weight:normal;font-family:arial,helvetica,sans-serif;}
.case_main .case_label span span {color: red;}
.submit_btn {float: left;text-align: left;width: 42%;position:relative;}
.address{float:left;width:200px;}
.colon {float: right;padding-top: 3px;}
.txt {font-family: Arial, Helvetica, sans-serif;font-size: 12px;font-weight: normal;line-height: 20px;padding-left: 0px }
.reg_input{font-family: Arial, Helvetica, sans-serif;font-size: 13px;font-weight: normal; }
.input_fld{ width: 206px; float: left;}
label.error{padding-left: 10px;color: red;float:left;width:150px;}
.case_input select{float:left;}
</style>
<?php

do_action('apptivo_business_cases_before_form'); //Before Form 

echo  '<form class="apptivo_case" id="apptivo_business_cases" class="business_cases" name="apptivo_business_cases" action="'.$_SERVER['REQUEST_URI'].'" method="post">';
echo '<input type="hidden" id="apptivo_cases_form" name="apptivo_cases_form" value="1" />';
echo '<div class="absp_business_main cases_outfrm">';
$i=0;
foreach($cases_fields as $field)
  {
  $i=$i+1;
  echo '<div class="case_main">';
  $mandatory_symbol =    ' '.awp_mandatoryfield($field,$before='<span>',$after='</span>',$mandatory_symbol = '*');
  echo awp_create_labelfield($field['showtext'],'','','<div class="case_label"><span style="float: left; padding-top: 3px;">',$mandatory_symbol.'</span></div>'); //Label Field   
  cases_textfield($form_properties,$field,$countries,$valuepresent,'<div class="case_input">','</div>',true, $i,true,'cases');//Text Field
  echo '</div>';
  }

do_action('apptivo_business_cases_before_submit_query'); //Before submit Query  

$submit = cases_submit_type($form_properties,"apptivo_casesform",'','','', $i+1); //SubMit Button Type
  echo '<div class="case_main"><div class="case_label"><span style="float: left; padding-top: 5px;">&nbsp;</span></div>
         <div class="submit_btn">'.$submit.'</div></div>';
echo  '</div></form>';

do_action('apptivo_business_cases_after_form'); //After Form
?>