<?php
/*
 Template Name: Default Template
 Template Type: Widget
 */
echo $before_widget;
                  if(!empty($newsletterformfields)){
                   if ($instance['title']) echo $before_title . apply_filters('widget_title', $instance['title']) . $after_title;
                 $css="";
                $html="";
                if( $newsletterproperties[css] != '' )
                {
                        $css='<style type="text/css">'.$newsletterproperties[css].'</style>';
                }
                wp_register_script('jquery_validation','http://ajax.aspnetcdn.com/ajax/jquery.validate/1.8.1/jquery.validate.min.js',array('jquery'));
	            wp_print_scripts('jquery_validation');
                $jscript='<script type="text/javascript">
						 jQuery(document).ready(function(){
						 jQuery.validator.addMethod("phoneUS", function(phone_number, element) {
						    phone_number = phone_number.replace(/\s+/g, "");
							return this.optional(element) || phone_number.length == 10 &&
								phone_number.match(/[0-9]{10}/);
						}, "Please specify a valid phone number");
						
						jQuery("#'.$newsletterform[name].'_newsletter_widget").validate({
						    rules: {
						        newsletter_phone: { phoneUS: true}
						       }
						});
						});
                </script>';
                if($successmsg!=""){
                        $html.= '<div>'.$successmsg."</div>";
                }
                $html.= '<form id="'.$newsletterform[name].'_newsletter_widget" name="'.$newsletterform[name].'_newsletter_widget" action="'.$_SERVER['REQUEST_URI'].'" method="post">';
                $html.='<input type="hidden" value="'.$newsletterform[name].'" name="awp_newsletterwidgetname" id="awp_newsletterwidgetname">';
                $html.='<input type="hidden" value="'.$newsletterproperties[category].'" name="newsletter_category" id="newsletter_category">';
                $html.='<div class="awp_newsletter_widget_maindiv_'.$newsletterform[name].'">';
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

                                $html.='<div class="form_section">'.
                                                '<div class="form_left_part">'.
                                                        '<span class="absp_newsletter_label">';
                                if($required)
                                        $html.='<span class="absp_newsletter_mandatory">*</span>';

                                $html.=$showtext.'</span>'.
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
                                                $html.='<input type="text" name="'.$fieldid.'" id="'.$fieldid.'" value=""  class="absp_newsletter_input_text'.$validateclass.'"/>';
                                        break;
                                        case "textarea":
                                                $html .=  '<textarea  name="'.$fieldid.'" id="'.$fieldid.'" value=""  class="absp_newsletter_textarea'.$validateclass.' size="50"></textarea>';
                                        break;
                                        case "select":
                                                $html .=  '<select  name="'.$fieldid.'" id="'.$fieldid.'" value=""  class="absp_newsletter_select'.$validateclass.'">';
                                                foreach( $optionvalues as $optionvalue )
                                                {
                                                        $html .=  '<option value="'.$optionvalue.'">'.$optionvalue.'</option>';
                                                }
                                                $html .=  '</select>';
                                        break;
                                        case "radio":
                                                $i=0;
                                                foreach( $optionvalues as $optionvalue )
                                                {
                                                        if($i>0)
                                                                $html.='<br>';
                                                        $html.='<label for="'.$fieldid.'">'.$optionvalue.'</label><input type="radio" name="'.$fieldid.'" id="'.$fieldid.'" value="'.$optionvalue.'"  class="absp_newsletter_input_radio '.$validateclass.'">';
                                                }
                                        break;
                                        case "checkbox":
                                                $i=0;
                                                foreach( $optionvalues as $optionvalue )
                                                {
                                                        if($i>0)
                                                                $html.='<br>';
                                                        $html.='<label for="'.$fieldid.'">'.$optionvalue.'</label><input type="checkbox" name="'.$fieldid.'" id="'.$fieldid.'" value="'.$optionvalue.'"  class="absp_newsletter_input_checkbox '.$validateclass.'">';
                                                        $i++;
                                                }
                                        break;
                                }
                                $html.='</div>'.'</div>';
                     }

                     $html.='</div>';
                     $html .= '<input type="hidden" name="newsletterform_widget" />';
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
        			 $html .= '<br /><input type="'.$newsletterproperties[submit_button_type].'"  '.$button_value.' class="absp_newsletter_button_submit" name="newsletterform_widget_submit"  id="newsletterform_widget_submit" />';
                     $html.='</form>';
                     echo $css;
                     echo $jscript;
                     echo $html;
                  }
                     echo $after_widget;
                    wp_reset_query();
?>