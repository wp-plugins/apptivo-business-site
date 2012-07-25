<?php
/**
 * Apptivo Job Lists Widget
 * @package apptivo-business-site
 * @author  RajKumar <rmohanasundaram[at]apptivo[dot]com>
 */
class JobList_Widget extends WP_Widget {
    /** constructor */
		var $widget_name;
		var $widget_description;
		
        function JobList_Widget() {        	
        
        $this->widget_description = __( 'Display open job positions', 'apptivo-businesssite' );
		$this->widget_name = __('[Apptivo] Job List', 'apptivo-businesssite' );
        $widget_ops = array('description' => $this->widget_description );
        $this->WP_Widget('joblist_widget', $this->widget_name, $widget_ops);
        
        }

        function widget($args, $instance) {
           extract($args);
           $instance = wp_parse_args((array) $instance, array(
                            'title' => '',
                            'isfeatured' => '',                           
                            'custom_css' => '',                           
                            'number_ofitems' => 5,
                            'more_text' => 'More...',
           					'readmore_link' => '',                                                  
                            'awp_widget_templatelayout' => ''
                    ) );
        
       
         $target_Link = $instance['readmore_link'];
         $total_items = $instance['number_ofitems'];
        
         if($instance['isfeatured'] == 'on') :
         	$isfeatured = 'true';
        else : 
             $isfeatured = 'false';
         endif;
         
         $listofJobs1 = getAllHrjobs($total_items,0,$isfeatured);
         $listofJobs = $listofJobs1->jobDetails;
         $listofJobs = awp_convertObjToArray($listofJobs);
          echo $before_widget;
               ?>
				<?php 
        		if($instance['custom_css'] != '')
         		{
           		$css='<style type="text/css">'.$instance['custom_css'].'</style>';
           		echo $css;
         		}
         		?>
         		<?php if ($instance['title']) echo $before_title . apply_filters('widget_title', $instance['title']) . $after_title; ?>
               <div class="widget_apptivo_jobs widget-container" id="widget_apptivo_job_lists">
               <ul>
               <?php 
               if(strlen(trim($listofJobs[0]->jobTitle)) != 0)
               {
               	foreach($listofJobs as $jobs)
               { ?>
               <li class="job_list job-list-<?php echo $jobs->jobNumber; ?>" ><a title="<?php echo $jobs->jobTitle;?>" href="/?page_id=<?php echo $target_Link; ?>&vacancyno=<?php echo $jobs->jobNumber; ?>"><?php echo $jobs->jobTitle;?></a>
               </li>
               <?php }
               }
               else 
               {
               	echo '<li>No job available</li>';
               	
               }
                ?>
               
               </ul>
               </div>
               <?php 
               echo $after_widget;
             
            }

            function update($new_instance, $old_instance) {
            	$new_instance['more_text']=(trim($new_instance['more_text'])!="")?$new_instance['more_text']:'More...';
            	if( !is_numeric($new_instance['number_ofitems']) || $new_instance['number_ofitems'] == 0 )
            	{
            		$new_instance['number_ofitems'] = 5;
            	}
                 return $new_instance;
                
            }
            function form($instance) {

             $instance = wp_parse_args( (array)$instance, array(
                            'title' => '',
                            'isfeatured' => '',                           
                            'custom_css' => '', 
                            'more_text' => 'More...',
                            'readmore_link' => '',                                                
                            'awp_widget_templatelayout' => '',
                            'number_ofitems' => 5                   
                            ) );
                        ?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'apptivo-businesssite'); ?>:</label>
				<input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" class="widefat" />
			</p>


            <p>
            	<label for="<?php echo $this->get_field_id('custom_css'); ?>"><?php _e('Custom CSS:','apptivo-businesssite'); ?></label>
            	<textarea id="<?php echo $this->get_field_id('custom_css'); ?>" name="<?php echo $this->get_field_name('custom_css'); ?>" class="widefat" rows="6" cols="4"><?php echo $instance['custom_css']; ?></textarea>
            </p>
            
            <p>
            	<label for="<?php echo $this->get_field_id('isfeatured'); ?>"><?php _e('Show Featured Jobs only','apptivo-businesssite'); ?></label>
            	<input <?php checked('on',$instance['isfeatured']); ?> type="checkbox" id="<?php echo $this->get_field_id('isfeatured'); ?>" name="<?php echo $this->get_field_name('isfeatured'); ?>" />
            </p>
            
            <p>
            	<label for="<?php echo $this->get_field_id('number_ofitems'); ?>"><?php _e('Number of items to show','apptivo-businesssite'); ?></label>
            	<input type="text" id="<?php echo $this->get_field_id('number_ofitems'); ?>" name="<?php echo $this->get_field_name('number_ofitems'); ?>" value="<?php echo esc_attr( $instance['number_ofitems'] ); ?>"/>
            </p>
            

             <p>
             <label for="<?php echo $this->get_field_id('readmore_link'); ?>"><?php _e('Job Description Page', 'apptivo-businesssite'); ?>:</label>
				  <select id="<?php echo $this->get_field_id('readmore_link'); ?>" name="<?php echo $this->get_field_name('readmore_link'); ?>">
				  <option value="" > Select Description Page </option>
				 <?php
				  $pages = get_pages(); 
				  foreach ($pages as $pagg) {
				  	?>
				  	<option value="<?php echo $pagg->ID; ?>"  <?php selected($pagg->ID, $instance['readmore_link']); ?> >
											<?php echo $pagg->post_title; ?>
					</option>
				  	<?php 
				  }
				 ?>	</select>
			 </p>
 
            <?php
            }
}