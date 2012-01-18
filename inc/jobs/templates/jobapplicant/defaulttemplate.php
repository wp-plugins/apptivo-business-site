<?php
/*
 Template Name:Default Template
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

$html.= '<input type="hidden" name="upload_script" id="upload_script" value="'.APPTIVO_DOC_UPLOADURL.'?token='.trim($token).'" />';

$html.= '<input type="hidden" value="'.$jobId.'" name="jobId" id="jobId"><input type="hidden" value="'.$jobNo.'" name="jobNo" id="jobNo">';
$html.='<input type="hidden" value="'.$hrjobsform[name].'" name="awp_jobsformname" id="awp_jobsformname">';
$html.='<div class="awp_jobsform_maindiv_'.$hrjobsform[name].'">';

if(trim($jobId) == '')
{
	if( count($allJobs) >= 1)
	{
	$html.= '<div class="jobapplicant_form_section"><div class="jobapplicant_form_left"><span>Job</span></div>
	<div class="jobapplicant_form_right">';
	$html.=  '<select class="awp_select joblists" value="" id="jobidwithnumber" name="jobidwithnumber">';
	foreach( $allJobs as $jobslists )
					{  
					if( strlen(trim($jobslists->jobTitle)) < 20)
					{
						$jobTitle = $jobslists->jobTitle;		
					}else { $jobTitle = substr($jobslists->jobTitle,0,20).'...'; }
					$html .=  '<option value="'.$jobslists->id.'::'.$jobslists->jobNumber.'">'.$jobTitle.'</option>';
						
					}
					
$html.= '</select>'; 
$html.= '</div></div>';
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
		
		$html.='<div class="jobapplicant_form_section">'.
				'<div class="jobapplicant_form_left">'.
					'<span class="absp_jobapplicant_label">';
		if($required)
			$html.='<span class="absp_jobapplicant_mandatory">*</span>';
		
		$html.=$showtext.'</span>'.
                       '</div>'.
                       '<div class="jobapplicant_form_right">';
		
		if($fieldtype=="select" || $fieldtype=="radio" || $fieldtype=="checkbox" ){			
		if(trim($fieldid) == 'industry')
				{
				$optionvalues=$options;
				$fieldtype = 'select';
				} else if(trim($options)!=""){
				$optionvalues=split(",", $options);				
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
                $html .=  '<select  name="'.$fieldid.'" id="'.$fieldid.'" value=""  class="awp_select'.$validateclass.'">';
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
				//$file_upload = '"file_upload"';
               // $html.= "<div class='upload_file' id='upload_files'  ><a href='javascript:uploadfile(".$file_upload.");'>  Upload Files</a></div>"; 
               $html.= '<input type="hidden" name="uploadfile_docid" id="uploadfile_docid" value="" class="absp_jobapplicant_input_text'.$validateclass.'"  />';
				
               break;
			case "radio":
				foreach( $optionvalues as $optionvalue )
				{
					if(!empty($optionvalue) && strlen(trim($optionvalue)) != 0)
					{
					
					$html.='<div class="awp_custom_fields"><input type="radio" name="'.$fieldid.'" id="'.$fieldid.'" value="'.$optionvalue.'"  class="absp_jobapplicant_input_radio '.$validateclass.'"/>&nbsp&nbsp<label class="awp_custom_lbl" for="'.$fieldid.'">'.$optionvalue.'</label> </div>';
					}
				}
			break;
			case "checkbox":
				
				
				foreach( $optionvalues as $optionvalue )
				{
					if(!empty($optionvalue) && strlen(trim($optionvalue)) != 0)
					{
					$html.='<div class="awp_custom_fields"><input type="checkbox" name="'.$fieldid.'[]" id="'.$fieldid.'" value="'.$optionvalue.'"  class="absp_jobapplicant_input_checkbox '.$validateclass.'"/>&nbsp&nbsp<label class="awp_custom_lbl" for="'.$fieldid.'">ss'.$optionvalue.'</label></div>';
					}
				}
			break;
		}
		$html.='</div>'.'</div>';
     }
   
      $html.='<div class="jobscnt_rgstr_line">'.
				'<div class="jobscnt_submit">';
      $html.='<input type="hidden" name="awp_hrjobsform_submit"/>';
      if($hrjobsform[submit_button_type]=="submit" &&($hrjobsform[submit_button_val])!=""){
        $button_value = 'value="'.$hrjobsform[submit_button_val].'"';
      }
      else{
         $button_value = 'src="'.$hrjobsform[submit_button_val].'"';
      }
      $html .= '<input type="'.$hrjobsform[submit_button_type].'" class="absp_jobapplicant_button_submit awp_hrjobsform_submit_'.$hrjobsform[name].'" '.$button_value.' name="awp_jobsform_submit" id="awp_jobsform_submit" />';
      
      $html.= '</div></div>';
      $html.='</div>';
      
      $html.='</form>';
      echo $css;
      echo $jscript;
      echo $html;

?>