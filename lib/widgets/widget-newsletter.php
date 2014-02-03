<?php
/**
 * Apptivo Newsletter Widget
 * @package apptivo-business-site
 * @author  RajKumar <rmohanasundaram[at]apptivo[dot]com>
 */
class AWP_Newsletter_Widget extends WP_Widget {
    /** constructor */
		var $widget_name;
		var $widget_description;
		
        function AWP_Newsletter_Widget() {
        	        
        $this->widget_description = __( 'A newsletter registration form', 'apptivo-businesssite' );
		$this->widget_name = __('[Apptivo] Newsletter', 'apptivo-businesssite' );
        $widget_ops = array('description' => $this->widget_description );
        $this->WP_Widget('awp_newsletter_widget', $this->widget_name, $widget_ops);
        
        }

        function widget($args, $instance) {
                    extract($args);
					$instance = wp_parse_args( (array) $instance, array(
                            'title' => '',
                            'formname' => '',
                            'template_name' => '',
                            'widget_style'=>''
                         ));
                    $_template_file = AWP_NEWSLETTER_TEMPLATEPATH."/".$instance['template_name'];
		            if (!file_exists($_template_file))
		            {   
		            	$_template_file = AWP_NEWSLETTER_TEMPLATEPATH."/".AWP_NEWSLETTER_WIDGET_DEFAULT_TEMPLATE;
		            }
                    $formExists="";
                    $newsletter_forms=array();
                    $newsletterform=array();
                    $newsletterformdetails=array();
                    $newsletter_forms=get_option('awp_newsletterforms');
                    $formname =trim($instance['formname']);
                    if($formname=="")
                        $formExists="";
                    else if(!empty($newsletter_forms)){
                        $formExists = awp_recursive_array_search($newsletter_forms,$formname,'name' );
                    }
                    if(trim($formExists)!=="" ){
                        $newsletterform=$newsletter_forms[$formExists];
                        $newsletterformfields=$newsletterform['fields'];
                        if(!empty($newsletterformfields)) {
                        usort($newsletterformfields, "awp_sort_by_order"); }
                        $newsletterproperties = $newsletterform['properties'];

                     }
                      $submitformname=$_POST['awp_newsletterwidgetname'];
                      if(isset($_POST['newsletterform_widget']) && $submitformname==$formname){
                         if(!empty($newsletterform)){
                         $newsletterformfields=$newsletterform['fields'];
                        //Process the $_POST here..
                        $submittedformvalues=array();
                        $submittedformvalues['category'] = $_POST['newsletter_category'];
                        usort($newsletterformfields, "awp_sort_by_order");
                        foreach($newsletterformfields as $field)
                        {
                                $fieldid=$field['fieldid'];
                         		if($fieldid=='newsletter_phone')
                         		{
             						if(isset($_POST[$formname.'_newsletter_phone1']))
             							{
                   							$submittedformvalues[$fieldid]= $_POST[$formname.'_newsletter_phone1'].$_POST[$formname.'_newsletter_phone2'].$_POST[$formname.'_newsletter_phone3'];
             							} else
								             {
								               $submittedformvalues[$fieldid]= $_POST[$fieldid];
								             }
         						 }else {
                                $submittedformvalues[$fieldid]= stripslashes($_POST[$fieldid]);
         						 }
                        }
                        //Submit the $submittedformvalues to Apptivo Lead Webservice
                        //Dont forgot to save the contact form name as Lead Source value
                        $category = $submittedformvalues[category];
                        $firstname = $submittedformvalues[newsletter_firstname];
                        $lastname = $submittedformvalues[newsletter_lastname];
                        $email = $submittedformvalues[newsletter_email];
                        $phoneNumber = $submittedformvalues[newsletter_phone];
                        $comments = $submittedformvalues[newsletter_comments];
                         if(!empty($email)){
                        $response = createTargetList($category, $firstname, $lastname,$email,$phoneNumber,$comments);
                        $successmsg = $response->return->statusMessage;
                        }
		               if($response == 'E_100')
		                { 
		                	$successmsg = awp_messagelist('newslettertarget-display-page'); 
		                }else if(!empty($successmsg) && $successmsg != "Email already registered"){
		                        if(!empty($newsletterproperties[confmsg])){
		                        $successmsg = $newsletterproperties[confmsg];
		                        }
		                        }
                        
                    }
                    }
              if(!empty($newsletter_forms) && !empty($newsletterformfields))
              {	
            	include $_template_file;           
              } else {  echo awp_messagelist('newsletter-display-page');} 
              
            }

            function update($new_instance, $old_instance) {
                    return $new_instance;
            }

            function form($instance) {

                    $instance = wp_parse_args( (array)$instance, array(
                            'title' => '',
                            'formname' => '',
                            'template_name' => '',
                            'widget_style'=>''
                            ));

                    $newsletter_forms=get_option('awp_newsletterforms');
                        ?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'apptivo-businesssite'); ?>:</label>
			<input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" class="widefat" />
		</p>
                    <p>
                    <label for="<?php echo $this->get_field_id('formname'); ?>"><?php _e('Form Name:','apptivo-businesssite'); ?></label>
                    <select name="<?php echo $this->get_field_name('formname'); ?>" id="<?php echo $this->get_field_id('formname'); ?>">
					<?php
					for($i=0; $i<count($newsletter_forms); $i++)
					{
						?>
                            <option value="<?php echo $newsletter_forms[$i][name]?>" <?php selected($newsletter_forms[$i][name], $instance['formname']); ?>><?php echo $newsletter_forms[$i][name]?>
						</option>
						<?php }?>
				</select>
                </p>
                
                            <p>
            <label for="<?php echo $this->get_field_id('template_name'); ?>"><?php _e('Select Template', 'apptivo-businesssite'); ?>:</label>
            <?php 
            $widgettemplates = get_awpTemplates(AWP_NEWSLETTER_TEMPLATEPATH,'widget');
           ?>
            
                  <select id="<?php echo $this->get_field_id('template_name'); ?>" name="<?php echo $this->get_field_name('template_name'); ?>" >
						<?php
						foreach (array_keys( $widgettemplates ) as $template )
						{
							?>
							<option value="<?php echo $widgettemplates[$template]?>"  <?php selected($widgettemplates[$template], $instance['template_name']); ?> >
							<?php echo $template?>
							</option>
							<?php }?>
				  </select>
		    </p>
                        <p>
                    <label for="<?php echo $this->get_field_id('widget_style'); ?>"><?php _e('Style', 'apptivo-businesssite'); ?>:</label><br/>
	             <textarea style="width: 220px; height: 100px;" class="awp_widgetstyle" id="<?php echo $this->get_field_id('widget_style'); ?>" name="<?php echo $this->get_field_name('widget_style'); ?>"><?php echo esc_attr( $instance['widget_style'] ); ?></textarea>
                            </p>
		    
            <?php
            }

}
?>