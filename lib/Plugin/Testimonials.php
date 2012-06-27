<?php
/**
 * Apptivo Testimonials plugin
 * @package apptivo-business-site
 * @author  RajKumar <rmohanasundaram[at]apptivo[dot]com>
 */
require_once AWP_LIB_DIR . '/Plugin.php';
require_once AWP_INC_DIR . '/apptivo_services/Testimonial.php';
/**
 * Class AWP_Testimonials
 */
class AWP_Testimonials extends AWP_Base
{
	var $_plugin_activated = false;
  	/**
     * PHP5 constructor
     */
    function __construct()
    {
    	$settings=array();
    	$this->_plugin_activated=false;
    	$settings=get_option("awp_plugins");
    	if(get_option("awp_plugins")!=="false"){
    		if($settings["testimonials"])
    			$this->_plugin_activated=true;
    	}
    }
    
    /**
     * Returns plugin instance
     *
     * @return AIP_Plugin_BrowserCache
     */
    function &instance()
    {
        static $instances = array();
        
        if (!isset($instances[0])) {
            $class = __CLASS__;
            $instances[0] = & new $class();
        }
        
        return $instances[0];
    }
    /**
     * Runs plugin
     */
    function run()
    {
    	if($this->_plugin_activated){
    		add_action( 'widgets_init', array(&$this,'register_widget'));	                
			add_shortcode('apptivo_testimonials_fullview',array(&$this, 'show_testimonials_fullview'));
			add_shortcode('apptivo_testimonials_inline',array(&$this,'show_testimonials_inline'));
	   }
	   add_action('the_posts',array(&$this,'check_for_shortcode')); 
    }
    
function check_for_shortcode($posts) {
		$testimonial_fullView=awp_check_for_shortcode($posts,'[apptivo_testimonials_fullview');	
		$testimonial_inlineView=awp_check_for_shortcode($posts,'[apptivo_testimonials_inline');		
		if ($testimonial_inlineView){
           // load styles and scripts	      
	       $this->loadscripts();
	    }	   
	    return $posts;
	}

	function loadscripts()
	{   
		wp_enqueue_script('jquery_cycleslider.js',AWP_PLUGIN_BASEURL. '/assets/js/jquery.cycle.all.latest.js',array('jquery'));
	}
	
	/* Add Testimonials */
	function add_testimonials() {
        $awp_testimonials_options = array(
	                'name' => stripslashes($_POST['awp_testimonials_name']),
	                'jobtitle' => stripslashes($_POST['awp_testimonials_jobtitle']),
	                'company' => stripslashes($_POST['awp_testimonials_company']),
	                'website' => stripslashes($_POST['awp_testimonials_website']),
	                'email' => stripslashes($_POST['awp_testimonials_email']),
	                'imageurl' => stripslashes($_POST['awp_testimonials_imageurl']),
	                'testimonial' => stripslashes($_POST['awp_testimonials_cnt']),
	                'order' => stripslashes($_POST['awp_testimonials_order'])
            
	        );
              $awp_testimonials_options= wp_parse_args($awp_testimonials_options,array(
                'name' => '',
                'jobtitle' => '',
                'company' => '',
                'website' => '',
                'email' => '',
                'imageurl' =>'',
                'testimonial' =>'',
                'order' => ''
            ));
            extract($awp_testimonials_options);
            $testimonial = apply_filters('the_content', $testimonial);
            $response = addTestimonials($account, $accountId, $company, $contact, $contactId,$creationDate, $email, $firmId, $images, $jobtitle, $name, $returnStatus, $order, $siteTestimonialId, $testimonial, $imageurl, $testimonialStatus, $website);
            return $response;
	 }
    
	
	//Update Testimonials
	function update_testimonials() {
        $awp_testimonials_options = array(
                    'testimonialId' => $_REQUEST['awp_tstid'],
                    'accountId' => $_REQUEST['awp_tst_accountId'],
                    'contactId' => $_REQUEST['awp_tst_contactId'],
	                'name' => stripslashes($_POST['awp_testimonials_name']),
	                'jobtitle' => stripslashes($_POST['awp_testimonials_jobtitle']),
	                'company' => stripslashes($_POST['awp_testimonials_company']),
	                'website' => stripslashes($_POST['awp_testimonials_website']),
	                'email' => stripslashes($_POST['awp_testimonials_email']),
	                'imageurl' => stripslashes($_POST['awp_testimonials_imageurl']),
	                'testimonial' => stripslashes($_POST['awp_testimonials_cnt']),
	                'order' => stripslashes($_POST['awp_testimonials_order'])
	   	        );
                 $awp_testimonials_options= wp_parse_args($awp_testimonials_options,array(
                'testimonialId' => '',
                'accountId' => '',
                'contactId' => '',
                'name' => '',
                'jobtitle' => '',
                'company' => '',
                'website' => '',
                'email' => '',
                'imageurl' =>'',
                'testimonial' =>'',
                'order' => ''
            ));
            extract($awp_testimonials_options);
            $testimonial = apply_filters('the_content', $testimonial);
            $response = updateTestimonials($account, $accountId, $company, $contact, $contactId, $creationDate, $email, $firmId, $images, $jobtitle, $name, $returnStatus, $order, $testimonialId, $testimonial, $imageurl, $testimonialStatus, $website);
            return $response;
	  }
	
	//Delete Testimonials
	function delete_testimonials(){
			$awp_tstid = $_REQUEST['tstid'];	        
	        $response = deleteTestimonialByTestimonialId($awp_tstid);
	        return $response;
	
	}
	
