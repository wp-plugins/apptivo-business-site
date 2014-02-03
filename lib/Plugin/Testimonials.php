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
class AWP_Testimonials extends AWP_Base {

    var $_plugin_activated = false;

    /**
     * PHP5 constructor
     */
    function __construct() {
        $settings = array();
        $this->_plugin_activated = false;
        $settings = get_option("awp_plugins");
        if (get_option("awp_plugins") !== "false") {
            if ($settings["testimonials"])
                $this->_plugin_activated = true;
        }
    }

    /**
     * Returns plugin instance
     *
     * @return AIP_Plugin_BrowserCache
     */
    function &instance() {
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
    function run() {
        if ($this->_plugin_activated) {
            add_action('widgets_init', array(&$this, 'register_widget'));
            add_shortcode('apptivo_testimonials_fullview', array(&$this, 'show_testimonials_fullview'));
            add_shortcode('apptivo_testimonials_inline', array(&$this, 'show_testimonials_inline'));
            add_shortcode('apptivo_testimonials_form', array(&$this, 'testimonialform'));
        }
        add_action('the_posts', array(&$this, 'check_for_shortcode'));
    }

    function check_for_shortcode($posts) {
        $testimonial_fullView = awp_check_for_shortcode($posts, '[apptivo_testimonials_fullview');
        $testimonial_inlineView = awp_check_for_shortcode($posts, '[apptivo_testimonials_inline');
        $testimonial_form = awp_check_for_shortcode($posts, '[apptivo_testimonials_form');
        if ($testimonial_inlineView) {
            // load styles and scripts
            $this->loadscripts();
        } else if ($testimonial_form) {
            
            $this->testscripts();
        }
        return $posts;
    }

   
    /**
     * Load the JS files
     */
   function loadscripts()
	{
		wp_enqueue_script('jquery_cycleslider.js',AWP_PLUGIN_BASEURL. '/assets/js/jquery.cycle.all.latest.js',array('jquery'));
        }
    function testscripts() {
      wp_enqueue_script('jquery_validation', AWP_PLUGIN_BASEURL . '/assets/js/validator-min.js', array('jquery'));
    }
    function teststyles_uploadify() {
		wp_enqueue_style('style_uploadify', AWP_PLUGIN_BASEURL.'/inc/jobs/files/uploadify/uploadify.css' , false, '1.0.0', 'screen');

	}
/* Form Setting */

function get_master_fields()
	{
        $fields = array(
            array('fieldid' => 'name', 'fieldname' => 'Name', 'defaulttext' => 'First Name', 'showorder' => '1', 'validation' => 'text', 'fieldtype' => 'text'),
            array('fieldid' => 'email', 'fieldname' => 'Email', 'defaulttext' => 'Email', 'showorder' => '2', 'validation' => 'email', 'fieldtype' => 'text'),
            array('fieldid' => 'captcha', 'fieldname' => 'Captcha', 'defaulttext' => 'Captcha', 'showorder' => '8', 'validation' => 'text', 'fieldtype' => 'captcha'),
            array('fieldid' => 'jobtitle', 'fieldname' => 'Job Title', 'defaulttext' => 'Job Title', 'showorder' => '3', 'validation' => 'text', 'fieldtype' => 'text'),
            array('fieldid' => 'company', 'fieldname' => 'Company', 'defaulttext' => 'Company', 'showorder' => '4', 'validation' => 'text', 'fieldtype' => 'text'),
            array('fieldid' => 'website', 'fieldname' => 'Website', 'defaulttext' => 'Website', 'showorder' => '5', 'validation' => 'text', 'fieldtype' => 'text'),
            //array('fieldid' => 'upload', 'fieldname' => 'Upload File', 'defaulttext' => 'Upload File', 'showorder' => '6', 'validation' => 'textarea', 'fieldtype' => 'upload'),
            array('fieldid' => 'imageurl', 'fieldname' => 'Image Url', 'defaulttext' => 'Enter Image Url', 'showorder' => '6', 'validation' => 'text', 'fieldtype' => 'text'),
            array('fieldid' => 'comments', 'fieldname' => 'Testimonial', 'defaulttext' => 'Testimonial', 'showorder' => '7', 'validation' => 'textarea', 'fieldtype' => 'textarea')
            
                /*  array('fieldid' => 'customfield1','fieldname' => 'Custom Field 1','defaulttext' => 'Custom Field1','showorder' => '18','validation' => '','fieldtype' => 'select'),
                  array('fieldid' => 'customfield2','fieldname' => 'Custom Field 2','defaulttext' => 'Custom Field2','showorder' => '19','validation' => '','fieldtype' => 'select'),
                  array('fieldid' => 'customfield3','fieldname' => 'Custom Field 3','defaulttext' => 'Custom Field3','showorder' => '20','validation' => '','fieldtype' => 'select'),
                  array('fieldid' => 'customfield4','fieldname' => 'Custom Field 4','defaulttext' => 'Custom Field4','showorder' => '21','validation' => '','fieldtype' => 'radio'),
                  array('fieldid' => 'customfield5','fieldname' => 'Custom Field 5','defaulttext' => 'Custom Field5','showorder' => '22','validation' => '','fieldtype' => 'checkbox') */
        );

        //For Additional custom fields.
        $addtional_custom = get_option('awp_addtional_custom_testimonialform');
        if (!empty($addtional_custom)):
            $fields = array_merge($fields, $addtional_custom);
        endif;

        return $fields;
    }
/**
	 * Retrieve list of validations supported by Apptivo hrjobs Form
	 *
	 */
	function get_master_validations(){
		$validations = array(
		array('validationLabel' => 'None','validation' => 'none'),
		array('validationLabel' => 'Email ID','validation' => 'email'),
		array('validationLabel' => 'Number','validation' => 'number')
		);
		return $validations;
	}
	/**
	 *
	 * * Retrieve list of Field Types supported by Apptivo hrjobs Form
	 */
	function get_master_fieldtypes(){
		$fieldtypes = array(
		array('fieldtypeLabel' => 'Checkbox','fieldtype' => 'checkbox'),
		array('fieldtypeLabel' => 'Radio Option','fieldtype' => 'radio'),
		array('fieldtypeLabel' => 'Select','fieldtype' => 'select'),
		array('fieldtypeLabel' => 'Textbox','fieldtype' => 'text'),
		array('fieldtypeLabel' => 'Textarea','fieldtype' => 'textarea')
		);
		return $fieldtypes;
	}

	function get_master_fieldtypes_testimonial(){
		$fieldtypes = array(
		array('fieldtypeLabel' => 'Checkbox','fieldtype' => 'checkbox'),
		array('fieldtypeLabel' => 'Select','fieldtype' => 'select')
		);
		return $fieldtypes;
	}


	/**
	 * return array of plugin templates available with Template name and template file name
	 */


	function get_apptivo_template_data( $apptivo_template_file,$template_filename)
	{

		$test = array();
		$default_headers = array(
		'Apptivo Template Name' => 'Apptivo Template Name',
		'Version' => 'Version',
		'Description' => 'Description',
		'Author' => 'Author',
		);

		$plugin_data = get_file_data( $apptivo_template_file, $default_headers, '' );
		if(strlen(trim($plugin_data['Apptivo Template Name'])) != 0 )
		{

			$test[$plugin_data['Apptivo Template Name']] = $template_filename;

		}

	}
function createformfield_array($fieldid,$showtext,$required,$type,$validation,$options,$displayorder){


	   $displayorder = (trim($displayorder)=="")?0:trim($displayorder);

	   $options = (is_array($options))?$options:stripslashes(str_replace( array('"'), '', strip_tags($options)));

		if( trim($type) != 'text' && trim($type) != 'textarea')
		{
			$pos = strpos(trim($fieldid), 'customfield');
			if( $pos !== false )
			{
				if( !is_array($options) && trim($options) == '')
				{
					return '';
				}
			}
		}


		$testimonialformfield= array(
	            'fieldid'=>$fieldid,
                'showtext' => stripslashes(str_replace( array('"'), '', strip_tags($showtext))),
	            'required' => $required,
				'type' => $type,
				'validation' => $validation,
				'options' => $options,
	   			'order' => $displayorder
		);
		return $testimonialformfield;
	}

        /**
	 * Get hrjobs form settings by form name to render in Admin
	 */
	function get_settings($formname,$type){
		$formExists="";
		$testimonial_forms=array();
		$testimonialform=array();
		$formname=trim($formname);
		if( $type == 'testimonialform')
		{
			$testimonial_forms=get_option('awp_testimonialforms');
		}
                if($formname=="")
		$formExists="";
		else if(!empty($testimonial_forms))
		$formExists = awp_recursive_array_search($testimonial_forms,$formname,'name' );

		if(trim($formExists)!=="" ){
			$testimonialform=$testimonial_forms[$formExists];
		}
		return $testimonialform;
	}
        function formsetting()
        {
            	
            	$updatemessage="";
		/*if( $Results->numResults != 0) { */

		$testimonial_forms=array();
		$testimonialformdetails=array();
		$testimonial_forms=get_option('awp_testimonialforms');
		if(empty($testimonial_forms))
		{
			$testimonial_array =array("name"=>'testimonialform');
			$testimonialform=array($testimonial_array);

			update_option('awp_testimonialforms',$testimonialform);
			$testimonial_forms=get_option('awp_testimonialforms');

		}

		/*
		 * Saving selected form settings
		 */

		if(isset($_POST['awp_testimonialform_settings'])){
			$templatelayout="";
			$newformname=$_POST['awp_testimonialform_name'];
                        $submit_type=$_POST['awp_testimonialform_option_submit_type'];
                        $submit_val=$_POST['awp_testimonialform_button_val'];
                       if($_POST['awp_testimonialform_templatetype']=="awp_plugin_template")
			$templatelayout=$_POST['awp_testimonialform_plugintemplatelayout'];
			else
			$templatelayout=$_POST['awp_testimonialform_themetemplatelayout'];
			$testimonialformproperties=array(
							'tmpltype' =>$_POST['awp_testimonialform_templatetype'],
	                        'layout' =>$templatelayout,
                                'tmpl_button_type' => $submit_type,
                                'tmpl_button_val'  => $submit_val,
	                        'confmsg' => stripslashes($_POST['awp_testimonialform_confirmationmsg']),
	                        'css' => stripslashes($_POST['awp_testimonialform_customcss']),
                            'subscribe_option' => $_POST['subscribe_option'],
                            'submit_button_type' => $_POST['awp_testimonialform_submit_type'],
                            'submit_button_val' => $_POST['awp_testimonialform_submit_val'],
			                'testimonialform_page'  => $_POST['awp_testimonialform_page'] );
                        //New custom fields
			$stack = array();
			$addtional_custom = array();
			$addtional_order = 23;
                        for($i=6;$i<200;$i++)
			{
                        	if(isset($_POST['customfield'.$i.'_newest']) )
				{
					$addtional_custom = array('fieldid' => 'customfield'.$i.'','fieldname' => 'Custom Field '.$i.'',
					                          'defaulttext' => 'Custom Field'.$i.'','showorder' => $addtional_order,'validation' => '',
					                          'fieldtype' => 'select');
                                        $addtional_order++;
					array_push($stack, $addtional_custom);

				}else {
					break;
				}
			}

			if(!empty($stack)) :
			update_option('awp_addtional_custom_testimonialform',$stack);
			endif;

			//General fields
			$testimonialformfields=array();
			foreach( $this->get_master_fields() as $fieldsmasterproperties )
			{
                            $enabled=0;
				$testimonialformfield=array();
				$fieldid=$fieldsmasterproperties['fieldid'];
				if(!empty ($_POST[$fieldid.'_order'])){
					$displayorder = $_POST[$fieldid.'_order'];
				}
				else{
					$displayorder = $fieldsmasterproperties['showorder'];
				}
				if(!empty ($_POST[$fieldid.'_text'])){
					$displaytext = $_POST[$fieldid.'_text'];
				}
				else{
					$displaytext = $fieldsmasterproperties['defaulttext'];
				}
				if($fieldid=='lastname' || $fieldid=='email' || $fieldid=='firstname' || $fieldid=='country' )
				{
					$enabled = 1;
					$required = 1;
				}
				else
				{
					$enabled = $_POST[$fieldid.'_show'];
					$required = $_POST[$fieldid.'_require'];
				}
				if($enabled){
					$testimonialformfield=$this->createformfield_array($fieldid,$displaytext,$required,$_POST[$fieldid.'_type'],$_POST[$fieldid.'_validation'],$_POST[$fieldid.'_options'],$displayorder);
					array_push($testimonialformfields, $testimonialformfield);
				}
                                
			}
			//usort($testimonialformfields, "awp_sort_by_order");
			if(!empty($testimonialformfields)){
				$newtestimonialformdetails=array('name'=>$newformname,'properties'=>$testimonialformproperties,'fields'=>$testimonialformfields);
				$formExists="";
				if(!empty($testimonial_forms))
				$formExists = awp_recursive_array_search($testimonial_forms,$newformname,'name' );
                            	if(trim($formExists)!=="" ){

					unset($testimonial_forms[$formExists]);
					array_push($testimonial_forms, $newtestimonialformdetails);
					sort($testimonial_forms);
                                        update_option('awp_testimonialforms',$testimonial_forms);
					$testimonial_forms=get_option('awp_testimonialforms');
                                        $updatemessage= "Testimonial Form '".$newformname."' settings updated. Use Short code '[apptivo_testimonials_form name=\"".$newformname."\"]' in your page to use this form.";
				}

			}else{
				$updatemessage="<span style='color:red;'>Select atleast one Form field for jobs Form.</span>";
			}
			$selectedtestimonialform=$newformname;
                }

		// Now display the settings editing screen

		echo '<div class="wrap">';

		//if updatemessage is not empty display the div
		if(trim($updatemessage)!=""){
			?>
<div id="message" style="width: 80%;" class="updated">
<p><?php echo $updatemessage;?></p>
</div>
			<?php }

			if(!empty($testimonial_forms)){
				//Template Files
				$themetemplates = get_awpTemplates(AWP_TESTIMONIALS_FORM_TEMPLATEPATH,'Plugin'); //Testimonial applicant theme template
				$plugintemplates=$this->get_plugin_templates(AWP_TESTIMONIALS_FORM_TEMPLATEPATH); //Testimonial form plugin templates
				?>
<br>
				<?php
				if(trim($selectedtestimonialform)==""){
					$selectedtestimonialform=$testimonial_forms[0][name];
				}
				$testimonialformdetails=$this->get_settings($selectedtestimonialform,'testimonialform');
				if(count($testimonialformdetails)>0){
					$selectedtestimonialform=$testimonialformdetails[name];
					$fields=$testimonialformdetails[fields];
					$formproperties=$testimonialformdetails[properties];
				}
				?>
				<?php

				if(!empty($formproperties)) { ?>
<table class="form-table">
	<tbody>
		<tr valign="top">
			<th valign="top"><label for="awp_testimonialform_select_form"><?php _e("Testimonials Form", 'apptivo-businesssite' ); ?>:</label>
			</th>
			<td valign="top"><input style="width: 350px;"
				name="awp_testimonialform_select_form" id="awp_testimonialform_select_form"
				type="text" readonly="readonly"
				value=<?php echo $selectedtestimonialform; ?> /></td>
		</tr>
		<tr valign="top">
			<th valign="top"><label for="awp_customform_shortcode"><?php _e("Form Shortcode", 'apptivo-businesssite' ); ?>:</label>
			<br>
			<span class="description"><?php _e("Copy and Paste this short code in your page to display the Testimonial applicant form.", 'apptivo-businesssite' ); ?></span>
			</th>
			<td valign="top"><span id="awp_customform_shortcode"
				name="awp_customform_shortcode"> <input style="width: 350px;"
				type="text" readonly="readonly" id="testimonialform_shortcode"
				name="testimonialform_shortcode"
				value='[apptivo_testimonials_form name="<?php echo $selectedtestimonialform?>"]' />
			</span> 
		</tr>
	</tbody>
</table>
<?php }  ?>
<form name="awp_testimonial_settings_form" method="post" action="">
<table class="form-table">
	<tbody>
               
                <tr valign="top">
			<th valign="top"><label for="awp_testimonialform_templatetype"><?php _e("Template Type", 'apptivo-businesssite' ); ?>:</label>
			</th>
			<td valign="top"><input type="hidden" id="awp_testimonialform_name"
				name="awp_testimonialform_name" value="<?php echo $selectedtestimonialform;?>">
			<select name="awp_testimonialform_templatetype"
				id="awp_testimonialform_templatetype"
				onchange="japplicant_change_template();">
				<option value="awp_plugin_template"
				<?php selected($formproperties[tmpltype],'awp_plugin_template'); ?>>
					<?php _e("Plugin Templates", 'apptivo-businesssite' ); ?></option>
					<?php if(!empty($themetemplates)) : ?>
			<!--	<option value="theme_template"
				<?php selected($formproperties[tmpltype],'theme_template'); ?>><?php// _e("Templates from Current Theme", 'apptivo-businesssite' ); ?></option> -->
				<?php endif; ?>
			</select> <span style="margin: 10px;">*Developers Guide - <a
				href="<?php echo awp_developerguide('testimonilas');?>"
				target="_blank">Testimonials Form Templates.</a></span></td>
		</tr>
		<tr valign="top">
			<th valign="top"><label for="awp_testimonialform_templatelayout"><?php _e("Template Layout", 'apptivo-businesssite' ); ?>:</label>
			<br>
			<span class="description"><?php _e("Selecting Theme template which doesnt support Testimonials form structure will wont show the Testimonial form in webpage.", 'apptivo-businesssite' ); ?></span>
			</th>
			<td valign="top"><select name="awp_testimonialform_plugintemplatelayout"
				id="awp_testimonialform_plugintemplatelayout" onchange="addTestimonials();"
				<?php if($formproperties['tmpltype'] == 'theme_template' ) echo 'style="display: none;"'; ?>>
				<?php foreach (array_keys( $plugintemplates ) as $template ) { 
                                    ?>
                                <option value="<?php echo $plugintemplates[$template]?>"
				<?php selected($formproperties[layout],$plugintemplates[$template]); ?>>
					<?php echo $template; ?></option>
					<?php }?>
			</select> <select name="awp_testimonialform_themetemplatelayout"
				id="awp_testimonialform_themetemplatelayout"
				<?php if($formproperties['tmpltype'] != 'theme_template' ) echo 'style="display: none;"'; ?>>
				<?php  foreach (array_keys( $themetemplates ) as $template ) {
                                    ?>
                            <option value="<?php echo $themetemplates[$template]?>"
				<?php selected($formproperties[layout],$themetemplates[$template]); ?>>
					<?php echo $template; ?></option>
					<?php }?>
			</select></td>
		</tr>
                 <tr class="testimonials_button_option">
			<th><label id="awp_testimonialform_option_submit_type"
				for="awp_testimonialform_option_submit_type"><?php _e("Testimonials form enable Button type", 'apptivo-businesssite' ); ?>:</label>
			<br>
			<span valign="top" class="description"></span></th>

			<td valign="top"><input type="radio" value="submit"
				id="awp_testimonial_cant_btn" name="awp_testimonialform_option_submit_type"
				<?php checked('submit',$formproperties[tmpl_button_type]); ?>
				checked="checked" /> <label for="awp_testimonial_cant_btn">Button</label> <input
				type="radio" value="image" id="awp_testimonial_cant_img"
				name="awp_testimonialform_option_submit_type"
				<?php checked('image',$formproperties[tmpl_button_type]); ?> /> <label
				for="awp_testimonial_cant_img">Image</label></td>
		</tr>
                <tr class="testimonials_button">
			<th><label for="awp_testimonialform_button_val"
				id="awp_testimonialform_option_submit_value"><?php _e("Button Text", 'apptivo-businesssite' ); ?>:</label>
			<br>
			<span valign="top" class="description"></span></th>
			<td valign="top"><input type="text" name="awp_testimonialform_button_val"
				id="awp_testimonialform_option_submit_val"
				value="<?php echo $formproperties[tmpl_button_val];?>" size="52" />
                            <span id="testimonial_img_button">
                                <input  class="button-primary" id="testimonial_upload_image" type="button" value="Upload Image" onclick="uploadImage('awp_testimonialform_option_submit_val')"/>
                                <br />
				<?php _e('Enter an URL or upload an image.','apptivo-businesssite'); ?>
			</span>
                        </td>
                </tr>
        	<tr valign="top">
			<th valign="top"><label for="awp_testimonialform_confirmationmsg"><?php _e("Confirmation Message", 'apptivo-businesssite' ); ?>:</label>
			<br>
			<span class="description">This message will shown in your website
			page, once Testimonial submitted.</span></th>
			<td valign="top">
			<div style="width: 620px;"><?php the_editor($formproperties[confmsg],'awp_testimonialform_confirmationmsg','',FALSE);  ?>
			</div>
			</td>
		</tr>
		<tr valign="top">
			<th><label for="awp_testimonialform_customcss"><?php _e("Custom CSS", 'apptivo-businesssite' ); ?>:</label>
			<br>
			<span valign="top" class="description"><?php _e("Style class provided here will override template style. Please refer Apptivo plugin help section for class name to be used.", 'apptivo-businesssite' ); ?></span>
			</th>
			<td valign="top"><textarea name="awp_testimonialform_customcss"
				style="width: 350px;" id="awp_testimonialform_customcss" size="100"
				cols="40" rows="10"><?php echo $formproperties[css];?></textarea> <span
				style="margin: 10px;">*Developers Guide - <a
				href="<?php echo awp_developerguide('testimonials-basic-config');?>"
				target="_blank">Testimonial Form CSS.</a></span></td>
		</tr>
		<tr valign="top">
			<th><label id="awp_testimonialform_submit_type"
				for="awp_testimonialform_submit_type"><?php _e("Submit Button Type", 'apptivo-businesssite' ); ?>:</label>
			<br>
			<span valign="top" class="description"></span></th>

			<td valign="top"><input type="radio" value="submit"
				id="awp_testimonial_cant_btn" name="awp_testimonialform_submit_type"
				<?php checked('submit',$formproperties[submit_button_type]); ?>
				checked="checked" /> <label for="awp_testimonial_cant_btn">Button</label> <input
				type="radio" value="image" id="awp_testimonial_cant_img"
				name="awp_testimonialform_submit_type"
				<?php checked('image',$formproperties[submit_button_type]); ?> /> <label
				for="awp_testimonial_cant_img">Image</label></td>
		</tr>
		<tr valign="top">
			<th><label for="awp_testimonialform_submit_val"
				id="awp_testimonialform_submit_value"><?php _e("Button Text", 'apptivo-businesssite' ); ?>:</label>
			<br>
			<span valign="top" class="description"></span></th>
			<td valign="top"><input type="text" name="awp_testimonialform_submit_val"
				id="awp_testimonialform_submit_val"
				value="<?php echo $formproperties[submit_button_val];?>" size="52" />

			<span id="testimonialform_upload_img_button"> <input
				id="testimonialform_upload_image" class="button-primary" type="button" value="Upload Image" onclick="uploadImage('awp_testimonialform_submit_val')" /> <br />
				<?php _e('Enter an URL or upload an image.','apptivo-businesssite'); ?>
			</span></td>
		</tr>


	</tbody>
</table>

<br>
				<?php
				echo "<h3>" . __( 'Testimonial Form Fields', 'apptivo-businesssite' ) . "</h3>";?>
<div style="amrgin: 10px;"><span class="description"><?php _e("Select and configure list of fields from below table to show in your Testimonials form.", 'apptivo-businesssite' ); ?></span>
<span style="margin: 10px;">*Developers Guide - <a
	href="<?php echo awp_developerguide('testimonilas');?>"
	target="_blank">Basic Testimonial Form Config.</a></span></div>
<br>
<table width="900" cellspacing="0" cellpadding="0"
	id="hrjobs_form_fields" name="hrjobs_form_fields"
	style="border-collapse: collapse;">
	<tbody>
		<tr>
			<th></th>
		</tr>
		<tr align="center"
			style="background-color: rgb(223, 223, 223); font-weight: bold;"
			class="widefat">

			<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Field Name','apptivo-businesssite'); ?></td>
			<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Show','apptivo-businesssite'); ?></td>
			<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Require','apptivo-businesssite'); ?></td>
			<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Display Order','apptivo-businesssite'); ?></td>
			<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Display Text','apptivo-businesssite'); ?></td>
			<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Field Type','apptivo-businesssite'); ?></td>
			<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Validation Type','apptivo-businesssite'); ?></td>
			<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php _e('Option Values','apptivo-businesssite'); ?></td>
		</tr>
		<tr>
			<th></th>
		</tr>
		<?php
		$pos = 0;
		$index_key = 0;
		foreach( $this->get_master_fields() as $fieldsmasterproperties )
		{   $enabled=0;
		$fieldExists=array();
		$fieldid=$fieldsmasterproperties['fieldid'];
		$fieldExistFlag="";
		if(!empty($fields))
		{
			$fieldExistFlag= awp_recursive_array_search($fields, $fieldid, 'fieldid');
		}

		if(trim($fieldExistFlag)!=="")
		{
			$enabled=1;
			$fieldData=array("fieldid"=>$fieldid,
											"fieldname"=>$fieldsmasterproperties['fieldname'],
											"show"=>$enabled,
											"required"=>$fields[$fieldExistFlag]['required'],
											"showtext"=>$fields[$fieldExistFlag]['showtext'],
											"type"=>$fields[$fieldExistFlag]['type'],
											"validation"=>$fields[$fieldExistFlag]['validation'],
											"options"=>$fields[$fieldExistFlag]['options'],
											"order"=>$fields[$fieldExistFlag]['order']);
		}else{
			if($fieldid=='lastname' || $fieldid=='email' || $fieldid=='firstname' || $fieldid=='country')
			{
				$enabled =1;
				$required =1;

			}

			$fieldData=array("fieldid"=>$fieldid,
											"fieldname"=>$fieldsmasterproperties['fieldname'],
											"show"=>$enabled,
											"required"=>$required,
											"showtext"=>$fieldsmasterproperties['defaulttext'],
											"type"=>"",
											"validation"=>"",
											"options"=>"",
											"order"=>"");
		}
		$pos=strpos($fieldsmasterproperties['fieldid'], "customfield");
		?>
		<tr>
			<td
				style="border: 1px solid rgb(204, 204, 204); padding-left: 10px; width: 150px;"><?php echo $fieldData['fieldname']?>
			</td>
			<td align="center" style="border: 1px solid rgb(204, 204, 204);"><input
			<?php  if($enabled) { ?> checked="checked"
			<?php } if($fieldData['fieldid']=='lastname' || $fieldData['fieldid']=='email' || $fieldData['fieldid']=='firstname' || $fieldData['fieldid']=='country'){?>
				disabled="disabled" <?php } ?> type="checkbox"
				id="<?php echo $fieldData['fieldid']?>_show"
				name="<?php echo $fieldData['fieldid']?>_show" size="30"
				onclick="hrjobsform_enablefield('<?php echo $fieldData['fieldid']?>')">
				<?php if($index_key > 20 ) :?> <input type="hidden"
				id="<?php echo $fieldData['fieldid']?>_newest"
				name="<?php echo $fieldData['fieldid']?>_newest" value="" /> <?php endif; $index_key++; ?>
			</td>
			<td align="center" style="border: 1px solid rgb(204, 204, 204);"><input
			<?php
			if(!$enabled) { ?> disabled="disabled"
			<?php }
			else if($fieldData['required'] || $fieldData['fieldid']=='captcha' ) { ?> checked="checked"
			<?php }?> type="checkbox"
			<?php if($fieldData['fieldid']=='lastname' || $fieldData['fieldid']=='email'|| $fieldData['fieldid']=='firstname' || $fieldData['fieldid']=='captcha' ){?>
				disabled="disabled" <?php } ?>
				id="<?php echo $fieldData['fieldid']?>_require"
				name="<?php echo $fieldData['fieldid']?>_require" size="30"></td>
			<td align="center" style="border: 1px solid rgb(204, 204, 204);"><input
				type="text" onkeypress="return isNumberKey(event)"
				id="<?php echo $fieldData['fieldid']?>_order"
				name="<?php echo $fieldData['fieldid']?>_order"
				value="<?php echo $fieldData['order']; ?>" size="3" maxlength="2"
				<?php if(!$enabled) { ?> disabled="disabled" <?php } ?>></td>
			<td align="center" style="border: 1px solid rgb(204, 204, 204);"><input
			<?php if(!$enabled) { ?> disabled="disabled" <?php } ?> type="text"
				id="<?php echo $fieldData['fieldid']?>_text"
				name="<?php echo $fieldData['fieldid']?>_text"
				value="<?php echo $fieldData['showtext']; ?>"></td>

			<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php
			$name_postfix="type";
			if($pos===false){
				?> <input type="hidden" id="<?php echo $fieldData['fieldid']?>_type"
				name="<?php echo $fieldData['fieldid']?>_type"
				<?php if($fieldid=="upload")
				{ echo 'value="file"' ; } else if($fieldid=="country" || $fieldid == 'industry'){
					?>
				value="select"
				<?php }else if($fieldid=="comments" || $fieldid=="coverletter" || $fieldid=="Skills"){ ?>
				value="textarea" <?php }
                                else if($fieldid!="captcha"){?> value="text" <?php }
                                 else if($fieldid=="captcha"){  ?>
							value="captcha"
							<?php  }
                                                        ?>> <input
				<?php if(!$enabled) { ?> disabled="disabled" <?php } ?> size="6"
				readonly="readonly" type="text"
				id="<?php echo $fieldData['fieldid']?>_typehiddentext"
				name="<?php echo $fieldData['fieldid']?>_typehiddentext"
				<?php if($fieldid=="upload"){ echo 'value="File"'; }else if($fieldid=="country" || $fieldid == 'industry'){
					?>
				value="Select" <?php }else if($fieldid=="comments"){ ?>
				value="Textarea" <?php }else if($fieldid!="captcha"){ ?> value="Text box" <?php }
                                else if($fieldid=="captcha"){  ?>
							value="captcha"
							<?php  }
                                                        ?>><?php
				$name_postfix="type_select";
			}else{

				?> <select name="<?php echo $fieldData['fieldid']?>_type"
				id="<?php echo $fieldData['fieldid']?>_type"
				<?php

				if($pos===false) {?> readonly="readonly"
				<?php }
				if(!$enabled || ($pos===false)) { ?> disabled="disabled"
				<?php } ?>
				onChange="hrjobsform_showoptionstextarea('<?php echo $fieldData['fieldid']?>');">
				<?php foreach( $this->get_master_fieldtypes() as $masterfieldtypes )
				{ ?>

				<option value="<?php echo $masterfieldtypes['fieldtype'];?>"
				<?php if($masterfieldtypes['fieldtype']==$fieldData['type']){?>
					selected="selected" <?php }?>><?php echo $masterfieldtypes[fieldtypeLabel];?></option>
					<?php }?>

			</select> <?php }
			?>


			<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php  $pos=strpos($fieldsmasterproperties['fieldid'], "customfield");
			?> <?php if($pos===false){
				?><input type="hidden"
				id="<?php echo $fieldData['fieldid']?>_validation"
				name="<?php echo $fieldData['fieldid']?>_validation"
				<?php if($fieldid=="email"){
					?> value="email"
					<?php }else if($fieldid=="telephonenumber"){ ?> value="number"
					<?php }else{ ?> value="none" <?php }?>> <input
					<?php if(!$enabled) { ?> disabled="disabled" <?php } ?> size="6"
				readonly="readonly" type="text"
				id="<?php echo $fieldData['fieldid']?>_validationhidden"
				name="<?php echo $fieldData['fieldid']?>_validationhidden"
				<?php if($fieldid=="email"){
					?> value="Email Id"
					<?php }else if($fieldid=="telephonenumber"){ ?> value="Number"
					<?php }else{ ?> value="None" <?php }?>> <?php
			}
			else{
				?> <select name="<?php echo $fieldData['fieldid']?>_validation"
				id="<?php echo $fieldData['fieldid']?>_validation"
				<?php if(!$enabled){ ?> disabled="disabled"
				<?php }
				if($pos===false) {?> readonly="readonly" <?php }?>>
				<?php foreach( $this->get_master_validations() as $masterfieldtypes )
				{ ?>
				<option value="<?php echo $masterfieldtypes['validation'];?>"
				<?php if($masterfieldtypes['validation']==$fieldData['validation']){?>
					selected="selected" <?php }?>><?php echo $masterfieldtypes[validationLabel];?></option>
					<?php }?>
			</select> <?php } ?></td>

			<td align="center" style="border: 1px solid rgb(204, 204, 204);"><?php
			if($pos===false){
				if($fieldData['fieldid']!='industry')
				echo "N/A";
				//Not a custom field. Dont show any thing
			}
			if($fieldData['fieldid'] == 'industry')
			{
				$fieldData['options'] = ($fieldData['options']) ? $fieldData['options'] : array();
				?> <select id="<?php echo $fieldData['fieldid']?>_options"
				name="<?php echo $fieldData['fieldid']?>_options[]" size="4"
				multiple="multiple" style="height: 75px;">
				<?php $allIndustries = getAllIndustries();
				foreach($allIndustries as $industries)
				{
					?>
				<option
				<?php if( in_array($industries->industryId.'::'.$industries->industryName, $fieldData['options'])) { echo 'selected="selected"';}  ?>
					value="<?php echo $industries->industryId.'::'.$industries->industryName;?>"><?php echo $industries->industryName;?></option>
					<?php
				}?>

			</select> <?php } else if($fieldData['fieldid'] != 'country')
			{
				if(($fieldData['type']=="select")||($fieldData['type']=="radio")||($fieldData['type']=="checkbox")) {
					?> <textarea style="width: 190px;" <?php if(!$enabled){ ?>
				disabled="disabled" <?php } ?>
				id="<?php echo $fieldData['fieldid']?>_options"
				name="<?php echo $fieldData['fieldid']?>_options"><?php echo $fieldData['options']; ?></textarea>
				<?php }else {?> <textarea disabled="disabled"
				style="display: none; width: 190px;"
				id="<?php echo $fieldData['fieldid']?>_options"
				name="<?php echo $fieldData['fieldid']?>_options"></textarea> <?php }  ?>

			</td>
		</tr>
		<?php  }
		} ?>

	</tbody>
</table>
		<?php
		$addtional_custom = get_option('awp_addtional_custom_testimonialform');
		if(empty($addtional_custom))
		{
			$cnt_custom_filed = 1;
		}else {
			$cnt_custom_filed = 1 + count($addtional_custom);
		}
		?>
<p class="submit">
<input type="submit" name="awp_testimonialform_settings" id="awp_testimonialform_settings" class="button-primary" value="<?php esc_attr_e('Save Configuration') ?>" />
</p>
</form>

</div>
<?php
			}

	}
        /* Form Setting */
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
checkSoapextension("Testimonials");
checkCaptchaOption();
if( $_REQUEST['keys'] == 'fullviewsetting')
{
 $generalClass  = 'nav-tab';
 $fullviewsettingClass = 'nav-tab nav-tab-active';
 $inlineviewsettingClass = 'nav-tab';
 $formsetting   ='nav-tab';
}else if( $_REQUEST['keys'] == 'inlineviewsetting'){
 $generalClass = 'nav-tab';
 $fullviewsettingClass  = 'nav-tab';
 $inlineviewsettingClass = 'nav-tab nav-tab-active';
 $formsetting   ='nav-tab';
}
else if( $_REQUEST['keys'] == 'formsetting'){
 $generalClass = 'nav-tab';
 $fullviewsettingClass  = 'nav-tab';
 $inlineviewsettingClass = 'nav-tab';
 $formsetting   ='nav-tab nav-tab-active';
}
else {
 $generalClass = 'nav-tab nav-tab-active';
 $fullviewsettingClass  = 'nav-tab';
 $inlineviewsettingClass = 'nav-tab';
 $formsetting   ='nav-tab';
}
 ?> 
<div class="icon32" style="margin-top:10px;background: url('<?php echo awp_image('testimonials_icon'); ?>') " ><br></div>             
<h2 class="nav-tab-wrapper">
<a class="<?php echo $generalClass; ?>" href="<?php echo SITE_URL;?>/wp-admin/admin.php?page=awp_testimonials"><?php _e('Testimonials','apptivo-businesssite'); ?></a>
<a class="<?php echo $fullviewsettingClass; ?>" href="<?php echo SITE_URL;?>/wp-admin/admin.php?page=awp_testimonials&keys=fullviewsetting"><?php _e('Full View Settings','apptivo-businesssite'); ?></a>
<a class="<?php echo $inlineviewsettingClass; ?>" href="<?php echo SITE_URL;?>/wp-admin/admin.php?page=awp_testimonials&keys=inlineviewsetting"><?php _e('Inline View Settings','apptivo-businesssite'); ?></a>
<a class="<?php echo $formsetting; ?>" href="<?php echo SITE_URL;?>/wp-admin/admin.php?page=awp_testimonials&keys=formsetting"><?php _e('Testimonial Form Settings','apptivo-businesssite'); ?></a>
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
	    	echo "Testiomonials plugin is currently <span style='color:red'>disabled</span>. Please enable this in <a href='".SITE_URL."/wp-admin/admin.php?page=awp_general'>Apptivo General Settings</a>.";
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
            case formsetting:
                 $this->formsetting();
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
 * Testimonial Page View Settings
 */
     function  formViewSettings()
    {
    	?>
        <div class="wrap">

        <?php
        if (isset($_POST['awp_testimonialform_settings'])) {
            $this->formsetting();
            echo '<div class="message" style="margin:5px 0 15px; background-color: #FFFFE0 ;border: 1px solid #E6DB55;"><p style="margin: 0.5em;padding: 2px;">Inline View Settings Saved Successfully.</p></div>';
        }
        $this->formsetting();
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
        }
        if($_REQUEST['tstmode']=="reject")
        {
            
            echo '<div class="message" id="errormessage" style="margin: 5px 0pt 15px; background-color: rgb(255, 255, 224); border: 1px solid rgb(230, 219, 85);width:80%;">
      	<p style="margin: 0.5em; padding: 2px;">Testimonial Rejected Successfully</p></div>';

        }
         if($_REQUEST['tstmode']=="approve")
        {
            echo '<div class="message" id="errormessage" style="margin: 5px 0pt 15px; background-color: rgb(255, 255, 224); border: 1px solid rgb(230, 219, 85);width:80%;">
      	<p style="margin: 0.5em; padding: 2px;">Testimonial Approved Successfully</p></div>';

        }
        ?>

	        <table class="widefat plugins" width="700" cellspacing="0" cellpadding="0">
	                                    <thead><tr>
	                                        <th><?php _e('Name','apptivo-businesssite'); ?></th>
                                                <th><?php _e('Company','apptivo-businesssite'); ?></th>
	                                        <th><?php _e('Website','apptivo-businesssite'); ?></th>
	                                        <th><?php _e('Email','apptivo-businesssite'); ?></th>
	                                        <th><?php _e('Testimonials','apptivo-businesssite'); ?></th>
	                                        <th><?php _e('Order','apptivo-businesssite'); ?></th>
	                                        <th><?php _e('Edit','apptivo-businesssite'); ?></th>
	                                        <th><?php _e('Delete','apptivo-businesssite'); ?></th>
                                            <th><?php _e('Approve','apptivo-businesssite'); ?></th>
                                            <th><?php _e('Reject','apptivo-businesssite'); ?></th>
                                            <th><?php _e('Status','apptivo-businesssite'); ?></th>
                                            </tr></thead>
	                                     <tfoot><tr>
	                                        <th style="border-top: 1px solid #DFDFDF;"><?php _e('Name','apptivo-businesssite'); ?></th>
	                                        <th style="border-top: 1px solid #DFDFDF;"><?php _e('Company','apptivo-businesssite'); ?></th>
	                                        <th style="border-top: 1px solid #DFDFDF;"><?php _e('Website','apptivo-businesssite'); ?></th>
	                                        <th style="border-top: 1px solid #DFDFDF;"><?php _e('Email','apptivo-businesssite'); ?></th>
	                                        <th style="border-top: 1px solid #DFDFDF;"><?php _e('Testimonials','apptivo-businesssite'); ?></th>
	                                        <th style="border-top: 1px solid #DFDFDF;"><?php _e('Order','apptivo-businesssite'); ?>r</th>
	                                        <th style="border-top: 1px solid #DFDFDF;"><?php _e('Edit','apptivo-businesssite'); ?></th>
	                                        <th style="border-top: 1px solid #DFDFDF;"><?php _e('Delete','apptivo-businesssite'); ?></th>
                                            <th style="border-top: 1px solid #DFDFDF;"><?php _e('Approve','apptivo-businesssite'); ?></th>
                                            <th style="border-top: 1px solid #DFDFDF;"><?php _e('Reject','apptivo-businesssite'); ?></th>
                                            <th style="border-top: 1px solid #DFDFDF;"><?php _e('Status','apptivo-businesssite'); ?></th>
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
                                        if($_REQUEST['tstmode']=="approve" || $_REQUEST['tstmode'] == "reject" || $_REQUEST['page']== "awp_testimonials")
                                        {
                                            $account=$awp_testimonial->account->accountName;
                                            $contact= $awp_testimonial->contact->companyName;
                                            $website=$awp_testimonial->account->website;
                                            $email  =$awp_testimonial->email;
                                            $order  =$awp_testimonial->sequenceNumber;
                                            $testimonialId=$awp_testimonial->siteTestimonialId;
                                            $testimonial=$awp_testimonial->testimonial;
                                            $testimonialStatus=$awp_testimonial->testimonialStatus;
                                            $imageurl		= $awp_testimonial->testimonialImageUrl;
                                            if($_REQUEST['tstmode']=="reject" && $_REQUEST['tstid'] == $testimonialId)
                                            {
                                                $testimonialStatus         =    "REJECTED";
                                            }

                                            if($_REQUEST['tstmode']=="approve" && $_REQUEST['tstid'] == $testimonialId)
                                            {
                                                $testimonialStatus="APPROVED";
                                            }
                                            updateTestimonials($account, $accountId, $company, $contact, $contactId, $creationDate, $email, $firmId, $images, $jobtitle, $name, $returnStatus, $order, $testimonialId, $testimonial, $imageurl, $testimonialStatus, $website);
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
	                                        <td><a href="<?php echo SITE_URL; ?>/wp-admin/admin.php?page=awp_testimonials&amp;tstmode=edit&amp;tstid=<?php echo $awp_testimonial->siteTestimonialId; ?>&amp;pageno=<?php echo intval($_GET['pageno']);?>"><img src="<?php echo awp_image('edit_icon'); ?>" title="Edit"/></a></td>
	                                        <td><a href="<?php echo SITE_URL; ?>/wp-admin/admin.php?page=awp_testimonials&amp;tstmode=delete&amp;tstid=<?php echo $awp_testimonial->siteTestimonialId; ?>" onclick="return delete_testimonials('<?php echo $this->_plugin_activated; ?>');" ><img src="<?php echo awp_image('delete_icon'); ?>" title="Delete"/></a></td>
                                            <td><a href="<?php echo SITE_URL; ?>/wp-admin/admin.php?page=awp_testimonials&amp;tstmode=approve&amp;tstid=<?php echo $awp_testimonial->siteTestimonialId; ?>&amp;pageno=<?php echo intval($_GET['pageno']);?>"><img src="<?php echo awp_image('approve_icon'); ?>" title="Approve"/></a></td>
                                            <td><a href="<?php echo SITE_URL; ?>/wp-admin/admin.php?page=awp_testimonials&amp;tstmode=reject&amp;tstid=<?php echo $awp_testimonial->siteTestimonialId; ?>&amp;pageno=<?php echo intval($_GET['pageno']);?>"><img src="<?php echo awp_image('reject_icon'); ?>" title="Reject"/></a></td>
                                            <?php 
                                            
                                            $testimonial_status =   $awp_testimonial->testimonialStatus;
                                            if($testimonialStatus=="PENDING_APPROVAL")
                                            {
                                                $testimonialStatus="PENDING";
                                            }
                                            ?>
                                            <td><?php echo mb_convert_case($testimonialStatus, MB_CASE_TITLE, "UTF-8");; ?></td>
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
		$dir_testimonials = AWP_TESTIMONIALS_FORM_TEMPLATEPATH;
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
 /*
  * Testimonial For Page View
  */
        function testimonialform($atts){
		ob_start();
		$testimonialidwith_Number = FALSE;
		extract(shortcode_atts(array('name'=>  ''), $atts));
		$formname=trim($name);
                $content="";
		$successmsg="";
		$testimonialform=$this->get_testimonialform_fields($formname);
		if(!empty($testimonialform[fields])) {
			foreach($testimonialform[fields] as $field){
                            
			}
		}
		$submitformname = $_POST['awp_testimonialformname'];
		if(isset($_POST['awp_testimonialformname']) && $submitformname==$formname)
		{
                    if(trim($testId) == '' && isset($_POST['testimonialidwithnumber']))
			{
				$testimonialid_Number = $_POST['testimonialidwithnumber'];
				$testimonialid_No = explode('::',$testimonialid_Number);
				$testimonialId = $testimonialid_No[0];
				$testimonialNo = $testimonialid_No[1];
				$testimonialidwith_Number = TRUE;
			}
			//$successmsg=$this->save_applicantjobs($submitformname,$testimonialId,$testimonialNo);
		}
		if($testimonialid_Number)
		{
			$testimonialId = '';
		}

		if(!empty($testimonialform)){
			include $testimonialform['templatefile'];
			echo '<style>label.error,.absp_testimonial_mandatory{color:#FF0400;}</style>';
		} else { echo awp_messagelist('testimonials-form-page'); }
		$content = ob_get_clean();
		return $content;
	}

        function get_testimonialform_fields($formname){
		$formExists="";
		$testimonial_forms=array();
		$testimonialform=array();
		$testimonialformdetails=array();
		$formname=trim($formname);
                $testimonial_forms=get_option('awp_testimonialforms');
                if($formname=="")
		$formExists="";
		else if(!empty($testimonial_forms))
		$formExists = awp_recursive_array_search($testimonial_forms,$formname,'name' );

		if(trim($formExists)!=="" ){
			$testimonialform=$testimonial_forms[$formExists];
			//build hrjobsformdetails array
			$testimonialformdetails['name']=$testimonialform['name'];
                        //add properties
			$testimonialformproperties=$testimonialform['properties'];
                        $testimonialformdetails['tmpltype']=$testimonialformproperties['tmpltype'];
                        $testimonialformdetails['tmpl_button_type']=$testimonialformproperties['tmpl_button_type'];
			$testimonialformdetails['tmpl_button_val']=$testimonialformproperties['tmpl_button_val'];
			$testimonialformdetails['layout']=$testimonialformproperties['layout'];
			$testimonialformdetails['confmsg']= stripslashes($testimonialformproperties['confmsg']);
			$testimonialformdetails['targetlist']=$testimonialformproperties['targetlist'];
			$testimonialformdetails['css']=stripslashes($testimonialformproperties['css']);
			$testimonialformdetails['submit_button_type']=$testimonialformproperties['submit_button_type'];
			$testimonialformdetails['submit_button_val']=$testimonialformproperties['submit_button_val'];
			//inclde templates.
			if($testimonialformproperties['tmpltype']=="awp_plugin_template") :
			$templatefile=AWP_TESTIMONIALS_FORM_TEMPLATEPATH."/".$testimonialformproperties['layout']; // Testimonial form plugin template
                        else :
			$templatefile=AWP_TESTIMONIALS_FORM_TEMPLATEPATH."/".$testimonialformproperties['layout']; // Testimonial form theme template
			endif;

			$testimonialformdetails['templatefile']=$templatefile;
			//add fields
			$testimonialformfields=$testimonialform['fields'];
                        if(!empty($testimonialformfields)){
				usort($testimonialformfields, "awp_sort_by_order");
				$newtestimonialformfields=$testimonialformfields;
				$testimonialformdetails['fields']=$newtestimonialformfields;
				echo '<style> textarea{width:53%;padding:0px;}  textarea#comments{width:53%;padding:0px;}
				#awp_testimonialform_submit{clear:both;}
				</style>';
			}
		}
                return $testimonialformdetails;
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
/*
 * Testimonial Front Page view
 */
        function getTestimonialforPageView()
        {
            $awp_testimonial_form_settings  = get_option('awp_testimonialforms');
             if($awp_testimonial_form_settings['tmpl_type']=="awp_plugin_template") :
	                $templatefile=AWP_TESTIMONIALS_TEMPLATEPATH."/".$awp_testimonial_form_settings['layout']; //plugin templates
             
                exit;
	        else :
	                $templatefile=TEMPLATEPATH."/testimonials/templates/frontend/".$awp_testimonial_form_settings['layout']; //theme templates
                
            endif;
              if (!file_exists($templatefile)) :
	            	$templatefile = AWP_TESTIMONIALS_TEMPLATEPATH."/".AWP_TESTIMONIALS_DEFAULT_TEMPLATE;
	          endif;

          //    $response = getAllTestimonials();
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
            return;
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
	        <form method="post" action="<?php echo SITE_URL; ?>/wp-admin/admin.php?page=awp_testimonials" name="awp_testimonials_form" id="awp_testimonials_form"  onsubmit="return validatetestimonialsforms()" >
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
									<input id="testimonials_upload_images" type="button" value="Upload Image"  class="button-primary"/>
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
	        <form method="post" action="<?php echo SITE_URL;?>/wp-admin/admin.php?page=awp_testimonials" name="awp_testimonials_form" onsubmit="return validatetestimonialsforms()">
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

/* Success Message to Front end Form */
function successMessage($success)
{
     $url=SITE_URL.$_SERVER['REQUEST_URI'];
                                                                  $display_msg = "Testimonial Is awaiting for Moderation";
                                                                  $actual_link = $_SESSION['request_link'];
                                                                   if(strpos($actual_link, 'status=Success')!=false)
                                                                   {
                                                                      $actual_link= str_replace('?status=Success', '', $actual_link);
                                                                      if (strpos($actual_link, '?') !== false) {$actual_link= str_replace('&status=Success', '', $actual_link); }
                                                                      unset($_SESSION['POST_VALUES']);
                                                                   }
                                                                    if(strpos($actual_link, 'status=Please%20enter%20correct%20Verification%20code')!=false)
                                                                   {
                                                                       $actual_link= str_replace('?status=Please%20enter%20correct%20Verification%20code', '', $actual_link);
                                                                       if (strpos($actual_link, '?') !== false) {$actual_link= str_replace('&status=Please%20enter%20correct%20Verification%20code', '', $actual_link); }
                                                                   }
                                                                  
                                                                   if (strpos($actual_link, '?') !== false) {
                                                                      header("Location:$actual_link&status=".urlencode($success));
                                                                      break;
                                                                  } else {
                                                                      header("Location:$actual_link?status=".urlencode($success));
                                                                      break;
                                                                  }
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
if($_REQUEST['page']=="awp_testimonials" && $_REQUEST['email'] !="")
{
   $awp_testimonials_options = array(
	                'name' => stripslashes($_POST['name']),
	                'jobtitle' => stripslashes($_POST['jobtitle']),
	                'company' => stripslashes($_POST['company']),
	                'website' => stripslashes($_POST['website']),
	                'email' => stripslashes($_POST['email']),
	                'imageurl' => stripslashes($_POST['imageurl']),
	                'testimonial' => stripslashes($_POST['comments']),
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
            $_SESSION['POST_VALUES']	= $_POST;
            extract($awp_testimonials_options);
            $testimonial = apply_filters('the_content', $testimonial);
             if (isset($_POST["recaptcha_challenge_field"]) && $_POST['email'] != "") {
                    $response_field =   $_POST["recaptcha_response_field"];
                    $challenge_field=   $_POST["recaptcha_challenge_field"];
                    $option=get_option('apptivo_business_recaptcha_settings');
                    $option=json_decode($option);
                    $private_key    =   $option->recaptcha_privatekey;
                    $captcha_response=    captchaValidation($private_key, $challenge_field, $response_field);

                    if($captcha_response!="1")
			{
                        $value_present = true;
	                $success = 'Please enter correct Verification code';
                        successMessage($success);
                        }
 else {
    $response = addTestimonials($account, $accountId, $company, $contact, $contactId, $creationDate, $email, $firmId, $images, $jobtitle, $name, $returnStatus, $order, $siteTestimonialId, $testimonial, $imageurl, $testimonialStatus, $website);
    $success = $response->return->statusMessage;
    successMessage($success);
}
}
 
              elseif ($_POST['email'] != "") {
                                                                  $response = addTestimonials($account, $accountId, $company, $contact, $contactId, $creationDate, $email, $firmId, $images, $jobtitle, $name, $returnStatus, $order, $siteTestimonialId, $testimonial, $imageurl, $testimonialStatus, $website);
                                                                  $success = $response->return->statusMessage;
                                                                  successMessage($success);
                                                              }
}