<?php
/*
 Template Name:Double Column Layout1 with US Phone#
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
jQuery("#'.$contactform[name].'_contactforms").validate({
    rules: {
        telephonenumber1: { minlength: 3},
        telephonenumber2: { minlength: 3 },
        telephonenumber3: { minlength: 4 }
    },
    groups: {
        telephonenumber: "telephonenumber1 telephonenumber2 telephonenumber3"
    },
messages: {
telephonenumber1: {
 required: "Please Enter Valid Phone Number.",
 minlength: jQuery.format("Please Enter Valid Phone Number")
        },
telephonenumber2: {
 required: "Please Enter Valid Phone Number.",
 minlength: jQuery.format("Please Enter Valid Phone Number")
        },
telephonenumber3: {
 required: "Please Enter Valid Phone Number.",
 minlength: jQuery.format("Please Enter Valid Phone Number")
        }
        },
   errorPlacement: function(error, element) {
         if (element.attr("name") == "telephonenumber1" || element.attr("name") == "telephonenumber2" || element.attr("name") == "telephonenumber3")
         error.insertAfter("#telephonenumber3");
       else
        error.insertAfter(element);
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
				if($fieldid != 'telephonenumber')
			 {
                                 $html.='<input type="text" name="'.$fieldid.'" id="'.$fieldid.'" value="'.$postValue.'"  class="absp_contact_input_text'.$validateclass.'">';
			 }
			 else
			 {
			 	 $html.='<input maxlength="3" size="3" type="text" name="'.$fieldid.'1" id="'.$fieldid.'1" value="'.$postValue.'"  class="absp_contact_input_text'.$validateclass.'">';
			 	 $html.='&nbsp;&nbsp;&nbsp;<input maxlength="3" size="3" type="text" name="'.$fieldid.'2" id="'.$fieldid.'2" value="'.$postValue.'"  class="absp_contact_input_text'.$validateclass.'">';
			 	 $html.='&nbsp;&nbsp;&nbsp;<input maxlength="4" size="4" type="text" name="'.$fieldid.'3" id="'.$fieldid.'3" value="'.$postValue.'"  class="absp_contact_input_text'.$validateclass.'">';
			 }
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
				$i=0;$opt=0;
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
							$html.='<br>';
						$html.='<label for="'.$fieldid.$opt.'">'.$optionvalue.'</label><input type="radio" name="'.$fieldid.'" id="'.$fieldid.$opt.'" value="'.$optionvalue.'"  class="absp_contact_input_radio '.$validateclass.'" '.$selected.'>';
						}
						$opt++;
				}
			break;
			case "checkbox":
				$i=0;$opt=0;
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
						$html.='<br>';
					$html.='<label for="'.$fieldid.$opt.'">'.$optionvalue.'</label><input type="checkbox" name="'.$fieldid.'[]" id="'.$fieldid.$opt.'" value="'.$optionvalue.'"  class="absp_contact_input_checkbox '.$validateclass.'" '.$selected.'>';
					$i++;$opt++;
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