	function options() 
	{
        ?>
            <div class="wrap">
            <h2><?php _e('Testimonials Management','apptivo-businesssite'); ?></h2>
            </div>
<?php 
if( $_REQUEST['keys'] == 'fullviewsetting')
{
 $generalClass  = 'nav-tab';
 $fullviewsettingClass = 'nav-tab nav-tab-active';
 $inlineviewsettingClass = 'nav-tab';
}else if( $_REQUEST['keys'] == 'inlineviewsetting'){
 $generalClass = 'nav-tab';
 $fullviewsettingClass  = 'nav-tab';
 $inlineviewsettingClass = 'nav-tab nav-tab-active';
}else {
 $generalClass = 'nav-tab nav-tab-active';
 $fullviewsettingClass  = 'nav-tab';
 $inlineviewsettingClass = 'nav-tab';
}
 ?> 
<div class="icon32" style="margin-top:10px;background: url('<?php echo awp_image('testimonials_icon'); ?>') " ><br></div>             
<h2 class="nav-tab-wrapper">
<a class="<?php echo $generalClass; ?>" href="/wp-admin/admin.php?page=awp_testimonials"><?php _e('Testimonials','apptivo-businesssite'); ?></a>
<a class="<?php echo $fullviewsettingClass; ?>" href="/wp-admin/admin.php?page=awp_testimonials&keys=fullviewsetting"><?php _e('Full View Settings','apptivo-businesssite'); ?></a>
<a class="<?php echo $inlineviewsettingClass; ?>" href="/wp-admin/admin.php?page=awp_testimonials&keys=inlineviewsetting"><?php _e('Inline View Settings','apptivo-businesssite'); ?></a>
</h2>
        <p>
	   <img id="elementToResize" src="<?php echo awp_flow_diagram('testimonials');?>" alt="Testimonials" title="Testimonials"  />
	   </p>
	  	   
    <p style="margin:10px;">
		For Complete instructions,see the <a href="<?php echo awp_developerguide('testimonilas');?>" target="_blank">Developer's Guide.</a>
	</p>       
            
        <?php
        //Message Displayed...
        if(!$this->_plugin_activated){
	    	echo "Testiomonials plugin is currently <span style='color:red'>disabled</span>. Please enable this in <a href='/wp-admin/admin.php?page=awp_general'>Apptivo General Settings</a>.";
	    }else if (isset($_POST['awp_testimonial_add']) && ($_POST['nogdog'] == $_SESSION['apptivo_single_testimonials']) ) {          //Add Testimonials.
	    	$addtestimonials_response = $this->add_testimonials();
	    	if(strlen(trim($_POST['awp_testimonials_name'])) == 0 )
	    	{
	    	$_SESSION['awp_testmonials_messge'] = 'Please enter a testimonial name';
            }else if($addtestimonials_response == 'E_100')
            {
            	$_SESSION['awp_testmonials_messge'] = '<span style=color:#f00;"> Invalid Keys </span>';
            }else if($addtestimonials_response->return->statusCode != '1000')
                    {
                    	$_SESSION['awp_testmonials_messge'] = '<span style=color:#f00;">'.$addtestimonials_response->return->statusMessage.'</span>';
                    }else {
						$_SESSION['awp_testmonials_messge'] = 'Testimonials Added Successfully';	    		
                    }
	    	            
        }else if ($_POST['awp_testimonial_update'] == 'Update') {     //Update Testimonails.
            $updatetestimonials_response = $this->update_testimonials();
            if($updatetestimonials_response->return->statusCode != '1000')
            {
            	$_SESSION['awp_testmonials_messge'] = '<span style=color:#f00;">'.$updatetestimonials_response->return->statusMessage.'</span>';
            }else {
            $_SESSION['awp_testmonials_messge'] = 'Testimonials Updated Successfully';
            }
        }else if ($_REQUEST['tstmode'] == 'delete') {         //Delete Testimonails.
            $deletetestimonials_response = $this->delete_testimonials();
        if($deletetestimonials_response->return->statusCode != '1000')
            {
            	$_SESSION['awp_testmonials_messge'] = '<span style=color:#f00;">'.$deletetestimonials_response->return->statusMessage.'</span>';
            }else {
            $_SESSION['awp_testmonials_messge'] = 'Testimonials Deleted Successfully';
            }
        }else {
        	$_SESSION['awp_testmonials_messge'] = '';
        }
        
        
       switch($_REQUEST['keys'])
        {
        	case fullviewsetting:
        		$this->fullViewSettings();
        		break;
        	case inlineviewsetting:
        		$this->inlineViewSettings();
        		break;
        	default :
                 $this->get_all_testimonials();                      //Display All testimonilas Lists.
        		if ($_REQUEST['tstmode'] == 'edit')                  //Testimonails Edit.
        		{                                     
        		   $awp_tstid = $_REQUEST['tstid'];	        
	               $all_awp_testimonials = getTestimonialByTestimonialId($awp_tstid);
	               if($all_awp_testimonials->statusCode != '1000' && isset($all_awp_testimonials->statusCode))
					{  
					echo '<div class="message" id="errormessage" style="margin: 5px 0pt 15px; background-color: rgb(255, 255, 224); border: 1px solid rgb(230, 219, 85);">
					      <p style="margin: 0.5em; padding: 2px;"><span style="color: rgb(255, 0, 0);">'.$all_awp_testimonials->methodResponse->statusMessage.'</span></p></div>';
					}
							         
        		   $this->edit_testimonials($all_awp_testimonials);   //Testimonails Edit Form.
        		} else {
        			$this->testimonials_form();                      //Testimonails Create Form.
        		}
        		break;
        } 
       
       ?>
<style type="text/css">
	        .awp_testimonials_form td { width:80px;}	          
</style>       

       <?php 
	}
	
	
/**
     * To Call Full View Settings.
     */
    function fullViewSettings()
    {
    	 ?>  <div class="wrap">
           
        <?php
        if (isset($_POST['full_view_settings'])){
            $this->save_testimonials_Settings();
            echo '<div class="message" style="margin:5px 0 15px; background-color: #FFFFE0 ;border: 1px solid #E6DB55;"><p style="margin: 0.5em;padding: 2px;">Full View Settings Saved Successfully.</p></div>';
        }
        $this->fullview_settings();
        ?>
        </div>
        <?php
    }
    /**
     * To Call Inline View Settings.
     *
     */
    function  inlineViewSettings()
    {
    	?>
        <div class="wrap">
        
        <?php
        if (isset($_POST['inline_view_settings'])) {
            $this->save_inline_settings();
            echo '<div class="message" style="margin:5px 0 15px; background-color: #FFFFE0 ;border: 1px solid #E6DB55;"><p style="margin: 0.5em;padding: 2px;">Inline View Settings Saved Successfully.</p></div>';
        }
        $this->inlineview_settings();
        ?>
        </div>
        <?php 
    }
    
