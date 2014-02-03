<?php
/* 
 Template Name: Name, Testimonials
 Template Type: Widget
 */
$awp_all_testimonials = $awp_testimonials;
$count=1;
$page_details = get_page($instance['page_id']);


if($instance['order'] == '1')
                        {
                            usort($awp_all_testimonials,'awp_creation_date_compare');
                        }
                    else if($instance['order'] == '2'){
                        usort($awp_all_testimonials,'awp_creation_date_compare');
                    $awp_all_testimonilas = array_reverse($awp_all_testimonials,true);
                        }
                    else if($instance['order'] == '3'){
                        shuffle($awp_all_testimonials);
                        }
                    else{
                         usort($awp_all_testimonials,'awp_sort_by_sequence');
                         }
                         if( $instance['custom_css'] != '' )
                        {
                        	
                          $css='<style type="text/css">'.$instance['custom_css'].'</style>';

                        }
                        
                       if($instance['itemstoshow']!=0){
                        $numberofitems = $instance['itemstoshow'];
                        }
                        else{
                        $numberofitems = count($awp_all_testimonials);
                        }
                       if(!empty($awp_all_testimonials)){
                        if ($instance['title']) echo $before_title . apply_filters('widget_title', $instance['title']) . $after_title;
                    foreach($awp_all_testimonials as $testimonial){
                   if($testimonial->testimonialStatus=="APPROVED"){
                        if($count <= $numberofitems){

                        if(strlen(strip_tags($testimonial->testimonial))>250)
                        {
                        	$testimonial_content = substr(strip_tags($testimonial->testimonial),0,250);                        	
                        }else {
                        	$testimonial_content = strip_tags($testimonial->testimonial);
                        }                        
                        echo '<div id="sfstest-sidebar">
                        <p class="testimonial_title_text">'.$testimonial->account->accountName.'</p>
                        <p class="testimonial_description_text">
                        '.$testimonial_content.'
                        </p>
                        <div align="left" class="bdr"></div>    
                        </div>';
                        
                   }                   
                   
                   $count++;
	        }
                    echo $css;
                    }
              }
              if(!empty($awp_all_testimonials)){
              echo '<div class="normal_text">
                        <strong>
                        <a href="'.$page_details->guid.'">'.$instance['more_text'].'</a>
                        </strong>
                        </div>';
              }              

?>