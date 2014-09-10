<?php
/*
 Template Name:Single Column Layout
 Template Type: Shortcode
 */
$formfields=array();
$formfields=$case_form[fields];
$countries = $countrylist;
$css="";
$count=1;
if( $case_form[css] != '' )
{
	echo $css='<style type="text/css">'.$case_form[css].'</style>';
}

echo $jss ='<script type="text/javascript">
function sendText(option){ }
jQuery(document).ready(function()
{
jQuery("#priority").change(function(){
      var fieldName= jQuery("option:selected", this).attr("rel");
      jQuery("#priority_name").val(fieldName);
        });
jQuery("#type").change(function(){
      var fieldName= jQuery("option:selected", this).attr("rel");
      jQuery("#type_name").val(fieldName);
        });
jQuery.validator.addMethod("phoneUS", function(phone_number, element) {
    return this.optional(element) || phone_number.match(/^\(?(\d{3})\)?[- ]?(\d{3})[- ]?(\d{4})$/);
}, "Please enter a valid phone number");
if (jQuery("#phone_id").is(".required")){
jQuery("#'.$case_form[name].'_casesforms").validate(
{ rules: {
        phone: "phoneUS",
       }
});
}else{
jQuery("#'.$case_form[name].'_casesforms").validate(
{
submitHandler: function(form) {
      form.submit();
    }
});
}
';
if($success_message!="" && $case_form[properties][confmsg]!="")
{
    echo ' document.getElementById("success_'.$case_form[name].'").scrollIntoView();
});
</script>';
}
else
{
    echo ' }); </script>';
}

?>
<style type="text/css">
div.message {background-color: #DFF2BF;color: #4F8A10;padding: 10px 10px 10px 32px;margin:15px;}
div.error {background-color: #FFD4D4;color: #D8000C;padding: 10px 10px 10px 32px;margin:15px;}
.cases_outfrm{width:440px;display:block;}
.case_main {font-family: arial, helvetica, sans-serif;font-size: 12px;margin-left: 20px;color: #000;width: 100%;display:inline-block;}
.case_main .case_label {float: left;width: 48%;}
.case_main .case_input {float: left;width: 48%;padding-bottom: 10px;}
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
.absp_business_main input.required{color:#000;}
.absp_success_msg{color:green;font-weight:bold;padding-bottom:5px;}
.absp_error{color:red;font-weight:bold;padding-bottom:5px;}
.case_main .case_input select{padding:6px;width:95%;}
.case_main .case_input input{width:95%;}
.case_main .case_input textarea{width:95%;}
.case_main input[type="submit"]{margin-left:0px !important;float:left;}
.case_input input[type="radio"],.case_input input[type="checkbox"]{width:auto;margin:0;float:left}
@media screen and (max-width:900px){
.case_main .case_label{width:100%;float:left;}
.case_main .case_input {width:100%;float:left;margin-top:5px;}
#recaptcha_widget_div{zoom:0.79;-moz-transform: scale(0.76);}
}
</style>
<?php

		  if ($value_present){
		    $postValue = $_REQUEST;
		  }
          else {
           	$postValue="";
          }
do_action('apptivo_business_cases_before_form'); //Before Form
if($captch_error!="")
{
    echo '<div class="absp_error error">The reCAPTCHA was not entered correctly. Please try again.</div>';
}
echo  '<div id="success_'.$case_form[name].'" class="absp_success_msg success_'.$case_form[name].'">'.$success_message."</div>";
echo  '<form id="'.$case_form[name].'_casesforms" name="'.$case_form[name].'_caseforms" action="'.$_SERVER['REQUEST_URI'].'" method="post">';
echo '<input type="hidden" value="'.$case_form[name].'" name="awp_caseformname" id="awp_caseformname">';
echo '<div class="absp_business_main cases_outfrm_'.$case_form[name].'">';
if(get_option ('apptivo_business_recaptcha_mode')=="yes")
{
    $option=get_option('apptivo_business_recaptcha_settings');
    $option=json_decode($option);
}
$i=0;
$count=1;
foreach($cases_fields as $field)
  {
  if($count%2==0) $style='';
  else $style='style="width: 50%;float:left;"';
  $i=$i+1;
  echo '<div class="case_main">';
  $mandatory_symbol =    ' '.awp_mandatoryfield($field,$before='<span>',$after='</span>',$mandatory_symbol = '*');
  echo awp_create_labelfield($field['showtext'],'','','<div class="case_label" ><span style="float: left; padding-top: 3px;">',$mandatory_symbol.'</span></div>'); //Label Field
  cases_textfield($form_properties,$field,$countries,$valuepresent,'<div class="case_input">','</div>',false, $i,true,'cases',$postValue);//Text Field
  echo '</div>';
  $count=$count+1;
  if($field['fieldid']=="status")
      {
          echo '<input type="hidden" name="awp_cases_status" value="'.$field[options].'"/>';
          echo '<input type="hidden" name="awp_cases_values" value="'.$field[value].'"/>';
      }
  }

  
do_action('apptivo_business_cases_before_submit_query'); //Before submit Query  

echo '<input type="hidden" name="awp_casesforms_submit" />';

$submit = cases_submit_type($form_properties,"apptivo_casesform",'','','', $i+1); //SubMit Button Type
  echo '<div class="case_main"><div class="case_label"><span style="float: left; padding-top: 5px;">&nbsp;</span></div>
         <div class="submit_btn">'.$submit.'</div></div>';
echo  '</div></form>';

do_action('apptivo_business_cases_after_form'); //After Form
?>