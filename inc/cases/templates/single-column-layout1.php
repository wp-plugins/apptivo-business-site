<?php
/*
 Template Name:Single Column Layout
 Template Type: Shortcode
 */
$formfields=array();
$formfields=$case_form['fields'];
if(isset($countrylist)){
$countries = $countrylist;
}
$css="";
$count=1;
$checkType="0";
$checkPriority = "0";
$checkStatus = "0";
$form_outer_width=$cases_width_size;
for($i=0;$i<count($formfields);$i++)
{
	if($formfields[$i]["fieldid"]=="type")
	 {
	 	$checkType= "1";
	 }
	else if($formfields[$i]["fieldid"]=="priority")
	 {
	 	$checkPriority= "1";
	 }
	else if($formfields[$i]["fieldid"]=="status")
	 {
	 	$checkStatus= "1";
	 }
}
if( isset($case_form['css']) != '' )
{
	echo $css='<style type="text/css">'.$case_form['css'].'</style>';
}

echo $jss ='<script type="text/javascript">
jQuery(document).ready(function()
{
 var priorityName= jQuery("#priority option:selected", this).text();
      jQuery("#priority_name").val(priorityName);
 var typeName= jQuery("#type option:selected", this).text();
      jQuery("#type_name").val(typeName);
jQuery("#priority").change(function(){
      var fieldName= jQuery("option:selected", this).text();
      jQuery("#priority_name").val(fieldName);
        });
jQuery("#type").change(function(){
      var fieldName= jQuery("option:selected", this).text();
      jQuery("#type_name").val(fieldName);
        });
jQuery("#status").change(function(){
      var fieldName= jQuery("option:selected", this).text();
      jQuery("#status_name").val(fieldName);
        });
jQuery("#'.$case_form['name'].'_casesforms").validate(
{
submitHandler: function(form) {
      form.submit();
    }
});';

if($success_message!="" && $case_form[properties][confmsg]!="")
{
    echo ' document.getElementById("success_'.$case_form[name].'").scrollIntoView();';
}

echo ' }); </script>';


?>
<style type="text/css">
.cases_outfrm_<?php echo $case_form[name];?>{width:<?php echo $form_outer_width;?>;}
div.message {background-color: #DFF2BF;color: #4F8A10;padding: 10px 10px 10px 32px;margin:15px;}
div.error {color: #D8000C;padding: 5px 0 0 5px;margin:15px;}
.cases_outfrm{width:440px;display:block;}
.case_main {font-family: arial, helvetica, sans-serif;font-size: 12px;margin-left: 20px;color: #000;width: 100%;display:inline-block;}
.case_main .case_label {float: left;width: 30%;}
.case_main .case_input {float: left;width: 65%;padding-bottom: 10px;}
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

		  if (isset($value_present)){
		    $postValue = $_REQUEST;
		  }
          else {
           	$postValue="";
          }
do_action('apptivo_business_cases_before_form'); //Before Form
if(isset($captcha_error)!="")
{
    echo '<div class="absp_error error">'.$captcha_error.'</div>';
}
echo  '<div id="success_'.$case_form['name'].'" class="absp_success_msg success_'.$case_form['name'].'">'.$success_message."</div>";
echo  '<form id="'.$case_form['name'].'_casesforms" name="'.$case_form['name'].'_caseforms" action="'.$_SERVER['REQUEST_URI'].'" method="post">';
echo '<input type="hidden" value="'.$case_form['name'].'" name="awp_caseformname" id="awp_caseformname">';
echo '<div class="absp_business_main cases_outfrm_'.$case_form['name'].'">';
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
  if(isset($field['showtext'])){
  echo awp_create_labelfield($field['showtext'],'','','<div class="case_label" ><span style="float: left; padding-top: 3px;">',$mandatory_symbol.'</span></div>'); /* Label Field */
  }
  cases_textfield($form_properties,$field,isset($countries),isset($valuepresent),'<div class="case_input">','</div>',false, $i,true,'cases',$postValue,$case_form['name']); /* Text Field */
  
  echo '</div>';
  $count=$count+1;
  }
getFirstConfigData($checkType,$checkPriority,$checkStatus,$case_form['name']);
do_action('apptivo_business_cases_before_submit_query'); //Before submit Query
echo '<input type="hidden" name="awp_casesforms_submit" />';

$submit = cases_submit_type($form_properties,"apptivo_casesform",'','','', $i+1); //SubMit Button Type
  echo '<div class="case_main"><div class="case_label"><span style="float: left; padding-top: 5px;">&nbsp;</span></div>
         <div class="submit_btn">'.$submit.'</div></div>';
echo  '</div></form>';

do_action('apptivo_business_cases_after_form'); //After Form
?>