	/*
	 * Testimonials Widet settings and view code
	 */
	function register_widget(){
	    //register new widget in Available widgets
	        register_widget( 'AWP_Testimonials_Widget' );
	}
	//Display All Testimonials
	function get_all_testimonials(){
	$all_awp_testimonials = getAllTestimonials();
	
	if (!empty($_SESSION['awp_testmonials_messge']) && strlen(trim($_SESSION['awp_testmonials_messge'])) != 0) : 
        echo '<div style="margin: 5px 0pt 15px; background-color: rgb(255, 255, 224); border: 1px solid rgb(230, 219, 85);width:80%;" id="errormessage" class="message">
      	<p style="margin: 0.5em; padding: 2px;">'.$_SESSION['awp_testmonials_messge'].'</p></div>';
     endif;
   
         
	$all_awp_testimonials = awp_convertObjToArray($all_awp_testimonials->testimonialsList);
    $numberofitems = count($all_awp_testimonials);
    if($numberofitems>0){
   	$itemsperpage =5;
   	$tpages = ceil($numberofitems/$itemsperpage); 
   	$currentpage   = intval($_GET['pageno']);
   	if($currentpage<=0)  $currentpage  = 1;
   	if($currentpage>=$tpages)  $currentpage  = $tpages;
   	$start = ( $currentpage - 1 ) * $itemsperpage;
   	$all_awp_testimonials = array_slice( $all_awp_testimonials, $start, $itemsperpage );
   	$reload = $_SERVER['PHP_SELF'].'?page=awp_testimonials';   	
    
     
        
	       if(!empty($all_awp_testimonials)){
	        ?>
	        <div class="wrap">
	      
	    <?php
	   
        if( $numberofitems > $itemsperpage)
        {
        echo awp_paginate($reload,$currentpage,$tpages,$numberofitems);
        }  ?>
	        <table class="widefat plugins" width="700" cellspacing="0" cellpadding="0">
	                                    <thead><tr>
	                                        <th><?php _e('Name','apptivo-businesssite'); ?></th>
                                            <th><?php _e('Company','apptivo-businesssite'); ?></th>
	                                        <th><?php _e('Website','apptivo-businesssite'); ?></th>
	                                        <th><?php _e('Email','apptivo-businesssite'); ?></td>
	                                        <th><?php _e('Testimonials','apptivo-businesssite'); ?></th>
	                                        <th><?php _e('Order','apptivo-businesssite'); ?></th>
	                                        <th><?php _e('Edit','apptivo-businesssite'); ?></th>
	                                        <th><?php _e('Delete','apptivo-businesssite'); ?></th>
	                                    </tr></thead>
	                                     <tfoot><tr>
	                                        <th style="border-top: 1px solid #DFDFDF;"><?php _e('Name','apptivo-businesssite'); ?></th>
	                                        <th style="border-top: 1px solid #DFDFDF;"><?php _e('Company','apptivo-businesssite'); ?></th>
	                                        <th style="border-top: 1px solid #DFDFDF;"><?php _e('Website','apptivo-businesssite'); ?></th>
	                                        <th style="border-top: 1px solid #DFDFDF;"><?php _e('Email','apptivo-businesssite'); ?></td>
	                                        <th style="border-top: 1px solid #DFDFDF;"><?php _e('Testimonials','apptivo-businesssite'); ?></th>
	                                        <th style="border-top: 1px solid #DFDFDF;"><?php _e('Order','apptivo-businesssite'); ?>r</th>
	                                        <th style="border-top: 1px solid #DFDFDF;"><?php _e('Edit','apptivo-businesssite'); ?></th>
	                                        <th style="border-top: 1px solid #DFDFDF;"><?php _e('Delete','apptivo-businesssite'); ?></th>
	                                    </tr></tfoot>
	                                   <tbody id="the-list">
	                                     <?php
	                                    foreach ($all_awp_testimonials as $awp_testimonial) {

	                                    if($_GET['tstid'] !='' && $_GET['tstid']== $awp_testimonial->siteTestimonialId && $_GET['tstmode'] =='edit')
                                    	{
                                    		$class = "active";
                                    	}else { 
                                    		$class = "inactive";
                                    	}
                                    	$cur_page = intval($_GET['pageno']);
                                    	if( $cur_page == '' || $cur_page == 0 || $currentpage == 1)
                                    	{
                                    		$cur_page = 0;
                                    	}else {
                                    		$cur_page = $cur_page - 1;
                                    	}
                                         ?><tr class="<?php echo $class; ?>" >
	                                            <td><?php echo $awp_testimonial->account->accountName; ?></td>
	                                            <td><?php echo $awp_testimonial->contact->companyName;  ?></td>
	                                            <td><?php echo $awp_testimonial->account->website; ?></td>
	                                            <td><?php echo $awp_testimonial->email; ?></td>
	                                            <td>
	                                         <?php if (strlen(strip_tags(html_entity_decode($awp_testimonial->testimonial))) < 30)
		                                            {
		                                                echo strip_tags(html_entity_decode($awp_testimonial->testimonial));
		                                            }                                                  
		                                            else
                                                  	{  
                                                  		 $sub = strip_tags(html_entity_decode($awp_testimonial->testimonial));                                                  	 
													     echo $sub = substr($sub, 0, 30).'...';
                                                  	}										       
                                            ?>
                                            </td>
	                                        <td><?php echo $awp_testimonial->sequenceNumber; ?></td>
	                                        <td><a href="/wp-admin/admin.php?page=awp_testimonials&amp;tstmode=edit&amp;tstid=<?php echo $awp_testimonial->siteTestimonialId; ?>&amp;pageno=<?php echo intval($_GET['pageno']);?>"><img src="<?php echo awp_image('edit_icon'); ?>"/></a></td>
	                                        <td><a href="/wp-admin/admin.php?page=awp_testimonials&amp;tstmode=delete&amp;tstid=<?php echo $awp_testimonial->siteTestimonialId; ?>" onclick="return delete_testimonials('<?php echo $this->_plugin_activated; ?>');" ><img src="<?php echo awp_image('delete_icon'); ?>"/></a></td>
	                                    </tr>
	                                    <?php
	                                        }
	            ?> </tbody></table>
	        </div>
	       
	        <?php
	                                    }
        }
	}
	
	// Plugin Templates
	function get_plugin_templates() {
	    		
    	$default_headers = array(
		'Template Name' => 'Template Name'	
	    );
	    $templates = array();	 
		$dir_testimonials = AWP_NEWSLETTER_TEMPLATEPATH;
		// Open a known directory, and proceed to read its contents
		if (is_dir($dir_testimonials)) {
		    if ($dh = opendir($dir_testimonials)) {
		        while (($file = readdir($dh)) !== false) {
		        	if ( substr( $file, -4 ) == '.php' )
		        	{		        		        	
					$plugin_data = get_file_data( $dir_testimonials."/".$file, $default_headers, '' );
					if(strlen(trim($plugin_data['Template Name'])) != 0 )
					{
						$templates[$plugin_data['Template Name']] = $file;						
					}
		        	}
		        }		        
		        closedir($dh);
		    }
		}
		return $templates;    
	}

