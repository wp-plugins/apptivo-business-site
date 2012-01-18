<?php

/* Apptivo Testimonials Widget
 */
class AWP_Testimonials_Widget extends WP_Widget {
    /** constructor */
		var $widget_name;
		var $widget_description;
		
        function AWP_Testimonials_Widget() {
        
        $this->widget_description = __( 'Display customer testimonials', 'apptivo-businesssite' );
		$this->widget_name = __('[Apptivo] Testimonials', 'apptivo-businesssite' );
        $widget_ops = array('description' => $this->widget_description );
        $this->WP_Widget('awp_testimonials_widget', $this->widget_name, $widget_ops);
        }

        function widget($args, $instance) {
                    extract($args);

                    $instance = wp_parse_args( (array) $instance, array(
                            'title' => '',
                            'style' => '',
                            'page_id' => '',
                            'custom_css' => '',
                            'order' => '',
                            'itemstoshow' => AWP_DEFAULT_ITEM_SHOW,
                            'more_text' => AWP_DEFAULT_MORE_TEXT,
                            'content_limit' => '',
                            'awp_widget_templatelayout' => ''
                    ) );

                    echo $before_widget;
                    $_template_file = AWP_TESTIMONIALS_TEMPLATEPATH."/".$instance['awp_widget_templatelayout'];
                    $awp_testimonials = getAllTestimonials();
                    $awp_testimonials = awp_convertObjToArray($awp_testimonials->testimonialsList);
                      if(!empty($awp_testimonials))
                        {
                            include $_template_file;
                        } else { echo awp_messagelist('testimonials-display-page'); }
              
                    echo $after_widget;
                    wp_reset_query();
            }

            function update($new_instance, $old_instance) {
                  $new_instance['more_text']=(trim($new_instance['more_text'])!="")?$new_instance['more_text']:AWP_DEFAULT_MORE_TEXT;
                    $new_instance['itemstoshow'] = is_numeric($new_instance['itemstoshow'])?$new_instance['itemstoshow']:AWP_DEFAULT_ITEM_SHOW;
                    return $new_instance;
            }

            function form($instance) {

                    $instance = wp_parse_args( (array)$instance, array(
                            'title' => '',
                            'style' => '',
                            'page_id' => '',
                            'custom_css' => '',
                            'order' => '',
                            'itemstoshow' => AWP_DEFAULT_ITEM_SHOW,
                            'more_text' => AWP_DEFAULT_MORE_TEXT,
                            'content_limit' => '',
                            'awp_widget_templatelayout' => ''
                            ) );

                        ?>
    <p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'genesis'); ?>:</label>
			<input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" class="widefat" />
		</p>
<!--                    <p>
              <label for="<?php echo $this->get_field_id('style'); ?>"><?php _e('Style:'); ?></label>
              <select id="<?php echo $this->get_field_id('style'); ?>" name="<?php echo $this->get_field_name('style'); ?>">
                            <option value="alignleft" <?php selected('Default', $instance['style']); ?>><?php _e('Default'); ?></option>
                            <option value="alignright" <?php selected('Custom', $instance['style']); ?>><?php _e('Custom'); ?></option>
                    </select>
            </p>-->
            <p>
              <label for="<?php echo $this->get_field_id('custom_css'); ?>"><?php _e('Custom CSS:'); ?></label>
              <textarea id="<?php echo $this->get_field_id('custom_css'); ?>" name="<?php echo $this->get_field_name('custom_css'); ?>" class="widefat" rows="6" cols="4"><?php echo $instance['custom_css']; ?></textarea>
            </p>
            <p><label for="<?php echo $this->get_field_id('order'); ?>"><?php _e('Order:'); ?></label>
                <select id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>">
                            <option value="1" <?php selected('1', $instance['order']); ?>><?php _e('Newest First'); ?></option>
                            <option value="2" <?php selected('2', $instance['order']); ?>><?php _e('Oldest First'); ?></option>
                            <option value="3" <?php selected('3', $instance['order']); ?>><?php _e('Random Order'); ?></option>
                            <option value="4" <?php selected('4', $instance['order']); ?>><?php _e('Custom Order'); ?></option>
            </select>
          </p>
            <p>
              <label for="<?php echo $this->get_field_id('itemstoshow'); ?>"><?php _e('Items to show:'); ?></label>
              <input  id="<?php echo $this->get_field_id('itemstoshow'); ?>" name="<?php echo $this->get_field_name('itemstoshow'); ?>" size="3" type="text" value="<?php echo $instance['itemstoshow']; ?>" />
            </p>
            <p>
              <label for="<?php echo $this->get_field_id('more_text'); ?>"><?php _e('More Items Link Title:'); ?></label>
              <input class="widefat" id="<?php echo $this->get_field_id('more_text'); ?>" name="<?php echo $this->get_field_name('more_text'); ?>" type="text" value="<?php echo $instance['more_text']; ?>" />
            </p>
            <p>
            <label for="<?php echo $this->get_field_id('awp_widget_templatelayout'); ?>"><?php _e('Select Template', 'apptivo'); ?>:</label>
            <?php
            $plugintemplates = get_awpTemplates(AWP_TESTIMONIALS_TEMPLATEPATH,'widget');
           ?>

                  <select id="<?php echo $this->get_field_id('awp_widget_templatelayout'); ?>" name="<?php echo $this->get_field_name('awp_widget_templatelayout'); ?>" >
						<?php
						foreach (array_keys( $plugintemplates ) as $template )
						{
							?>
							<option value="<?php echo $plugintemplates[$template]?>"  <?php selected($plugintemplates[$template], $instance['awp_widget_templatelayout']); ?> >
							<?php echo $template?>
							</option>
							<?php }?>
				  </select>
		    </p>
            <p><label for="<?php echo $this->get_field_id('page_id'); ?>"><?php _e('Page', 'genesis'); ?>:</label>
                    <?php wp_dropdown_pages(array('name' => $this->get_field_name('page_id'), 'selected' => $instance['page_id'])); ?></p>
            

            <?php
            }

}
?>