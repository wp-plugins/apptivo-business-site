<?php
/*
 Template Name:Double Column Layout1
 Template Type: Shortcode
 */ 
$formfields=array();
$formfields=$contactform[fields];
$countries = $countrylist;
$css="";
$html="";
$count=1;
if( $contactform[css] != '' )
{ 
	$css='<style type="text/css">'.$contactform[css].'</style>';
}

$jscript='<script type="text/javascript">
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
	$html.= '<div id="success_'.$contactform[name].'" class="success_'.$contactform[name].'">'.$successmsg."</div>";
}
if($captch_error!="" && $submitformname==$contactform[name]){

        $html.= '<div id="error'.$contactform[name].'" class="error_'.$contactform[name].'">'.$captch_error."</div>";
}
$html.= '<form id="'.$contactform[name].'_contactforms" name="'.$contactform[name].'_contactforms" action="'.$_SERVER['REQUEST_URI'].'" method="post">';
$html.='<input type="hidden" value="'.$contactform[name].'" name="awp_contactformname" id="awp_contactformname">';
$html.='<div class="awp_contactform_maindiv_'.$contactform[name].'">';
foreach($formfields as $field) 
	{
                if($count%2==0) $style='';
                else $style='style="width: 50%;float:left;"';
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
		
		$html.='<div class="form_section" '.$style.'>';
		 if($showtext!="")
                {
				$html.='<div class="form_left_part">'.
					'<span class="absp_contact_label">';
		if($required)
			$html.='<span class="absp_contact_mandatory">*</span>';

		$html.=$showtext.'</span>'.
                       '</div>';
                }
                       $html.='<div class="form_rgt_part">';
		
		if($fieldtype=="select" || $fieldtype=="radio" || $fieldtype=="checkbox" ){
			if(trim($options)!=""){
				$optionvalues=split("[\n]",trim($options));//Split the String line by line.
			}
		}
                 if ($value_present)
                    $postValue = $_REQUEST[$fieldid];
                else
                    $postValue="";
		switch($fieldtype)
		{
			case "text":
				$html.='<input type="text" name="'.$fieldid.'" id="'.$fieldid.'_id" value=""  class="absp_contact_input_text'.$validateclass.'">';
			break;
			case "textarea":
				$html .=  '<textarea  name="'.$fieldid.'" id="'.$fieldid.'" value=""  class="absp_contact_textarea'.$validateclass.'" size="50"></textarea>';
			break;
			case "select":
                              if($fieldid == 'country'){
                                 $html .=  '<select  name="'.$fieldid.'" id="'.$fieldid.'" value=""  class="absp_contact_select'.$validateclass.'">';
				foreach($countries as $country)
				{                   
					                $country_Code = ((trim($postValue)) == '')?'US':(trim($postValue));
                                    if($country_Code == trim($country->countryCode)){
                                      $selected='selected="selected"';
                                      }
                                     else{
                                         $selected = "";
                                     }
                                    $html .=  '<option value="'.$country->countryCode.'" '.$selected.'>'.$country->countryName.'</option>';
				}
				$html .=  '</select>';
                                }
                                else{
				$html .=  '<select  name="'.$fieldid.'" id="'.$fieldid.'" value=""  class="absp_contact_select'.$validateclass.'">';
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
					$html .=  '<option value="'.$optionvalue.'" '.$selected.'>'.$optionvalue.'</option>';
					}
				}
				$html .=  '</select>';
                                }
			break;
			case "radio":
				$i=0;
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
							$html.='';
						$html.='<label for="'.$fieldid.'">'.$optionvalue.'</label><input type="radio" name="'.$fieldid.'" id="'.$fieldid.'" value="'.$optionvalue.'"  class="absp_contact_input_radio '.$validateclass.'" '.$selected.'>';
						}
				}
			break;
			case "checkbox":
				$i=0;
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
						$html.='';
					$html.='<label for="'.$fieldid.'">'.$optionvalue.'</label><input type="checkbox" name="'.$fieldid.'[]" id="'.$fieldid.'" value="'.$optionvalue.'"  class="absp_contact_input_checkbox '.$validateclass.'" '.$selected.'>';
					$i++;
					}
				}
			break;
                        case "captcha":
                                $html.='<div class="captcha_image"><img src="'.$contactform['captchaimagepath'].'" id="captchaimg" style="border:1px solid #000;"/></div>
                                     <div class="captcha_label"><label for="message">*'.$field['showtext'].'</label></div>
                                     <div class="captcha_input"><input type="text" name="'.$fieldid.'" id="'.$fieldid.'_id" value=""  class="absp_contact_input_text'.$validateclass.'"/></div>';
                            break;
		}
		$html.='</div>'.'</div>';
                $count++;
     }
 if($contactform[subscribe_option]=='yes') :
        $subscribe_to_newsletter = ($contactform['subscribe_to_newsletter_displaytext'] != '')?$contactform['subscribe_to_newsletter_displaytext']:'Subscribe to Newsletter';
        $html.='<div class="form_section"><div class="form_left_part">'.
					'<span>'.$subscribe_to_newsletter.'<span>
                                 </div>
                        <div class="form_rgt_part">
                        <input type="checkbox" name="subscribe" id="subscribe" />
                        </div></div>';
 endif;
      $html.='<input type="hidden" name="awp_contactform_submit"/>';
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
      $html .= '<input type="'.$contactform[submit_button_type].'" class="absp_contact_button_submit awp_contactform_submit_'.$contactform[name].'" '.$button_value.' name="awp_contactform_submit_'.$contactform[name].'"  id="awp_contactform_submit_'.$contactform[name].'" />';
      $html.='</div>';
      $html.='</form>';
      echo $css;
      echo $jscript;
      echo $html;    
?>