	//Inline View Settings form
	function inlineview_settings(){
	        $awp_testimonials_inline_settings = get_option('awp_testimonials_inline_settings');
	        
	        //Inline theme template.
			$awp_tst_themetemplates = get_awpTemplates(TEMPLATEPATH.'/testimonials','Inline');
	        //Inline plugin template.
	        $awp_tst_plugintemplates = get_awpTemplates(AWP_TESTIMONIALS_TEMPLATEPATH,'Inline');
	        ksort($awp_tst_plugintemplates);
			if( empty($awp_testimonials_inline_settings) )
		        {
		        	echo '<span style="color:#f00;"> Save the below settings to get the Shortcode for inline view. </span>';
		        }
	         ?>
	        <form action="" class="awp_testimonials_form" name="awp_testimonial_inline" method="post">
	            <table class="form-table" width="700" cellspacing="0" cellpadding="0">
                        <tbody>
                    <?php if(isset($awp_testimonials_inline_settings) && !empty($awp_testimonials_inline_settings)) {?>
                    <tr valign="top">
					<td valign="top"><label for="testimonials_inlineview_shortcode">Shortcode:</label>
					<br><span class="description"><?php _e('Copy and Paste this shortcode in your page to display the testimonilas.','apptivo-businesssite'); ?></span>
					</td>
					<td valign="top"><span id="awp_customform_shortcode" name="awp_customform_shortcode">
					<input type="text" style="width: 300px;" id="testimonials_inlineview_shortcode" name="testimonials_inlineview_shortcode" readonly="true" value="[apptivo_testimonials_inline]">
					</span>
					<span style="margin:10px;">*Developers Guide - <a href="<?php echo awp_developerguide('testimonilas-inline-shortcode');?>" target="_blank">Testimonials Inline Shortcodes.</a></span>
					</td>
				    </tr> <?php } ?>
				    
 <tr valign="top"> <td><?php _e('Template Type','apptivo-businesssite'); ?></td>
	                                        <td valign="top">
	                                        <select name="awp_testimonials_templatetype" id="awp_testimonials_templatetype" onchange="testimonials_change_template();">
	                                                <option value="awp_plugin_template" <?php selected($awp_testimonials_inline_settings['template_type'],'awp_plugin_template'); ?> ><?php _e('Plugin Templates','apptivo-businesssite'); ?></option>
	                                                <?php if(!empty($awp_tst_themetemplates)) :?>
	                                                 <option value="theme_template" <?php selected($awp_testimonials_inline_settings['template_type'],'theme_template'); ?> ><?php _e('Templates from Current Theme','apptivo-businesssite'); ?></option>
	                                                <?php endif; ?>
	                                            </select>
	                                            <span style="margin:10px;">*Developers Guide - <a href="<?php echo awp_developerguide('testimonilas-inline-template');?>" target="_blank">Testimonials Inline Templates.</a></span>
	                                        </td>
	                                    </tr>
	                                    <tr valign="top">
	                                        <td><?php _e('Select Layout','apptivo-businesssite'); ?></td>
	                                        <td valign="top">
	                                        
	                                            <select name="awp_testimonials_plugintemplatelayout" id="awp_testimonials_plugintemplatelayout" <?php if($awp_testimonials_inline_settings['template_type'] == 'theme_template')  echo 'style="display: none;"'; ?> >
	                                                  <?php foreach (array_keys($awp_tst_plugintemplates) as $template) : ?>
				                                        <option value="<?php echo $awp_tst_plugintemplates[$template] ?>" <?php selected($awp_tst_plugintemplates[$template],$awp_testimonials_inline_settings['template_layout']); ?> >
				                                        <?php echo $template ?>
				                                        </option>
	                                                 <?php endforeach; ?>	
	                                             </select>
	                                             
	                                             <select name="awp_testimonials_themetemplatelayout" id="awp_testimonials_themetemplatelayout" <?php if($awp_testimonials_inline_settings['template_type'] != 'theme_template')  echo 'style="display: none;"'; ?> >
		                                              <?php foreach (array_keys($awp_tst_themetemplates) as $template)  : ?>
		                                   				<option value="<?php echo $awp_tst_themetemplates[$template] ?>" <?php selected($awp_tst_themetemplates[$template],$awp_testimonials_inline_settings['template_layout']); ?> > <?php echo $template ?>  </option>
		                                			   <?php endforeach; ?>	
	                                             </select>
	                                             
	                                         </td>
	                                     </tr>
	                                        <tr><td><?php _e('Order','apptivo-businesssite'); ?></td>
	                                        <td>
	                                            <select  name="order">
	                                                <option value="1" <?php selected('1', $awp_testimonials_inline_settings['order']); ?> >Newest First</option>
	                                                <option value="2" <?php selected('2', $awp_testimonials_inline_settings['order']); ?> >Oldest First</option>
	                                                <option value="3" <?php selected('3', $awp_testimonials_inline_settings['order']); ?> >Random Order</option>
	                                                <option value="4" <?php selected('4', $awp_testimonials_inline_settings['order']); ?> >Custom Order</option>
	                                            </select>
	                                        </td></tr>
	                                    <tr>
	                                        <td><?php _e('Items to show','apptivo-businesssite'); ?></td>
	                                        <td><input type="text" name="itemstoshow" value="<?php echo ($awp_testimonials_inline_settings['itemstoshow'] == '')?AWP_DEFAULT_ITEM_SHOW:$awp_testimonials_inline_settings['itemstoshow']; ?>" size="3"/>&nbsp;&nbsp;<small>(Default  : <?php echo AWP_DEFAULT_ITEM_SHOW; ?>)</small></td>
	                                    </tr>
	                                    <tr><td><?php _e('More items Link title','apptivo-businesssite'); ?></td>
	                                        <td><input type="text" name="more_text" value="<?php echo ($awp_testimonials_inline_settings['more_text'] == '' )?AWP_DEFAULT_MORE_TEXT:$awp_testimonials_inline_settings['more_text']; ?>"/>&nbsp;&nbsp;<small>(Default : <?php echo AWP_DEFAULT_MORE_TEXT;?>)</small></td></tr>
	                                    <tr><td><?php _e('Full View  page name','apptivo-businesssite'); ?></td><td>
	                        <?php wp_dropdown_pages(array('name' => 'page_ID', 'selected' => $awp_testimonials_inline_settings['page_ID'])); ?>
	                        </td></tr>
                                            <tr><td valign="top"><?php _e('Custom CSS','apptivo-businesssite'); ?></td>
	                                        <td><textarea name="custom_css" cols="30" rows="5"><?php echo $awp_testimonials_inline_settings['custom_css']; ?></textarea>
	                                        <span style="margin:10px;">*Developers Guide - <a href="<?php echo awp_developerguide('testimonilas-inline-customcss');?>" target="_blank">Testimonials Inline CSS.</a></span>
	                                        </td></tr>
	                    <tr><td></td><td><input type="submit" value="Save Settings" name="inline_view_settings" class="button-primary" /></td></tr>
                        </tbody>
                    </table>
	
	        </form>
	      
	        
	        <?php
	}
	
	
	//ShortCode For Testimonials Full View
	function show_testimonials_fullview(){
            $awp_testimonials = $this->getAllTestimonialsForFullView();
            $awp_testimonials_settings = get_option('awp_testimonials_settings');
            ob_start();
            
            if(empty($awp_testimonials_settings))
        	{
        		echo awp_messagelist('testimonialsconfigure-display-page');//Testimonials are not configured in admin page
        	}else if(empty($awp_testimonials['alltestimonials']))
	        {  
	        	echo awp_messagelist('testimonials-display-page');  //Testimonials are not found.Need to create Testimonials.
	        }else { include $awp_testimonials['templatefile']; 
	        }
	        
	        $show_testimonials = ob_get_clean();
	        return $show_testimonials;
	}
 	
