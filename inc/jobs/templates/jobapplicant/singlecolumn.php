<?php
/*
 Template Name:Single Column Layout
 Template Type: Shortcode
 */ 

$formfields=array();
$formfields=$hrjobsform[fields];
$countries = $countrylist;
$css="";
$html="";

if( $hrjobsform[css] != '' )
{ 
	$css='<style type="text/css">'.$hrjobsform[css].'</style>';
}

$jscript='<script type="text/javascript">
jQuery(document).ready(function(){
 jQuery.validator.addMethod("phoneUS", function(phone_number, element) {
    phone_number = phone_number.replace(/\s+/g, "");
	return this.optional(element) || phone_number.length == 10 &&
		phone_number.match(/[0-9]{10}/);
}, "Please specify a valid phone number");

jQuery("#'.$hrjobsform[name].'_hrjobsforms").validate({
    rules: {
        telephonenumber: { phoneUS: true}
       }
});
});
</script>';
echo '<style type="text/css">
        
        input.awp_hrjobsform_submit_jaform { margin-top:10px;} 
        </style>';
if($submitformname==$hrjobsform[name] && $successmsg!=""){
	$html.= '<div id="success_'.$hrjobsform[name].'" class="success_'.$hrjobsform[name].'">'.$successmsg."</div>";
}
$html.= '<form id="'.$hrjobsform[name].'_hrjobsforms" class="awp_hrjobs_form" name="'.$hrjobsform[name].'_hrjobsforms" action="'.$_SERVER['REQUEST_URI'].'" method="post">';
$html.='<input type="hidden" value="'.$hrjobsform[name].'" name="awp_jobsformname" id="awp_jobsformname">';
$html.= '<input type="hidden" value="'.$jobId.'" name="jobId" id="jobId"><input type="hidden" value="'.$jobNo.'" name="jobNo" id="jobNo">';
$html.='<input type="hidden" value="'.$hrjobsform[name].'" name="awp_jobsformname" id="awp_jobsformname">';
$html.='<div class="awp_hrjobsform_maindiv_'.$hrjobsform[name].'">';

if(trim($jobId) == '' )
{
	if( count($allJobs) >= 1)
	{
	$html.= '<div class="jobsform_section"><div class="jobsform_left_part"><span>Job</span></div>
             <div class="jobsform_rgt_part">
             <select class="absp_jobapplicant_select joblists" value="" id="jobidwithnumber" name="jobidwithnumber">
             <option value="0">Select</option>';
foreach( $allJobs as $jobslists )
					{  
					if( strlen(trim($jobslists->jobTitle)) < 20)
					{
						$jobTitle = $jobslists->jobTitle;		
					}else { $jobTitle = substr($jobslists->jobTitle,0,20).'...'; }
							$html .=  '<option value="'.$jobslists->id.'::'.$jobslists->jobNumber.'">'.$jobTitle.'</option>';
						
					}
					
$html.= '</select></div></div>';
	}
}

