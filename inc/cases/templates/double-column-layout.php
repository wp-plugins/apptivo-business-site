<?php
/*
 Template Name:Double Column Layout
 Template Type: Shortcode
 */
$formfields=array();
$formfields=$case_form[fields];
$countries = $countrylist;
$css="";
$count=1;
$checkType="0";
$checkPriority="0";
$checkStatus = "0";
$form_outer_width=$cases_width_size;
echo '<div class="awp_cases_content">';
for($i=0;$i<count($formfields);$i++)
{
	if($formfields[$i]["fieldid"]=="type")
	 {
	 	$checkType= "1";
	 }
	if($formfields[$i]["fieldid"]=="priority")
	 {
	 	$checkPriority= "1";
	 }
	else if($formfields[$i]["fieldid"]=="status")
	 {
	 	$checkStatus= "1";
	 }
}
if( $case_form[css] != '' )
{
	echo $css='<style type="text/css">'.$case_form[css].'</style>';
}

echo $jss ='<script type="text/javascript">
jQuery(document).ready(function()
{
 var priorityName= jQuery("#priority option:selected", this).attr("rel");
      jQuery("#priority_name").val(priorityName);
 var typeName= jQuery("#type option:selected", this).attr("rel");
      jQuery("#type_name").val(typeName);      
jQuery("#priority").change(function(){
      var fieldName= jQuery("option:selected", this).attr("rel");
      jQuery("#priority_name").val(fieldName);
        });
jQuery("#type").change(function(){
      var fieldName= jQuery("option:selected", this).attr("rel");
      jQuery("#type_name").val(fieldName);
        });
jQuery("#status").change(function(){
      var fieldName= jQuery("option:selected", this).attr("rel");
      jQuery("#status_name").val(fieldName);
        });        
jQuery("#'.$case_form[name].'_casesforms").validate(
{
submitHandler: function(form) {
      form.submit();
    }
});';
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
.cases_outfrm_<?php echo $case_form[name];?>{width:<?php echo $form_outer_width;?>;}
.awp_cases_content{width:100%;clear:both;display:block;float:left;}
.case_main{clear: both;float: right;}
div.absp_success_msg {color: #4F8A10;font-weight:bold;}
div.error {background-color: #FFD4D4;color: #D8000C;padding: 10px 10px 10px 32px;margin:15px;}
.form_section{float:left;width:100%;margin-bottom:15px;}
.form_left_part {width:40%;float:left;}
.form_rgt_part{width:60%;float:left;}
.form_rgt_part input{width:100%;}
.form_rgt_part textarea{width:100%;}
.submit_btn input{float:left;}
input[type="checkbox"],input[type="radio"]{float:left;margin:0;width:auto;}
.form_section input[type="checkbox"],input[type="radio"]{float:left;clear:both;margin:0px ;}
.form_rgt_part label{float:left;line-height:13px;width: 90%;word-wrap: break-word;}
.form_rgt_part select{padding:6px;width:100%;color:#000000;}
#recaptcha_widget_div{zoom:0.59;-moz-transform: scale(0.56);}
.awp_recaptcha_error .error{margin-left:25px;}
.form_section label.error{color:red;font-weight:normal;}
input.required,select.required{color:#000;font-weight:normal;}
@media screen and (max-width:900px){
.case_main .case_label{width:100%;float:left;}
.case_main .case_input {width:100%;float:left;margin-top:5px;}
#recaptcha_widget_div{zoom:0.49;-moz-transform: scale(0.46);}
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
  $style="";
  	if($count%2==0) $style='style="width: 48%;float:right;"';
  else $style='style="width: 48%;float:left;clear:both;"';
  $i=$i+1;
  echo '<div class="form_section" '.$style.'>';
  $mandatory_symbol =    ' '.awp_mandatoryfield($field,$before='<span>',$after='</span>',$mandatory_symbol = '*');
  echo '<div class="form_left_part">';
  echo awp_create_labelfield($field['showtext'],'','','<span style="float: left; padding-top: 3px;">',$mandatory_symbol.'</span>'); //Label Field
  echo '</div>';
  echo '<div class="form_rgt_part">';
  cases_textfield($form_properties,$field,$countries,$valuepresent,'','',false, $i,true,'cases',$postValue,$case_form[name]);//Text Field
  echo '</div></div>';
  if($field['fieldid']=="status")
      {
          echo '<input type="hidden" name="awp_cases_status_name" value="'.$field[options].'"/>';
          echo '<input type="hidden" name="awp_cases_status_value" value="'.$field[value].'"/>';
      }
      $count++;
  }
getFirstConfigData($checkType,$checkPriority,$checkStatus,$case_form[name]);
do_action('apptivo_business_cases_before_submit_query'); //Before submit Query  
echo '<input type="hidden" name="awp_casesforms_submit" />';
$submit = cases_submit_type($form_properties,"apptivo_casesform",'','','', $i+1); //SubMit Button Type
  echo '<div class="case_main"><div class="case_label"><span style="float: left; padding-top: 5px;">&nbsp;</span></div>
         <div class="submit_btn">'.$submit.'</div></div>';
echo  '</div></form></div>';

do_action('apptivo_business_cases_after_form'); //After Form
?>