 	function display_testimonials()
    {
    	$awp_testimonials = $this->getAllTestimonialsForInline();
    	$awp_testimonials['alltestimonials'] = array_slice($awp_testimonials['alltestimonials'],0,$awp_testimonials['itemstoshow']);
        unset($awp_testimonials['templatefile']);
        unset($awp_testimonials['custom_css']);
        return $awp_testimonials;           
    } 
	//Short code for inline view
	function show_testimonials_inline(){
            $awp_testimonials_inline_settings = get_option('awp_testimonials_inline_settings');   
	        $awp_testimonials = $this->getAllTestimonialsForInline();
	        ob_start();
				        
	        if(empty($awp_testimonials_inline_settings))
        	{
        		echo awp_messagelist('testimonialsconfigure-display-page'); //Testimonials are not configured in admin page
        	}else if(empty($awp_testimonials[alltestimonials]))
	        {  
	        	echo awp_messagelist('testimonials-display-page'); //Testimonials are not found.Need to create Testimonials.
	        }else { include $awp_testimonials['templatefile']; }
	        
	        $show_testimonials = ob_get_clean();
	        return $show_testimonials;
                
	}
	/**
	 * Testimonials Inline View.
	 *
	 * @return unknown
	 */
	function getAllTestimonialsForInline(){
               $awp_testimonials_inline_settings = get_option('awp_testimonials_inline_settings');
               
	        if($awp_testimonials_inline_settings['template_type']=="awp_plugin_template") :
	                $templatefile=AWP_TESTIMONIALS_TEMPLATEPATH."/".$awp_testimonials_inline_settings['template_layout']; // Plugin templates
	        else :
	                $templatefile=TEMPLATEPATH."/testimonials/".$awp_testimonials_inline_settings['template_layout']; //theme templates
	        endif;
	        
	            if (!file_exists($templatefile)) : 
	            	$templatefile = AWP_TESTIMONIALS_TEMPLATEPATH."/sliderview1.php";
	            endif; 
	            
                $response = getAllTestimonials();
                $awp_all_testimonials = awp_convertObjToArray($response->testimonialsList);
	            $page_details = get_page($awp_testimonials_inline_settings['page_ID']);
                $awp_testimonials = array();
                $order=$awp_testimonials_inline_settings['order'];
                $awp_testimonials = $this->sortTestimonialByOrder($awp_all_testimonials, $order);
                $testimonials = array();
                $testimonials['alltestimonials'] = $awp_testimonials;
                $testimonials['custom_css'] = $awp_testimonials_inline_settings['custom_css'];
                $testimonials['itemstoshow'] = $awp_testimonials_inline_settings['itemstoshow'];
                $testimonials['pagelink'] = $page_details->guid;
                $testimonials['more_text'] = $awp_testimonials_inline_settings['more_text'];
                $testimonials['templatefile'] = $templatefile;
                return $testimonials;
        }
        
        /**
         * Testimonials Full View.
         *
         * @return unknown
         */
        function getAllTestimonialsForFullView(){
            $awp_testimonials_settings = get_option('awp_testimonials_settings');
     
	        if($awp_testimonials_settings['template_type']=="awp_plugin_template") :
	                $templatefile=AWP_TESTIMONIALS_TEMPLATEPATH."/".$awp_testimonials_settings['template_layout']; //plugin templates
	        else :
	                $templatefile=TEMPLATEPATH."/testimonials/".$awp_testimonials_settings['template_layout']; //theme templates
            endif;

              if (!file_exists($templatefile)) :
	            	$templatefile = AWP_TESTIMONIALS_TEMPLATEPATH."/".AWP_TESTIMONIALS_DEFAULT_TEMPLATE;
	          endif;
	          
              $response = getAllTestimonials();
              $awp_all_testimonials = awp_convertObjToArray($response->testimonialsList);
               
              $testimonials_pageid = get_option('awp_testimonials_pageid'); 
              if(count($response->testimonialsList) == 0 && empty($response->testimonialsList) && $testimonials_pageid != '')
	            {
	            $awp_all_testimonials = dummy_testimonials();
	            }
	            
	           $order=$awp_testimonials_settings['order'];
               $awp_testimonials = $this->sortTestimonialByOrder($awp_all_testimonials, $order);
               $testimonials = array();
               $testimonials['alltestimonials'] = $awp_testimonials;
               $testimonials['custom_css'] = $awp_testimonials_settings['custom_css'];
               $testimonials['templatefile'] = $templatefile;
               return $testimonials;

        }
        