foreach($formfields as $field) 
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
		
		$html.='<div class="jobsform_section">'.
				'<div class="jobsform_left_part">'.
					'<span class="absp_jobapplicant_label">';
		if($required)
			$html.='<span class="absp_jobapplicant_mandatory">*</span>';
		
		$html.=$showtext.'</span>'.
                       '</div>'.
                       '<div class="jobsform_rgt_part">';
		
	if($fieldtype=="select" || $fieldtype=="radio" || $fieldtype=="checkbox" ){			
		if(trim($fieldid) == 'industry')
				{
				$optionvalues=$options;
				$fieldtype = 'select';
				} else if(trim($options)!=""){
				//$optionvalues=split(",", $options);				
				$optionvalues=split("[\n]",trim($options));//Split the String line by line.	
			}
		}
		switch($fieldtype)
		{
			case "text":
                $html.='<input type="text" name="'.$fieldid.'" id="'.$fieldid.'" value=""  class="absp_jobapplicant_input_text'.$validateclass.'">';
                break;
			case "textarea":
				$html .=  '<textarea  name="'.$fieldid.'" id="'.$fieldid.'" value=""  class="absp_jobapplicant_textarea'.$validateclass.' size="50"></textarea>';
			break;
			case "select":
               if($fieldid == 'country')
               {
                $html .=  '<select  name="'.$fieldid.'" id="'.$fieldid.'" value=""  class="absp_jobapplicant_select'.$validateclass.'">';
					foreach($countries as $country)
					{
	                  $html .=  '<option value="'.$country->countryCode.'">'.$country->countryName.'</option>';
					}
				$html .=  '</select>';
                }
                else  if($fieldid == 'industry')
                {
                if(!empty($optionvalues))
                {                	
                $html .=  '<select  name="'.$fieldid.'" id="'.$fieldid.'" value=""  class="absp_jobapplicant_select'.$validateclass.'">';
                	foreach( $optionvalues as $optionvalue )
					{  if(!empty($optionvalue) && strlen(trim($optionvalue)) != 0)
						{
							$options = explode("::",$optionvalue);
							$html .=  '<option value="'.$options[0].'">'.$options[1].'</option>';
						}
					}
				$html .=  '</select>';
                }else {
                	 $html .=  '<select  name="'.$fieldid.'" id="'.$fieldid.'" value=""  class="absp_jobapplicant_select'.$validateclass.'">';
                	 $html .=  '<option value="0">Default</option>';
					 $html .=  '</select>';
                }
                } else {
				$html .=  '<select  name="'.$fieldid.'" id="'.$fieldid.'" value=""  class="absp_jobapplicant_select'.$validateclass.'">';
					foreach( $optionvalues as $optionvalue )
					{  if(!empty($optionvalue) && strlen(trim($optionvalue)) != 0)
						{
							$html .=  '<option value="'.$optionvalue.'">'.$optionvalue.'</option>';
						}
					}
				$html .=  '</select>';
                                }
			break;
			case "file":
				$html.='<input type="file" id="file_upload" name="file_upload" />';
				$html.= '<input type="hidden" name="uploadfile_docid" id="uploadfile_docid" value="" class="absp_jobapplicant_input_text'.$validateclass.'"  />';
               break;
			case "radio":
				$i=0;$opt=0;
				foreach( $optionvalues as $optionvalue )
				{
					if(!empty($optionvalue) && strlen(trim($optionvalue)) != 0)
					{
					
					$html.='<div class="awp_custom_fields"><input type="radio" name="'.$fieldid.'" id="'.$fieldid.$opt.'" value="'.$optionvalue.'"  class="absp_jobapplicant_input_radio '.$validateclass.'">&nbsp&nbsp
					<label class="awp_custom_lbl" for="'.$fieldid.$opt.'">'.trim($optionvalue).'</label> </div>';
					$i++;$opt++;
					}
				}
			break;
			case "checkbox":
				$i=0;$opt=0;
				foreach( $optionvalues as $optionvalue )
				{
					if(!empty($optionvalue) && strlen(trim($optionvalue)) != 0)
					{
				
					$html.='<div class="awp_custom_fields">
					<input type="checkbox" name="'.$fieldid.'[]" id="'.$fieldid.$opt.'" value="'.$optionvalue.'"  class="absp_jobapplicant_input_checkbox '.$validateclass.'">&nbsp&nbsp<label class="awp_custom_lbl" for="'.$fieldid.$opt.'">'.trim($optionvalue).'</label>
                                            </div>';
					$i++;$opt++;
					}
				}
			break;
		}
		$html.='</div>'.'</div>';
     }
   

      $html.='<input type="hidden" name="awp_hrjobsform_submit"/>';
      if($hrjobsform[submit_button_type]=="submit" &&($hrjobsform[submit_button_val])!=""){
        $button_value = 'value="'.$hrjobsform[submit_button_val].'"';
      }
      else{
      	if($hrjobsform[submit_button_val] == '' || empty($hrjobsform[submit_button_val])) :
      		$hrjobsform[submit_button_val] = awp_image('submit_button');
      	endif;
         $button_value = 'src="'.$hrjobsform[submit_button_val].'"';
      }
      $html .= '<input type="'.$hrjobsform[submit_button_type].'" class="absp_jobapplicant_button_submit awp_hrjobsform_submit_'.$hrjobsform[name].'" '.$button_value.' name="awp_jobsform_submit" id="awp_jobsform_submit" />';
      $html.='</div>';
      
      $html.='</form>';
      echo $css;
      echo $jscript;
      echo $html;
    
?>