        /**
         * Sorting Testimonails.
         *
         * @param unknown_type $awp_testimonials
         * @param unknown_type $order
         * @return unknown
         */
        function sortTestimonialByOrder($awp_testimonials,$order){
        if(!empty($awp_testimonials)) {      	
        switch($order){
                case '1':
                    usort($awp_testimonials,'awp_creation_date_compare');
                    break;
                case '2':
                    usort($awp_testimonials,'awp_creation_date_compare');
                    $awp_testimonials = array_reverse($awp_testimonials);
                    break;
                case '3':
                    shuffle($awp_testimonials);
                    break;
                default:
                    usort($awp_testimonials,'awp_sort_by_sequence');
                    break;

             } 
        return $awp_testimonials; 
        }
        return false;//No data available.
             
        }
    //function is to append page content with shortcode
	function update_page_content() {
	        $awp_testimonials_settings = get_option('awp_testimonials_settings');
	        $page_details = get_page($awp_testimonials_settings['page_ID']);
	        $page_content = str_replace('[apptivo_testimonials_fullview]', '', $page_details->post_content) . "[apptivo_testimonials_fullview]";
	        //Update page
	        $my_post = array();
	        $my_post[ID] = $awp_testimonials_settings['page_ID'];
	        $my_post['post_content'] = $page_content;
	        //Update the post into the database
	        wp_update_post($my_post);
	}
	
	
	//Full View Settings Form
	function fullview_settings() {
	        $awp_testimonials_settings = get_option('awp_testimonials_settings');
	        //Full view theme template
	        $awp_tst_themetemplates = get_awpTemplates(TEMPLATEPATH.'/testimonials','Plugin');
	        //Full view Plugin template
	        $awp_tst_plugintemplates = get_awpTemplates(AWP_TESTIMONIALS_TEMPLATEPATH,'Plugin');
	        
	        $awp_tst_plugintemplates = get_awpTemplates(AWP_TESTIMONIALS_TEMPLATEPATH,'Plugin');
	        ksort($awp_tst_plugintemplates);
	 
	        if( empty($awp_testimonials_settings) ) :
        	  echo '<span style="color:#f00;"> Save the below settings to get the Shortcode for full view. </span>';
            endif; // if( empty($awp_testimonials_settings) )
              
	        ?>
	        <form action="" class="awp_testimonials_form" name="awp_testimonial_full" method="post">
	            <table class="form-table" width="700" cellspacing="0" cellpadding="0">
                        <tbody>        
                    <?php if(isset($awp_testimonials_settings) && !empty($awp_testimonials_settings)) {?>
                    <tr valign="top">
					<td valign="top"><label for="testimonials_fullview_shortcode"><?php _e('Shortcode:','apptivo-businesssite'); ?></label>
					<br><span class="description"><?php _e('Copy and Paste this shortcode in your page to display the testimonials.','apptivo-businesssite'); ?></span>
					</td>
					<td valign="top"><span id="awp_customform_shortcode" name="awp_customform_shortcode">
					<input type="text" style="width: 300px;" id="testimonials_fullview_shortcode" name="testimonials_fullview_shortcode" readonly="true" value="[apptivo_testimonials_fullview]">
					</span>
					<span style="margin:10px;">*Developers Guide - <a href="<?php echo awp_developerguide('testimonilas-fullview-shortcode');?>" target="_blank">Testimonials Fullview Shortcodes.</a></span>
					</td>
				    </tr> <?php } ?>
				    
	                                    <tr valign="top"> <td><?php _e('Template Type','apptivo-businesssite'); ?> </td>
	                                        <td valign="top">
	                                        <select name="awp_testimonials_templatetype" id="awp_testimonials_templatetype" onchange="testimonials_change_template();">
	                                                <option value="awp_plugin_template" <?php selected($awp_testimonials_settings['template_type'],'awp_plugin_template'); ?> ><?php _e('Plugin Templates','apptivo-businesssite'); ?></option>
	                                                <?php if (!empty($awp_tst_themetemplates)) : ?>
	                                                	<option value="theme_template" <?php selected($awp_testimonials_settings['template_type'],'theme_template'); ?> ><?php _e('Templates from Current Theme','apptivo-businesssite'); ?></option>
	                                                <?php endif; ?>
	                                            </select>
	                                            <span style="margin:10px;">*Developers Guide - <a href="<?php echo awp_developerguide('testimonilas-fullview-template');?>" target="_blank">Testimonials Fullview Templates.</a></span>
	                                        </td>
	                                    </tr>
	                                    <tr valign="top">
	                                        <td><?php _e('Select Layout','apptivo-businesssite'); ?></td>
	                                        <td valign="top">
	                                        
	                                        <select name="awp_testimonials_plugintemplatelayout" id="awp_testimonials_plugintemplatelayout" <?php if($awp_testimonials_settings['template_type'] == 'theme_template' ) echo 'style="display: none;"'; ?> >
	                                                 <?php
	                                                 foreach (array_keys($awp_tst_plugintemplates) as $template) :
	                                                 ?>
	                                				<option value="<?php echo $awp_tst_plugintemplates[$template] ?>" <?php selected($awp_testimonials_settings['template_layout'],$awp_tst_plugintemplates[$template]); ?> >
	                                        		<?php echo $template ?>
	                                         		</option>
	                            					<?php endforeach; ?>
	                                        </select> 
	                                        
	                                        <select name="awp_testimonials_themetemplatelayout" id="awp_testimonials_themetemplatelayout" <?php if($awp_testimonials_settings['template_type'] != 'theme_template' ) echo 'style="display: none;"'; ?> >
	                                              <?php foreach (array_keys($awp_tst_themetemplates) as $template) : ?>
	                                              	<option value="<?php echo $awp_tst_themetemplates[$template] ?>" <?php selected($awp_testimonials_settings['template_layout'],$awp_tst_themetemplates[$template]); ?>  > <?php echo $template ?> </option>
	                                              <?php endforeach; ?>
	                                        </select>
	                                         </td>
	                                     </tr>
	                                     <tr><td><?php _e('Order','apptivo-businesssite'); ?></td><td>
	                                             <select  name="order">
	                                                 <option value="1" <?php selected('1', $awp_testimonials_settings['order']); ?> >Newest First</option>
	                                                 <option value="2" <?php selected('2', $awp_testimonials_settings['order']); ?> >Oldest First</option>
	                                                 <option value="3" <?php selected('3', $awp_testimonials_settings['order']); ?> >Random Order</option>
	                                                 <option value="4" <?php selected('4', $awp_testimonials_settings['order']); ?> >Custom Order</option>
	                                             </select>
	                                         </td></tr>

                                <tr><td valign="top"><?php _e('Custom CSS','apptivo-businesssite'); ?></td><td>
                                <textarea name="custom_css" cols="30" rows="5"><?php echo $awp_testimonials_settings['custom_css']; ?></textarea>
                                <span style="margin:10px;">*Developers Guide - <a href="<?php echo awp_developerguide('testimonilas-fullview-customcss');?>" target="_blank">Testimonials Fullview CSS.</a></span>
                                </td></tr>
	                                         <tr><td></td><td><input type="submit" value="<?php _e('Save Settings','apptivo-businesssite'); ?>" name="full_view_settings" class="button-primary" /></td></tr>
                        </tbody>
                    </table>
	
	        </form>
	       
	        <?php
	}

	
	//Save Inline View settings
	function save_inline_settings()
	{
            if ($_POST['awp_testimonials_templatetype'] == "awp_plugin_template") :
	            $testimonial_layout = $_POST['awp_testimonials_plugintemplatelayout'];
	        else:
	            $testimonial_layout = $_POST['awp_testimonials_themetemplatelayout'];
	        endif;    
		     //Inline Testimonials items to show.
	         $inline_testimonials_itemtoshow = $_POST['itemstoshow'];
	         if(!is_numeric($_POST['itemstoshow']) || $_POST['itemstoshow'] <= 0 ):
	         	$inline_testimonials_itemtoshow =   AWP_DEFAULT_ITEM_SHOW;
	         endif; 
	         
	        $awp_testimonials_inline_settings = array(
                            'template_type' => $_POST['awp_testimonials_templatetype'],
                            'template_layout' => $testimonial_layout,
	                        'style' => $_POST['style'],
	                     	'custom_css' => stripslashes($_POST['custom_css']),
	                     	'order' => $_POST['order'],
	                     	'itemstoshow' => $inline_testimonials_itemtoshow,
	                     	'more_text' => (trim($_POST['more_text'])!="")?$_POST['more_text']:AWP_DEFAULT_MORE_TEXT,
	                     	'page_ID' => $_POST['page_ID'],
	                     	);
	       
	        update_option('awp_testimonials_inline_settings', $awp_testimonials_inline_settings);
	}
	//Save Testomonials Settings
	function save_testimonials_Settings() {
	        if ($_POST['awp_testimonials_templatetype'] == "awp_plugin_template") :
	            $testimonial_layout = $_POST['awp_testimonials_plugintemplatelayout'];
	        else :
	            $testimonial_layout = $_POST['awp_testimonials_themetemplatelayout'];
	        endif;    
	        $awp_testimonials_settings = array(
	            'template_type' => $_POST['awp_testimonials_templatetype'],
	            'template_layout' => $testimonial_layout,
	            'custom_css' => stripslashes($_POST['custom_css']),
	            'order' => $_POST['order'],
	            'page_ID' => $_POST['page_ID'],
	            'itemsperpage' => (!empty($_POST['itemsperpage'])) ? $_POST['itemsperpage'] : 5
	        );
	       
	        update_option('awp_testimonials_settings', $awp_testimonials_settings);
            
	}
	
	
	//Testimonials Form
	function testimonials_form(){
	        ?>
	        <div class="wrap">
            <h2>Add Testimonials</h2>
            <div class="testimonilas_err"></div>
            <?php $nogdog = uniqid();$_SESSION['apptivo_single_testimonials'] = $nogdog; ?>
	        <form method="post" action="/wp-admin/admin.php?page=awp_testimonials" name="awp_testimonials_form" id="awp_testimonials_form"  onsubmit="return validatetestimonialsforms()" >
	        <input type="hidden" name="nogdog" value="<?php echo $nogdog;?>" >
	            <table class="form-table" width="700" cellspacing="0" cellpadding="0">
	                                    <tr>
	                                        <td><?php _e('Name','apptivo-businesssite'); ?>&nbsp;<span style="color:#f00;">*</span></td>
	                                        <td><input type="text" name="awp_testimonials_name" id="awp_testimonials_name" value="" size="43"/></td>
	                                    </tr>
	                                    <tr>
	                                        <td><?php _e('Job Title','apptivo-businesssite'); ?></td>
	                                        <td><input type="text" name="awp_testimonials_jobtitle" id="awp_testimonials_jobtitle" value="" size="43"/></td>
	                                    </tr>
	                                    <tr>
	                                        <td><?php _e('Company','apptivo-businesssite'); ?></td>
	                                        <td><input type="text" name="awp_testimonials_company" id="awp_testimonials_company" value="" size="43"/></td>
	                                    </tr>
	                                    <tr>
	                                        <td><?php _e('Website','apptivo-businesssite'); ?></td>
	                                        <td><input type="text" name="awp_testimonials_website" id="awp_testimonials_website" value="" size="43"/>&nbsp;&nbsp;<small>(For ex: http://www.example.com/)<small></td>
	                                    </tr>
	                                    <tr>
	                                        <td><?php _e('Email','apptivo-businesssite'); ?></td>
	                                        <td><input type="text" name="awp_testimonials_email" id="awp_testimonials_email" value="" size="43"/></td>
	                                    </tr>
	                                    
	                                   
	                                <tr valign="top">
									<th scope="row"><?php _e('Image URL','apptivo-businesssite'); ?></th>
									<td><label for="upload_image">
									<input id="awp_testimonials_imageurl" type="text" size="43" name="awp_testimonials_imageurl" value="" />
									<input id="testimonials_upload_images" type="button" value="Upload Image" />
									<br /><?php _e('Enter an URL or upload an image.','apptivo-businesssite'); ?>
									</label></td>
									</tr>
									
	                                    <tr>
	                                        <td valign="top"style="padding-bottom:10px;"><?php _e('Testimonials','apptivo-businesssite'); ?>&nbsp;<span style="color:#f00;">*</span></td>
	                                        <td>
	                                        <div style="width:630px;">
	                                        <?php the_editor('','awp_testimonials_cnt','',FALSE); ?>
	                                        </div>
	                                        </td>
	                                        
	                                    </tr>
	                                    <tr>
	                                        <td><?php _e('Order To Show','apptivo-businesssite'); ?></td>
	                                        <td><input type="text" name="awp_testimonials_order" id="awp_testimonials_order" value="" size="3" /></td>
	                                    </tr>
	                                    <tr>
	                                        <td></td>
	                                        <td><input type="submit" value="<?php _e('Add Testimonials','apptivo-businesssite'); ?>" name="awp_testimonial_add" class="button-primary"/></td>
	                                    </tr>
	
	                                </table>
	
	        </form>
	        </div>
	            <?php
	}
	//Edit Testimonials Form
	function edit_testimonials($all_awp_testimonials){
	        
            ?>
	        <div class="wrap">
	        <h2><?php _e('Edit Testimonials','apptivo-businesssite'); ?></h2><div class="testimonilas_err"></div>
	        <form method="post" action="/wp-admin/admin.php?page=awp_testimonials" name="awp_testimonials_form" onsubmit="return validatetestimonialsforms()">
	            <table class="form-table" width="700" cellspacing="0" cellpadding="0">
	                                    <tr>
	                                        <td><?php _e('Name','apptivo-businesssite'); ?> &nbsp;<span style="color:#f00;">*</span></td>
	                                        <td><input type="text" name="awp_testimonials_name" id="awp_testimonials_name" value="<?php echo $all_awp_testimonials->account->accountName; ?>" size="43"/></td>
	                                    </tr>
	                                    <tr>
	                                        <td><?php _e('Job Title','apptivo-businesssite'); ?></td>
	                                        <td><input type="text" name="awp_testimonials_jobtitle" id="awp_testimonials_jobtitle" value="<?php echo $all_awp_testimonials->contact->jobTitle; ?>" size="43"/></td>
	                                    </tr>
	                                    <tr>
	                                        <td><?php _e('Company','apptivo-businesssite'); ?></td>
	                                        <td><input type="text" name="awp_testimonials_company" id="awp_testimonials_company" value="<?php echo $all_awp_testimonials->contact->companyName; ?>" size="43"/></td>
	                                    </tr>
	                                    <tr>
	                                        <td><?php _e('Website','apptivo-businesssite'); ?></td>
	                                        <td><input type="text" name="awp_testimonials_website" id="awp_testimonials_website" value="<?php echo $all_awp_testimonials->account->website; ?>" size="43"/> &nbsp;&nbsp;<small>(For ex: http://www.example.com/)<small></td>
	                                    </tr>
	                                    <tr>
	                                        <td><?php _e('Email','apptivo-businesssite'); ?></td>
	                                        <td><input type="text" name="awp_testimonials_email" id="awp_testimonials_email" value="<?php echo $all_awp_testimonials->email; ?>" size="43"/></td>
	                                    </tr>
	                                 <tr valign="top">
									<th scope="row"><?php _e('Image URL','apptivo-businesssite'); ?></th>
									<td><label for="upload_image">
									<input id="awp_testimonials_imageurl" type="text" size="43" name="awp_testimonials_imageurl" value="<?php echo $all_awp_testimonials->testimonialImageUrl; ?>" />
									<input id="testimonials_upload_images" type="button" value="Upload Image" />
									<br /><?php _e('Enter an URL or upload an image.','apptivo-businesssite'); ?>
									</label></td>
									</tr>
									
	                                    <tr>
	                                        <td valign="top"style="padding-bottom:10px;"><?php _e('Testimonials','apptivo-businesssite'); ?>&nbsp;<span style="color:#f00;">*</span></td>
	                                        <td>
	                                    <div style="width:630px;">  
	                                    <?php  
                                        $updated_value = $all_awp_testimonials->testimonial;
                                        the_editor($updated_value,'awp_testimonials_cnt','',FALSE); ?>
                                        </div>
                                        </td>
	                                    </tr>
	                                    <tr>
	                                        <td><?php _e('Order To Show','apptivo-businesssite'); ?></td>
	                                        <td><input type="text" name="awp_testimonials_order" id="awp_testimonials_order" value="<?php echo $all_awp_testimonials->sequenceNumber; ?>" size="3" /></td>
	                                    </tr>
	                                    <tr>
	                                        <td></td>
	                                        <td><input type="hidden" name="awp_tstid" value="<?php echo $all_awp_testimonials->siteTestimonialId;?>"/>
                                                    <input type="hidden" name="awp_tst_accountId" value="<?php echo $all_awp_testimonials->account->accountId;?>"/>
                                                    <input type="hidden" name="awp_tst_contactId" value="<?php echo $all_awp_testimonials->contact->contactId;?>"/>
                                                    <input type="submit" value="Update" name="awp_testimonial_update" class="button-primary"/></td>
	                                    </tr>
	
	            </table>
	        </form>
	        </div>
	        <?php
	}
}


/**
 * To Add testimonials.
 *
 * createTestimonial(String siteKey, Testimonial testimonial, List<DocumentDetails> imageDetails)
 */
function addTestimonials($account, $accountId, $company, $contact, $contactId, $creationDate, $email, $firmId, $images, $jobTitle, $name, $returnStatus, $sequenceNumber, $siteTestimonialId, $testimonial, $testimonialImageUrl, $testimonialStatus, $website)
{
  $mktg_testimonials = new AWP_MktTestimonial($account, $accountId, $company, $contact, $contactId, $creationDate, $email, $firmId, $images, $jobTitle, $name, $returnStatus, $sequenceNumber, $siteTestimonialId, $testimonial, $testimonialImageUrl, $testimonialStatus, $website);
  $params = array (
                "arg0" => APPTIVO_BUSINESS_API_KEY,
  		        "arg1" => APPTIVO_BUSINESS_ACCESS_KEY,
                "arg2" => $mktg_testimonials,
                "arg3" => null
                );
    $response = getsoapCall(APPTIVO_BUSINESS_SERVICES,'createTestimonial',$params);
    return $response;
	
}
/**
 * @method getAllTestimonials
 * @return <type>
 */
function getAllTestimonials()
{
	     $pubdate_params = array ( 
                "arg0" => APPTIVO_BUSINESS_API_KEY,
                "arg1" => APPTIVO_BUSINESS_ACCESS_KEY
	            );
	      $plugin_params = array ( 
               "arg0" => APPTIVO_BUSINESS_API_KEY,
			   "arg1" => APPTIVO_BUSINESS_ACCESS_KEY,
               "arg2" => null
                );
          $response = get_data(APPTIVO_BUSINESS_SERVICES,'-testimonials-publisheddate','-testimonials-data','getLastPublishDate','getAllTestimonials',$pubdate_params,$plugin_params);
          return $response->return;
}
/**
 * @method getAllTestimonials
 * @return <type>
 */
function getTestimonialByTestimonialId($awp_tstid)
{
	$params = array (
                "arg0" => APPTIVO_BUSINESS_API_KEY,
				"arg1" => APPTIVO_BUSINESS_ACCESS_KEY,
                "arg2" => $awp_tstid
                );
    $response = getsoapCall(APPTIVO_BUSINESS_SERVICES,'getTestimonialByTestimonialId',$params);
    return $response->return;
}
/**
 * Enter description here...
 *
 * @param unknown_type $awp_tstid
 * @return unknown
 */
function deleteTestimonialByTestimonialId($awp_tstid)
{
	$params = array (
                "arg0" => APPTIVO_BUSINESS_API_KEY,
				"arg1" => APPTIVO_BUSINESS_ACCESS_KEY,
                "arg2" => $awp_tstid
                );
    $response = getsoapCall(APPTIVO_BUSINESS_SERVICES,'deleteTestimonialByTestimonialId',$params);
    return $response;
}
/**
 * To Update Testimnails.
 *
 */
function updateTestimonials($account, $accountId, $company, $contact, $contactId, $creationDate, $email, $firmId, $images, $jobTitle, $name, $returnStatus, $sequenceNumber, $siteTestimonialId, $testimonial, $testimonialImageUrl, $testimonialStatus, $website)
{
  $mktg_testimonials = new AWP_MktTestimonial($account, $accountId, $company, $contact, $contactId, $creationDate, $email, $firmId, $images, $jobTitle, $name, $returnStatus, $sequenceNumber, $siteTestimonialId, $testimonial, $testimonialImageUrl, $testimonialStatus, $website);
  $params = array (
                "arg0" => APPTIVO_BUSINESS_API_KEY,
  				"arg1" => APPTIVO_BUSINESS_ACCESS_KEY,
                "arg2" => $mktg_testimonials,
                "arg3" => null
                );
  $response = getsoapCall(APPTIVO_BUSINESS_SERVICES,'updateTestimonial',$params);
  return $response;
}